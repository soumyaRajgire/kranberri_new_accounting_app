<!DOCTYPE html>
<?php
session_start(); 

error_reporting(E_ALL);
ini_set('display_errors', 1);


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
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];

    // Check if a specific branch is selected
    if (isset($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];
    } 
}

include("config.php");


// Check if `inv_id` exists and is numeric
$inv_id = isset($_GET['inv_id']) && is_numeric($_GET['inv_id']) ? intval($_GET['inv_id']) : 0;

// Ensure `inv_id` is set before using it
// $inv_id = isset($_GET['inv_id']) && is_numeric($_GET['inv_id']) ? intval($_GET['inv_id']) : 0;

if ($inv_id === 0) {
    echo "<script>alert('Error: Missing or invalid invoice ID. Redirecting...'); window.location.href='view-esign-action.php?inv_id=<?php echo $inv_id; ?>';</script>";
    exit();
}

// Function to fetch quotation details
// Function to fetch quotation and signature details
function getQuotationDetails($conn, $inv_id) {
    if ($inv_id == 0) {
        return false; // Prevent processing if `inv_id` is missing
    }

    $quotationId = $conn->real_escape_string((string) $inv_id); // Ensure it's a string

    // Query to fetch main quotation details
    $query = "SELECT q.*, c.*, a.*, qi.*
              FROM invoice q
              JOIN customer_master c ON q.customer_id = c.id
              JOIN address_master a ON c.id = a.customer_master_id
              LEFT JOIN invoice_items qi ON q.id = qi.invoice_id
              WHERE q.id = '$quotationId'";

    $result = $conn->query($query);
    if (!$result || $result->num_rows == 0) {
        return false;
    }

    // Fetch main quotation details
    $quotationData = $result->fetch_assoc();

    // Fetch invoice items properly
    $quotationItems = [];
    $result->data_seek(0); // Reset pointer
    while ($row = $result->fetch_assoc()) {
        $quotationItems[] = [
            'itemno' => $row['itemno'],
            'product' => $row['product'],
            'prod_desc' => $row['prod_desc'],
            'price' => $row['price'],
            'qty' => $row['qty'],
            'line_total' => ($row['price'] * $row['qty']),
            'total' => $row['total'],
            'gst_amt' => $row['total_gst'],
            'gst' => $row['gst'],
            'cess_rate' => $row['cess_rate'],
            'cess_amt' => $row['cess_amount'],
            'discount' => $row['discount'],
            'cgst' => $row['cgst'],
            'sgst' => $row['sgst'],
            'igst' => $row['igst'],
        ];
    }
    $quotationData['quotation_items'] = $quotationItems;

    // Fetch signature details based on `inv_id`
    $signatureQuery = "SELECT id, signature_name, font_style, font_weight, font_style_type, authorized_user, 
                              remarks, uploaded_file, created_at, inv_id 
                       FROM signatures WHERE inv_id = '$quotationId'";

    $signatureResult = $conn->query($signatureQuery);
    $signatures = [];
    if ($signatureResult && $signatureResult->num_rows > 0) {
        while ($row = $signatureResult->fetch_assoc()) {
            $signatures[] = $row;
        }
    }
    $quotationData['signatures'] = $signatures;

    return $quotationData;
}

// Fetch quotation details along with signature details
$quotationDetails = getQuotationDetails($conn, $inv_id);


