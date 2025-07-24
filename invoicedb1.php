
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

error_reporting(E_ALL);
session_start(); 
if(!isset($_SESSION['name']) && $_SESSION['ROLE']!='1')
{
   header("Location:login.php");
}
else
{
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
}
include("config.php");

// if(isset($_POST['submit']))
// {
 
//   include("config.php");
  include("fpdf/fpdf.php");
 
//        $customer_name = mysqli_real_escape_string($conn,$_POST['customer_name_choice']);
 
//  $customer_email=mysqli_real_escape_string($conn,$_POST['customer_email']);
//    $cst_mstr_id = mysqli_real_escape_string($conn,$_POST['cst_mstr_id']);

//    // $tax_rate=mysqli_real_escape_string($conn,$_POST['tax_rate']);
//     // $tax_amount=mysqli_real_escape_string($conn,$_POST['tax_amount']);

//    $sub_total=mysqli_real_escape_string($conn,$_POST['sub_total']);
//    $pack_price = mysqli_real_escape_string($conn,$_POST['pack_price']);
//     $total_amount = mysqli_real_escape_string($conn,$_POST['total_amount']);
//     $remarks = mysqli_real_escape_string($conn,$_POST['remarks']);
// $note = mysqli_real_escape_string($conn,$_POST['note']);

//     // $sale_person_name = $_SESSION['name'];
//     //  $sales_person_phone =$_SESSION['phone'];
// $invoice_code = mysqli_real_escape_string($conn, $_POST['purchaseNo']);
// $dueDate = mysqli_real_escape_string($conn, $_POST['dueDate']);
// $purchaseDate = mysqli_real_escape_string($conn,$_POST['purchaseDate']);
// $terms = mysqli_real_escape_string($conn,$_POST['terms_condition']);

//  date_default_timezone_set('Asia/Kolkata');
// $date1 =  date("d-m-Y");
// $time1 = date("h:i:sa");
// $created_by = $_SESSION['name'];

// $id="";
// $id1="";
// $id3="";




// if(($customer_name != "") )
// {

//  $result1=mysqli_query($conn,"select id from invoice where id=(select max(id) from invoice)");
//   if($row1=mysqli_fetch_array($result1))
//   {
//     $id=$row1['id']+1;
//   }else
//   {
//     $id=1;
//   }

class PDF extends FPDF{
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

// ... [Previous code remains unchanged up to $pdf=new PDF('P','mm','A4');]

// Add logo and header
$pdf->Image('img/logo.png',10,6,30); // Adjust path and dimensions
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80); // Move to the right
$pdf->Cell(30,10,'KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)',0,1,'C'); // Company name
$pdf->SetFont('Arial','',10);
$pdf->Cell(190,10,'Address line, City, State, Zip',0,1,'C'); // Company address

// Billing and Shipping Address
$pdf->SetFont('Arial','B',10);
$pdf->Cell(95,10,'Billing Address',1,0,'C');
$pdf->Cell(95,10,'Shipping Address',1,1,'C');
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(95,10,"$billingAddress",1,'L',0); // Replace with actual data
$pdf->SetXY(105, 40);
$pdf->MultiCell(95,10,"$shippingAddress",1,'L',0); // Replace with actual data

// Invoice Details
$pdf->SetFont('Arial','B',9);
$pdf->Cell(47.5,10,'Invoice Number',1,0);
$pdf->SetFont('Arial','',9);
$pdf->Cell(47.5,10,"$invoiceNumber",1,0); // Replace with actual data
$pdf->SetFont('Arial','B',9);
$pdf->Cell(47.5,10,'Invoice Date',1,0);
$pdf->SetFont('Arial','',9);
$pdf->Cell(47.5,10,"$invoiceDate",1,1); // Replace with actual data

// ... [Additional invoice details in similar manner]

// Itemized Details
$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,10,'#',1,0,'C');
$pdf->Cell(70,10,'Item Description',1,0,'C');
$pdf->Cell(30,10,'Quantity',1,0,'C');
$pdf->Cell(30,10,'Price',1,0,'C');
$pdf->Cell(50,10,'Total',1,1,'C');

