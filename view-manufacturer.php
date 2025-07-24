<!DOCTYPE html>
<?php
session_start(); 

// Check if the user is logged in
if(!isset($_SESSION['LOG_IN'])){
    header("Location:login.php");
    exit();
}

// Check if a business is selected
if(!isset($_SESSION['business_id'])){
    header("Location:dashboard.php");
    exit();
} else {
 // Set up variables for selected business and branch
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    // Check if a specific branch is selected
    if (isset($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];
        // Branch-specific code or logic here
    } 
}

include("config.php");
?>

<html lang="en">
<head>
    <title>iiiQbets - Customers</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
    <!-- Font Awesome CDN -->
  <!--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">-->
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
  <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">-->
  <!--<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>-->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 <style>
    .tab-button.active {
    background-color: #007bff;
    color: #fff;
}
.mandatory-symbol {
    color: red;
  }
  .error {
            color: red;
            size: 80%
        }

        .hidden {
            display: none;
        }

</style>
<style>
    .highlight-error {
        border: 2px solid red;
    }
</style>

</head>

<body class="">
    <!-- Rest of your HTML content for customers -->
    <!-- [ Pre-loader ] start -->
    <?php include("menu.php"); ?>

    <!-- [ Main Content ] start -->
    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
        <!--     <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">View Customers</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                
                                 <div class="col-md-12">
                                     <h4 class="m-b-10">View Manufacturer</h4><hr/>
                                    <!-- Download Options -->
                                    <div class="col-md-12" style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                                        <!-- Month and Year Selection -->
                                        <div id="reportrange" class="col-md-4" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
    <i class="fa fa-calendar"></i>&nbsp;
    <span></span> <i class="fa fa-caret-down"></i>

</div><button id="download-btn" class="btn btn-sm btn-info">Download Report</button>
                                      <!--  <form class="form-inline" method="POST" action="download_customers_monthly.php" style="display: flex; align-items: center; margin-right: 10px;">
                                            <label style="margin-right: 10px;">Monthly Data:</label>
                                            <select class="form-control" id="month" name="month" style="margin-right: 10px; width: auto;">
                                                <?php
                                                $current_month = date("m");
                                                for ($month = 1; $month <= 12; $month++) {
                                                    $selected = ($current_month == $month) ? 'selected' : '';
                                                    echo "<option value=\"$month\" $selected>" . date('F', mktime(0, 0, 0, $month, 1)) . "</option>";
                                                }
                                                ?>
                                            </select>
                                            <select class="form-control" id="year" name="year" style="margin-right: 10px; width: auto;" required>
                                                <?php
                                                $current_year = date("Y");
                                                for ($i = $current_year; $i >= 2017; $i--) {
                                                    echo "<option value=\"$i\">$i</option>";
                                                }
                                                ?>
                                            </select>
                                            <button type="submit" class="btn btn-success btn-sm" name="download_month">
                                                <i class="fas fa-download"></i> Download
                                            </button>
                                        </form>-->
                                        
                                        <!-- Date Range Selection -->
                                      <!--  <form class="form-inline" method="POST" action="download_customers_range.php" style="display: flex; align-items: center; margin-right: 10px;">
                                            <label style="margin-right: 10px;">Date Range:</label>
                                            <input type="date" class="form-control" id="from_date" name="from_date" required 
                                                   style="margin-right: 10px; width: auto;"
                                                   value="<?php echo date('Y-m-d', strtotime('-1 month')); ?>">
                                            <input type="date" class="form-control" id="to_date" name="to_date" required 
                                                   style="margin-right: 10px; width: auto;"
                                                   value="<?php echo date('Y-m-d'); ?>">
                                            <button type="submit" class="btn btn-success btn-sm" name="download_range">
                                                <i class="fas fa-download"></i> Download Range
                                            </button>
                                        </form>-->
                                        
                                        <!-- Add Customer Button -->
                                        <form action="add_manufacturer.php" method="POST" style="margin-left: auto;">
                                            <button class="btn btn-success btn-sm" name="addCustBtn" id="addCustBtn" type="submit">Add Manufacturer</button>
                                        </form>
                                    </div>
                                </div>
                                    <!--<form action="customer_form.php" method="POST">-->
                                    <!--    <button class="btn btn-success btn-sm float-end" name="addCustBtn" id="addCustBtn" type="submit">Add Customer</button>-->
                                    <!--</form>-->
                                </div>
                            </div>
                              <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Contact Info</th>
                                            <th>Tax Information</th>
                                            <th>Created BY</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                       if (!isset($_SESSION['branch_id'])) {
    // Query to get customers, ordered by the 'created_on' field in descending order
    $sql = "SELECT * FROM customer_master WHERE contact_type = 'Manufacturer' AND business_id = '$business_id' ORDER BY created_on DESC";
} else {
    // Query to get customers for a specific branch, ordered by 'created_on' in descending order
    $sql = "SELECT * FROM customer_master WHERE contact_type = 'Manufacturer' AND business_id = '$business_id' AND branch_id='$branch_id' ORDER BY created_on DESC";
}
                        //    echo $sql;            
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                                <tr>
                                                    <td>
                                                    <a href="customer-details.php?id=<?php echo $row['id']; ?>">
                                                        <?php echo $row['customerName']; ?>
                                                    </a><br/>
                                                        <?php echo $row["business_name"] === "" ? '<a href="update-customer.php?id=' . $row["id"] . '">Update</a>' : $row["business_name"]; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row["mobile"] === "" ? '<a href="update-customer.php?id=' . $row["id"] . '">Update Mobile</a>' : $row["mobile"]; ?><br/>
                                                        <?php echo $row["email"] === "" ? '<a href="update-customer.php?id=' . $row["id"] . '">Update Email</a>' : $row["email"]; ?>
                                                    </td>
                                                   <td>
    PAN: <?php echo $row["pan"] === "" ? '<a href="update-customer.php?id=' . $row["id"] . '&tab=tab2">Update PAN</a>' : $row["pan"]; ?><br/>
    GSTIN: <?php echo $row["gstin"] === "" ? '<a href="update-customer.php?id=' . $row["id"] . '&tab=tab2">Update GSTIN</a>' : $row["gstin"]; ?>
