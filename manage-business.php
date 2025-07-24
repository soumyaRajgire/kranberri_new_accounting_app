<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['LOG_IN'])) {
    header("Location: login.php");
    exit();
}


// Check if a business is selected
if (!isset($_SESSION['business_id'])) {
    header("Location: dashboard.php");
    exit();
} else {
    // Set up variables for selected business and branch
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    
    // Include database connection
    include("config.php");

    // Fetch business details
    $query_business = "SELECT business_id, business_name, PAN, GSTIN, email, mobile_number, contact_person, address_line1, address_line2, pincode, city, state, logo FROM add_business WHERE business_id = ?";
    $stmt_business = $conn->prepare($query_business);
    $stmt_business->bind_param("i", $business_id);
    $stmt_business->execute();
    $result_business = $stmt_business->get_result();

    // Check if business details exist
    if ($result_business->num_rows > 0) {
        $business = $result_business->fetch_assoc();
    } else {
        $business = [];
    }
    $stmt_business->close();

    // Check if a specific branch is selected
    if (isset($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];

        // Fetch branch details
        $query_branch = "SELECT * FROM add_branch WHERE branch_id = ?";
        $stmt_branch = $conn->prepare($query_branch);
        $stmt_branch->bind_param("i", $branch_id);
        $stmt_branch->execute();
        $result_branch = $stmt_branch->get_result();

        // Check if branch details exist
        if ($result_branch->num_rows > 0) {
            $branch = $result_branch->fetch_assoc();
        } else {
            $branch = [];
        }
        $stmt_branch->close();
    } else {
        header("Location: dashboard.php");
        exit();
    }
}
?>

 
 


<html lang="en">
<head>
    <title>iiiQbets</title>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <?php include("header_link.php");?>
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<!-- Your JavaScript code -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</head>
<style>
    .required {
        color:red;
    }
    .tab-content h6 {
        
        font-size: 15px;
    }
    a.hover-effect:hover {
        color: blue; /* Change the color to blue when hovered */
    }
    #btn-gstin {
        border-radius: 5px;
        background-color: #ffb822;
        border-color: #ffb822;
        font-size: 12px;
    }
    #btn-gstin:hover {
        background-color: #ffcc00;
    }
    i:hover {
       color: #646c9a;
    }
    .did-floating-label-content { 
  position:relative; 
  margin-bottom:20px; 
 }
 .did-floating-label {
  color:#1e4c82; 
  font-size:13px;
  font-weight:normal;
  position:absolute;
  pointer-events:none;
  left:15px;
  top:11px;
  padding:0 5px;
  background:#fff;
  transition:0.2s ease all; 
  -moz-transition:0.2s ease all; 
  -webkit-transition:0.2s ease all;
 }
 .did-floating-input, .did-floating-select {
  font-size:12px;
  display:block;
  width:100%;
  height:36px;
  padding: 0 20px;
  background: #fff;
  color: #323840;
  border: 1px solid #3D85D8;
  border-radius: 4px;
  box-sizing: border-box;
  &:focus{
    outline:none;
    ~ .did-floating-label{
      top:-8px;
      font-size:13px;
    }
  }
 }

 select.did-floating-select {
  -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
 }
 select.did-floating-select::-ms-expand {
  display: none;
 }

 .did-floating-input:not(:placeholder-shown) ~ .did-floating-label {
  top:-8px;
  font-size:13px;
 }
 .did-floating-select:not([value=""]):valid ~ .did-floating-label {
  top:-8px;
  font-size:13px;
 }
 .did-floating-select[value=""]:focus ~ .did-floating-label {
  top:11px;
  font-size:13px;
 }
 .did-floating-select:not([multiple]):not([size]) {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='6' viewBox='0 0 8 6'%3E%3Cpath id='Path_1' data-name='Path 1' d='M371,294l4,6,4-6Z' transform='translate(-371 -294)' fill='%23003d71'/%3E%3C/svg%3E%0A");
    background-position: right 15px top 50%;
    background-repeat: no-repeat;
 }
 .dropdown-card {
        display: none;
    }
    .active-card {
        display: block;
    }
</style>
<body class="">
    <!-- [ Pre-loader ] start -->
     
     <?php include("menu.php");?>
    
    
    <!-- [ Header ] end -->
    

<!-- [ Main Content ] start -->
<section class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <!-- <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">Dashboard</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top: -40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr> -->



    <div class="card">
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs" id="Tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#basic">Basic</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#banking">Banking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#user">User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kyc-docs">KYC Docs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#collect-payments">Collect Payments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#invoice">Invoice</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#advanced">Advanced</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#api-docs">API Docs</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="tab-content" style="margin-top: -15px;">
        <div class="tab-pane fade show active" id="basic">
            <div class="row">
            <div class="col-9">
    <div class="card">
        <div class="card-body">
        <form id="updateForm" method="POST" action="update_user.php">
    <div class="row">
        <div class="col-6">
            <h6 class="control-label">Do you have GSTIN?</h6>
            <div class="input-group mb-2">
            <div class="input-group-prepend">
    <select class="form-control" id="confirmGstin" onchange="toggleGstinInput()" name="confirmGstin">
        <option value="0" <?php echo (isset($business['GSTIN']) && empty($business['GSTIN'])) ? 'selected' : ''; ?>>No</option>
        <option value="1" <?php echo (!empty($business['GSTIN'])) ? 'selected' : ''; ?>>Yes</option>
    </select>
</div>

<input type="text" id="gstin" name="gstin" class="form-control" placeholder="GST Number" value="<?php echo isset($business['GSTIN']) ? $business['GSTIN'] : ''; ?>" 
      <?php echo !empty($business['GSTIN']) ? 'readonly' : 'disabled'; ?> >


            </div>

            <h6 class="control-label">Constitution of Business</h6>
            <select id="constitution" name="constitution" class="form-control">
                <option value="">Please select the Appropriate</option>
                <?php 
                $options = [
                    "proprietorship" => "Proprietorship",
                    "Partnership" => "Partnership",
                    "Hindu Undivided Family" => "Hindu Undivided Family",
                    "Private Limited Company" => "Private Limited Company",
                    "One Person Company" => "One Person Company",
                    "Limited Company" => "Limited Company",
                    "Section 8 Company" => "Section 8 Company",
                    "Society/Club/Trust/Association of Persons" => "Society/Club/Trust/Association of Persons",
                    "Limited Liability Partnership" => "Limited Liability Partnership",
                    "Foreign Limited Liability Partnership" => "Foreign Limited Liability Partnership"
                ];
                foreach ($options as $key => $label) {
                    $selected = (isset($branch['constitution']) && $branch['constitution'] == $key) ? 'selected' : '';
                    echo "<option value=\"$key\" $selected>$label</option>";
                }
                ?>
            </select>                                                         
        </div>
        <div class="col-6">
            <div class="form-group mb-2">
                <h6 class="control-label">Alternate Business Name <p style="display: inline;font-size: smaller;"> (Replaces Business Name on Invoices)</p></h6>
                <input type="text" id="alias_name" name="alias_name" class="form-control" placeholder="Alternate Business Name" 
                       value="<?php echo isset($branch['alias_name']) ? $branch['alias_name'] : ''; ?>">
            </div>
            <div class="form-group mb-2">
                <h6 class="control-label">Mobile Number<span class="required"> * </span></h6>
                <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="Mobile Number" 
                       value="<?php echo isset($branch['phone_number']) ? $branch['phone_number'] : ''; ?>">
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-6">
            <div class="form-group mb-2">
                <h6 class="control-label">Address Line 1<span class="required"> * </span></h6>
                <input type="text" id="address_line11" name="address_line11" class="form-control" placeholder="Address Line 1" 
                       value="<?php echo isset($branch['address_line1']) ? $branch['address_line1'] : ''; ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group mb-2">
                <h6 class="control-label">Address Line 2</h6>
                <input type="text" id="address_line12" name="address_line12" class="form-control" placeholder="Address Line 2" 
                       value="<?php echo isset($branch['address_line2']) ? $branch['address_line2'] : ''; ?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <h6 class="control-label">Pin Code<span class="required"> * </span></h6>
            <input type="text" id="pincode1" name="pincode1" class="form-control" placeholder="Pin Code" 
                   value="<?php echo isset($branch['pincode']) ? $branch['pincode'] : ''; ?>">
        </div>
        <div class="col-3">
            <h6 class="control-label">City<span class="required"> * </span></h6>
            <input type="text" id="city1" name="city1" class="form-control" placeholder="City" 
                   value="<?php echo isset($branch['city']) ? $branch['city'] : ''; ?>">
        </div>
        <div class="col-3">
            <h6 class="control-label">State<span class="required"> * </span></h6>
            <select class="form-control" id="state1" name="state1" onchange="updateStateCode()">
    <option value="">Select State</option>
    <?php 
    // GST State Codes Mapping
    $states = [
        "Andhra Pradesh" => "37", "Arunachal Pradesh" => "12", "Assam" => "18", "Bihar" => "10",
        "Chhattisgarh" => "22", "Goa" => "30", "Gujarat" => "24", "Haryana" => "06", "Himachal Pradesh" => "02",
        "Jharkhand" => "20", "Karnataka" => "29", "Kerala" => "32", "Madhya Pradesh" => "23",
        "Maharashtra" => "27", "Manipur" => "14", "Meghalaya" => "17", "Mizoram" => "15", "Nagaland" => "13",
        "Odisha" => "21", "Punjab" => "03", "Rajasthan" => "08", "Sikkim" => "11", "Tamil Nadu" => "33",
        "Telangana" => "36", "Tripura" => "16", "Uttar Pradesh" => "09", "Uttarakhand" => "05",
        "West Bengal" => "19", "Delhi" => "07", "Puducherry" => "34"
    ];
    
    foreach ($states as $state => $code) {
        $selected = (isset($branch['state']) && $branch['state'] == $state) ? 'selected' : '';
        echo "<option value=\"$state\" data-code=\"$code\" $selected>$state</option>";
    }
    ?>