$pdf->SetFont('Arial','',9);
// Loop through items
foreach ($items as $item) { // Replace with actual items array
    $pdf->Cell(10,10,$item['no'],1,0,'C');
    $pdf->Cell(70,10,$item['description'],1,0);
    $pdf->Cell(30,10,$item['quantity'],1,0,'C');
    $pdf->Cell(30,10,$item['price'],1,0,'C');
    $pdf->Cell(50,10,$item['total'],1,1,'C');
}

// Summary
$pdf->SetFont('Arial','B',9);
$pdf->Cell(140,10,'',0,0); // Spacer
$pdf->Cell(30,10,'Subtotal',1,0);
$pdf->SetFont('Arial','',9);
$pdf->Cell(20,10,"$subtotal",1,1,'C'); // Replace with actual data

// ... [Additional summary rows for taxes, total, etc.]

// Bank Details
$pdf->SetFont('Arial','B',9);
$pdf->Cell(30,10,'Bank Name:',0,0);
$pdf->SetFont('Arial','',9);
$pdf->Cell(70,10,'Your Bank Name',0,1); // Replace with actual data

// ... [Additional bank details]

// Terms & Conditions and Signature
$pdf->SetFont('Arial','B',9);
$pdf->MultiCell(190,10,"Terms and Conditions\n$termsAndConditions",1,'L',0); // Replace with actual data
$pdf->Cell(190,10,'Authorized Signature',0,1,'R');

// Output the PDF
$pdf->Output('I', 'invoice.pdf');


// $pdf->SetFont('Arial', '', 9);
//   $table = array(array("img/logo.png","\n KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets) \n Skyline Beverly Park, # D 402, Amruthahalli Main Road, Amruthahalli,Amruthal,Bangalore - 560092, \n KARNATAKA \nEmail: abhijith.mavatoor@gmail.com, Phone: 9481024700 \n GSTIN: 29AAICK7493G1ZX \n"));
//  $lineheight = 4;
//  $fontsize = 10;
//  $aligns = array('C','C');
//  $widths = array(35,154);
//  $border=1;
//  $pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

// $pdf->SetFont('Arial', '', 9);
//   $table = array(array("GST INVOICE"));
//  $lineheight = 8;
//  $fontsize = 10;
//  $aligns = array('C');
//  $widths = array(189);
//  $border=1;
//  $backgroundColors = array(array(255, 200, 200)); // RGB color for the background (light red in this example)

//  $pdf->plot_table($widths, $lineheight, $table,$border,$aligns,$backgroundColors);


//  $result1=mysqli_query($conn,"SELECT *
// FROM customer_master
// JOIN address_master ON customer_master.id = address_master.customer_master_id");
//  ;

//    if($row1=mysqli_fetch_array($result1))
//    {

//     $pdf->SetFont('Arial', 'B', 9);  

// $table = array(array($row1['business_name'],$row1['b_address_line1'], $row1['s_address_line1']));
// $lineheight = 6;
// $fontsize = 10;
// $widths = array(68,68,68);
// $border=0;
// $pdf->plot_table($widths, $lineheight, $table,$border);

// }



// Fetch data from the database
// $result1 = mysqli_query($conn, "SELECT *  FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id");

// if ($row1 = mysqli_fetch_array($result1)) {

// $pdf->SetFont("Arial","B",8);
// $pdf->SetTextColor(0,0,0,1);
// $pdf->SetLineWidth(0);
// $pdf->Cell(94.5,10,"Billing Address",1,0,'L');
// $pdf->Cell(94.5,10,"Shipping Address",1,1,'L');

// $table = array(array("$row1['b_address_line1'] \n $row1['b_address_line2'] \n $row1['b_city'] \n $row1['b_Pincode'] \n $row1['b_state'])","$row1['s_address_line1'] \n $row1['s_address_line2'] \n $row1['s_city'] \n $row1['s_Pincode'] \n $row1['s_state']");

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

