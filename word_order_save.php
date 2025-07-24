<html>

<head>
    
    <!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

<?php
include('config.php'); // Database connection
include('fpdf/fpdf.php'); // Include FPDF library
session_start();
 $branch_id = $_SESSION['branch_id'] ?? null;
// Assuming you're fetching the data from the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Fetch the necessary form data
    $pi_code = $_POST['pi_code'];
    $pi_date = $_POST['purchase_invoice_date'];
     $wo_date = $_POST['invoice_date'];
    $wo_number = $_POST['invoice_code']; // Unique Work Order ID (e.g., WO001)
    $supplier_id = $_POST['supplier_id']; // Supplier ID (retrieved from the form)
    $note = $_POST['note']; // Additional note
$manufacturer_id = $_POST['select_manufacturer'];
$manufacturer_name = $_POST['manufacturer_name'];

    // Work order data (Raw Material, Product Name, DNo, Size, Color, E. Qty, F. Qty)
    $raw_material = $_POST['product_raw_mat']; 
    $product_name = $_POST['product_name'];
    $product_id = $_POST['product_id'];
    $prod_desc =$_POST['prod_desc'];
    $dno = $_POST['dno'];
    $size = $_POST['size'];
    $color = $_POST['color'];
    $eqty = $_POST['eqty'];
     // $eqty = $_POST['eqty'];
      $product_barcodeno = $_POST['product_barcodeno'];
      $product_barcodeimage = $_POST['product_barcodeimage'];
    // $fqty = $_POST['fqty'];
    $product_batchno = $_POST['product_batchno'];

    $raw_material_batchno = $_POST['raw_materail_batchno']; // Adding raw material batchno field
 class PDF extends FPDF {
       function plot_table($widths, $lineheight, $table, $border, $aligns = array(), $fills = array(), $backgroundColors = array(),$links = array()) {
   $func = function($text, $c_width){
    $len=strlen($text);
    $twidth = $this->GetStringWidth($text);
    $split = 0;
    if ($twidth != 0) {
        $split = floor($c_width * $len / $twidth);
    }
    $w_text = explode("\n", wordwrap($text, $split, "\n", true));
    return $w_text;
};

    foreach ($table as $line) {
        $line = array_map($func, $line, $widths);
        $maxlines = max(array_map("count", $line));

        foreach ($line as $key => $cell) {
            $x_axis = $this->GetX();
            $height = 0;

            if (count($cell) != 0) {
                $height = $lineheight * $maxlines / count($cell);
            }

            $len = count($line);
            $width = (isset($widths[$key]) === TRUE ? $widths[$key] : $widths / count($line));
            $align = (isset($aligns[$key]) === TRUE ? $aligns[$key] : '');
            $fill = (isset($fills[$key]) === TRUE ? $fills[$key] : false);
            $link = (isset($links[$key]) === TRUE ? $links[$key] : '');

  $backgroundColor = (isset($backgroundColors[$key]) === TRUE ? $backgroundColors[$key] : '');

        // Set background color if available
        if (!empty($backgroundColor)) {
            $this->SetFillColor($backgroundColor[0], $backgroundColor[1], $backgroundColor[2]);
            $this->Rect($this->GetX(), $this->GetY(), array_sum($widths), $height, 'F');
        }

            foreach ($cell as $textline) {
                // Check if the textline is an image path
                if (is_string($textline) && file_exists($textline)) {
                    $imageWidth = 30;  // Replace with your desired width
                    $imageHeight = 15;  // Keep the same height as the text
                    $imageX = $this->GetX() + ($width - $imageWidth) / 2;
                    $imageY = $this->GetY() + ($height - $imageHeight) / 2;
                    $this->Image($textline, $imageX, $imageY, $imageWidth, $imageHeight);
                } else {
                    $this->Cell($widths[$key], $height, $textline, 0, 0, $align, $fill, $link);
                }

                $height += 2 * $lineheight * $maxlines / count($cell);
                $this->SetX($x_axis);
            }

            if ($key == $len - 1) {
                $lbreak = 1;
            } else {
                $lbreak = 0;
            }

            $this->Cell($widths[$key], $lineheight * $maxlines, '', $border, $lbreak);
        }
    }
}
    }



        // Generate Work Order PDF
        $pdf = new PDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);

  $pdf->SetFillColor(232,232,232);
  $pdf->SetFont('Arial', '', 9);
   $result1 = mysqli_query($conn, "SELECT *  FROM add_branch where branch_id='$branch_id'");