</select>

<!-- Hidden input field to store GST state code -->
<input type="hidden" id="state_code1" name="state_code1" value="">

        </div>
    
    </div>
<script>
    function updateStateCode() {
        var selectElement = document.getElementById("state1");
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var stateCode = selectedOption.getAttribute("data-code");

        document.getElementById("state_code1").value = stateCode || '';
    }

    // Call function on page load to set the initial value (if applicable)
    window.onload = updateStateCode;
</script>

<script>

    document.getElementById('gstin').addEventListener('blur', function () {
        const gstin = this.value;

        if (gstin.length > 0) {
            fetch('get_gst_details.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `gstin=${gstin}`,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Populate fields dynamically
                        // document.getElementById('customer_registered_name').value = data.LegalName || '';
                        // document.getElementById('business_name').value = data.TradeName || '';
                        // document.getElementById('additional_business_name').value = data.TradeName || '';
                        // document.getElementById('display_name').value = data.LegalName || '';
                        // document.getElementById('bill_address_line1').value = data.AddrBno + "," + data.AddrBnm || '';
                        // document.getElementById('bill_address_line2').value = data.AddrSt || '';
                        // document.getElementById('bill_city').value = data.AddrLoc || '';
                        // document.getElementById('bill_pin_code').value = data.AddrPncd || '';
                        // document.getElementById('bill_state').value = data.StateCode || '';
                        console.log(data);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching GSTIN details:', error);
                    alert('An error occurred. Please try again.');
                });
        }
    });  


</script>
      <hr/>
     <h6 class="control-label mt-1" style="font-weight: bold;">E-Way Bill Login Details</h6>
    <div class="row"> 
        <div class="col-6">
          
            <div class="form-group mb-2">
                <input type="text" id="eway_user" name="eway_user" class="form-control" placeholder="E-Way User" value="<?php echo isset($branch['eway_user']) ? $branch['eway_user'] : ''; ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="orm-group mb-2">
                <input type="password" id="eway_password" name="eway_password" class="form-control" placeholder="Password" value="<?php echo isset($branch['eway_password']) ? $branch['eway_password'] : ''; ?>">
            </div>
        </div>
    </div>
    
    <!-- Gov Portal Login Details -->
    <h6 class="control-label mt-1" style="font-weight: bold;">Gov Portal Login Details</h6>
    <div class="row">
        <div class="col-6">           
            <div class="form-group mb-2">
                <input type="text" id="gov_user" name="gov_user" class="form-control" placeholder="Gov. User" value="<?php echo isset($branch['gov_user']) ? $branch['gov_user'] : ''; ?>">
            </div>
             </div>
        <div class="col-6">
            <div class="form-group mb-2">
                <input type="password" id="gov_password" name="gov_password" class="form-control" placeholder="Password" value="<?php echo isset($branch['gov_password']) ? $branch['gov_password'] : ''; ?>">
            </div>
        </div>
    </div>

    <!-- E-Invoice Portal Login Details -->
     <h6 class="control-label mt-1" style="font-weight: bold;">E-Invoice Portal Login Details</h6>
    <div class="row">
        <div class="col-6">
           
            <div class="form-group mb-2">
                <input type="text" id="einv_user" name="einv_user" class="form-control" placeholder="E-Inv. User" value="<?php echo isset($branch['einv_user']) ? $branch['einv_user'] : ''; ?>">
            </div>
            </div>
        <div class="col-6">
            <div class="form-group mb-2">
                <input type="password" id="einv_password" name="einv_password" class="form-control" placeholder="Password" value="<?php echo isset($branch['einv_password']) ? $branch['einv_password'] : ''; ?>">
            </div>
        </div>
    </div>

    <!-- UPI for Payment Collections -->
     <h6 class="control-label mt-1" style="font-weight: bold;">UPI for Payment Collections</h6>
    <div class="row">
        <div class="col-6">
            <div class="form-group mb-2">
                <input type="text" id="bank_upi" name="bank_upi" class="form-control" placeholder="Bank UPI" value="<?php echo isset($branch['bank_upi']) ? $branch['bank_upi'] : ''; ?>">
            </div>
            </div>
        <div class="col-6">
            <div class="form-group mb-2">
                <input type="text" id="payee_name" name="payee_name" class="form-control" placeholder="Payee Name" value="<?php echo isset($branch['payee_name']) ? $branch['payee_name'] : ''; ?>">
            </div>
        </div>
    </div>
    <div class="col-3">
            <button id="info-update-btn" type="submit" class="btn btn-success btn-block" style="margin-top: 20px;">
                <span id="invoice-btn-text"> Update</span>
            </button>
        </div>
</form>
        </div>
    </div>
</div>

<script>
    function toggleGstinInput() {
        const gstinInput = document.getElementById('gstin');
        const confirmGstin = document.getElementById('confirmGstin').value;
        if (confirmGstin == '1') {
            gstinInput.disabled = false;
        } else {
            gstinInput.disabled = true;
            gstinInput.value = '';
        }
    }
</script>

   <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <img src="images/1709008010.png" style="width: 130px; margin-left: 60px; margin-top: -20px;">
                            <hr style="margin-top: -5px;margin-bottom: 30px;">
                            <h6 class="hover-effect"><a href="#">Business Name</a>  <a href=""><i class="fas fa-sync-alt"></i></a></h6>
                            <p style="color: #74788d;">ADMIN</p>
                         
                            <h6 class="hover-effect"><a href="">Super Admin Email</a> <a href=""><i class="fas fa-edit"></i></a>  </h6>
                            <p style="color: #74788d;">admin@gmail.com</p>
                           
                            <h6 class="hover-effect"><a href="">PAN</a></h6>
                            
                            <p style="color: #74788d; display: inline;">KXIPK4991K</p> - <button href="" id="btn-gstin">Update GSTIN</button>
                           
                            <!-- Additional content for the Basic section (if any) -->
                        </div>
                    </div>
                    
                <!-- </div> -->
                  <!-- <div class="col-3" style="margin-top: -50px;"> -->
                    <div class="card">
                        <div class="card-body">
                             <h6 style="font-size:17px; font-weight: 600;">Tax Connect</h6>
                             <hr>
                             <h6 class="hover-effect"><a href="">GST API Connection</a></h6>
                             <a href=""><p style="color: #fd397a !important;">Not Connected</p></a>
                         
                            <h6 class="hover-effect"><a href="">GST Invoice</a></h6>
                            <a href=""><p style="color: #fd397a !important;">Not Active</p></a>

                            <h6 class="hover-effect"><a href="">GST eWay Bill</a></h6>
                            <a href=""><p style="color: #fd397a !important;">Not Active</p></a>
                        </div>
                    </div>
                </div>

            </div>
          
            <div class="row">
            <h6 style="display: inline; margin-top: -5px; margin-left: 15px;">Branches or Stores</h6>
            <a href="#addBranchModal" data-toggle="modal" class="hover-effect" style="margin-left: 635px; margin-top: -5px;">Add Branches or Stores</a>
            <div class="col-9 mt-1">
    <div class="card">
        <div class="card-body">
        <table class="table table-bordered">
    <thead>
        <tr>
            <th scope="col">Branch</th>
            <th scope="col">Contacts</th>
            <th scope="col">Place</th>
            <th scope="col">Action</th> <!-- Added Action column -->
        </tr>
    </thead>
    <tbody>
        <?php
        include('config.php');

        $sql = "SELECT `branch_id`, `branch_name`, `email`, CONCAT(`address_line1`, ' ', `city`, ', ', `state`) AS `address`, `phone_number` FROM `add_branch`";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <th scope='row' style='color: #00acc1;'>{$row['branch_name']}<br>
                        <button href='#addBranchModal' data-toggle='modal' id='btn-gstin' style='background-color: #00acc1; border-color: #00acc1; color: white;'>Update GSTIN</button>
                    </th>
                    <td style='color: #74788d;'>{$row['email']}<br>{$row['phone_number']}</td>
                    <td style='color: #74788d;'>{$row['address']}</td>
                    <td>
                        <button class='btn btn-danger btn-sm' onclick='deleteBranch({$row['branch_id']})'>
                            <i class='fa fa-trash'></i>
                        </button>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4' style='text-align: center;'>No branches found</td></tr>";
        }

        $conn->close();
        ?>
    </tbody>
</table>

<!-- JavaScript for Deleting -->
<script>
    function deleteBranch(branchId) {
        if (confirm("Are you sure you want to delete this branch?")) {
            window.location.href = 'delete_branch.php?branch_id=' + branchId;
        }
    }
</script>

        </div>
    </div>
