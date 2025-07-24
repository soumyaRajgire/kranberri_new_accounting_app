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

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>  
 


<html lang="en">
<head>
    <title>iiiQbets</title>

    <meta charset="utf-8">
    <?php include("header_link.php");?>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"  crossorigin="anonymous">

    <style>
    .text-grey {
    color: grey; /* or any other shade of grey you prefer */
    background-color: white;
}
.tooltip-inner  {
    background-color: white;
    color: grey; /* Set text color to black or your preferred color */
    border-radius: 2px;
    font-size: 13px;
}

h5{
    font-size: 13px !important;
}
</style>


</head>

<body class="">

    <!-- [ Pre-loader ] start -->
        <?php
        function getQuotationDetails($conn, $id) {
    $quotationId = $conn->real_escape_string($id); // Sanitize input
$bid  = $_SESSION['branch_id'];
    // Your database query logic here to fetch data from the 'quotation' table
    $query = "SELECT * from delivery_challan WHERE id='$quotationId' AND branch_id = '$bid'";
    
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $quotationData = $result->fetch_assoc();
        $quotationItems = [];
        

        // Add quotation items array to the main quotation data
        $quotationData['quotation_items'] = $quotationItems;

        return $quotationData;
    } else {
        return false; // Quotation not found
    }
}

$id = $_GET['id'];
$quotationDetails = getQuotationDetails($conn, $id);

// Close the database connection
// $conn->close();

        ?>   


           <?php 

        // DELETE PERMENant 
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $inv_id = $conn->real_escape_string($_GET['id']);
    
    // Begin transaction
    $conn->begin_transaction();

    try {
         
        // Delete related items
        $deleteItemsQuery = "DELETE FROM delivery_challan WHERE id = '$inv_id'";
        $conn->query($deleteItemsQuery);

        // Delete the debit note
        $deleteNoteQuery = "DELETE FROM  delivery_challan_items WHERE dc_id = '$inv_id'";
        $conn->query($deleteNoteQuery);

        $deleteNoteQuery1 = "DELETE FROM  delivery_challan_additional_charges WHERE dc_id = '$inv_id'";
        $conn->query($deleteNoteQuery1);

          $deleteNoteQuery2 = "DELETE FROM  delivery_challan_other_details WHERE dc_id = '$inv_id'";
        $conn->query($deleteNoteQuery2);

         $deleteNoteQuery3 = "DELETE FROM  delivery_challan_transportation_details WHERE dc_id = '$inv_id'";
        $conn->query($deleteNoteQuery3);


        // Commit transaction
        $conn->commit();

        echo "<script>alert('Debit note permanently deleted.'); window.location.href='debit_note_list.php';</script>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error permanently deleting debit note.'); window.location.href='debit_note_list.php';</script>";
    }
}




        ?>
     <?php include("menu.php");?>
    
    <?php include("createReceiptModal.php");?>

     <?php include("createTDSModal.php");?>
    <!-- [ Header ] end -->
    

<!-- [ Main Content ] start -->
<section class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h4 class="m-b-10"> Delivery Challan</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                            <!-- <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li> -->
                            <!-- <li class="breadcrumb-item"><a href="#">View credit note</a></li> -->
                            <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
  
<div class="card" style="padding: 5px;">
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <ul class="nav">
                <li class="nav-item mt-2">
                    <!-- <h5><a href="#" class="customer_est_name text-primary"  style="font-size: 19px;">Customer: <?php echo $quotationDetails['customerName'];?></a></h5> -->
                </li>
            </ul>
        </div>
        <div class="col-md-6 d-flex justify-content-end align-items-center">
            <ul class="nav">
                <li class="nav-item">
                    <div class="btn-group" role="group">
    
<div class="btn-group">
    <a href="#" class="btn border border-grey" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-list-ul text-grey" aria-hidden="true"></i> &nbsp;
        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">
  
        <!-- <a class="dropdown-item" href="delete_invoice.php?inv_id=<?php echo $inv_id; ?>" onclick="return confirm('Are you sure you want to delete this invoice?');">Delete</a> -->
         <a class="dropdown-item" href="view-delivery-challan-action.php?action=delete&id=<?php echo $id; ?>" 
       onclick="return confirm('Are you sure you want to delete this invoice?');">Delete</a>

    <!-- Permanent Delete -->

     
    </div>

    <a href="" class="btn border border-grey" onclick="printPDF('<?php echo $quotationDetails['dc_file']?>')" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print text-grey" aria-hidden="true"></i> </a>
    <a href="<?php echo $quotationDetails['dc_file']?>" target="_blank" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Download"><i class="fa fa-download text-grey" aria-hidden="true"></i></a>
          