?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("fpdf/fpdf.php");
    $form_type = $_POST['form_type'];
    $inv_id = isset($_POST['inv_id']) ? intval($_POST['inv_id']) : 0;

    if ($inv_id == 0) {
        die("❌ Error: Invalid Invoice ID.");
    }

    try {
        // ✅ Handle Signature Creation (Convert Text to Image)
        if ($form_type === "signature") {
            $signature_name = $_POST['signature_name'] ?? 'Signature';
            $font_style = $_POST['font_style'] ?? 'Arial';
            $font_weight = $_POST['font_weight'] ?? 'normal';
            $font_style_type = $_POST['font_style_type'] ?? 'normal';
            $authorized_user = $_POST['authorized_user'] ?? 'Authorized User';
            $remarks = $_POST['remarks'] ?? '';

            // ✅ Store Signatures in `pdf/` Folder
            $target_dir = "pdf/"; // 🔹 Change from `uploads/` to `pdf/`
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_name = "signature_" . time() . ".png";
            $file_path = $target_dir . $file_name; // ✅ Store full path

            $img_width = 400;
            $img_height = 100;
            $image = imagecreatetruecolor($img_width, $img_height);

            if (!$image) {
                throw new Exception("❌ Error: Unable to create image.");
            }

            // ✅ Set Background to White
            $white = imagecolorallocate($image, 255, 255, 255);
            imagefilledrectangle($image, 0, 0, $img_width, $img_height, $white);

            // ✅ Set Text Color to Black
            $black = imagecolorallocate($image, 0, 0, 0);

            // ✅ Define Font Path
            $font_path = __DIR__ . "/fonts/";
            //$font_file = $font_path . str_replace(' ', '', strtolower($font_style)) . ".ttf";
            $font_file = $font_path . str_replace(' ', '', $font_style) . ".ttf";


            if (!file_exists($font_file)) {
                $available_fonts = array_diff(scandir($font_path), array('..', '.'));
                throw new Exception("❌ Error: Font file '$font_file' not found! Available fonts: " . implode(", ", $available_fonts));
            }

            // ✅ Apply Signature Text to Image
            imagettftext($image, 30, 0, 50, 60, $black, $font_file, $signature_name);

            // ✅ Save Image
            if (!imagepng($image, $file_path)) {
                throw new Exception("❌ Error: Failed to save image.");
            }
            imagedestroy($image);

            
            // $stmt->close();
            // Check if file_path already exists for the given inv_id
           // Check if any file exists for the given inv_id
                    $check_sql = "SELECT id FROM signatures WHERE inv_id = ?";
                    $check_stmt = $conn->prepare($check_sql);
                    if (!$check_stmt) {
                        throw new Exception("❌ Error: Database prepare failed - " . $conn->error);
                    }
                    
                    $check_stmt->bind_param("i", $inv_id); // Assuming inv_id is an integer
                    $check_stmt->execute();
                    $check_stmt->store_result();
                    
                    // If record exists for the given inv_id, update the file_path and other fields
                    if ($check_stmt->num_rows > 0) {
                        // Update the record for the given inv_id
                        $update_sql = "UPDATE signatures SET uploaded_file = ?, authorized_user = ?, remarks = ? WHERE inv_id = ?";
                        $update_stmt = $conn->prepare($update_sql);
                        if (!$update_stmt) {
                            throw new Exception("❌ Error: Database prepare failed for update - " . $conn->error);
                        }
                    
                        // Bind parameters for the update statement
                        $update_stmt->bind_param("sssi", $file_path, $authorized_user, $remarks, $inv_id);
                    
                        // Execute the update
                        if ($update_stmt->execute()) {
                            echo "✔️ Signature updated successfully.";
                        } else {
                            throw new Exception("❌ Error: Failed to update the signature - " . $update_stmt->error);
                        }
                    
                    } else {
                        // If no record exists for the given inv_id, insert a new record
                        $insert_sql = "INSERT INTO signatures (inv_id, uploaded_file, authorized_user, remarks, created_at) VALUES (?, ?, ?, ?, NOW())";
                        $insert_stmt = $conn->prepare($insert_sql);
                        if (!$insert_stmt) {
                            throw new Exception("❌ Error: Database prepare failed for insert - " . $conn->error);
                        }
                    
                        // Bind parameters for the insert statement
                        $insert_stmt->bind_param("isss", $inv_id, $file_path, $authorized_user, $remarks);
                    
                        // Execute the insert
                        if ($insert_stmt->execute()) {
                            echo "✔️ Signature inserted successfully.";
                              $insert_stmt->close();
                        } else {
                            throw new Exception("❌ Error: Failed to insert the signature - " . $insert_stmt->error);
                        }
                    }
                    
                    // Close the prepared statements
                    $check_stmt->close();
                    $update_stmt->close();
                  

        }

        // ✅ Handle File Upload for Signature
elseif ($form_type === "upload") {
    $authorized_user = $_POST['authorized_user'] ?? '';
    $remarks = $_POST['remarks'] ?? '';

    if (!empty($_FILES["uploaded_file"]["name"])) {
        $target_dir = "pdf/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($_FILES["uploaded_file"]["name"]);
        $uploaded_file = $target_dir . $file_name; 
        $file_type = strtolower(pathinfo($uploaded_file, PATHINFO_EXTENSION));

        // ✅ Allowed file types: Images (png, jpg, jpeg, gif) and PDF
        $allowed_types = ['png', 'jpg', 'jpeg', 'gif', 'pdf'];

        if (!in_array($file_type, $allowed_types)) {
            throw new Exception("❌ Error: Invalid file type! Only PNG, JPG, JPEG, GIF, and PDF are allowed.");
        }

        // ✅ Validate MIME Type (Extra Security)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $_FILES["uploaded_file"]["tmp_name"]);
        finfo_close($finfo);

        $allowed_mime_types = [
            'image/png', 'image/jpeg', 'image/gif', 
            'application/pdf'
        ];

        if (!in_array($mime_type, $allowed_mime_types)) {
            throw new Exception("❌ Error: File MIME type is not allowed.");
        }

        // ✅ Move the uploaded file
        if (!move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $uploaded_file)) {
            throw new Exception("❌ Error: File upload failed!");
        }

        // ✅ Store Full Path in Database
        $sql = "INSERT INTO signatures (inv_id, uploaded_file, authorized_user, remarks, created_at) 
                VALUES (?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("❌ Error: Database prepare failed - " . $conn->error);
        }

        $stmt->bind_param("isss", $inv_id, $uploaded_file, $authorized_user, $remarks);

        if (!$stmt->execute()) {
            throw new Exception("❌ Error: Database insert failed - " . $stmt->error);
        }
        else
        {

        echo "✅ Signature uploaded successfully!";
        
echo "<script>
    window.location.href = 'call_save_signature_for_all_templates.php?inv_id=$inv_id';
</script>";

       
        $stmt->close();
        }
    } else {
        throw new Exception("❌ Error: No file selected!");
    }
}

    } catch (Exception $e) {
        echo $e->getMessage();
        error_log($e->getMessage(), 3, "error_log.txt"); // ✅ Log errors for debugging
    }
    
    }
    ?>