</div>


                
              
                
            </div>
        </div>
   

        <div class="tab-pane fade" id="banking">
            <div class="row">
                <div class="col-9">
                    <div class="card">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <img src="images/no_acct.png" alt="" style="width: 200px; margin-left: 90px;margin-top: 40px;">
                            </div>
                            <div class="col-7 mt-1 mb-4">
                               <h6 style="font-weight:bold;">Setup Banking on LEDGERS</h6>
                               <p style="line-height: 25px;">LEDGERS can help you easily automate your bank statement 
                                reconciliation, simplify accounting and prepare detailed
                                banking reports. To begin using LEDGERS for banking, 
                                please update your bank account details. Once the details 
                                are updated, you can choose to connect your <b style="color: #00acc1;">LEDGERS with ICICI
                                Bank</b> for real-time reconciliation or manually upload bank 
                                statement.</p>
                                <div class="row">
                                    <div class="col-5">
                                        <button id="add-bank-btn" type="button" class="btn btn-success btn-block">
                                        <span id="add-btn-text">Add Bank Account</span>
                                        </button>
                                    </div>
                                    <div class="col-5">
                                    <button id="add-bank-btn" type="button" class="btn btn-primary btn-block">
                                        <span id="add-btn-text">Open ICICI Account</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
             
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 style="font-weight:bold;">New Bank Account</h6>
                            <hr style="margin-top:-1px;">
                            <div class="did-floating-label-content">
                                <input type="text" id="holder_name" name="holder_name" class="did-floating-input" placeholder="" required>
                                <label for="holder_name" class="did-floating-label">Holder Name</label>
                            </div>
                            <div class="did-floating-label-content">
                                <input type="text" id="acc_number" name="acc_number" class="did-floating-input" placeholder="" required>
                                <label for="acc_number" class="did-floating-label">Account Number</label>
                            </div>
                            <div class="did-floating-label-content">
                                <select id="bank_name" name="bank_name" class="did-floating-input" required>
                                    <option value="">Select Bank</option>
                                    <option value="">Select Bank Name</option>
                                    <option value="ABU DHABI COMMERCIAL BANK">ABU DHABI COMMERCIAL BANK</option>
                                    <option value="ABHYUDAYA COOPERATIVE BANK LIMITED">ABHYUDAYA COOPERATIVE BANK LIMITED</option>
                                    <option value="ALLAHABAD BANK">ALLAHABAD BANK</option>
                                    <option value="ANDHRA BANK">ANDHRA BANK</option>
                                    <option value="AXIS BANK">AXIS BANK</option>
                                    <option value="BANK OF AMERICA">BANK OF AMERICA</option>
                                    <option value="BANK OF BAHARAIN AND KUWAIT BSC">BANK OF BAHARAIN AND KUWAIT BSC</option><option value="BANK OF BARODA">BANK OF BARODA</option><option value="BANK OF CEYLON">BANK OF CEYLON</option><option value="BANK OF INDIA">BANK OF INDIA</option><option value="BANK OF MAHARASHTRA">BANK OF MAHARASHTRA</option><option value="BANK OF TOKYO MITSUBISHI LIMITED">BANK OF TOKYO MITSUBISHI LIMITED</option><option value="BARCLAYS BANK">BARCLAYS BANK</option><option value="BASSEIN CATHOLIC COOPERATIVE BANK LIMITED">BASSEIN CATHOLIC COOPERATIVE BANK LIMITED</option><option value="B N P PARIBAS">B N P PARIBAS</option><option value="CANARA BANK">CANARA BANK</option><option value="CATHOLIC SYRIAN BANK LIMITED">CATHOLIC SYRIAN BANK LIMITED</option><option value="CENTRAL BANK OF INDIA">CENTRAL BANK OF INDIA</option><option value="CHINATRUST COMMERCIAL BANK LIMITED">CHINATRUST COMMERCIAL BANK LIMITED</option><option value="CITI BANK">CITI BANK</option><option value="CITIZEN CREDIT COOPERATIVE BANK LIMITED">CITIZEN CREDIT COOPERATIVE BANK LIMITED</option><option value="CITY UNION BANK LIMITED">CITY UNION BANK LIMITED</option><option value="CORPORATION BANK">CORPORATION BANK</option><option value="CREDIT AGRICOLE CORPORATE AND INVESTMENT BANK CALYON BANK">CREDIT AGRICOLE CORPORATE AND INVESTMENT BANK CALYON BANK</option><option value="DEVELOPMENT BANK OF SINGAPORE">DEVELOPMENT BANK OF SINGAPORE</option><option value="DENA BANK">DENA BANK</option><option value="DEUSTCHE BANK">DEUSTCHE BANK</option><option value="DCB BANK LIMITED">DCB BANK LIMITED</option><option value="DHANALAKSHMI BANK">DHANALAKSHMI BANK</option><option value="DEPOSIT INSURANCE AND CREDIT GUARANTEE CORPORATION">DEPOSIT INSURANCE AND CREDIT GUARANTEE CORPORATION</option><option value="DOMBIVLI NAGARI SAHAKARI BANK LIMITED">DOMBIVLI NAGARI SAHAKARI BANK LIMITED</option><option value="FIRSTRAND BANK LIMITED">FIRSTRAND BANK LIMITED</option><option value="HDFC BANK">HDFC BANK</option><option value="HSBC BANK">HSBC BANK</option><option value="ICICI BANK LIMITED">ICICI BANK LIMITED</option><option value="IDBI BANK">IDBI BANK</option><option value="INDIAN BANK">INDIAN BANK</option><option value="INDIAN OVERSEAS BANK">INDIAN OVERSEAS BANK</option><option value="INDUSIND BANK">INDUSIND BANK</option><option value="ING VYSYA BANK">ING VYSYA BANK</option><option value="JANAKALYAN SAHAKARI BANK LIMITED">JANAKALYAN SAHAKARI BANK LIMITED</option><option value="JANASEVA SAHAKARI BANK LIMITED">JANASEVA SAHAKARI BANK LIMITED</option><option value="KAPOL COOPERATIVE BANK LIMITED">KAPOL COOPERATIVE BANK LIMITED</option><option value="KARNATAKA BANK LIMITED">KARNATAKA BANK LIMITED</option><option value="KARUR VYSYA BANK">KARUR VYSYA BANK</option><option value="KOTAK MAHINDRA BANK LIMITED">KOTAK MAHINDRA BANK LIMITED</option><option value="MAHANAGAR COOPERATIVE BANK">MAHANAGAR COOPERATIVE BANK</option><option value="MAHARASHTRA STATE COOPERATIVE BANK">MAHARASHTRA STATE COOPERATIVE BANK</option><option value="MASHREQBANK PSC">MASHREQBANK PSC</option><option value="MIZUHO CORPORATE BANK LIMITED">MIZUHO CORPORATE BANK LIMITED</option><option value="NEW INDIA COOPERATIVE BANK LIMITED">NEW INDIA COOPERATIVE BANK LIMITED</option><option value="NKGSB COOPERATIVE BANK LIMITED">NKGSB COOPERATIVE BANK LIMITED</option><option value="NUTAN NAGARIK SAHAKARI BANK LIMITED">NUTAN NAGARIK SAHAKARI BANK LIMITED</option><option value="OMAN INTERNATIONAL BANK SAOG">OMAN INTERNATIONAL BANK SAOG</option><option value="ORIENTAL BANK OF COMMERCE">ORIENTAL BANK OF COMMERCE</option><option value="G P PARSIK BANK">G P PARSIK BANK</option><option value="PUNJAB AND MAHARSHTRA COOPERATIVE BANK">PUNJAB AND MAHARSHTRA COOPERATIVE BANK</option><option value="PUNJAB AND SIND BANK">PUNJAB AND SIND BANK</option><option value="PUNJAB NATIONAL BANK">PUNJAB NATIONAL BANK</option><option value="RAJKOT NAGRIK SAHAKARI BANK LIMITED">RAJKOT NAGRIK SAHAKARI BANK LIMITED</option><option value="RESERVE BANK OF INDIA">RESERVE BANK OF INDIA</option><option value="SHINHAN BANK">SHINHAN BANK</option><option value="SOCIETE GENERALE">SOCIETE GENERALE</option><option value="SOUTH INDIAN BANK">SOUTH INDIAN BANK</option><option value="STANDARD CHARTERED BANK">STANDARD CHARTERED BANK</option><option value="STATE BANK OF BIKANER AND JAIPUR">STATE BANK OF BIKANER AND JAIPUR</option><option value="STATE BANK OF HYDERABAD">STATE BANK OF HYDERABAD</option><option value="STATE BANK OF INDIA">STATE BANK OF INDIA</option><option value="STATE BANK OF MAURITIUS LIMITED">STATE BANK OF MAURITIUS LIMITED</option><option value="STATE BANK OF MYSORE">STATE BANK OF MYSORE</option><option value="STATE BANK OF PATIALA">STATE BANK OF PATIALA</option><option value="STATE BANK OF TRAVANCORE">STATE BANK OF TRAVANCORE</option><option value="SYNDICATE BANK">SYNDICATE BANK</option><option value="TAMILNAD MERCANTILE BANK LIMITED">TAMILNAD MERCANTILE BANK LIMITED</option><option value="THE BANK OF NOVA SCOTIA">THE BANK OF NOVA SCOTIA</option><option value="AHMEDABAD MERCANTILE COOPERATIVE BANK">AHMEDABAD MERCANTILE COOPERATIVE BANK</option><option value="BHARAT COOPERATIVE BANK MUMBAI LIMITED">BHARAT COOPERATIVE BANK MUMBAI LIMITED</option><option value="THE COSMOS CO OPERATIVE BANK LIMITED">THE COSMOS CO OPERATIVE BANK LIMITED</option><option value="FEDERAL BANK">FEDERAL BANK</option><option value="THE GREATER BOMBAY COOPERATIVE BANK LIMITED">THE GREATER BOMBAY COOPERATIVE BANK LIMITED</option><option value="JAMMU AND KASHMIR BANK LIMITED">JAMMU AND KASHMIR BANK LIMITED</option><option value="KALUPUR COMMERCIAL COOPERATIVE BANK">KALUPUR COMMERCIAL COOPERATIVE BANK</option><option value="THE KARANATAKA STATE COOPERATIVE APEX BANK LIMITED">THE KARANATAKA STATE COOPERATIVE APEX BANK LIMITED</option><option value="KALYAN JANATA SAHAKARI BANK">KALYAN JANATA SAHAKARI BANK</option><option value="LAXMI VILAS BANK">LAXMI VILAS BANK</option><option value="THE MEHSANA URBAN COOPERATIVE BANK">THE MEHSANA URBAN COOPERATIVE BANK</option><option value="THE NAINITAL BANK LIMITED">THE NAINITAL BANK LIMITED</option><option value="RBL Bank Limited">RBL Bank Limited</option><option value="THE ROYAL BANK OF SCOTLAND N V">THE ROYAL BANK OF SCOTLAND N V</option><option value="SARASWAT COOPERATIVE BANK LIMITED">SARASWAT COOPERATIVE BANK LIMITED</option><option value="THE SHAMRAO VITHAL COOPERATIVE BANK">THE SHAMRAO VITHAL COOPERATIVE BANK</option><option value="THE SURATH PEOPLES COOPERATIVE BANK LIMITED">THE SURATH PEOPLES COOPERATIVE BANK LIMITED</option><option value="THE TAMIL NADU STATE APEX COOPERATIVE BANK">THE TAMIL NADU STATE APEX COOPERATIVE BANK</option><option value="TJSB SAHAKARI BANK LTD">TJSB SAHAKARI BANK LTD</option><option value="THE WEST BENGAL STATE COOPERATIVE BANK">THE WEST BENGAL STATE COOPERATIVE BANK</option><option value="UCO BANK">UCO BANK</option><option value="UNION BANK OF INDIA">UNION BANK OF INDIA</option><option value="UNITED BANK OF INDIA">UNITED BANK OF INDIA</option><option value="VIJAYA BANK">VIJAYA BANK</option><option value="YES BANK">YES BANK</option><option value="THE ANDHRA PRADESH STATE COOPERATIVE BANK LIMITED">THE ANDHRA PRADESH STATE COOPERATIVE BANK LIMITED</option><option value="THE KARAD URBAN COOPERATIVE BANK LIMITED">THE KARAD URBAN COOPERATIVE BANK LIMITED</option><option value="THE NASIK MERCHANTS COOPERATIVE BANK LIMITED">THE NASIK MERCHANTS COOPERATIVE BANK LIMITED</option><option value="ALMORA URBAN COOPERATIVE BANK LIMITED">ALMORA URBAN COOPERATIVE BANK LIMITED</option><option value="APNA SAHAKARI BANK LIMITED">APNA SAHAKARI BANK LIMITED</option><option value="AUSTRALIA AND NEW ZEALAND BANKING GROUP LIMITED">AUSTRALIA AND NEW ZEALAND BANKING GROUP LIMITED</option><option value="CAPITAL SMALL FINANCE BANK LIMITED">CAPITAL SMALL FINANCE BANK LIMITED</option><option value="CREDIT SUISEE AG">CREDIT SUISEE AG</option><option value="JALGAON JANATA SAHAKARI BANK LIMITED">JALGAON JANATA SAHAKARI BANK LIMITED</option><option value="JANATA SAHAKARI BANK LIMITED">JANATA SAHAKARI BANK LIMITED</option><option value="KALLAPPANNA AWADE ICHALKARANJI JANATA SAHAKARI BANK LIMITED">KALLAPPANNA AWADE ICHALKARANJI JANATA SAHAKARI BANK LIMITED</option><option value="THE MUMBAI DISTRICT CENTRAL COOPERATIVE BANK LIMITED">THE MUMBAI DISTRICT CENTRAL COOPERATIVE BANK LIMITED</option><option value="PRIME COOPERATIVE BANK LIMITED">PRIME COOPERATIVE BANK LIMITED</option><option value="RABOBANK INTERNATIONAL">RABOBANK INTERNATIONAL</option><option value="THE THANE BHARAT SAHAKARI BANK LIMITED">THE THANE BHARAT SAHAKARI BANK LIMITED</option><option value="THE A.P. MAHESH COOPERATIVE URBAN BANK LIMITED">THE A.P. MAHESH COOPERATIVE URBAN BANK LIMITED</option><option value="THE GUJARAT STATE COOPERATIVE BANK LIMITED">THE GUJARAT STATE COOPERATIVE BANK LIMITED</option><option value="KARNATAKA VIKAS GRAMEENA BANK">KARNATAKA VIKAS GRAMEENA BANK</option><option value="THE MUNICIPAL COOPERATIVE BANK LIMITED">THE MUNICIPAL COOPERATIVE BANK LIMITED</option><option value="NAGPUR NAGARIK SAHAKARI BANK LIMITED">NAGPUR NAGARIK SAHAKARI BANK LIMITED</option><option value="THE KANGRA CENTRAL COOPERATIVE BANK LIMITED">THE KANGRA CENTRAL COOPERATIVE BANK LIMITED</option><option value="THE RAJASTHAN STATE COOPERATIVE BANK LIMITED">THE RAJASTHAN STATE COOPERATIVE BANK LIMITED</option><option value="THE SURAT DISTRICT COOPERATIVE BANK LIMITED">THE SURAT DISTRICT COOPERATIVE BANK LIMITED</option><option value="THE VISHWESHWAR SAHAKARI BANK LIMITED">THE VISHWESHWAR SAHAKARI BANK LIMITED</option><option value="WOORI BANK">WOORI BANK</option><option value="SUTEX COOPERATIVE BANK LIMITED">SUTEX COOPERATIVE BANK LIMITED</option><option value="GURGAON GRAMIN BANK">GURGAON GRAMIN BANK</option><option value="COMMONWEALTH BANK OF AUSTRALIA">COMMONWEALTH BANK OF AUSTRALIA</option><option value="PRATHAMA BANK">PRATHAMA BANK</option><option value="NORTH MALABAR GRAMIN BANK">NORTH MALABAR GRAMIN BANK</option><option value="THE VARACHHA COOPERATIVE BANK LIMITED">THE VARACHHA COOPERATIVE BANK LIMITED</option><option value="SHRI CHHATRAPATI RAJASHRI SHAHU URBAN COOPERATIVE BANK LIMITED">SHRI CHHATRAPATI RAJASHRI SHAHU URBAN COOPERATIVE BANK LIMITED</option><option value="SBER BANK">SBER BANK</option><option value="TUMKUR GRAIN MERCHANTS COOPERATIVE BANK LIMITED">TUMKUR GRAIN MERCHANTS COOPERATIVE BANK LIMITED</option><option value="VASAI VIKAS SAHAKARI BANK LIMITED">VASAI VIKAS SAHAKARI BANK LIMITED</option><option value="VASAI VIKAS SAHAKARI BANK LTD">VASAI VIKAS SAHAKARI BANK LTD</option><option value="WESTPAC BANKING CORPORATION">WESTPAC BANKING CORPORATION</option><option value="ANDHRA PRAGATHI GRAMEENA BANK">ANDHRA PRAGATHI GRAMEENA BANK</option><option value="SUMITOMO MITSUI BANKING CORPORATION">SUMITOMO MITSUI BANKING CORPORATION</option><option value="THE SEVA VIKAS COOPERATIVE BANK LIMITED">THE SEVA VIKAS COOPERATIVE BANK LIMITED</option><option value="THE THANE DISTRICT CENTRAL COOPERATIVE BANK LIMITED">THE THANE DISTRICT CENTRAL COOPERATIVE BANK LIMITED</option><option value="JP MORGAN BANK">JP MORGAN BANK</option><option value="THE GADCHIROLI DISTRICT CENTRAL COOPERATIVE BANK LIMITED">THE GADCHIROLI DISTRICT CENTRAL COOPERATIVE BANK LIMITED</option><option value="THE AKOLA DISTRICT CENTRAL COOPERATIVE BANK">THE AKOLA DISTRICT CENTRAL COOPERATIVE BANK</option><option value="THE KURMANCHAL NAGAR SAHAKARI BANK LIMITED">THE KURMANCHAL NAGAR SAHAKARI BANK LIMITED</option><option value="THE JALGAON PEOPELS COOPERATIVE BANK LIMITED">THE JALGAON PEOPELS COOPERATIVE BANK LIMITED</option><option value="NATIONAL AUSTRALIA BANK LIMITED">NATIONAL AUSTRALIA BANK LIMITED</option><option value="SAHEBRAO DESHMUKH COOPERATIVE BANK LIMITED">SAHEBRAO DESHMUKH COOPERATIVE BANK LIMITED</option><option value="BANK INTERNASIONAL INDONESIA">BANK INTERNASIONAL INDONESIA</option><option value="SOLAPUR JANATA SAHAKARI BANK LIMITED">SOLAPUR JANATA SAHAKARI BANK LIMITED</option><option value="INDUSTRIAL AND COMMERCIAL BANK OF CHINA LIMITED">INDUSTRIAL AND COMMERCIAL BANK OF CHINA LIMITED</option><option value="UNITED OVERSEAS BANK LIMITED">UNITED OVERSEAS BANK LIMITED</option><option value="ZILA SAHAKRI BANK LIMITED GHAZIABAD">ZILA SAHAKRI BANK LIMITED GHAZIABAD</option><option value="JANASEVA SAHAKARI BANK BORIVLI LIMITED">JANASEVA SAHAKARI BANK BORIVLI LIMITED</option><option value="THE DELHI STATE COOPERATIVE BANK LIMITED">THE DELHI STATE COOPERATIVE BANK LIMITED</option><option value="RAJGURUNAGAR SAHAKARI BANK LIMITED">RAJGURUNAGAR SAHAKARI BANK LIMITED</option><option value="NAGAR URBAN CO OPERATIVE BANK">NAGAR URBAN CO OPERATIVE BANK</option><option value="AKOLA JANATA COMMERCIAL COOPERATIVE BANK">AKOLA JANATA COMMERCIAL COOPERATIVE BANK</option><option value="BHARATIYA MAHILA BANK LIMITED">BHARATIYA MAHILA BANK LIMITED</option><option value="HSBC BANK OMAN SAOG">HSBC BANK OMAN SAOG</option><option value="THE KANGRA COOPERATIVE BANK LIMITED">THE KANGRA COOPERATIVE BANK LIMITED</option><option value="THE ZOROASTRIAN COOPERATIVE BANK LIMITED">THE ZOROASTRIAN COOPERATIVE BANK LIMITED</option><option value="SHIKSHAK SAHAKARI BANK LIMITED">SHIKSHAK SAHAKARI BANK LIMITED</option><option value="THE HASTI COOP BANK LTD">THE HASTI COOP BANK LTD</option><option value="KERALA GRAMIN BANK">KERALA GRAMIN BANK</option><option value="PRAGATHI KRISHNA GRAMIN BANK">PRAGATHI KRISHNA GRAMIN BANK</option><option value="DOHA BANK QSC">DOHA BANK QSC</option><option value="DOHA BANK">DOHA BANK</option><option value="EXPORT IMPORT BANK OF INDIA">EXPORT IMPORT BANK OF INDIA</option><option value="TJSB SAHAKARI BANK LIMITED">TJSB SAHAKARI BANK LIMITED</option><option value="BANDHAN BANK LIMITED">BANDHAN BANK LIMITED</option><option value="SURAT NATIONAL COOPERATIVE BANK LIMITED">SURAT NATIONAL COOPERATIVE BANK LIMITED</option><option value="IDFC BANK LIMITED">IDFC BANK LIMITED</option><option value="INDUSTRIAL BANK OF KOREA">INDUSTRIAL BANK OF KOREA</option><option value="SBM BANK MAURITIUS LIMITED">SBM BANK MAURITIUS LIMITED</option><option value="NATIONAL BANK OF ABU DHABI PJSC">NATIONAL BANK OF ABU DHABI PJSC</option><option value="KEB Hana Bank">KEB Hana Bank</option><option value="THE PANDHARPUR URBAN CO OP. BANK LTD. PANDHARPUR">THE PANDHARPUR URBAN CO OP. BANK LTD. PANDHARPUR</option><option value="SAMARTH SAHAKARI BANK LTD">SAMARTH SAHAKARI BANK LTD</option><option value="SHIVALIK MERCANTILE CO OPERATIVE BANK LTD">SHIVALIK MERCANTILE CO OPERATIVE BANK LTD</option><option value="EQUITAS SMALL FINANCE BANK LIMITED">EQUITAS SMALL FINANCE BANK LIMITED</option><option value="JANATHA SEVA COOPERATIVE BANK LIMITED">JANATHA SEVA COOPERATIVE BANK LIMITED</option><option value="SARDAR VALLABHBHAI CO. SAHAKARI BANK">SARDAR VALLABHBHAI CO. SAHAKARI BANK</option><option value="UJJIVAN SMALL FINANCE BANK">UJJIVAN SMALL FINANCE BANK</option>
                                </select>
                            </div>
                            <div class="did-floating-label-content">
                                <input type="text" id="ifsc_code" name="ifsc_code" class="did-floating-input" placeholder="" required>
                                <label for="ifsc_code" class="did-floating-label">IFSC Code</label>
                            </div>
                            <div class="did-floating-label-content">
                                <select id="account_type" name="account_type" class="did-floating-input" required>
                                    <option value="">Select Account Type</option>
                                    <option value="Current Account">Current Account</option>
                                    <option value="Savings Account">Savings Account</option>
                                    <option value="Overdraft Account">Overdraft Account</option>
                                    <option value="Credit Card">Credit Card</option>
                                </select>
                            </div>
                            <button id="add-bank-btn" type="button" class="btn btn-success btn-block">
                                        <span id="add-btn-text">Add Bank Account</span>
                                        </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          
        <div class="tab-pane fade" id="user">
            <div class="row">
                <div class="col-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <ul class="filter-list list-unstyled">
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <li>
                                                    <div class="dropdown mx-2">
                                                        <a class="btn btn-secondary dropdown-toggle" href="#" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Manage User</a>
                                                        <!-- Dropdown content -->
                                                        <div class="dropdown-menu" aria-labelledby="userDropdown">
                                                            <a class="dropdown-item mfilter" data-filter="pi" onclick="selectDropdownOption(this, 'userCard')">Manage User</a>
                                                            <a class="dropdown-item mfilter" data-filter="po" onclick="selectDropdownOption(this, 'ipCard')">Allowed IPs</a>
                                                            <a class="dropdown-item mfilter" data-filter="vi" onclick="selectDropdownOption(this, 'bankingCard')">Banking</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </div>
                                            <script>
                                                function selectDropdownOption(element) {
                                                    var selectedOptionText = element.textContent;
                                                    document.getElementById("manageUser").innerText = selectedOptionText;
                                                }
                                            </script>
                                            <div class="col-lg-4">
                                                <ul class="nav">
                                                    <li class="nav-item">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" style="width: 200px; height: 43px;" placeholder="Search..." id="generalSearch1">
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2"></div>
                                            <div class="col-lg-2">
                                                <button id="add-user-btn btn-sm" href="#addUserModal" data-toggle="modal" type="button" class="btn btn-success btn-block">
                                                    <span id="add-btn-text">Add Users</span>
                                                </button>
                                            </div>
                                            <div class="col-lg-2"  >
                                            <div class="dt-buttons btn-group btns_gst">
                                                <select class="form-control user-type-filter2" style="height: 43px;">
                                                    <option value="">All</option>
                                                    <optgroup label="Active">
                                                        <option value="1">Billing</option>
                                                        <option value="2">Sales Terminal</option>
                                                        <option value="3">Purchase</option>
                                                        <option value="4">Administrator</option>
                                                    </optgroup>
                                                    <optgroup label="Inactive">
                                                        <option value="5">Billing</option>
                                                        <option value="6">Sales Terminal</option>
                                                        <option value="7">Purchase</option>
                                                        <option value="8">Administrator</option>                                                   
                                                    </optgroup>
                                                </select>  
                                            </div>

                                            </div>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                          <!-- Search Bar -->                                                      
                        </div>
                    </div>
                </div>
               

                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                        <div class="widget-item">
                         <div class="row">
                            <div class="widget-info" style="margin-left: 15px;">
                                <span class="widget-username">Administrators</span>
                            </div>
                            <span class="count-font bold" style="margin-left: 100px;">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-success total-admins" data-toggle="tooltip" data-container="body" title="Active">0</button>
                                    <button type="button" class="btn btn-outline-danger total-inactive-admins" data-toggle="tooltip" data-container="body" title="Inactive">0</button>
                                </div>
                            </span>
                            </div>
                        </div>
                       <hr>
                        <div class="widget-item mt-3">
                        <div class="row">
                            <div class="widget-info" style="margin-left: 15px;">
                                <span class="widget-username">Billing</span>
                            </div>
                            <span class="count-font bold" style="margin-left: 157px;">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-success total-billing" data-toggle="tooltip" data-container="body" title="Active">0</button>
                                    <button type="button" class="btn btn-outline-danger total-inactive-billing" data-toggle="tooltip" data-container="body" title="Inactive">0</button>
                                </div>
                            </span>
                        </div>
                        </div>
                        <hr>
                        <div class="widget-item mt-3">
                        <div class="row">
                            <div class="widget-info" style="margin-left: 15px;">
                                <span class="widget-username">Sales Terminal</span>
                            </div>
                            <span class="count-font bold" style="margin-left: 102px;">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-success total-sales-terminal" data-toggle="tooltip" data-container="body" title="Active">0</button>
                                    <button type="button" class="btn btn-outline-danger total-inactive-sales-terminal" data-toggle="tooltip" data-container="body" title="Inactive">0</button>
                                </div>
                            </span>
                            </div>
                        </div>
                        <hr>
                        <div class="widget-item mt-3">
                        <div class="row">
                            <div class="widget-info"  style="margin-left: 15px;">
                                <span class="widget-username">Purchase Managers</span>
                            </div>
                            <span class="count-font bold"  style="margin-left: 68px;">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-success total-purchase-managers" data-toggle="tooltip" data-container="body" title="Active">0</button>
                                    <button type="button" class="btn btn-outline-danger total-inactive-purchase-managers" data-toggle="tooltip" data-container="body" title="Inactive">0</button>
                                </div>
                            </span>
                            </div>
                        </div>

                        </div>
                    </div>
                </div>
            </div>

        <div class="row" style="margin-top: -140px;">
        <div class="col-md-9" style="margin-top: -60px;">
        <div id="userCard" class="dropdown-card active-card">
        <div class="card">
            <div class="card-body">
            <h5 class="card-title">Manage Users</h5>
            <!-- Add your table content here -->
            <table class="table table-bordered" id="users-datatable">
                <!-- Table content goes here -->
                <thead>
                    <tr>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Access</th>
                    <th>Created By</th>
                    </tr>
               </thead>
                <tbody>
                    <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
        </div>
        </div>
        </div>

        <div class="row mt-3">
        <div class="col-md-9" style="margin-top: -80px;">
        <div id="ipCard" class="dropdown-card">
        <div class="card">
            <div class="card-body">
            <h5 class="card-title">Allowed IPS</h5>
            <!-- Add your table content here -->
            <table class="table table-bordered" id="users-datatable">
                <!-- Table content goes here -->
                <thead>
                    <tr>
                    <th>Action</th>
                    <th>ips</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    </tr>
               </thead>
                <tbody>
                    <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
        </div>
        </div>
        </div>
        <div class="row mt-3">
        <div class="col-md-9" style="margin-top: -95px;">
        <div id="bankingCard" class="dropdown-card">
        <div class="card">
            <div class="card-body">
            <h5 class="card-title">Manage Banking</h5>
            <!-- Add your table content here -->
            <table class="table table-bordered" id="users-datatable">
                <!-- Table content goes here -->
                <thead>
                    <tr>
                    <th>Name</th>
                    <th>Max Fund Transfer</th>
                    <th>Bank Statement</th>
                    <th>View Balance</th>
                    </tr>
               </thead>
                <tbody>
                    <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
        </div>
        </div>
        </div>

        <script>
    function selectDropdownOption(element, cardId) {
        var selectedOptionText = element.textContent;
        document.getElementById("manageUser").innerText = selectedOptionText;

        // Hide all cards
        document.querySelectorAll('.dropdown-card').forEach(card => {
            card.classList.remove('active-card');
        });

        // Show the selected card
        const selectedCard = document.getElementById(cardId);
        if (selectedCard) {
            selectedCard.classList.add('active-card');
        }

        // Update URL with card parameter
        const newUrl = window.location.pathname + '?' + cardId;
        window.history.pushState({}, '', newUrl);
    }
