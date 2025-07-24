
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

$tagFolder = 'generated_tags/';
$fontPath = 'fonts/OpenSans-Regular.ttf';
$logoPath = 'img/logo.png';

if (!file_exists($tagFolder)) {
    mkdir($tagFolder, 0777, true);
}

$work_order_id = $_GET['wo_id'] ?? '';


    $sql = "SELECT  wi.id AS item_id, wi.raw_material, wi.product_name, wi.prod_desc, wi.dno, wi.size, wi.color, wi.eqty, wi.barcode_no, wi.raw_material_batchno, wi.product_batchno, wi.barcode_image AS item_barcode_image, im.price AS product_price, im.barcode_image AS product_barcode_image, pb.batch_no AS batch_no, pb.manufacturer AS batch_manufacturer, pb.mfg_date AS batch_mfg_date, pb.exp_date AS batch_exp_date, pb.batch_price AS batch_price, 
        pb.barcode_image AS batch_barcode_image FROM  work_order_items wi JOIN  inventory_master im ON wi.product_id = im.id LEFT JOIN  product_batches pb ON wi.product_batchno = pb.batch_no WHERE wi.work_order_id = '$work_order_id'";


$result = mysqli_query($conn, $sql);
$tagImages = [];
?>


<!DOCTYPE html>

<html lang="en">

<head>
    <title>iiiQbets</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body class="">
    <!-- [ Pre-loader ] start -->

    <?php //include("menu.php"); ?>


    <!-- [ Header ] end -->

<?php
echo "<h2>Work Order Tags</h2>";
  echo "<button class='btn btn-info' style='float:right' onclick='window.history.back()'>← Back</button> ";
echo "<div style='display:flex; flex-wrap:wrap; gap:15px;'>";

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $product_name = $row['product_name'];
        $eqty = (int)$row['eqty'];
        $dno = $row['dno'];
        $size = $row['size'];
        $mfd = $row['batch_mfg_date'];
        $price = $row['product_price'];
        $manufacturer = $row['batch_manufacturer'];
        $barcode_image = $row['item_barcode_image'] ?: $row['product_barcode_image'] ?: $row['batch_barcode_image'];
        $barcode_path = $barcode_image ?: 'barcode_image';

        for ($i = 0; $i < $eqty; $i++) {
            $width = 600;
            $height = 300;
            $image = imagecreatetruecolor($width, $height);

            $white = imagecolorallocate($image, 255, 255, 255);
            $black = imagecolorallocate($image, 0, 0, 0);
            $red = imagecolorallocate($image, 255, 0, 0);
            $grey = imagecolorallocate($image, 169, 169, 169);
            imagefill($image, 0, 0, $white);

            if (file_exists($logoPath)) {
                $logoImg = imagecreatefrompng($logoPath);
                imagecopyresized($image, $logoImg, 10, 10, 0, 0, 100, 50, imagesx($logoImg), imagesy($logoImg));
            }

            $y = 30;
            imagettftext($image, 12, 0, 120, $y, $black, $fontPath, $product_name);
            $y += 25;
            imagettftext($image, 12, 0, 120, $y, $black, $fontPath, "QTY 1 PC");
            $y += 25;
            imagettftext($image, 12, 0, 120, $y, $black, $fontPath, "D.NO : $dno");
            $y += 25;
            imagettftext($image, 12, 0, 120, $y, $black, $fontPath, "SIZE : $size");
            $y += 25;
            imagettftext($image, 12, 0, 120, $y, $black, $fontPath, "MFD : $mfd");
            $y += 25;
            imagettftext($image, 12, 0, 120, $y, $black, $fontPath, "MRP : ₹ $price");
            $y += 25;
            imagettftext($image, 12, 0, 120, $y, $black, $fontPath, "(INCL. OF ALL TAXES)");

            imagettftext($image, 12, 0, 120, 230, $red, $fontPath, "Mfd & Mkd by: $manufacturer");
            imagettftext($image, 11, 0, 120, 250, $black, $fontPath, "# 7/8, 1st Floor, 1st Main Road, Bylappa Circle, T. Dasarahalli\n, Bangalore - 560 057");
            imagettftext($image, 11, 0, 120, 290, $black, $fontPath, "Customer Care: Ph: +91 988 099 0431");

            imageline($image, 10, 295, 590, 295, $grey);
// Add barcode at the top
$barcodeHeight = 60;
$barcodeWidth = 180;
$barcodeY = 20;
$barcodeX = ($width - $barcodeWidth) / 2;

// $barcodeImg = imagecreatefrompng($barcode_image);
// imagecopyresized($image, $barcodeImg, $barcodeX, $barcodeY, 0, 0, $barcodeWidth, $barcodeHeight, imagesx($barcodeImg), imagesy($barcodeImg));

            if (file_exists($barcode_path)) {
                $barcodeImg = imagecreatefrompng($barcode_path);
                // imagecopyresized($image, $barcodeImg, 400, 200, 0, 0, 150, 50, imagesx($barcodeImg), imagesy($barcodeImg));
                imagecopyresized($image, $barcodeImg, $barcodeX, $barcodeY, 0, 0, $barcodeWidth, $barcodeHeight, imagesx($barcodeImg), imagesy($barcodeImg));

            }

            $fileName = $tagFolder . 'tag_' . $row['item_id'] . '_' . $i . '.png';
            imagepng($image, $fileName);
            imagedestroy($image);

            $tagImages[] = $fileName;

            // Display tag image preview with download
            echo "<div style='border:1px solid #ccc; padding:10px;'><img src='$fileName' width='300'><br><a href='$fileName' download>Download</a></div>";
        }
    }
    echo "</div>";

    // Show ZIP download button
    echo "
    <form method='post'>
        <input type='hidden' name='zip' value='1'>
        <button type='submit'>Download All as ZIP</button>
    </form>
    ";
     echo "<button onclick='window.history.back()'>← Back</button> ";
} else {
    echo "No data found.";
}