<html lang="en">
<head>
    <title>iiiQbets</title>

    <meta charset="utf-8">
    <?php include("header_link.php");?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


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
        
     
     
    <!-- [ Header ] end -->
    

<!-- [ Main Content ] start -->
<section class="pcoded-main-container" style="margin-left: 0px !important;">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                   <!--  <div class="col-md-12">
                        <div class="page-header-title">
                            <h4 class="m-b-10">View Invoice</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">View Invoice</a></li>
                          
                        </ul>
                    </div> -->
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
  
<div class="card" style="padding: 5px;">
    <div class="row">
        <div class="col-md-3 col-lg-3">
            <ul class="nav">
                <li class="nav-item mt-2">
                    <h5><a href="#" class="customer_est_name text-primary"  style="font-size: 19px;">Customer: <?php echo $quotationDetails['customerName'];?></a></h5>
                </li>
            </ul>
        </div>
        <div class="col-md-9 d-flex justify-content-end align-items-center">
            <ul class="nav">
                <li class="nav-item">
                    <div class="btn-group" role="group">
 

 <div class="btn-group">
         <a class="dropdown-item" href="" >eSign with Aadhar</a>
      </div>
      <div class="">
            <a class="dropdown-item" href="" onclick="">Delete</a>
      </div>
<div class="">
            <a class="dropdown-item" href="" >Register for eSign</a>
      </div>
   
<div class="btn-group">
    <a href="#" class="btn border border-grey" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
       Options
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">
      <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#signatureModal">Create</a>
      <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#uploadSignatureModal">Upload</a>
    
    <a href="<?php echo $quotationDetails['invoice_file']; ?>" id="pdfLink" style="display: none;">Download PDF</a>
    </div>