</script>

        </div>

        
      
        <div class="tab-pane fade" id="kyc-docs">
            <div class="row">
                <div class="col-9">
                    <div class="card">
                        <div class="card-body">
                            <!-- Basic content goes here -->
                            <p>This is the content for Basic.</p>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <!-- Additional content for the Basic section (if any) -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="collect-payments">
            <div class="row">
                <div class="col-9">
                    <div class="card">
                            <div class="col-md-12">
                                <div class="row align-items-center">
                                    <div class="col-md-2 col-lg-2 text-center">
                                        <img src="images/LEDGERS-pay-online.png" class="img-fluid" style="width:120px;">
                                    </div>
                                    <div class="col-md-7 col-lg-7 border-left border-right">
                                        <h5 class="mb-0 pl-3 pt-3" style="font-size:14px;">Ledgers Payment Gateway</h5>
                                        <p class="mb-0 pl-3 pb-3">Get payments through credit card, debit card, net banking, UPI, EMI, Wallets and cardless EMI supported by Ledgers Payment Gateway.</p>
                                    </div>
                                    <div class="col-md-3 col-lg-3 text-center led_noact_btn">
                                        <button class="btn btn-sm btn-primary ledgers_update" type="button" >Activate</button>
                                    </div>
                                    <div class="col-md-3 col-lg-3 text-center led_act_btn" style="display:none;">
                                        <button class="btn btn-sm btn-success" type="button">Enabled</button>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                        <div class="card" style="margin-top:-15px;">
                            <div class="col-md-12">
                            <div class="row align-items-center">
                                    <div class="col-md-2 col-lg-2 text-center">
                                        <img src="images/upi-logo.png" class="img-fluid" style="width:90px;">
                                    </div>
                                    <div class="col-md-7 col-lg-7 border-left border-right">
                                        <h5 class="mb-0 pl-3 pt-3" style="font-size:14px;">UPI</h5>
                                        <p class="mb-0 pl-3 pb-3">UPI is the fastest growing payment mode. Please update UPI id to accept UPI payments on your estimates and invoices.</p>
                                    </div>
                                    <div class="col-md-3 col-lg-3 text-center upi_btn_sec">
                                        <button class="btn btn-sm btn-primary" type="button">Activate</button>
                                    </div>
                                </div>
                                <div class="row align-items-center upi_edit">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="border-top pl-3 p-2">                                    
                                        <a href="#" class="kt-font-bold" data-toggle="modal" data-target="#upi_modal">Update UPI ID</a>
                                        </div>
                                    </div>                                
                                </div>
                            </div>
                        </div>
                        <div class="card" style="margin-top:-15px;">
                        <div class="row align-items-center">
                                    <div class="col-md-2 col-lg-2 text-center">
                                        <img src="images/ccavenue-min-logo.png" class="img-fluid" style="width:120px;">
                                    </div>
                                    <div class="col-md-7 col-lg-7 border-left border-right">
                                        <h5 class="mb-0 pl-3 pt-3" style="font-size:14px;">CC Avenue</h5>
                                        <p class="mb-0 pl-3 pb-3">Get payments through credit card, debit card, net banking, UPI, EMI, Wallets and other payment options supported by ccavenue payment gateway. ccavenue account required.</p>
                                    </div>
                                    <div class="col-md-3 col-lg-3 text-center cca_noact_btn">
                                        <button class="btn btn-sm btn-primary cc_update" type="button" data-pay="cc" style="margin-left: -23px;" data-toggle="modal" data-target="#cc_modal">Activate</button>
                                    </div>
                                    <div class="col-md-3 col-lg-3 text-center cca_act_btn" style="display:none;">
                                        <button class="btn btn-sm btn-success" type="button" >Enabled</button>
                                    </div>
                                </div>
                        </div>
                        </div>
              
                        <div class="col-3">
                            <div class="card">
                                <div class="card-body">                                                          
                                    <div class="row">
                                        <div class="col-md-12 text-center qr_sec" style="">
                                            <div  style="background: #F8FAFC;"><p class="mb-0 upi_id"></p></div>                    
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <img id="qrious" class="img-fluid qr_sec" style="margin-left:auto;margin-right:auto;height:170px;width:370px;display: none;" src=""> 
                                            <a href="#" data-toggle="modal" data-target="#upi_modal"><img class="img-fluid no_qr_sec" style="margin-left:auto;margin-right:auto;height:170px;width:170px;cursor:pointer;" src="images/Setup-UPI-QR.png"></a>
                                            <img class="img-fluid " style="margin-left:auto;margin-right:auto;height:170px;width:170px;cursor:pointer;display: none;" src="images/UPI-Disabled.png">
                                        </div>
                                        <div class="col-md-12 text-center mt-3">
                                            <p class="mt-1 qr_text">Scan and pay with any BHIM UPI app</p>
                                        </div>
                                        <div class="col-md-12">
                                            <ul class="pl-0 mb-0 qr_ul" style="list-style-type: none; display: flex; justify-content: center; align-items: center;">
                                                <li> 
                                                    <img src="images/upi-logo.png" class="img-fluid" style="width: 80px;">
                                                </li>
                                                <li>
                                                    <img src="images/phone-pe-logo.png" class="img-fluid mx-3" style="width: 120px;">
                                                </li>
                                                <li>
                                                    <img src="images/gpay-logo.jpg" class="img-fluid mx-3" style="width: 120px;">
                                                </li>
                                                <!-- <li><img src="/m/app/assets/images/Paytm_logo.png" class="img-fluid" style="width: 100px;"></li> -->
                                            </ul>
                                        </div>   
                                    </div>
                             </div>
                         </div>
                       </div>
            </div>
            </div>
        <div class="tab-pane fade" id="invoice">
            <div class="row">
                <div class="col-9">
                    <div class="card">
                        <div class="card-body">
                            <!-- Basic content goes here -->
                            <p>This is the content for Basic.</p>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <!-- Additional content for the Basic section (if any) -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="advanced">
            <div class="row">
                <div class="col-9">
                    <div class="card">
                        <div class="card-body">
                            <!-- Basic content goes here -->
                            <p>This is the content for Basic.</p>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <!-- Additional content for the Basic section (if any) -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="api-docs">
            <div class="row">
                <div class="col-9">
                    <div class="card">
                        <div class="card-body">
                            <!-- Basic content goes here -->
                            <p>This is the content for Basic.</p>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <!-- Additional content for the Basic section (if any) -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="addBranchModal" tabindex="-1" role="dialog" style="margin-top: 28px;">
    <div class="modal-dialog" role="document" style="max-width: 800px;">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <!-- Tabs navigation -->
                <ul class="nav nav-tabs" id="Tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="gstin-tab" data-toggle="tab" href="#gstintab" role="tab" aria-controls="gstin" aria-selected="true">GSTIN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="false">Information</a>
                    </li>
                </ul>

                <!-- Single Form for Both Tabs -->
                <form id="addBranchForm" method="POST" action="branchdb.php">
                    <div class="tab-content mt-3" id="myTabContent">
                        <!-- GSTIN Tab -->
                        <div class="tab-pane fade show active" id="gstintab" role="tabpanel" aria-labelledby="gstin-tab">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="branch_name">Branch Name<span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="Enter Branch Name" required>
                                     <input type="text" class="form-control" id="additional_business_name" name="additional_business_name"  hidden>
                                       <input type="text" class="form-control" id="b_business_name" name="b_business_name"  hidden>
                                </div>
                                <div class="form-group col-md-6">
    <label for="email">Email<span style="color: red;">*</span></label>
    <input type="text" class="form-control" id="email1" name="bemail" placeholder="Enter Email" required>