// // }
// $pdf->SetFont("Arial","",9);
// $pdf->SetTextColor(0,0,0,0);

//   $table = array(array("Invoice Number","$invoice_code","Place of Supply","{$row1['s_state']}"));
// $lineheight = 9;
// $fontsize = 10;
// $widths = array(47.25,47.25,47.25,47.25);
// $aligns = array('L','L','L','L');
// $border=1;
// $pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

// $table = array(array("Invoice Date","$purchaseDate","Created By","$created_by"));
// $lineheight = 9;
// $fontsize = 10;
// $widths = array(47.25,47.25,47.25,47.25);
// $aligns = array('L','L','L','L');
// $border=1;
// $pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

// $table = array(array("Due Date","$dueDate","Invoice Type","Original"));
// $lineheight = 9;
// $fontsize = 10;
// $widths = array(47.25,47.25,47.25,47.25);
// $aligns = array('L','L','L','L');
// $border=1;
// $pdf->plot_table($widths, $lineheight, $table,$border,$aligns);
// }
// $pdf->SetFont("Arial","B",8);
// $pdf->SetTextColor(0,0,0,0);
// $pdf->SetDrawColor(0,0,0,0);
// $pdf->SetLineWidth(0);
// $pdf->SetFillColor(232,232,232);
// // $pdf->Cell(10,10,"Sl.No.",1,0);
// // $pdf->CellFitScale(70,10,"products",1,0,'',1);

// // $pdf->CellFitScale(70,10,"proddesc",1,0,'',1);
// $pdf->Cell(7,10,"#",1,0,'C',1);
//  $pdf->Cell(92.3,10,"Product Description",1,0,'C',1);
// $pdf->Cell(14,10,"GST",1,0,'C',1);
// $pdf->Cell(20,10,"RATE",1,0,'C',1);
// $pdf->Cell(12, 10, "QTY", 1, 0, 'C',1);
// // $pdf->Cell(20, 10, "Taxable Amt", 1, 0, 'C',1);
// $pdf->Cell(20, 10, "GST", 1, 0, 'C',1);
// $pdf->Cell(24, 10, "TOTAL", 1, 1, 'C',1); 

// $cgsttotal =0;
// $sgsttotal = 0;
// $pricevaltot =0;
// $tot_total =0;
// $tot_qty=0;
// $nontax_tot_amt=0;
// $pdf->SetFillColor(255,255,255);
// foreach ($_POST["products"] as $key => $val)
//  {
// $tot =0;
//   // $product_price=mysqli_real_escape_string($conn,$_POST['price'][$key]);

//    $itemnum=mysqli_real_escape_string($conn,$_POST['itemnum'][$key]);
//    $products=mysqli_real_escape_string($conn,$_POST['products'][$key]);
//   $proddesc=mysqli_real_escape_string($conn,$_POST['proddesc'][$key]);
//    $qtyvalue=mysqli_real_escape_string($conn,$_POST['qtyvalue'][$key]); 
//    $priceval=mysqli_real_escape_string($conn,$_POST['priceval'][$key]); 
//    $gstval=mysqli_real_escape_string($conn,$_POST['gstval'][$key]); 
//     $cgstval=mysqli_real_escape_string($conn,$_POST['cgstval'][$key]); 
//     $sgstval=mysqli_real_escape_string($conn,$_POST['sgstval'][$key]); 
//     $netprice = mysqli_real_escape_string($conn,$_POST['netpriceval'][$key]);
//    $total=mysqli_real_escape_string($conn,$_POST['total'][$key]);
// $productid = mysqli_real_escape_string($conn,$_POST['productids'][$key]);
// $in_ex_gst = mysqli_real_escape_string($conn,$$_POST['in_ex_gst_val'][$key]);
// $cgsttotal += floatval($cgstval); // Convert $cgstval to integer before adding
// $sgsttotal += floatval($sgstval); // Convert $sgstval to integer before adding
// $pricevaltot += floatval($priceval); 
// $tot_total += floatval($total);
// $tot = $total +$cgstval +$sgstval;
//  $tot_formatted = number_format($tot, 0, '.', ''); 

