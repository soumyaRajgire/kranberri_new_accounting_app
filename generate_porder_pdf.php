<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

error_reporting(E_ALL);
session_start(); 
if(!isset($_SESSION['name']) && $_SESSION['ROLE']!='1')
{
   // Check if the user is logged in
// if(!isset($_SESSION['LOG_IN'])){
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
include("fpdf/fpdf.php");

function safe_get($array, $key, $default = '') {
    return isset($array[$key]) ? mysqli_real_escape_string($GLOBALS['conn'], $array[$key]) : $default;
}

$order_id = safe_get($_POST, 'order_id');
$customer_name = safe_get($_POST, 'customer_name_choice');
$customer_email = safe_get($_POST, 'customer_email');
$cst_mstr_id = safe_get($_POST, 'cst_mstr_id');
$sub_total = safe_get($_POST, 'sub_total');
$pack_price = safe_get($_POST, 'pack_price');
$total_amount = safe_get($_POST, 'total_amount');
$remarks = safe_get($_POST, 'remarks');
$note = safe_get($_POST, 'note');
$order_code = safe_get($_POST, 'orderNo');
$dueDate = safe_get($_POST, 'dueDate');
$orderDate = safe_get($_POST, 'orderDate');
$terms = safe_get($_POST, 'terms_condition');

date_default_timezone_set('Asia/Kolkata');
$date1 =  date("d-m-Y");
$time1 = date("h:i:sa");
$created_by = $_SESSION['name'];

class PDF extends FPDF {
    function plot_table($widths, $lineheight, $table, $border, $aligns = array(), $fills = array(), $backgroundColors = array(),$links = array()) {
        $func = function($text, $c_width){
              $len=strlen($text);
              $twidth = $this->GetStringWidth($text);
              $split = 0;
  if ($twidth != 0) {
       $split = floor($c_width * $len / $twidth);
  }
              // $split = floor($c_width * $len / $twidth);
              $w_text = explode( "\n", wordwrap( $text, $split, "\n", true));
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
    

$pdf = new PDF('P','mm','A4');
$file_name = md5(rand()) . '.pdf';
$pdf->AddPage();
$pdf->SetFont("Arial","",10);

$pdf->SetFillColor(232,232,232);

$pdf->SetFont('Arial', '', 9);
  $table = array(array("img/logo.png","\n KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets) \n Skyline Beverly Park, # D 402, Amruthahalli Main Road, Amruthahalli,Amruthal,Bangalore - 560092, \n KARNATAKA \nEmail: abhijith.mavatoor@gmail.com, Phone: 9481024700 \n GSTIN: 29AAICK7493G1ZX \n"));
 $lineheight = 4;
 $fontsize = 10;
 $aligns = array('C','C');
 $widths = array(35,154);
 $border=1;
 $pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

$pdf->SetFont('Arial', '', 9);
  $table = array(array("GST purchase_order"));
 $lineheight = 8;
 $fontsize = 10;
 $aligns = array('C');
 $widths = array(189);
 $border=1;
 $backgroundColors = array(array(255, 200, 200)); // RGB color for the background (light red in this example)

 $pdf->plot_table($widths, $lineheight, $table,$border,$aligns,$backgroundColors);


// Fetch data from the database
$result1 = mysqli_query($conn, "SELECT *  FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id");

if ($row1 = mysqli_fetch_array($result1)) {

$pdf->SetFont("Arial","B",8);

$table = array(array(
        "\n Billing Address \n\n {$row1['b_address_line1']} \n {$row1['b_address_line2']} \n {$row1['b_city']} - {$row1['b_Pincode']} \n {$row1['b_state']} \n",
        "\n Shipping Address \n\n {$row1['s_address_line1']} \n {$row1['s_address_line2']} \n {$row1['s_city']} - {$row1['s_Pincode']} \n {$row1['s_state']} \n"
    )
);

$lineheight = 5;
$fontsize = 10;
$widths = array(94.5,94.5);
$aligns = array('L','L');
$border=1;
$pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

// }
$pdf->SetFont("Arial","",9);
$pdf->SetTextColor(0,0,0,0);

  $table = array(array("Order Number","$order_code","Place of Supply","{$row1['s_state']}"));
$lineheight = 9;
$fontsize = 10;
$widths = array(47.25,47.25,47.25,47.25);
$aligns = array('L','L','L','L');
$border=1;
$pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

$table = array(array("Order Date","$orderDate","Created By","$created_by"));
$lineheight = 9;
$fontsize = 10;
$widths = array(47.25,47.25,47.25,47.25);
$aligns = array('L','L','L','L');
$border=1;
$pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

$table = array(array("Due Date","$dueDate","Order Type","Original"));
$lineheight = 9;
$fontsize = 10;
$widths = array(47.25,47.25,47.25,47.25);
$aligns = array('L','L','L','L');
$border=1;
$pdf->plot_table($widths, $lineheight, $table,$border,$aligns);
}

$pdf->SetFont("Arial","B",8);
$pdf->SetTextColor(0,0,0,0);
$pdf->SetDrawColor(0,0,0,0);
$pdf->SetLineWidth(0);
$pdf->SetFillColor(232,232,232);
$pdf->Cell(7,10,"#",1,0,'C',1);
$pdf->Cell(92.3,10,"Product Description",1,0,'C',1);
$pdf->Cell(14,10,"GST",1,0,'C',1);
$pdf->Cell(20,10,"RATE",1,0,'C',1);
$pdf->Cell(12, 10, "QTY", 1, 0, 'C',1);
$pdf->Cell(20, 10, "GST", 1, 0, 'C',1);
$pdf->Cell(24, 10, "TOTAL", 1, 1, 'C',1);

$cgsttotal = 0;
$sgsttotal = 0;
$pricevaltot = 0;
$tot_total = 0;
$tot_qty = 0;
$nontax_tot_amt = 0;
$pdf->SetFillColor(255,255,255);

// Check if keys exist in the $_POST array before accessing them
$products = isset($_POST["products"]) ? $_POST["products"] : [];
$proddesc = isset($_POST["proddesc"]) ? $_POST["proddesc"] : [];
$qtyvalue = isset($_POST["qtyvalue"]) ? $_POST["qtyvalue"] : [];
$priceval = isset($_POST["priceval"]) ? $_POST["priceval"] : [];
$gstval = isset($_POST["gstval"]) ? $_POST["gstval"] : [];
$cgstval = isset($_POST["cgstval"]) ? $_POST["cgstval"] : [];
$sgstval = isset($_POST["sgstval"]) ? $_POST["sgstval"] : [];
$netprice = isset($_POST["netpriceval"]) ? $_POST["netpriceval"] : [];
$total = isset($_POST["total"]) ? $_POST["total"] : [];
$productids = isset($_POST["productids"]) ? $_POST["productids"] : [];
$in_ex_gst = isset($_POST["in_ex_gst_val"]) ? $_POST["in_ex_gst_val"] : [];
$itemnum = isset($_POST["itemnum"]) ? $_POST["itemnum"] : [];

foreach ($products as $key => $val) {
    $tot = 0;
    $cgsttotal = 0;
    $sgsttotal = 0;
    $gsttot = 0;
    $nontax_amt = 0;

    // Use safe_get for arrays to ensure valid access
    $itemnum_val = isset($itemnum[$key]) ? $itemnum[$key] : '';
    $product = isset($products[$key]) ? $products[$key] : '';
    $description = isset($proddesc[$key]) ? $proddesc[$key] : '';
    $qty = isset($qtyvalue[$key]) ? $qtyvalue[$key] : 0;
    $price = isset($priceval[$key]) ? $priceval[$key] : 0;
    $gst = isset($gstval[$key]) ? $gstval[$key] : 0;
    $cgst = isset($cgstval[$key]) ? $cgstval[$key] : 0;
    $sgst = isset($sgstval[$key]) ? $sgstval[$key] : 0;
    $netprice_val = isset($netprice[$key]) ? $netprice[$key] : 0;
    $total_val = isset($total[$key]) ? $total[$key] : 0;
    $productid = isset($productids[$key]) ? $productids[$key] : '';
    $in_ex_gst_val = isset($in_ex_gst[$key]) ? $in_ex_gst[$key] : '';

    $cgsttotal += floatval($cgst);
    $sgsttotal += floatval($sgst);
    $pricevaltot += floatval($price);
    $tot_total += floatval($total_val);
    $tot = floatval($total_val) + floatval($cgst) + floatval($sgst);
    $tot_formatted = number_format($tot, 0, '.', '');

    $gsttot = ($cgsttotal + $sgsttotal);
    $tot_qty += $qty;
    if ($in_ex_gst_val === "inclusive of GST") {
        $nontax_amt = $price / (1 + ($gst / 100));
    } else {
        $nontax_amt = $price;
    }
    $nontax_tot_amt += $nontax_amt;

    $table = array(array($itemnum_val, $product."\n". $description, $gst, $price, $qty, $gsttot, $tot));
    $lineheight = 7;
    $fontsize = 10;
    $widths = array(7,92,14,20,12,20,24);
    $aligns = array('C','L','C','C','C','C','C');
    $border=1;
    $pdf->plot_table($widths, $lineheight, $table, $border, $aligns);

    $result2 = mysqli_query($conn,"select id from purchase_order_items where id=(select max(id) from purchase_order_items)");
    if($row2 = mysqli_fetch_array($result2)) {
        $id1 = $row2['id'] + 1;
    }
    $line_tot = $qty * $price;
    $gstamt = number_format(($gst / 2) * $total_val / 100, 2, '.', '');

    mysqli_query($conn,"INSERT INTO `purchase_order_items` (`id`, `itemno`, `order_id`, `product_id`, `product`, `prod_desc`, `qty`, `price`, `line_total`, `gst`, `gst_amt`, `total`, `created_by`) VALUES ('$id1', '$itemnum_val', '$order_id', '$productid', '$product','$description','$qty','$price', '$line_tot', '$gst', '$gstamt', '$tot', '$created_by')");
}

$gsttot = ($cgsttotal + $sgsttotal);
$tot_amt = $gsttot + $tot_total;

$pdf->Cell(150, 6, "Nontaxable Amount", 'L', 0, 'R');
$pdf->Cell(39, 6, number_format($nontax_tot_amt, 2),'R', 1, 'R');

$pdf->Cell(150, 6, "Taxable Amount", 'L', 0, 'R');
$pdf->Cell(39, 6, number_format($pricevaltot, 2),'R', 1, 'R');

$pdf->Cell(150, 6, "GST Total", 'L', 0, 'R');
$pdf->Cell(39, 6, number_format($gsttot, 2),'R', 1, 'R');

$pdf->Cell(150, 6, "Adjustment", 'L', 0, 'R');
$pdf->Cell(39, 6, "0",'R', 1, 'R');

$pdf->Cell(120,6,"Amount in words",'BL',0,'L');
$pdf->Cell(30, 6, "Purchase Order Total", 'B', 0, 'R');
$pdf->Cell(39, 6, "INR " . number_format($tot_amt, 2),'BR', 1, 'R');

$pdf->SetFont("Arial","B",8);

$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->Cell(27, 6, "Bank Name", 'L', 0, 'L');
$pdf->Cell(66, 6, "IDFC BANK LIMITED",'R', 0, 'L');
$pdf->MultiCell(96,4,"Note : $note",'TR',1,'L');
$pdf->SetXY($x , $y + 6);
$pdf->Cell(27, 6, "Account Name", 'L', 0, 'L');
$pdf->Cell(66, 6, "KRIKA MKB CORPORATION PRIVATE LIMITED",'R', 0, 'L');
$pdf->Cell(96,6,"",'R',1,'L');

$pdf->Cell(27, 6, "Account No", 'L', 0, 'L');
$pdf->Cell(66, 6, "10069839667",'R', 0, 'L');
$pdf->Cell(96,6,"",'R',1,'L');

$pdf->Cell(27, 6, "IFSC Code", 'BL', 0, 'L');
$pdf->Cell(66, 6, "IDFB0080177",'BR', 0, 'L');
$pdf->Cell(96,6,"",'BR',1,'L');

$pdf->SetFont("Arial","B",);

if (empty($terms)) {
    $terms = " ";
}
$startY = $pdf->GetY();
$currentX = $pdf->GetX();
$pdf->MultiCell(100, 6, "Terms and Condition:\n$terms", 0, 'L');
$endYFirst = $pdf->GetY();
$pdf->SetXY($currentX + 100, $startY);
$pdf->MultiCell(89, 6, "For KRIKA MKB CORPORATION PRIVATE LIMITED \n\n Authorised Signatory", 0, 'L');
$endYSecond = $pdf->GetY();
$maxY = max($endYFirst, $endYSecond);
$pdf->Rect($currentX, $startY, 100, $maxY - $startY, 'L');
$pdf->Rect($currentX + 100, $startY, 89, $maxY - $startY, 'R');
$pdf->SetY($maxY);
$pdf->Cell(0,10,"Thank you for your Business!",1,1,'C');

ob_end_clean();
$separator = md5(time());
$eol = PHP_EOL;
$filename = "purchase_order/".$file_name;
$pdfdoc = $pdf->Output('S');
file_put_contents($filename, $pdfdoc);

$sql = "UPDATE purchase_order SET order_file='$filename' WHERE id='$order_id'";
if ($conn->query($sql) === TRUE) {
    // PDF generation and update successful
} else {
    echo "Error updating record: " . $conn->error;
}
?>