</div>
                                <div class="form-group col-md-6">
                                    <label for="gst">GSTIN</label>
                                    <input type="text" class="form-control" id="gst" name="gst" placeholder="Enter GSTIN" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="address_line1">Address Line 1 <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="branch_address_line1" name="branch_address_line1" placeholder="Enter address line 1" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="address_line2">Address Line 2 <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="branch_address_line2" name="branch_address_line2" placeholder="Enter address line 2" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="city">City <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="Enter city" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="pincode">Pincode <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Enter pincode" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="state">State <span style="color: red;">*</span></label>
                                    <select class="form-control" id="state" name="state" required>
                                        <option value="">Select State</option>
                                    
                                        <option value="Andhra Pradesh">Andhra Pradesh</option>
                                        <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                        <option value="Assam">Assam</option>
                                        <option value="Bihar">Bihar</option>
                                        <option value="Chhattisgarh">Chhattisgarh</option>
                                        <option value="Goa">Goa</option>
                                        <option value="Gujarat">Gujarat</option>
                                        <option value="Haryana">Haryana</option>
                                        <option value="Himachal Pradesh">Himachal Pradesh</option>
                                        <option value="Jharkhand">Jharkhand</option>
                                        <option value="Karnataka">Karnataka</option>
                                        <option value="Kerala">Kerala</option>
                                        <option value="Madhya Pradesh">Madhya Pradesh</option>
                                        <option value="Maharashtra">Maharashtra</option>
                                        <option value="Manipur">Manipur</option>
                                        <option value="Meghalaya">Meghalaya</option>
                                        <option value="Mizoram">Mizoram</option>
                                        <option value="Nagaland">Nagaland</option>
                                        <option value="Odisha">Odisha</option>
                                        <option value="Punjab">Punjab</option>
                                        <option value="Rajasthan">Rajasthan</option>
                                        <option value="Sikkim">Sikkim</option>
                                        <option value="Tamil Nadu">Tamil Nadu</option>
                                        <option value="Telangana">Telangana</option>
                                        <option value="Tripura">Tripura</option>
                                        <option value="Uttar Pradesh">Uttar Pradesh</option>
                                        <option value="Uttarakhand">Uttarakhand</option>
                                        <option value="West Bengal">West Bengal</option>
                                        <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                        <option value="Chandigarh">Chandigarh</option>
                                        <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                                        <option value="Daman and Diu">Daman and Diu</option>
                                        <option value="Lakshadweep">Lakshadweep</option>
                                        <option value="Delhi">Delhi</option>
                                        <option value="Puducherry">Puducherry</option>
                                  </select>
                                   <input type="text" class="form-control" id="branch_state_code" name="branch_state_code" hidden>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="country">Country <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="country" name="country" placeholder="Enter country" required>
                                </div>
                            </div>
                            <div class="form-row">
                            <div class="form-group col-md-6">
                                    <label for="billing_scheme">Select GST Filing Scheme</label>
                                    <select class="form-control" id="billing_scheme" name="billing_scheme" >
                                        <option value="">Select Scheme</option>
                                        <option value="QRMP-Quaterly Filing">QRMP-Quaterly Filing</option>
                                        <option value="Regular">Regular</option>
                                    </select>
                            </div>
                            </div>
                       
                    </div>

                      <!-- Information Tab -->
                      <div class="tab-pane fade" id="info" role="tabpanel" aria-labelledby="info-tab">
                            <div class="form-row">
                            <div class="form-group col-md-6">
    <label for="office_email">Office Email<span style="color: red;">*</span></label>
    <input type="text" class="form-control" id="office_email" name="office_email" placeholder="Enter Office Email" >