// If ZIP requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['zip'])) {
    $zipFile = $tagFolder . 'tags_' . time() . '.zip';
    $zip = new ZipArchive();
    if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
        foreach ($tagImages as $tagImg) {
            $zip->addFile($tagImg, basename($tagImg));
        }
        $zip->close();

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . basename($zipFile));
        header('Content-Length: ' . filesize($zipFile));
        readfile($zipFile);
        unlink($zipFile);
        exit;
    } else {
        echo "Failed to create ZIP.";
    }
}
?>

</body>
</html>
<?php
// Include necessary files and start the session
// session_start(); 
// include("config.php");

// // Fetch work_order_id from the query parameter
// $work_order_id = $_GET['wo_id']; // Or use POST if it's passed via form

// // Fetch work order details along with product and batch info
// $sql = "
//     SELECT 
//         wi.id AS item_id,
//         wi.raw_material, 
//         wi.product_name, 
//         wi.prod_desc, 
//         wi.dno, 
//         wi.size, 
//         wi.color, 
//         wi.eqty, 
//         wi.barcode_no, 
//         wi.raw_material_batchno, 
//         wi.product_batchno,
//         wi.barcode_image AS item_barcode_image,
//         im.price AS product_price, 
//         im.barcode_image AS product_barcode_image, 
//         pb.batch_no AS batch_no, 
//         pb.manufacturer AS batch_manufacturer, 
//         pb.mfg_date AS batch_mfg_date, 
//         pb.exp_date AS batch_exp_date, 
//         pb.batch_price AS batch_price, 
//         pb.barcode_image AS batch_barcode_image
//     FROM 
//         work_order_items wi
//     JOIN 
//         inventory_master im ON wi.product_id = im.id
//     LEFT JOIN 
//         product_batches pb ON wi.product_batchno = pb.batch_no
//     WHERE 
//         wi.work_order_id = '$work_order_id'
// ";

// // Execute the query
// $result = mysqli_query($conn, $sql);