</div>


                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-9">
        <div id="pdf-container" style="position: relative;">
            <canvas id="pdf-canvas" style="width:100%"></canvas>
            <div id="controls">
                <button id="prev-page" class="btn">Previous</button>
                <span>Page: <span id="current-page">1</span> / <span id="total-pages">0</span></span>
                <button id="next-page" class="btn">Next</button>
            </div>
        </div>
    </div>
</div>

<!-- Signature Section -->
<!-- <div class="row mt-4">
    <div class="col-md-12 text-end">
        <h5>Authorized Signature:</h5>
        <?php if (!empty($quotationDetails['signatures'])): ?>
            <?php foreach ($quotationDetails['signatures'] as $signature): ?>
                <div class="signature-box" style="margin-top: 10px;">
                    <?php if (!empty($signature['uploaded_file'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($signature['uploaded_file']); ?>" alt="Signature" width="200">
                    <?php else: ?>
                        <p style="
                            font-family: <?php echo htmlspecialchars($signature['font_style']); ?>;
                            font-weight: <?php echo htmlspecialchars($signature['font_weight']); ?>;
                            font-style: <?php echo htmlspecialchars($signature['font_style_type']); ?>;
                            font-size: 20px;
                            color: black;
                            text-decoration: underline;
                        ">
                            <?php echo htmlspecialchars($signature['signature_name']); ?>
                        </p>
                    <?php endif; ?>
                    <p style="margin-top: 5px; font-size: 14px; color: grey;">
                        <?php echo htmlspecialchars($signature['authorized_user']); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No signature available.</p>
        <?php endif; ?>
    </div>
</div> -->

   
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
<?php



$sql = "SELECT `id`, `temp_name`, `status`, `updated_at`
        FROM `invoice_temp` 
        WHERE `status` = 'active' 
       ";

$result = $conn->query($sql);

$templates = [];
if ($result->num_rows > 0) {
    // Fetch the result
    while ($row = $result->fetch_assoc()) {
        // Store the active templates in an array
        $templates[$row['temp_name']] = [
            'invoice_file_template1' => $quotationDetails['invoice_file_template1'],
            'invoice_file_template2' => $quotationDetails['invoice_file_template2'],
            'invoice_file_template3' => $quotationDetails['invoice_file_template3'],
            'invoice_file_template4' => $quotationDetails['invoice_file_template4']
        ];
    }
} else {
    echo "No active templates found.";
}

$conn->close();
?>




<script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
       <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
        <script>
document.addEventListener("DOMContentLoaded", async () => {
    //const pdfUrl = "<?php echo htmlspecialchars($quotationDetails['invoice_file'] ?? ''); ?>";
    
<?php if (isset($templates['template1'])): ?>
    const pdfUrl = "<?php echo htmlspecialchars($templates['template1']['invoice_file_template1'] ?? ''); ?>";
<?php endif; ?>

<?php if (isset($templates['template2'])): ?>
    const pdfUrl = "<?php echo htmlspecialchars($templates['template2']['invoice_file_template2'] ?? ''); ?>";
<?php endif; ?>

<?php if (isset($templates['template3'])): ?>
    const pdfUrl = "<?php echo htmlspecialchars($templates['template3']['invoice_file_template3'] ?? ''); ?>";
<?php endif; ?>

<?php if (isset($templates['template4'])): ?>
    const pdfUrl = "<?php echo htmlspecialchars($templates['template4']['invoice_file_template4'] ?? ''); ?>";
<?php endif; ?>

    const signatures = <?php echo json_encode($quotationDetails['signatures'] ?? []); ?>;

  

    console.log("📄 Loading Invoice PDF:", pdfUrl);
    console.log("✍️ Signature Data:", signatures);

    const pdfjsLib = window["pdfjs-dist/build/pdf"];
    pdfjsLib.GlobalWorkerOptions.workerSrc =
        "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js";

    let pdfDoc = null,
        pageNum = 1,
        scale = 2.0,
        canvas = document.getElementById("pdf-canvas"),
        ctx = canvas.getContext("2d");

    // ✅ Function to Render a PDF Page
    const renderPage = (num) => {
        pdfDoc.getPage(num).then((page) => {
            const viewport = page.getViewport({ scale });
            canvas.width = viewport.width;
            canvas.height = viewport.height;

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport,
            };

            const renderTask = page.render(renderContext);

            renderTask.promise.then(() => {
                document.getElementById("current-page").textContent = num;

                // ✅ If signatures exist, add them on the last page
                if (signatures.length > 0 && num === pdfDoc.numPages) {
                    signatures.forEach((signature, index) => {
                        if (signature.uploaded_file) {
                            addSignatureToCanvas("uploads/" + signature.uploaded_file, index);
                        } else {
                            addTextSignatureToCanvas(signature, index);
                        }
                    });
                }
            });
        });
    };
      // Fetch the `invoice_id` dynamically from the URL query parameters
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    const invoiceId = getQueryParam("inv_id"); // Extract invoice_id from URL
    
     fetch(`fetch_invoice_path.php?invoice_id=${invoiceId}`)
        .then((response) => response.json())
        .then((data) => {
              console.log(data);
            if (data.status === "success") {
              
                const url = data.file_url; // Fetch the file URL from the response
                invoiceFileUrl = data.file_url;
  // Update the Download Button
                const downloadBtn = document.getElementById("download-invoice");
                if (downloadBtn) {
                    downloadBtn.href = invoiceFileUrl;
                } else {
                    console.error("Download button not found in the DOM.");
                }

                // Update the Print Button
                const printBtn = document.getElementById("print-invoice");
                if (printBtn) {
                    printBtn.addEventListener("click", (e) => {
                        e.preventDefault();
                        printInvoice(invoiceFileUrl);
                    });
                } else {
                    console.error("Print button not found in the DOM.");
                }



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
                    document.getElementById("total-pages").textContent =
                        pdfDoc.numPages;
                    renderPage(pageNum);
                });
            } else {
                alert("Failed to fetch invoice file: " + data.message);
            }
        })
        .catch((error) => {
            console.error("Error fetching invoice file:", error);
            alert("Unable to fetch PDF file.");
        });

    // ✅ Load PDF and Render First Page
    pdfjsLib.getDocument(pdfUrl).promise.then((pdfDoc_) => {
        pdfDoc = pdfDoc_;
        document.getElementById("total-pages").textContent = pdfDoc.numPages;
        renderPage(pageNum);
    });

    // ✅ Function to Add an Image Signature
    function addSignatureToCanvas(signatureUrl, index) {
        const img = new Image();
        img.src = signatureUrl;
        img.onload = function () {
            const signatureWidth = 200;
            const signatureHeight = 40;
            const xPos = canvas.width - signatureWidth - 50;
            const yPos = canvas.height - (signatureHeight + 50) - (index * 60);

            ctx.drawImage(img, xPos, yPos, signatureWidth, signatureHeight);
            console.log(`✅ Image Signature ${index + 1} added at X: ${xPos}, Y: ${yPos}`);
        };
        img.onerror = function () {
            console.error("❌ Signature Image Not Found:", signatureUrl);
        };
    }

    // ✅ Function to Add a Text Signature
    function addTextSignatureToCanvas(signature, index) {
        ctx.font = `${signature.font_weight} 24px ${signature.font_style}`;
        ctx.fillStyle = "black";
        const xPos = canvas.width - 250;
        const yPos = canvas.height - 100 - (index * 60);

        ctx.fillText(signature.signature_name, xPos, yPos);
        console.log(`✅ Text Signature ${index + 1} added at X: ${xPos}, Y: ${yPos}`);
    }
});
</script>