</div>
                                <div class="form-group col-md-6">
                                    <label for="phone_number">Office Telephone Number</label>
                                    <input type="text" class="form-control" id="phone_number1" name="phone_number1" placeholder="Enter Office Number" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                <label for="nature_of_premises">Nature of Premises</label>
                                    <select class="form-control" id="nature_of_premises" name="nature_of_premises" >
                                        <option value="Nature of Premises">Select Nature of Premises</option>
                                        <option value="Leased">Leased</option>
                                        <option value="Rented">Rented</option>
                                        <option value="Consent">Consent</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nature_of_business">Select Nature of Bussiness</label>
                                    <select class="form-control" id="nature_of_business" name="nature_of_business">
                                        <option value="">Select Nature of Bussiness</option>
                                        <option value="Wholesale Business">Wholesale Business</option>
										<option value="Retail Business">Retail Business</option>
										<option value="Warehouse/Depot">Warehouse/Depot</option>
										<option value="Bonded Warehouse">Bonded Warehouse</option>
									    <option value="Supplier of services">Supplier of services</option>
										<option value="Office/Sale Office">Office/Sale Office</option>
										<option value="Leasing Business">Leasing Business</option>
										<option value="Recipient of goods or services">Recipient of goods or services</option>
										<option value="EOU/ STP/ EHTP">EOU/ STP/ EHTP</option>
										<option value="Works Contract">Works Contract</option>
										<option value="Export">Export</option>
										<option value="Import">Import</option>
										<option value="Others">Others</option>
									</select>                                  
                                </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                    <input type="text" class="form-control" id="taluka" name="taluka" placeholder="Taluka/Block">
                                    </div>
                                    <!-- <div class="form-group col-md-6">
                                       <a href="#add-map" id="locval" data-target="#m_modal_5" data-toggle="modal" class="form-control location">Latitude and Longitude</a>
									   <input type="hidden" name="location">
                                    </div> -->
                                </div>
                                <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="Active">Active</option>
                                        <option value="Disabled">Disabled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/stateMapping.js"></script>