</div>


                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- <iframe id="pdfViewer" src="<?php echo $quotationDetails['cnote_file']; ?>" width="100%" height="600px"></iframe> -->



            

                
<div class="row">
    <div class="col-md-8">
        
        <div id="pdf-container" style="position: relative;">
            
        
        <canvas id="pdf-canvas" style="width:700px"></canvas>
        <div id="controls">
            <button id="prev-page" class="btn">Previous</button>
            <span>Page: <span id="current-page">1</span> / <span id="total-pages">0</span></span>
            <button id="next-page" class="btn">Next</button>
        </div>
    </div>
    </div>


 
   
</div>


</div>
<script>
  function printPDF(pdfFilePath) {
        var printWindow = window.open(pdfFilePath, '_blank');
        printWindow.onload = function() {
            printWindow.print();
        };
    }
</script>

<script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
       <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script>
    
  document.addEventListener("DOMContentLoaded", () => {
    const statusOverlay = document.getElementById("status-overlay");

    // Set the status label visibility
    if (statusOverlay) {
        statusOverlay.style.display = "block"; // Ensure the label is visible
    }

    // Fetch the `invoice_id` dynamically from the URL query parameters
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    const invoiceId = getQueryParam("id"); // Extract invoice_id from URL

    // Construct the dynamic URL for the PDF file from PHP variable
    const url = "<?php echo $quotationDetails['dc_file']; ?>"; // Make sure it is echoed as a string

    const pdfjsLib = window["pdfjs-dist/build/pdf"];
    pdfjsLib.GlobalWorkerOptions.workerSrc =
        "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js";

    let pdfDoc = null,
        pageNum = 1,
        pageRendering = false,
        pageNumPending = null;

    const scale = 1.5,
        canvas = document.getElementById("pdf-canvas"),
        ctx = canvas.getContext("2d");

    // Render the page
    const renderPage = (num) => {
        pageRendering = true;
        pdfDoc.getPage(num).then((page) => {
            const viewport = page.getViewport({ scale });
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport,
            };
            const renderTask = page.render(renderContext);

            renderTask.promise.then(() => {
                pageRendering = false;

                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
            });
        });

        document.getElementById("current-page").textContent = num;
    };

    const queueRenderPage = (num) => {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    };

    const onPrevPage = () => {
        if (pageNum <= 1) {
            return;
        }
        pageNum--;
        queueRenderPage(pageNum);
    };

    const onNextPage = () => {
        if (pageNum >= pdfDoc.numPages) {
            return;
        }
        pageNum++;
        queueRenderPage(pageNum);
    };

    document
        .getElementById("prev-page")
        .addEventListener("click", onPrevPage);
    document
        .getElementById("next-page")
        .addEventListener("click", onNextPage);

    // Load the PDF dynamically
    pdfjsLib.getDocument(url).promise.then((pdfDoc_) => {
        pdfDoc = pdfDoc_;
        document.getElementById("total-pages").textContent = pdfDoc.numPages;
        renderPage(pageNum);
    }).catch((error) => {
        console.error("Error loading PDF:", error);
        alert("Unable to load PDF file.");
    });
});



</script>
    <script>
      
    </script>

<!-- <script>
  // Function to show/hide tabs based on the selected option
  $(document).ready(function() {
    $('#payment_mode').on('change', function() {
      var selectedOption = $(this).val();

      // Hide all tabs
      $('.tab-content').hide();

      // Show the selected tab
      $('#' + selectedOption + 'Tab').show();
    });
  });
</script> -->

<script>
// $(document).ready(function() {
//     $('#payment_mode').change(function() {
//         var selectedOption = $(this).val();
        
//         // Hide all related fields
//         $('#collected_by_tab, #bank_name_tab, #trans_no_tab, #cheque_no_tab,#dd_no_tab,#credit_debit_card_tab').hide();
        