</td>

                                                    <td>
                                                        <?php echo $row["created_by"] ?><br/>
                                                        <?php echo $row["created_on"] ?>
                                                    </td>
                                                    <td>
                                                        <a href="update-customer.php?id=<?php echo $row["id"]; ?>" class="text-primary mr-2">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="delete-customer.php?id=<?php echo $row["id"]; ?>" class="text-danger" id="deleteButton<?php echo $row['id']; ?>">
    <i class="fas fa-trash-alt"></i>
</a>
                                                      
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                        ?>
                                            <tr>
                                                <td colspan="5"><?php echo "No Records found"; ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div>
                      
                    </div>
                </div>
                <!-- [ stiped-table ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </section>
<script type="text/javascript">
    
    

    function enableSubmitButton() {
        // No need to disable the submit button in this case
    }

function validateNumericInput(input) {
    input.value = input.value.replace(/[^0-9]/g, ''); // Replace any non-numeric characters
}

function validateName(input_str) {
    var re = /^[a-zA-Z ]{2,30}$/;
    return re.test(input_str);
}

function validateDisplayName(input_str) {
    var re = /^[a-zA-Z ]{2,30}$/;
    return re.test(input_str);
}

function validateMobileNumber(input_str) {
    // Allow only digits and exactly 10 digits
    var re = /^\d{10}$/;
    return re.test(input_str);
}

function validatePhoneNumber(input_str) {
    // Allow only digits and exactly 10 digits
    var re = /^\d{10}$/;
    return re.test(input_str);
}

function validateEmail(input_str) {
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(input_str);
}

function validateGSTIN(input_str) {
    var re = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;
    return re.test(input_str);
}

function displayError(inputId, errorId, isValid) {
    var errorElement = document.getElementById(errorId);
    if (!isValid) {
        errorElement.classList.remove('hidden');
    } else {
        errorElement.classList.add('hidden');
    }
}

function validateAndDisplayError(inputId, validationFunction, errorId) {
    var inputValue = document.getElementById(inputId).value.trim();
    var isValid = (inputValue === "") || validationFunction(inputValue); // Validate only if not empty
    displayError(inputId, errorId, isValid);
    return isValid;
}


function validateCurrentTab(tabId) {
    let isValid = true;

    // Validate required fields
    isValid &= validateAndDisplayError('name', validateName, 'name_error');
    isValid &= validateAndDisplayError('mobile_number', validateMobileNumber, 'mobile_number_error');
    isValid &= validateAndDisplayError('email', validateEmail, 'email_error');

    // Optionally validate fields if they have a value
    isValid &= validateAndDisplayError('customer_gstin', validateGSTIN, 'customer_gstin_error');
    isValid &= validateAndDisplayError('phone_number', validatePhoneNumber, 'phone_number_error');
    isValid &= validateAndDisplayError('display_name', validateDisplayName, 'display_name_error');

    // Highlight fields with errors
    const requiredFields = document.getElementById(tabId).querySelectorAll('[required]');
    requiredFields.forEach(function(field) {
        const value = field.value.trim();
        if (!value) {
            isValid = false;
            field.classList.add('highlight-error');
        } else {
            field.classList.remove('highlight-error');
        }
    });

    return !!isValid;
}



document.getElementById('name').addEventListener('input', function() {
    validateAndDisplayError('name', validateName, 'name_error');
});

document.getElementById('display_name').addEventListener('input', function() {
    validateAndDisplayError('display_name', validateDisplayName, 'display_name_error');
});

document.getElementById('mobile_number').addEventListener('input', function() {
    validateAndDisplayError('mobile_number', validateMobileNumber, 'mobile_number_error');
});

document.getElementById('email').addEventListener('input', function() {
    validateAndDisplayError('email', validateEmail, 'email_error');
});

document.getElementById('phone_number').addEventListener('input', function() {
    validateAndDisplayError('phone_number', validatePhoneNumber, 'phone_number_error');
});

document.getElementById('customer_gstin').addEventListener('input', function() {
    validateAndDisplayError('customer_gstin', validateGSTIN, 'customer_gstin_error');
});


document.getElementById('submit_btn').addEventListener('click', function(event) {
    let isFormValid = true;

    // Validate required fields
    isFormValid &= validateAndDisplayError('name', validateName, 'name_error');
    isFormValid &= validateAndDisplayError('display_name', validateDisplayName, 'display_name_error');
    isFormValid &= validateAndDisplayError('mobile_number', validateMobileNumber, 'mobile_number_error');
    isFormValid &= validateAndDisplayError('email', validateEmail, 'email_error');
    
    // Validate optional fields only if they have a value
    // isFormValid &= validateAndDisplayError('phone_number', validatePhoneNumber, 'phone_number_error');
    // isFormValid &= validateAndDisplayError('customer_gstin', validateGSTIN, 'customer_gstin_error');
    
    if (!isFormValid) {
        event.preventDefault();
    }
});

    document.getElementById('tab1').style.display = 'block';

function enableOrDisableNextButton(tabId, nextButtonId) {
    const isTabValid = validateCurrentTab(tabId);
    const nextButton = document.getElementById(nextButtonId);
    if (nextButton) {
        nextButton.disabled = !isTabValid;  // Disable the button if the tab is not valid
    }
}

// Attach event listeners to inputs
document.querySelectorAll('.tab-content input, .tab-content select').forEach(function(input) {
    input.addEventListener('input', function() {
        const currentTabId = input.closest('.tab-content').id;
        const nextButton = input.closest('.tab-content').querySelector('.next-btn');
        if (nextButton) {
            enableOrDisableNextButton(currentTabId, nextButton.id);
        }
    });
});

function openTab(evt, tabName) {
    const currentTabId = evt.currentTarget.closest('.tab-content').id;
    const isTabValid = validateCurrentTab(currentTabId);

    if (!isTabValid) {
        evt.preventDefault();
        document.getElementById('top-error-message').innerText = 'Please fill out all required fields before proceeding.';
        document.getElementById('top-error-message').classList.remove('hidden');
        return;
    }

    // Hide all tabs
    const tabcontent = document.getElementsByClassName("tab-content");
    for (let i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Remove "active" class from all buttons
    const tablinks = document.getElementsByClassName("tab-button");
    for (let i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";

    // Hide top error message when switching tabs
    document.getElementById('top-error-message').classList.add('hidden');
}

document.getElementById('submit_btn').addEventListener('click', function(event) {
    const isFormValid = validateAllTabs();

    // Prevent form submission if any field has an error
    if (!isFormValid) {
        event.preventDefault();
    }
});

// Enable "Next" button for the first tab on page load
// Enable "Next" button for the first tab on page load
// Enable "Next" button for the first tab on page load
document.addEventListener("DOMContentLoaded", function() {
    enableOrDisableNextButton('tab1', 'next-btn-1');
});


    document.addEventListener("DOMContentLoaded", function() {
        var checkbox = document.getElementById("checkbox_id");
        var addressForm = document.getElementById("addressForm");

        function toggleAddressForm() {
            if (checkbox.checked) {
                addressForm.style.display = "none";
            } else {
                addressForm.style.display = "block";
            }
        }

        toggleAddressForm(); // Initial call to set the form's display

        checkbox.addEventListener("change", toggleAddressForm);
    });

// <div id="top-error-message" class="error hidden" style="text-align: center; margin-bottom: 20px;"></div>


    function validateAllTabs() {
        let isValid = true;
        const requiredFields = [
            { id: 'name', validator: validateName, errorId: 'name_error' },
            // { id: 'display_name', validator: validateDisplayName, errorId: 'display_name_error' },
            { id: 'mobile_number', validator: validateMobileNumber, errorId: 'mobile_number_error' },
            { id: 'email', validator: validateEmail, errorId: 'email_error' },
            // { id: 'phone_number', validator: validatePhoneNumber, errorId: 'phone_number_error' },
            // Add other required fields here
        ];

        requiredFields.forEach(function(field) {
            const inputValue = document.getElementById(field.id).value;
            const fieldIsValid = field.validator(inputValue);
            displayError(field.id, field.errorId, fieldIsValid);

            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (!isValid) {
            document.getElementById('top-error-message').innerText = 'Please fill out all required fields.';
            document.getElementById('top-error-message').classList.remove('hidden');
        } else {
            document.getElementById('top-error-message').classList.add('hidden');
        }

        return isValid;
    }

    document.getElementById('submit_btn').addEventListener('click', function(event) {
        const isFormValid = validateAllTabs();

        // Prevent form submission if any field has an error
        if (!isFormValid) {
            event.preventDefault();
        }
    });

</script>
    <!-- Required Js -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <?php include("contactModal.php");?>

     <!-- <script src="assets/js/contactsjs.js"></script> -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataTables-example').DataTable();
            $('.dataTables_length').addClass('bs-select');
        });
        $('#dataTables-example').dataTable({
            "orderFixed": [3, 'desc']
        });
    </script>
<script type="text/javascript">
  $(function() {
    var start = moment().startOf('month');
    var end = moment().endOf('month');

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

    // When the user selects a date range manually
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');
        
        // Store the selected date range for use
        $('#reportrange').data('start', startDate);
        $('#reportrange').data('end', endDate);
    });

    // Handle the download button click for monthly or custom date range download
  $('#download-btn').on('click', function() {
    var startDate = $('#reportrange').data('start');
    var endDate = $('#reportrange').data('end');

    if (!startDate || !endDate) {
        alert('Please select a date range first.');
        return;
    }

    // Send the selected date range to the backend for processing
    $.ajax({
        url: 'download-customer-data.php', // PHP script to handle the download
        type: 'GET',
        data: {
            from_date: startDate,
            to_date: endDate,
            download_range: true // Indicates custom date range download
        },
        success: function(response) {
            // If no data is found, the server returns JSON with 'error'
            try {
                var data = JSON.parse(response);
                if (data.status === 'error') {
                    // No data found, show SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'No Data Found',
                        text: data.message, // This will show the "No data found" message
                    });
                    return;  // Prevent file download
                }
            } catch (e) {
                // If JSON parsing fails, assume the response is the Excel file
                // Proceed with the file download
                window.location.href = 'download-customer-data.php?from_date=' + startDate + '&to_date=' + endDate + '&download_range=true';
            }
        },
        error: function(xhr, status, error) {
            // Handle error if the request fails
            alert('Error: ' + error);
        }
    });
});

});
</script>
<script>
    // Wait for the document to be ready
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listener for each delete link
        const deleteButtons = document.querySelectorAll('a[id^="deleteButton"]');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();  // Prevent the default action of the link (which is redirecting to delete page)
                
                const deleteUrl = this.href;  // Get the delete URL from the href attribute
                
                // Show the SweetAlert confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this contact!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, navigate to the delete URL
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    });
</script>

</body>
</html>