<script>

    document.getElementById('gst').addEventListener('blur', function () {
        const gstin = this.value;

        if (gstin.length > 0) {
            fetch('get_gst_details.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `gstin=${gstin}`,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Populate fields dynamically
                        document.getElementById('branch_name').value = data.LegalName || '';
                        document.getElementById('b_business_name').value = data.TradeName || '';
                        document.getElementById('additional_business_name').value = data.TradeName || '';
                        // document.getElementById('display_name').value = data.LegalName || '';
                        document.getElementById('branch_address_line1').value = data.AddrBno + "," + data.AddrBnm || '';
                        document.getElementById('branch_address_line2').value = data.AddrSt || '';
                        document.getElementById('city').value = data.AddrLoc || '';
                        document.getElementById('pincode').value = data.AddrPncd || '';
                        document.getElementById('branch_state_code').value = data.StateCode || '';
                        console.log(data);

                          const stateName = stateMapping[data.StateCode] || '';
                    document.getElementById('state').value = stateName;
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching GSTIN details:', error);
                    alert('An error occurred. Please try again.');
                });
        }
    });  


</script>
<script>
//    $(document).ready(function() {
//     $('#tabs a').click(function(e) {
//         e.preventDefault();
//         $(this).tab('show');
//     });
// });
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('#Tabs a');
    tabs.forEach((tab) => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            document.querySelectorAll('.tab-pane').forEach((pane) => pane.classList.remove('show', 'active'));
            target.classList.add('show', 'active');
        });
    });
});

</script>
<!--begin::Modal-->
<div class="modal fade" id="m_modal_5" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #00acc1;color: #fff; padding: 15px;">
				<h5 class="modal-title" id="exampleModalLabel" style="color: #fff;">
					Location Information
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff;">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<p style="margin-top: 9px;margin-bottom: 0;font-size: 15px;padding-left: 17px;">Zoom out, drag the marker to your
				city, zoom in and place the marker at your business location.</p>
			<div class="modal-body" style="padding-bottom: 0;">
				<form>
					<div class="form-group">
						<div class="row">
							<div class="col-lg-9">
								<div id="gmapsbasic" style="height: 300px;width: 100%"></div>
							</div>
							<div class="col-lg-3">
								<input disabled type="text" class="lati form-control">
								<input disabled type="text" class="longi form-control" style="margin: 10px 0">
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer" style="padding:15px;">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">
					Close
				</button>
				<button type="button" id="map_update" class="btn btn-primary">
					Save
				</button>
			</div>
		</div>
	</div>
</div>
<!--end::Modal-->

<!-- USER modal -->
<div class="modal" id="addUserModal" tabindex="-1" role="dialog" style="margin-top: 28px;">
    <div class="modal-dialog" role="document" style="max-width: 900px;">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for adding a branch -->
                <form id="addBranchForm1">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <div class="input-group">
                            <select class="form-control" name="title" id="title">
                                <option value="">Title</option>
                                <option value="MR.">MR.</option>
                                <option value="MRS.">MRS.</option>
                                <option value="MISS.">MISS.</option>
                                <option value="MS.">MS.</option>
                            </select>
                            <input type="text" class="form-control" name="cust_name" style="width:65%;" placeholder="User Name*" id="cust_name">
                        </div>
                    </div>
                    <div class="form-group col-md-6">

                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile Number" required>
                    </div>
                </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">Email <span style="color: red;">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="user_level">User Level <span style="color: red;">*</span></label>
                            <select class="form-control" id="user_level" name="user_level">
                                <option value="">Select User Level</option>
                                <option value="Administrator">Administrator</option>
                                <option value="Billing">Billing</option>
                                <option value="Sales Terminal">Sales Terminal</option>
                                <option value="Purchase">Purchase</option>
                            </select>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <!-- Add cancel and submit buttons -->
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Invite user</button>
            </div>
        </div>
    </div>
</div>
<!-- USER modal -->

<!-- UPI modal -->
<div class="modal fade" id="upi_modal" tabindex="-1" role="dialog" aria-labelledby="upi_modal_label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <img src="images/upi-logo.png" class="img-fluid" style="width: 80px;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Input fields -->
        <div class="form-group">
          <input type="text" class="form-control" id="business_upi_id" name="business_upi_id" placeholder="Business UPI ID (upi id@bank)">
        </div>
        <div class="form-group">
          <input type="text" class="form-control" id="business_name" name="business_name" placeholder="VPA Name (Business Name)">
        </div>
        <button type="button" class="btn btn-primary" style="width:459px;">Update UPI Payment Gateway</button>
      </div>
        
    </div>
  </div>
</div>
<!-- UPI modal -->