//         // Show the relevant field based on the selected option
//         if (selectedOption === 'cash') {
//             $('#collected_by_tab,#transaction_date_tab').show();
//         } else if (selectedOption === 'cheque') {
//             $('#bank_name_tab, #cheque_no_tab,#transaction_date_tab').show();
//         } else if (selectedOption === 'direct_deposit') {
//              $('#bank_name_tab,#transaction_date_tab').show();
//         }else if (selectedOption === 'demand_draft') {
//              $('#bank_name_tab,#dd_no_tab,#transaction_date_tab').show();
//         }else if (selectedOption === 'credit_debit_card') {
//              $('#credit_debit_card_tab,#transaction_date_tab').show();
//         }else if (selectedOption === 'online_payment') {
//              $('#trans_no_tab,#transaction_date_tab').show();
//         }else if (selectedOption === 'neft_rtgs') {
//              $('#bank_name_tab,#transaction_date_tab').show();
//         }

//         // Add more conditions as needed for other options
//     });
// });
</script>
<script>
$(document).ready(function() {
    // Function to show the relevant tab based on the selected option
    function showTab(selectedOption) {
        $('#collected_by_tab, #bank_name_tab, #trans_no_tab, #cheque_no_tab, #dd_no_tab, #credit_debit_card_tab, #transaction_date_tab').hide();

        if (selectedOption === 'Cash') {
            $('#collected_by_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'Cheque') {
            $('#bank_name_tab, #cheque_no_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'Direct Deposit') {
            $('#bank_name_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'Demand Draft') {
            $('#bank_name_tab, #dd_no_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'Credit Debit Card') {
            $('#credit_debit_card_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'Online Payment') {
            $('#trans_no_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'NEFT/RTGS') {
            $('#bank_name_tab, #transaction_date_tab').show();
        }
    }

    // Initial setup to show the default tab
    var selectedOption = $('#payment_mode').val();
    showTab(selectedOption);

    // Change event handler for the dropdown
    $('#payment_mode').change(function() {
        var selectedOption = $(this).val();
        showTab(selectedOption);
    });
});
</script>


<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
    // WhatsApp sharing
    $('#shareWhatsApp').on('click', function() {
        var pdfLink = $('#pdfLink').attr('href');
        var message = 'Check out this PDF: ' + pdfLink;
        var whatsappUrl = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(message);

        window.open(whatsappUrl, '_blank');
    });

    // Email sharing
    $('#shareEmail').on('click', function() {
        var pdfLink = $('#pdfLink').attr('href');
        var emailUrl = 'path/to/your/email-script.php?pdf=' + encodeURIComponent(pdfLink);

        window.location.href = emailUrl;
    });
});
</script>
<script type="text/javascript">
    
    $(document).ready(function () {
        // Initial setup
        $("#create_tab").show();
        $("#reconcile_tab").hide();

        // Switching tabs
        $(".create_tab").on("click", function () {
            $("#create_tab").show();
            $("#reconcile_tab").hide();

            // Change background color for the selected tab
            $(".add_cust_filter").removeClass("active");
            $(this).addClass("active");

            // Add code to load data for the "Create" tab
            // Example: loadDataForCreateTab();
        });

        $(".reconcile_tab").on("click", function () {
            $("#create_tab").hide();
            $("#reconcile_tab").show();

            // Change background color for the selected tab
            $(".add_cust_filter").removeClass("active");
            $(this).addClass("active");

            // Add code to load data for the "Reconcile" tab
            // Example: loadDataForReconcileTab();
        });
    });
</script>

<script>
$(document).ready(function(){
    // Function to calculate and update the amount
    function calculateAmount() {
        // Get the selected TDS rate
        var selectedRate = $("#tds_section1 option:selected").data("tds_rate");
        
        // Assuming grand total is stored in a variable named grandTotal, replace it with your actual variable
        var grandTotal = <?php echo $tot_amt?>; // Example value, replace it with your actual variable
        
        // Calculate the amount based on the selected TDS rate
        var tdsDeductible = (selectedRate / 100) * grandTotal;
        
        // Update the TDS Deductible input field
        $("#tds_deductable").val(tdsDeductible.toFixed(2));
    }

    // Attach the calculateAmount function to the change event of the TDS dropdown
    $("#tds_section1").change(function() {
        calculateAmount();
    });

    // Trigger the calculation on page load
    calculateAmount();
});
</script>

</body>
</html>