// $gsttot=($cgsttotal + $sgsttotal);
// $tot_qty += $qtyvalue;
// if($in_ex_gst === "inclusive of GST")
// {
//    $nontax_amt = $priceval / (1 + ($gstval / 100));

// }else{
// $nontax_amt = $priceval;
// }
// $nontax_tot_amt += $nontax_amt; 
// // $table = array(array($itemnum,$products."\n". $proddesc,$gstval,$priceval, $qtyvalue, $priceval,$cgstval,$sgstval,$tot));
// $table = array(array($itemnum,$products."\n". $proddesc,$gstval,$priceval, $qtyvalue,$gsttot,$tot));


// $lineheight = 7;
// $fontsize = 10;
// $widths = array(7,92,14,20,12,20,24);
// $aligns = array('C','L','C','C','C','C','C');
// $border=1;
// $pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

//  $result2=mysqli_query($conn,"select id from invoice_items where id=(select max(id) from quotation_items)");
//    if($row2=mysqli_fetch_array($result2))
//    {
//      $id1=$row2['id']+1;
//    }
// $line_tot = $qtyvalue * $priceval ;
// $gstamt = number_format(($gstval / 2) * $total / 100, 2, '.', '');


//  // mysqli_query($conn,"INSERT INTO `invoice_items` (`id`, `itemno`, `invoice_id`, `product_id`, `product`, `prod_desc`, `qty`, `price`, `line_total`, `gst`, `gst_amt`, `total`, `created_by`) VALUES ('$id1', '$itemnum', '$id', '$productid', '$products','$proddesc','$qtyvalue','$priceval', '$line_tot', '$gstval', '$gstamt', '$tot_formatted', '$created_by')");

//   // mysqli_query($conn,"INSERT INTO `invoice_items` (`id`, `itemno`, `invoice_id`, `product_id`, `product`, `prod_desc`, `qty`, `price`, `line_total`, `gst`, `gst_amt`, `total`, `created_by`) VALUES ('$id1', '$itemnum', '$id', '$productid', '$products','$proddesc','$qtyvalue','$priceval', '$line_tot', '$gstval', '$gstamt', '$tot', '$created_by')");

  
//   }

//  // $nonTaxableAmount = $totalAmount / (1 + ($taxRate / 100));

// $tot_amt = $gsttot + $tot_total;
// $table = array(array("\n Nontaxable Amount : $nontax_tot_amt \n Taxable Amount  $tot_total"   ));

// $lineheight = 5;
// $fontsize = 10;
// $widths = array(189);
// $aligns = array('R');
// $border=1;
// $pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

// // $table = array(
// //     array(
// //         "\n Billing Address \n\n {$row1['b_address_line1']} \n {$row1['b_address_line2']} \n {$row1['b_city']} - {$row1['b_Pincode']} \n {$row1['b_state']} \n",
// //         "\n Shipping Address \n\n {$row1['s_address_line1']} \n {$row1['s_address_line2']} \n {$row1['s_city']} - {$row1['s_Pincode']} \n {$row1['s_state']} \n"
// //     )
// // );

// // $lineheight = 5;
// // $fontsize = 10;
// // $widths = array(94.5,94.5);
// // $aligns = array('L','L');
// // $border=1;
// // $pdf->plot_table($widths, $lineheight, $table,$border,$aligns);


// $pdf->Cell(189,6,"Nontaxable Amount : $nontax_tot_amt",0,1,'R');
// $pdf->Cell(189,6,"Taxable Amount : $tot_total",0,1,'R');
// $pdf->Cell(189,6,"GST Total : $gsttot",0,1,'R');
// $pdf->Cell(189,6,"Adjusment : 0",0,1,'R');
// $pdf->Cell(189,6,"Invoice Total : $tot_amt",0,1,'R');

