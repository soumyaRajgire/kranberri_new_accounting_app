
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


{
 
  include("config.php");
  include("fpdf/fpdf.php");
 
       $customer_name = mysqli_real_escape_string($conn,$_POST['customer_name_choice']);
 
 $customer_email=mysqli_real_escape_string($conn,$_POST['customer_email']);
   $cst_mstr_id = mysqli_real_escape_string($conn,$_POST['cst_mstr_id']);

   // $tax_rate=mysqli_real_escape_string($conn,$_POST['tax_rate']);
    // $tax_amount=mysqli_real_escape_string($conn,$_POST['tax_amount']);

   $sub_total=mysqli_real_escape_string($conn,$_POST['sub_total']);
   $pack_price = mysqli_real_escape_string($conn,$_POST['pack_price']);
    $total_amount = mysqli_real_escape_string($conn,$_POST['total_amount']);
    $remarks = mysqli_real_escape_string($conn,$_POST['remarks']);
$note = mysqli_real_escape_string($conn,$_POST['note']);

    // $sale_person_name = $_SESSION['name'];
    //  $sales_person_phone =$_SESSION['phone'];
$pinvoice_code = mysqli_real_escape_string($conn, $_POST['pinvoiceNo']);
$dueDate = mysqli_real_escape_string($conn, $_POST['dueDate']);
$pinvoiceDate = mysqli_real_escape_string($conn,$_POST['pinvoiceDate']);
$terms = mysqli_real_escape_string($conn,$_POST['terms_condition']);

 date_default_timezone_set('Asia/Kolkata');
$date1 =  date("d-m-Y");
$time1 = date("h:i:sa");
$created_by = $_SESSION['name'];

$id="";
$id1="";
$id3="";




if(($customer_name != "") )
{


 $result1=mysqli_query($conn,"select id from purchase_invoice where id=(select max(id) from purchase_invoice)");
  if($row1=mysqli_fetch_array($result1))
  {
    $id=$row1['id']+1;
  }else
  {
    $id=1;
  }

class PDF extends FPDF{
//     function plot_table($widths, $lineheight, $table, $border, $aligns=array(), $fills=array(), $links=array()){
//         $func = function($text, $c_width){
//             $len=strlen($text);
//             $twidth = $this->GetStringWidth($text);
//             $split = 0;
// if ($twidth != 0) {
//      $split = floor($c_width * $len / $twidth);
// }
//             // $split = floor($c_width * $len / $twidth);
//             $w_text = explode( "\n", wordwrap( $text, $split, "\n", true));
//             return $w_text;
//         };
//         foreach ($table as $line){
//             $line = array_map($func, $line, $widths);
//             $maxlines = max(array_map("count", $line));
//             foreach ($line as $key => $cell){
//                 $x_axis = $this->getx();
//                 $height = 0;
// if (count($cell) != 0) {
//     $height = $lineheight * $maxlines / count($cell);
// }
//                 // $height = $lineheight * $maxlines / count($cell);
//                 $len = count($line);
//                 $width = (isset($widths[$key]) === TRUE ? $widths[$key] : $widths / count($line));
//                 $align = (isset($aligns[$key]) === TRUE ? $aligns[$key] : '');
//                 $fill = (isset($fills[$key]) === TRUE ? $fills[$key] : false);
//                 $link = (isset($links[$key]) === TRUE ? $links[$key] : '');
//                 foreach ($cell as $textline){
//                     $this->cell($widths[$key],$height,$textline,0,0,$align,$fill,$link);
//                     $height += 2 * $lineheight * $maxlines / count($cell);
//                     $this->SetX($x_axis);
//                 }
//                 if($key == $len - 1){
//                     $lbreak=1;
//                 }
//                 else{
//                     $lbreak = 0;
//                 }
//                 $this->cell($widths[$key],$lineheight * $maxlines, '',$border,$lbreak);
//             }
//         }
//     }
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
//   $func = function ($text, $c_width) {
//     // Avoid division by zero
//     if ($c_width <= 0) {
//         $c_width = 1; // Set a minimum width
//     }

//     $len = mb_strlen($text, 'UTF-8');
//     $twidth = $this->GetStringWidth($text);
    
//     $split = ($twidth != 0) ? max(1, floor($c_width * $len / $twidth)) : 1;

//     $w_text = explode("\n", wordwrap($text, $split, "\n", true));
//     return $w_text;
// };


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


$pdf=new PDF('P','mm','A4');
$file_name = md5(rand()) . '.pdf';

$pdf->AddPage();
$pdf->SetFont("Arial","",10);

// $pdf->SetDrawColor(221,221,221,1);
// // $pdf->SetFillColor(51, 184, 255);
// $pdf->SetFillColor(113, 163, 244 );
// $pdf->Cell(0,10,"Quotation",0,1,'C',true);


// $pdf->Ln(3);
// $pdf->SetFont("Arial","",10);
// $pdf->SetDrawColor(221,221,221,1);
// $pdf->SetDrawColor(0,0,0,1);
$pdf->SetFillColor(232,232,232);
// $pdf->Cell(0,1,"",0,1,'C',true);
// $pdf->SetLineWidth(2);
// $pdf->Ln(3);
// $pdf->Cell(15,10,"Buyer :",0,0);
//$w= $pdf->GetStringWidth($customer_name)+6;
// $pdf->SetX((210-$w)/2);
// $pdf->Cell(120,10,$customer_name,0,0);

$pdf->SetFont('Arial', '', 9);
  $table = array(array("img/logo.png","\n KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets) \n Skyline Beverly Park, # D 402, Amruthahalli Main Road, Amruthahalli,Amruthal,Bangalore - 560092, \n KARNATAKA \nEmail: abhijith.mavatoor@gmail.com, Phone: 9481024700 \n GSTIN: 29AAICK7493G1ZX \n"));
 $lineheight = 4;
 $fontsize = 10;
 $aligns = array('C','C');
 $widths = array(35,154);
 $border=1;
 $pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

$pdf->SetFont('Arial', '', 9);
  $table = array(array("GST purchase_invoice"));
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

  $table = array(array("Purchase Invoice Number","$pinvoice_code","Place of Supply","{$row1['s_state']}"));
$lineheight = 9;
$fontsize = 10;
$widths = array(47.25,47.25,47.25,47.25);
$aligns = array('L','L','L','L');
$border=1;
$pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

$table = array(array("Purchase Invoice Date","$pinvoiceDate","Created By","$created_by"));
$lineheight = 9;
$fontsize = 10;
$widths = array(47.25,47.25,47.25,47.25);
$aligns = array('L','L','L','L');
$border=1;
$pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

$table = array(array("Due Date","$dueDate","Purchase Invoice Type","Original"));
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
// $pdf->Cell(10,10,"Sl.No.",1,0);
// $pdf->CellFitScale(70,10,"products",1,0,'',1);

// $pdf->CellFitScale(70,10,"proddesc",1,0,'',1);
$pdf->Cell(7,10,"Slno",1,0,'C',1);
 $pdf->Cell(92.3,10,"Product Description",1,0,'C',1);
$pdf->Cell(14,10,"GST",1,0,'C',1);
$pdf->Cell(20,10,"RATE",1,0,'C',1);
$pdf->Cell(12, 10, "QTY", 1, 0, 'C',1);
// $pdf->Cell(20, 10, "Taxable Amt", 1, 0, 'C',1);
$pdf->Cell(20, 10, "GST", 1, 0, 'C',1);
$pdf->Cell(24, 10, "TOTAL", 1, 1, 'C',1); 

$cgsttotal =0;
$sgsttotal = 0;
$pricevaltot =0;
$tot_total =0;
$tot_qty=0;
$nontax_tot_amt=0;
$pdf->SetFillColor(255,255,255);

$slno = 1; // Initialize the serial number counter

foreach ($_POST["products"] as $key => $val)
 {
$tot =0;
$cgsttotal =0;
$sgsttotal = 0;
$gsttot =0;
$nontax_amt=0;

  // $product_price=mysqli_real_escape_string($conn,$_POST['price'][$key]);

  $itemnum = $slno; // Use the serial number counter
   $products=mysqli_real_escape_string($conn,$_POST['products'][$key]);
  $proddesc=mysqli_real_escape_string($conn,$_POST['proddesc'][$key]);
   $qtyvalue=mysqli_real_escape_string($conn,$_POST['qtyvalue'][$key]); 
   $priceval=mysqli_real_escape_string($conn,$_POST['priceval'][$key]); 
   $gstval=mysqli_real_escape_string($conn,$_POST['gstval'][$key]); 
    $cgstval=mysqli_real_escape_string($conn,$_POST['cgstval'][$key]); 
    $sgstval=mysqli_real_escape_string($conn,$_POST['sgstval'][$key]); 
    $netprice = mysqli_real_escape_string($conn,$_POST['netpriceval'][$key]);
   $total=mysqli_real_escape_string($conn,$_POST['total'][$key]);
$productid = mysqli_real_escape_string($conn,$_POST['productids'][$key]);
$in_ex_gst = mysqli_real_escape_string($conn,$$_POST['in_ex_gst_val'][$key]);
$cgsttotal += floatval($cgstval); // Convert $cgstval to integer before adding
$sgsttotal += floatval($sgstval); // Convert $sgstval to integer before adding
$pricevaltot += floatval($priceval); 
$tot_total += floatval($total);
$tot = $total +$cgstval +$sgstval;
 $tot_formatted = number_format($tot, 0, '.', ''); 

$gsttot=($cgsttotal + $sgsttotal);
$tot_qty += $qtyvalue;
if($in_ex_gst === "inclusive of GST")
{
   $nontax_amt = $priceval / (1 + ($gstval / 100));

}else{
$nontax_amt = $priceval;
}
$nontax_tot_amt += $nontax_amt; 
// $table = array(array($itemnum,$products."\n". $proddesc,$gstval,$priceval, $qtyvalue, $priceval,$cgstval,$sgstval,$tot));
$table = array(array($itemnum,$products."\n". $proddesc,$gstval,$priceval, $qtyvalue,$gsttot,$tot));


$lineheight = 7;
$fontsize = 10;
$widths = array(7,92,14,20,12,20,24);
$aligns = array('C','L','C','C','C','C','C');
$border=1;
$pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

 $result2=mysqli_query($conn,"select id from purchase_invoice_items where id=(select max(id) from purchase_invoice_items)");
   if($row2=mysqli_fetch_array($result2))
   {
     $id1=$row2['id']+1;
   }
$line_tot = $qtyvalue * $priceval ;
$gstamt = number_format(($gstval / 2) * $total / 100, 2, '.', '');

    // Increment the serial number counter
    $slno++;
    
 // mysqli_query($conn,"INSERT INTO `purchase_order_items` (`id`, `itemno`, `purchase_order_id`, `product_id`, `product`, `prod_desc`, `qty`, `price`, `line_total`, `gst`, `gst_amt`, `total`, `created_by`) VALUES ('$id1', '$itemnum', '$id', '$productid', '$products','$proddesc','$qtyvalue','$priceval', '$line_tot', '$gstval', '$gstamt', '$tot_formatted', '$created_by')");

  mysqli_query($conn,"INSERT INTO `purchase_invoice_items` (`id`, `itemno`, `pinvoice_id`, `product_id`, `product`, `prod_desc`, `qty`, `price`, `line_total`, `gst`, `gst_amt`, `total`, `created_by`) VALUES ('$id1', '$itemnum', '$id', '$productid', '$products','$proddesc','$qtyvalue','$priceval', '$line_tot', '$gstval', '$gstamt', '$tot', '$created_by')");

  
  }

 // $nonTaxableAmount = $totalAmount / (1 + ($taxRate / 100));

// $table = array(
//     array(
//         "\n Billing Address \n\n {$row1['b_address_line1']} \n {$row1['b_address_line2']} \n {$row1['b_city']} - {$row1['b_Pincode']} \n {$row1['b_state']} \n",
//         "\n Shipping Address \n\n {$row1['s_address_line1']} \n {$row1['s_address_line2']} \n {$row1['s_city']} - {$row1['s_Pincode']} \n {$row1['s_state']} \n"
//     )
// );

// $lineheight = 5;
// $fontsize = 10;
// $widths = array(94.5,94.5);
// $aligns = array('L','L');
// $border=1;
// $pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

$gsttot=($cgsttotal + $sgsttotal);
$tot_amt = $gsttot + $tot_total;

$pdf->Cell(150, 6, "Nontaxable Amount", 'L', 0, 'R');
$pdf->Cell(39, 6, "$nontax_tot_amt",'R', 1, 'R');

$pdf->Cell(150, 6, "Taxable Amount", 'L', 0, 'R');
$pdf->Cell(39, 6, "$pricevaltot",'R', 1, 'R');

$pdf->Cell(150, 6, "GST Total", 'L', 0, 'R');
$pdf->Cell(39, 6, "$gsttot",'R', 1, 'R');

$pdf->Cell(150, 6, "Adjusment", 'L', 0, 'R');
$pdf->Cell(39, 6, "0",'R', 1, 'R');

$pdf->Cell(120,6,"Amount in words",'BL',0,'L');
$pdf->Cell(30, 6, "purchase_order Total", 'B', 0, 'R');
$pdf->Cell(39, 6, "INR $tot_amt",'BR', 1, 'R');


// $pdf->Cell(21,10,$tot_amt,1,1,'C');


$pdf->SetFont("Arial","B",8);
// $pdf->MultiCell(80, 10, "For KRIKA MKB CORPORATION PRIVATE LIMITED \n\n Authorised Signatory", 1, 'L');

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
    $terms = " "; // Set a space to ensure the cell has some content
}
$startY = $pdf->GetY();
$currentX = $pdf->GetX();

// First MultiCell
$pdf->MultiCell(100, 6, "Terms and Condition:\n$terms", 0, 'L');
$endYFirst = $pdf->GetY();

// Reset position for second MultiCell
$pdf->SetXY($currentX + 100, $startY);
$pdf->MultiCell(89, 6, "For KRIKA MKB CORPORATION PRIVATE LIMITED \n\n Authorised Signatory", 0, 'L');
$endYSecond = $pdf->GetY();

// Determine the maximum Y position reached
$maxY = max($endYFirst, $endYSecond);

// Draw rectangles for borders
$pdf->Rect($currentX, $startY, 100, $maxY - $startY, 'L'); // Left border for first cell
$pdf->Rect($currentX + 100, $startY, 89, $maxY - $startY, 'R'); // Right border for second cell

// Reset Y position to the maximum Y
$pdf->SetY($maxY);


$pdf->Cell(0,10,"Thank you for your Business!",1,1,'C');


ob_end_clean();

// a random hash will be necessary to send mixed content
$separator = md5(time());

// carriage return type (we use a PHP end of line constant)
$eol = PHP_EOL;

// attachment name
$filename = "purchase_invoice/".$file_name;


// encode data (puts attachment in proper format)
 $pdfdoc = $pdf->Output('S');
$pf = $pdf->Output();

file_put_contents($filename, $pdfdoc);

  $sql="INSERT INTO purchase_invoice (`id`, `pinvoice_code`, `pinvoice_file`,`customer_id`, `customer_name`, `email`, `pinvoice_date`, `due_date`, `total_amount`,`total_tax`, `grand_total`,  `terms_condition`, `note`,`status`, `created_by`) VALUES ('$id', '$pinvoice_code','$filename','$cst_mstr_id', '$customer_name', '$customer_email', '$pinvoiceDate', '$dueDate', '$pricevaltot', '$gsttot','$tot_amt', '$terms', '$note','pending', '$created_by')";

  if ($conn->query($sql) === TRUE) 
           {
  ?>

    <script>
      window.location="purchase_invoices.php?invoicesCard";
        alert("Successfully Created Purchase Invoice");
    </script> 
    <?php
 }
 else{
 ?>

    <script>
      window.location="create-purchase_invoice.php";
        alert("Unable to create invoice try again");
      <?php echo "Error: " . $sql . "<br>" . $conn->error;?>
    </script> 
    <?php

}

}
}
?>