<!-- cc modal -->
<div class="modal fade" id="cc_modal" tabindex="-1" role="dialog" aria-labelledby="cc_modal_label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <!-- Input fields -->
        <div class="did-floating-label-content">
            <input type="text" id="merchant_id" name="merchant_id" class="did-floating-input" placeholder="" required>
            <label for="merchant_id" class="did-floating-label">Merchant ID*</label>
        </div>
        <div class="did-floating-label-content">
            <input type="text" id="key" name="key" class="did-floating-input" placeholder="" required>
            <label for="key" class="did-floating-label">Key*</label>
        </div>
        <div class="did-floating-label-content">
            <input type="text" id="code" name="code" class="did-floating-input" placeholder="" required>
            <label for="code" class="did-floating-label">Code*</label>
        </div>
        <div class="did-floating-label-content">
            <input type="text" id="landing_url" name="landing_url" class="did-floating-input" placeholder="" required>
            <label for="landing_url" class="did-floating-label">Landing URL*</label>
        </div>
        <div class="form-group mb-2">
            <label>Do you have Domain?</label>
            <div class="kt-radio-inline">
                <label class="kt-radio">
                    <input type="radio" name="domainpay" value="yes" checked onclick="toggleDomainField()"> Yes
                    <span></span>
                </label>
                &nbsp; &nbsp; &nbsp;
                <label class="kt-radio">
                    <input type="radio" name="domainpay" value="no" onclick="toggleDomainField()"> No
                    <span></span>
                </label>
            </div>
        </div>
        <div class="form-group mb-3">
            <label>Payment Domain URL*</label>
            <div class="input-group">
                <div class="input-group-prepend pre_sec" id="inputGroupPrepend">
                    <span class="input-group-text" style="height: 36px;">ledgerspay.</span>
                </div>
                <input type="url" class="form-control" placeholder="Enter domain name" id="pxurl" name="pxurl" required="required" minlength="3">
                <!-- <div class="input-group-append pre_sec">
                    <span class="input-group-text">/ccapayment.php</span>
                </div> -->
            </div>
        </div>

        <script>
            function toggleDomainField() {
                var radioValue = document.querySelector('input[name="domainpay"]:checked').value;
                var domainInput = document.getElementById('pxurl');
                var prependSpan = document.getElementById('inputGroupPrepend');

                if (radioValue === 'yes') {
                    domainInput.value = ''; // Clear the input field
                    domainInput.placeholder = 'Enter domain name';
                    prependSpan.style.display = 'block'; // Display the span
                } else {
                    domainInput.value = 'https://checkout.ledgers.cloud/cca/init';
                    domainInput.placeholder = 'https://checkout.ledgers.cloud/cca/init'; // Set placeholder
                    prependSpan.style.display = 'none'; // Hide the span
                }
            }
        </script>
      </div>
    <div>
    <button type="button" class="btn btn-primary float-right mb-4" style="margin-right: 20px; margin-top: -10px;">Save</button>
    </div>
      
      
    </div>
  </div>
</div>
<!-- cc modal -->
<script>
	$(document).on('click', '.gst_li', function() {
		$("#m_portlet_tab_addr").css("display", "none");
		$("#m_portlet_tab_info").css("display", "block");

	});

	$(document).on('click', '.info_li', function() {
		$("#m_portlet_tab_addr").css("display", "block");
		$("#m_portlet_tab_info").css("display", "none");

	});

	$(document).on('click', '.add_addr_filter', function() {
		$('.add_addr_filter').removeClass('active');
		$(this).addClass('active');
	});
	$(".gstin-number").focusout(function() {
		var gstin = $("#custgstin").val();
		if (gstin != '' && gstin.length == 15) {
			$.ajax({
				url: "manage-place-post-service",
				type: "post",
				dataType: "JSON",
				data: {
					"operation": "check_gstin_details",
					"gstin": gstin
				},
				success: function(res) {
					var sts = "";
					$("#branch_name").val("");
					$("#baddr1").val("");
					$("#baddr2").val("");
					$("#bcity").val("");
					$("#taluka").val("");
					$("#bpincode").val("");
					$("#bstate").val("");
					$("#bscheme").val("");
					if (res['status'] == 1) {
						$("#gstinvalid").val("1");
						if (res['data']['sts'] == "Active") {
							sts = 1;
						}
						if (res['data']['tradeNam']) {
							$("#branch_name").val(res['data']['tradeNam']);
						} else if (res['data']['lgnm']) {
							$("#branch_name").val(res['data']['lgnm']);
						}
						if (res['data']['pradr']['addr']["st"]) {
							$("#baddr1").val((res['data']['pradr']['addr']["st"]).toUpperCase());
						}
						if (res['data']['pradr']['addr']["loc"]) {
							$("#baddr2").val((res['data']['pradr']['addr']["loc"]).toUpperCase());
						}
						if (res['data']['pradr']['addr']["dst"]) {
							$("#bcity").val((res['data']['pradr']['addr']["dst"]).toUpperCase());
							$("#taluka").val((res['data']['pradr']['addr']["dst"]).toUpperCase());
						} else if (res['data']['stj']) {
							$("#bcity").val((res['data']['stj']).toUpperCase());
							$("#taluka").val((res['data']['stj']).toUpperCase());
						}
						if (res['data']['pradr']['addr']["pncd"]) {
							$("#bpincode").val(res['data']['pradr']['addr']["pncd"]);
						}
						if (res['data']['pradr']['addr']["stcd"]) {
							$("#bstate").val((res['data']['pradr']['addr']["stcd"]).toUpperCase());
						}
						/* if (res['data']['dty']) {
							// $("#bscheme").val((res['data']['bscheme']));
							if(res['data']['dty'] == 'Regular'){
								$('#bscheme option[value="Q1"]').attr("selected", true);
							}else if(res['data']['dty'] == 'Composition'){
								$('#bscheme option[value="Q3"]').attr("selected", true);
							}else{
								$('#bscheme option[value="Q2"]').attr("selected", true);
							}
						} */
						//var arr = res['data']['nba'].split(",");
						//$("#m_select2_3").select2().val(arr).trigger('change');
						$("#status").val(sts);
					} else {
						$("#gstinvalid").val("0");
					}

				}
			});
		} else {
			$("#gstinvalid").val("");
		}
	});
</script>


</section>           
<script>
  $(document).ready(function(){
    $('#Tabs a').on('click', function (e) {
      e.preventDefault();
      $(this).tab('show');
      
      // Update the URL hash with the tab ID
      var tabId = $(this).attr('href');
      window.location.hash = tabId;
    });

    // Check if there's a hash in the URL on page load
    if (window.location.hash) {
      // Show the tab based on the hash in the URL
      $('#myTabs a[href="' + window.location.hash + '"]').tab('show');
    }
  });
</script>
<script>
    function selectDropdownOption(element, cardId) {
        var selectedOptionText = element.textContent;
        document.getElementById("userDropdown").innerText = selectedOptionText;
        showCard(cardId);
    }

    function showCard(cardId) {
        // Hide all cards
        document.querySelectorAll('.dropdown-card').forEach(card => {
            card.classList.remove('active-card');
        });

        // Show the selected card
        const selectedCard = document.getElementById(cardId);
        if (selectedCard) {
            selectedCard.classList.add('active-card');
        }

        // Update URL with card parameter
        const newUrl = window.location.pathname + '?' + cardId;
        window.history.pushState({}, '', newUrl);
    }
</script>
<script>
    function toggleGstinInput() {
        var confirmGstin = document.getElementById('confirmGstin');
        var gstinInput = document.getElementById('gstin');

        if (confirmGstin.value === '1') {
            gstinInput.disabled = false;
        } else {
            gstinInput.disabled = true;
        }
    }
</script>
<script>
    document.getElementById('add-bank-btn').addEventListener('click', function() {
        // Scroll to the account number input field
        document.getElementById('acc_number').scrollIntoView({ behavior: 'smooth' });
        
        // Focus on the account number input field
        document.getElementById('acc_number').focus();
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#branchSelector').on('change', function() {
        const branchId = $(this).val();

        if (branchId) {
            $.ajax({
                url: 'fetch_branch_details.php',
                type: 'POST',
                data: { branch_id: branchId },
                dataType: 'json',
                success: function(response) {
                    if (response && response.status !== 'error') {
                        $('#address1').val(response.address_line1);
                        $('#address2').val(response.address_line2);
                        $('#city').val(response.city);
                        $('#pincode').val(response.pincode);
                        $('#state').val(response.state);
                        // Populate other fields as needed
                    } else {
                        alert(response.message || 'Error fetching branch details.');
                    }
                },
                error: function() {
                    alert('An error occurred while fetching branch details.');
                }
            });
        } else {
            // Clear the form fields if no branch is selected
            $('#updateForm').trigger('reset');
        }
    });
});
</script>
<script>
$(document).ready(function () {
    $("#updateForm").submit(function (event) {
        event.preventDefault(); // Prevent default form submission
        $.ajax({
    url: "update_user.php",
    type: "POST",
    data: $(this).serialize(),
    dataType: "json",
    success: function (response) {
        console.log("Server Response:", response); // Debugging
        if (response.status === "success") {
            alert(response.message);
            location.reload();
        } else {
            alert(response.message);
        }
    },
    error: function (xhr, status, error) {
        console.log("AJAX Error:", status, error);
        console.log("Server Response:", xhr.responseText); // Show full response
        alert("Something went wrong. Please try again.");
    }
});


    // Toggle GSTIN input field based on dropdown selection
    $("#confirmGstin").change(function () {
        if ($(this).val() == "1") {
            $("#gstin").prop("disabled", false);
        } else {
            $("#gstin").prop("disabled", true).val("");
        }
    });
});
});
</script>



    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>
</body>
</html>