// // $pdf->Cell(21,10,$tot_amt,1,1,'C');

// $pdf->SetFont("Arial","",8);
// $pdf->Cell(34,10,"Bank Name",1,0,'L');
// $pdf->Cell(77,10,"IDFC BANK LIMITED ",1,0,'L');
// $pdf->Cell(59,10,"Sub total",1,0,'C');
// $pdf->Cell(21,10,$pricevaltot,1,1,'C');

// $pdf->Cell(34,10,"Account Name",1,0,'L');
// $pdf->Cell(77,10,"KRIKA MKB CORPORATION PRIVATE LIMITED",1,0,'L');
// $pdf->Cell(59,10,"GST total",1,0,'C');
// $pdf->Cell(21,10,$gsttot,1,1,'C');

// $pdf->Cell(34,10,"Account No",1,0,'L');
// $pdf->Cell(77,10,"10069839667",1,0,'L');
// $pdf->Cell(59,10,"Grand total",1,0,'C');
// $pdf->Cell(21,10,$tot_amt,1,1,'C');

// // Cell for IFSC Code
// $pdf->Cell(34, 10, "IFSC Code", 1, 0, 'L');
// $pdf->Cell(77, 10, "IDFB0080177", 1, 0, 'L');
// $pdf->SetFont("Arial","B",);

// $pdf->MultiCell(80, 10, "For KRIKA MKB CORPORATION PRIVATE LIMITED \n\n Authorised Signatory", 1, 'L');
// // $pdf->SetY(160); // Adjust the Y-coordinate according to your layout
// $pdf->SetY($pdf->GetY() - 20); 

// if (empty($terms)) {
//     $terms = " "; // Set a space to ensure the cell has some content
// }
// $pdf->MultiCell(111, 10, "Terms and Condition:\n$terms", 1, 'L');


// // $pdf->Cell(34,10,"IFSC Code",1,0,'L');
// // $pdf->Cell(77,10,"IDFB0080177",1,0,'L');
// // $pdf->MultiCell(70,10,"For KRIKA MKB CORPORATION PRIVATE LIMITED",1,1,'L');
// // // $pdf->Cell(21,10,$total,1,1,'C');

// // $pdf->Cell(111,10,"Terms and Condition",1,1,'L');
//  // $pdf->Cell(77,10,"term",1,0,'L');
// // $pdf->Cell(50,10,"For",1,0,'C');
// // $pdf->Cell(0,10,$total,1,1,'C');


// $pdf->Cell(0,10,"Thank you for your Business!",0,1,'C');


// ob_end_clean();

// // a random hash will be necessary to send mixed content
// $separator = md5(time());

// // carriage return type (we use a PHP end of line constant)
// $eol = PHP_EOL;

// // attachment name
// $filename = "pdf/".$file_name;


// encode data (puts attachment in proper format)
// / $pdfdoc = $pdf->Output('S');
// $pf = $pdf->Output();

// file_put_contents($filename, $pdfdoc);

  // $sql="INSERT INTO invoice (`id`, `invoice_code`, `invoice_file`,`customer_id`, `email`, `invoice_date`, `due_date`, `total_amount`,`total_tax`, `grand_total`,  `terms_condition`, `note`,`status`, `created_by`) VALUES ('$id', '$invoice_code','$filename','$cst_mstr_id', '$customer_email', '$purchaseDate', '$dueDate', '$pricevaltot', '$gsttot','$tot_amt', '$terms', '$note','pending', '$created_by')";

  // if ($conn->query($sql) === TRUE) 
           // {
  ?>

    <script>
      // window.location="view-invoices.php";
        // alert("Successfully Created Quotation");
    </script> 
    <?php
 // }
 // else{
 ?>

    <script>
      // window.location="create-invoice.php";
        // alert("Unable to create Quotation try again");
      // <?php echo "Error: " . $sql . "<br>" . $conn->error;?>
    </script> 
    <?php

// }

// }
// }
?>