<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>




<div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signatureModalLabel">Create Signature</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <form id="signatureForm" method="POST" enctype="multipart/form-data" action="save_signature.php">
                    <!-- Hidden input for inv_id -->
                    <input type="hidden" name="inv_id" value="<?php echo htmlspecialchars($inv_id); ?>">

                    <input type="hidden" name="form_type" value="signature"> <!-- Identify Form -->
                    
                    <!-- Signature Name -->
                    <div class="mb-3">
                        <label for="signatureName" class="form-label">Signature Name</label>
                        <input type="text" class="form-control" id="signatureName" name="signature_name" placeholder="Enter your name">
                    </div>
                    
                    <!-- Font Selection -->
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Select Font</label>
                            <select class="form-control" name="font_style" id="fontStyle">
                                <option value="Courgette">Courgette</option>
                                <option value="Pacifico">Pacifico</option>
                                <option value="Great Vibes">Great Vibes</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Weight</label>
                            <select class="form-control" name="font_weight" id="fontWeight">
                                <option value="bold">Bold</option>
                                <option value="normal">Normal</option>
                                <option value="lighter">Light</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Style</label>
                            <select class="form-control" name="font_style_type" id="fontStyleType">
                                <option value="normal">Regular</option>
                                <option value="italic">Italic</option>
                                <option value="underline">Underline</option>
                            </select>
                        </div>
                    </div>

                    <!-- Signature Preview -->
                    <div class="text-center mt-3">
                        <p id="signaturePreview"></p>
                    </div>

                    <!-- Additional Fields -->
                    <div class="mb-3">
                        <label for="authorizedUser" class="form-label">Authorised User</label>
                        <input type="text" class="form-control" name="authorized_user">
                    </div>
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <input type="text" class="form-control" name="remarks">
                    </div>

                    <!-- Submit Button Inside Form -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to update signature preview based on user input
        function updatePreview() {
            // Get the values from the inputs
            const signatureName = document.getElementById('signatureName').value || ""; // Default text is empty
            const fontStyle = document.getElementById('fontStyle').value;
            const fontWeight = document.getElementById('fontWeight').value;
            const fontStyleType = document.getElementById('fontStyleType').value;
            
            // Get the preview element
            const previewElement = document.getElementById('signaturePreview');
            
            // Update the preview element's style and text
            previewElement.style.fontFamily = fontStyle;
            previewElement.style.fontWeight = fontWeight;
            previewElement.style.fontStyle = fontStyleType;
            previewElement.style.fontSize = "36px";  // Increased font size
            previewElement.textContent = signatureName; // Update the text content
        }
        
        // Attach event listeners to the input fields
        document.getElementById('signatureName').addEventListener('input', updatePreview);
        document.getElementById('fontStyle').addEventListener('change', updatePreview);
        document.getElementById('fontWeight').addEventListener('change', updatePreview);
        document.getElementById('fontStyleType').addEventListener('change', updatePreview);
        
        // Initial preview update on page load (for any pre-filled value)
        updatePreview();
    });