// // Check if data was fetched successfully
// if ($result && mysqli_num_rows($result) > 0) {
//     // Process each work order item
//     while ($row = mysqli_fetch_assoc($result)) {
//         $raw_material = $row['raw_material'];
//         $product_name = $row['product_name'];
//         $prod_desc = $row['prod_desc'];
//         $dno = $row['dno'];
//         $size = $row['size'];
//         $color = $row['color'];
//         $eqty = $row['eqty'];
//         $barcode_image = !empty($row['item_barcode_image']) ? $row['item_barcode_image'] : $row['product_barcode_image']; // Fallback to product barcode if item barcode is empty
//         $price = $row['product_price'];
//         $batch_manufacturer = $row['batch_manufacturer'];
//         $batch_mfg_date = $row['batch_mfg_date'];
//         $batch_exp_date = $row['batch_exp_date'];
//         $batch_barcode_image = $row['batch_barcode_image'];
        
//         // Create the tag image dynamically
//         header('Content-Type: image/png');

//         // Set image width and height for the tag
//         $width = 600;
//         $height = 300;

//         // Create a blank image
//         $image = imagecreatetruecolor($width, $height);

//         // Set up colors
//         $white = imagecolorallocate($image, 255, 255, 255);  // White background
//         $black = imagecolorallocate($image, 0, 0, 0);        // Black text
//         $red = imagecolorallocate($image, 255, 0, 0);         // Red color for "Mfd by"
//         $grey = imagecolorallocate($image, 169, 169, 169);    // Gray for dividers

//         // Fill the background with white color
//         imagefill($image, 0, 0, $white);

//         // Add the logo (path to the logo image)
//         $logo = 'img/logo.png';  // Replace with the actual path to your logo
//         $logoWidth = 100;
//         $logoHeight = 50;
//         imagecopyresized($image, imagecreatefrompng($logo), 10, 10, 0, 0, $logoWidth, $logoHeight, imagesx(imagecreatefrompng($logo)), imagesy(imagecreatefrompng($logo)));

//         // Set font and size for the text
//         $fontPath = 'fonts/Pacifico.ttf'; // Path to the TrueType font
//         $fontSize = 12;

//         // Draw the product details on the image dynamically
//         imagettftext($image, $fontSize, 0, 120, 30, $black, $fontPath, $product_name);
//         imagettftext($image, $fontSize, 0, 120, 55, $black, $fontPath, "QTY $eqty");
//         imagettftext($image, $fontSize, 0, 120, 80, $black, $fontPath, "D.NO : $dno");
//         imagettftext($image, $fontSize, 0, 120, 105, $black, $fontPath, "SIZE : $size");
//         imagettftext($image, $fontSize, 0, 120, 130, $black, $fontPath, "MFD : $batch_mfg_date");
//         imagettftext($image, $fontSize, 0, 120, 155, $black, $fontPath, "MRP : ₹ $price");

//         imagettftext($image, $fontSize, 0, 120, 220, $red, $fontPath, "Mfd & Mkd by: $batch_manufacturer");
//         imagettftext($image, $fontSize, 0, 120, 240, $black, $fontPath, "# 7/8, 1st Floor, 1st Main Road, Bylappa Circle, T. Dasarahalli, Bangalore - 560 057\nCustomer Care: Ph: +91 988 099 0431");

//         // Barcode image generation (use either product or batch barcode)
//         $barcodeWidth = 150;
//         $barcodeHeight = 50;
//         imagecopyresized($image, imagecreatefrompng($barcode_image), 380, 200, 0, 0, $barcodeWidth, $barcodeHeight, imagesx(imagecreatefrompng($barcode_image)), imagesy(imagecreatefrompng($barcode_image)));

//         // Add a divider (optional)
//         imageline($image, 10, 270, 590, 270, $grey);

//         // Output the image as PNG
//         imagepng($image);

//         // Destroy the image to free up memory
//         imagedestroy($image);
//     }
// } else {
//     echo "No work order found for the selected ID.";
// }
?>