if ($row1 = mysqli_fetch_array($result1)) {

  $table = array(array("img/logo.png","\n {$row1['branch_name']} \n {$row1['address_line1']}, {$row1['address_line2']}, {$row1['city']} - {$row1['pincode']}, \n {$row1['state']} \nEmail: {$row1['office_email']}, Phone: {$row1['phone_number']} \n GSTIN: {$row1['GST']} \n"));

}
 $lineheight = 4;
 $fontsize = 10;
 $aligns = array('C','C');
 $widths = array(35,154);
 $border=1;
 $pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

$pdf->SetFont('Arial', '', 9);
  $table = array(array("Work Order Details"));
 $lineheight = 8;
 $fontsize = 10;
 $aligns = array('C');
 $widths = array(189);
 $border=1;
 $backgroundColors = array(array(255, 200, 200)); // RGB color for the background (light red in this example)

 $pdf->plot_table($widths, $lineheight, $table,$border,$aligns,$backgroundColors);

 
//  $result1 = mysqli_query($conn, "SELECT *  FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id WHERE customer_master.id = '$supplier_id'");

// if ($row1 = mysqli_fetch_array($result1)) {

// $pdf->SetFont("Arial","B",8);

// $table = array(array(
//         "\n Billing Address \n\n {$row1['b_address_line1']} \n {$row1['b_address_line2']} \n {$row1['b_city']} - {$row1['b_Pincode']} \n {$row1['b_state']} \n",
//         "\n Shipping Address \n\n {$row1['s_address_line1']} \n {$row1['s_address_line2']} \n {$row1['s_city']} - {$row1['s_Pincode']} \n {$row1['s_state']} \n"
//     )
// );
// }
$lineheight = 5;
$fontsize = 10;
$widths = array(94.5,94.5);
$aligns = array('L','L');
$border=1;
$pdf->plot_table($widths, $lineheight, $table,$border,$aligns);
$pdf->SetFont("Arial","",9);
$pdf->SetTextColor(0,0,0,0);
  
$table = array(
    array("PI Number", "$pi_code", "PI Date", "$pi_date", "Manufacturer", "$manufacturer_name")
);

// New widths array with 6 columns instead of 4
$widths = array(31.5, 31.5, 31.5, 31.5, 31.5, 31.5); // Adjusting for 6 columns now
$lineheight = 7;
$fontsize = 9;
$aligns = array('L', 'L', 'L', 'L', 'L', 'L'); // Align all columns to left
$border = 1;

// Call the plot_table function to display the table
$pdf->plot_table($widths, $lineheight, $table, $border, $aligns);


$table = array(array("WO Number","$wo_number","WO Date","$wo_date"));
$lineheight = 7;
$fontsize = 9;
$widths = array(47.25,47.25,47.25,47.25);
$aligns = array('L','L','L','L');
$border=1;
$pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