</script>


<!-- Modal for Uploading Signature -->
<div class="modal fade" id="uploadSignatureModal" tabindex="-1" aria-labelledby="uploadSignatureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Signature</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" enctype="multipart/form-data" action="save_signature.php" method="POST">
                    <!-- Hidden input for inv_id -->
                    <input type="hidden" name="inv_id" value="<?php echo htmlspecialchars($inv_id); ?>">

                    <input type="hidden" name="form_type" value="upload"> <!-- Identify Form -->

                    <!-- File Upload Field -->
                    <div class="mb-3 text-center">
                        <label class="form-label">Signature Image</label>
                        <div class="border rounded p-4 d-flex flex-column align-items-center">
    <input type="file" id="signatureUpload" name="uploaded_file" class="d-none" accept="image/*">
    <label for="signatureUpload" class="text-muted">
        <img src="https://cdn-icons-png.flaticon.com/512/109/109612.png" width="50">
        <p class="mt-2">Click to upload image</p>
    </label>
    <!-- Display file name -->
    <p id="fileName" class="mt-2 text-muted"></p>
</div>
                    </div>

                    <!-- Authorized User -->
                    <div class="mb-3">
                        <label class="form-label">Authorized User</label>
                        <input type="text" class="form-control" name="authorized_user">
                    </div>

                    <!-- Remarks -->
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <input type="text" class="form-control" name="remarks">
                    </div>

                    <!-- Submit Button Inside the Form -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // When a file is selected
    document.getElementById('signatureUpload').addEventListener('change', function() {
        // Get the selected file name
        var fileName = this.files[0] ? this.files[0].name : "No file selected";
        
        // Update the displayed file name
        document.getElementById('fileName').textContent = fileName;
    });
</script>



</body>
</html>