$branch_id = $_SESSION['branch_id'];
        // $pdf->Ln(10);
   // Generate work order in database using provided structure
    $sql = "INSERT INTO work_orders (wo_number, wo_date, pi_code, pi_date, supplier_id,manufacturer_id, note, created_at, work_order_file,branch_id)
            VALUES ('$wo_number', '$wo_date', '$pi_code', '$pi_date', '$supplier_id','$manufacturer_id', '$note', NOW(), '','$branch_id')";

    if ($conn->query($sql) === TRUE) {
        $work_order_id = $conn->insert_id; // Get the inserted work order ID

        // Insert work order items into the work_order_items table
        for ($i = 0; $i < count($raw_material); $i++) {
            $raw_material_value = $raw_material[$i];
            $product_value = $product_name[$i];
             $product_id_value = $product_id[$i];
             $prod_desc_value = $prod_desc[$i];
            $dno_value = $dno[$i];
            $size_value = $size[$i];
            $color_value = $color[$i];
            $eqty_value = $eqty[$i];
            $product_barcodeno_val = $product_barcodeno[$i];
            $product_barcodeimage_val = $product_barcodeimage[$i];
            // $fqty_value = $fqty[$i];
            $product_batch_value = $product_batchno[$i];
            $raw_material_batch_value = $raw_material_batchno[$i]; // Raw material batchno from form

            // Insert each item into the work_order_items table
            $sql_item = "INSERT INTO work_order_items (work_order_id, raw_material, product_name, product_id, prod_desc, dno, size, color, eqty, barcode_no, raw_material_batchno, product_batchno, barcode_image)
                         VALUES ('$work_order_id', '$raw_material_value', '$product_value', '$product_id_value', '$prod_desc_value', '$dno_value', '$size_value', '$color_value', '$eqty_value', '$product_barcodeno_val', '$raw_material_batch_value', '$product_batch_value','$product_barcodeimage_val')";

            if (!$conn->query($sql_item)) {
                die("Error inserting work order item: " . $conn->error);
            }
        }
        
        // Table for Work Order Detail
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(44, 6, "Raw Material", 1, 0, 'C');
        $pdf->Cell(45, 6, "Product Name", 1, 0, 'C');
        $pdf->Cell(18, 6, "DNO", 1, 0, 'C');
        $pdf->Cell(15, 6, "Size", 1, 0, 'C');
        $pdf->Cell(20, 6, "Color", 1, 0, 'C');
        $pdf->Cell(20, 6, "E. Qty", 1, 0, 'C');
        $pdf->Cell(27, 6, "Barcode", 1, 1, 'C');

        // Loop through work order items and add to the table
        for ($i = 0; $i < count($raw_material); $i++) {
            $pdf->SetFont('Arial', '', 8);
           $pdf->Cell(44, 6, $raw_material[$i], 1, 0, 'C');
            $pdf->Cell(45, 6, $product_name[$i], 1, 0, 'C');
            $pdf->Cell(18, 6, $dno[$i], 1, 0, 'C');
            $pdf->Cell(15, 6, $size[$i], 1, 0, 'C');
            $pdf->Cell(20, 6, $color[$i], 1, 0, 'C');
            $pdf->Cell(20, 6, $eqty[$i], 1, 0, 'C');
     // Check if the barcode image path is valid
$barcode_image_path = $product_barcodeimage[$i]; // Assuming $product_barcodeimage_val[$i] contains the image path
if (!empty($barcode_image_path) && file_exists($barcode_image_path)) {
    // Add the barcode image to the PDF (you can adjust the width and height as needed)
    $pdf->Image($barcode_image_path, $pdf->GetX(), $pdf->GetY(), 18, 5);
} else {
    $pdf->Cell(27, 8, "No Image", 1, 0, 'C'); // Placeholder if no barcode image is available
}

$pdf->Ln(); // Move to the next row
        }


$pdf->SetFont("Arial","B",8);


$pdf->SetFont("Arial","B",);


if (empty($note)) {
    $note = " "; // Set a space to ensure the cell has some content
}
$startY = $pdf->GetY();
$currentX = $pdf->GetX();

// First MultiCell
$pdf->MultiCell(100, 6, "Note:\n$note", 0, 'L');
$endYFirst = $pdf->GetY();

// Reset position for second MultiCell
$pdf->SetXY($currentX + 100, $startY);
$pdf->MultiCell(89, 6, "For  \n\n Authorised Signatory", 0, 'L');
$endYSecond = $pdf->GetY();

// Determine the maximum Y position reached
$maxY = max($endYFirst, $endYSecond);

// Draw rectangles for borders
$pdf->Rect($currentX, $startY, 100, $maxY - $startY, 'L'); // Left border for first cell
$pdf->Rect($currentX + 100, $startY, 89, $maxY - $startY, 'R'); // Right border for second cell

// Reset Y position to the maximum Y
$pdf->SetY($maxY);


$pdf->Cell(189,10,"Thank you for your Business!",1,1,'C');


        // Output PDF
        $directory = 'work_orders/'; // The directory where you want to save the PDF
$file_name = "work_order_" . $wo_number . ".pdf";
$pdf_file_path = $directory . $file_name; // File path relative to the project root

        $pdf->Output('F', $pdf_file_path); // Save the file to the specified location

        $sql_update = "UPDATE work_orders SET work_order_file = '$pdf_file_path' WHERE id = '$work_order_id'";
        $conn->query($sql_update);

          echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Work Order Created',
                text: 'The work order has been successfully generated and saved.',
                confirmButtonText: 'OK'
            }).then(function() {
                window.location.href = 'view_work_orders.php?id=$work_order_id'; // Redirect to work order view page
            });
        </script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
</body>
</html>