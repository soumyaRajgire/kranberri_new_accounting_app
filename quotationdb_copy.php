
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

if(isset($_POST['submit']))
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
$invoice_code = mysqli_real_escape_string($conn, $_POST['purchaseNo']);
$dueDate = mysqli_real_escape_string($conn, $_POST['dueDate']);
$purchaseDate = mysqli_real_escape_string($conn,$_POST['purchaseDate']);
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

  // $result1=mysqli_query($conn,"select id from quotation_list where id=(select max(id) from quotation_list)");
  // if($row1=mysqli_fetch_array($result1))
  // {
  //   $id=$row1['id']+1;
  //   $i=$row1['id'];
  //   $s=preg_replace("/[^0-9]/", '', $i);
  //   $invoice_code="Quotation0".($s+1);
  // }

  // mysqli_query($conn, "INSERT INTO  quotation_list(id,invoice_code,customer_name,email,sub_total,tax_rate,tax_amount,pack_price,total_amount,remark,note,date1,time1,sale_person_name) VALUES ('$id','$invoice_code','$customer_name','$customer_email','$sub_total','$tax_rate','$tax_amount','$pack_price','$total_amount','$remarks','$note','$date1','$time1','$sale_person_name')");

 $result1=mysqli_query($conn,"select id from quotation where id=(select max(id) from quotation)");
  if($row1=mysqli_fetch_array($result1))
  {
    $id=$row1['id']+1;
  }else
  {
    $id=1;
  }

class PDF extends FPDF{
    function plot_table($widths, $lineheight, $table, $border, $aligns=array(), $fills=array(), $links=array()){
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
        foreach ($table as $line){
            $line = array_map($func, $line, $widths);
            $maxlines = max(array_map("count", $line));
            foreach ($line as $key => $cell){
                $x_axis = $this->getx();
                $height = 0;
if (count($cell) != 0) {
    $height = $lineheight * $maxlines / count($cell);
}
                // $height = $lineheight * $maxlines / count($cell);
                $len = count($line);
                $width = (isset($widths[$key]) === TRUE ? $widths[$key] : $widths / count($line));
                $align = (isset($aligns[$key]) === TRUE ? $aligns[$key] : '');
                $fill = (isset($fills[$key]) === TRUE ? $fills[$key] : false);
                $link = (isset($links[$key]) === TRUE ? $links[$key] : '');
                foreach ($cell as $textline){
                    $this->cell($widths[$key],$height,$textline,0,0,$align,$fill,$link);
                    $height += 2 * $lineheight * $maxlines / count($cell);
                    $this->SetX($x_axis);
                }
                if($key == $len - 1){
                    $lbreak=1;
                }
                else{
                    $lbreak = 0;
                }
                $this->cell($widths[$key],$lineheight * $maxlines, '',$border,$lbreak);
            }
        }
    }
}

$pdf=new PDF('P','mm','A4');
$file_name = md5(rand()) . '.pdf';

$pdf->AddPage();
$pdf->SetFont("Arial","",14);

// $pdf->SetDrawColor(221,221,221,1);
// // $pdf->SetFillColor(51, 184, 255);
// $pdf->SetFillColor(113, 163, 244 );
// $pdf->Cell(0,10,"Quotation",0,1,'C',true);

$pdf->Image('img/logo.png', 160, 20, 25, 15); // Adjust x, y, width, and height as needed
$pdf->Ln(28);
$pdf->SetFont("Arial","",10);
$pdf->SetDrawColor(0,0,0,1);
$pdf->SetFillColor(232,232,232);
$pdf->Cell(0,1,"",0,1,'C',true);
// $pdf->SetLineWidth(2);
$pdf->Ln(3);
$pdf->Cell(140,6,"KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)",0,0,'L');
$pdf->Cell(0,6,"ESTIMATE",0,1,'L');
$pdf->Cell(140,6,"120 Newport Center Dr, Newport Beach, CA 92660",0,0,'L');
$pdf->Cell(0,6,"ESTIMATE #: $invoice_code",0,1,'L');
$pdf->Cell(140,6,"Phone: 91 7550705070",0,0,'L');
$pdf->Cell(0,6,"Date : $purchaseDate",0,1,'L');
$pdf->Cell(140,6,"Email: sales.usa@iiiqbets.com",0,0,'L');
$pdf->Cell(0,6,"Validity Date: $dueDate",0,1,'L');
$pdf->Cell(140,6,"GST : 29AAICK7493G1ZX",0,0,'L');
$pdf->Cell(0,6,"Created By : $created_by",0,1,'L');


$pdf->Ln(3);
$pdf->SetFont("Arial","",10);
// $pdf->SetDrawColor(221,221,221,1);
$pdf->SetDrawColor(0,0,0,1);
$pdf->SetFillColor(232,232,232);
$pdf->Cell(0,1,"",0,1,'C',true);
// $pdf->SetLineWidth(2);
$pdf->Ln(3);
// $pdf->Cell(15,10,"Buyer :",0,0);
//$w= $pdf->GetStringWidth($customer_name)+6;
// $pdf->SetX((210-$w)/2);
// $pdf->Cell(120,10,$customer_name,0,0);

// $pdf->SetFont('Arial', 'B', 10);
// $table = array(array("Customer","Billing Address", "Shipping Address"));
// $lineheight = 6;
// $fontsize = 10;
// $widths = array(68,68,68);
// $border=0;
// $pdf->plot_table($widths, $lineheight, $table,$border);

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
$result1 = mysqli_query($conn, "SELECT *  FROM customer_master  JOIN address_master ON customer_master.id = address_master.customer_master_id");

if ($row1 = mysqli_fetch_array($result1)) {
    // Prepare the table data
    // $customerName = $row1['business_name'] ."\n". $row1['mobile'] ."\n". $row1['email'] ."\n". "Place of Supply: ".$row1['b_state'];
    // $billingAddress = $row1['b_address_line1'] . "\n" . $row1['b_address_line2'] . "\n" .$row1['b_city'] ."-".$row1['b_Pincode']."\n" .$row1['b_state'];
    // $shippingAddress = $row1['s_address_line1'] . "\n" . $row1['s_address_line2'] . "\n" .$row1['s_city'] ."-".$row1['s_Pincode']."\n" .$row1['s_state'];;
// $pdf->SetFont('Arial', 'B', 9);
//     // Output the table headers
//     $pdf->Cell(55, 6, "Customer", 1);
//     $pdf->Cell(70, 6, "Billing Address", 1);
//     $pdf->Cell(70, 6, "Shipping Address", 1);
//     $pdf->Ln(); // Move to the next line

// $pdf->SetFont('Arial', 'B', 8);

//     // Output the customer name
//     $pdf->MultiCell(55, 4, $customerName, 1);

//     // Output billing address
//     $pdf->SetY($pdf->GetY() - 16); // Move up to align with the customer name
//     $pdf->SetX(65); // Move to the billing address column
//     $pdf->MultiCell(70, 4, $billingAddress, 1);

//     // Output shipping address
//     $pdf->SetY($pdf->GetY() - 20); // Move up to align with the customer name
//     $pdf->SetX(135); // Move to the shipping address column
//     $pdf->MultiCell(70, 4, $shippingAddress, 1);
// }

$pdf->SetFont("Arial","B",8);
$pdf->SetTextColor(0,0,0,1);
$pdf->SetDrawColor(221,221,221,1);
$pdf->SetLineWidth(0);
$pdf->Cell(65,10,"Customer",0,0,'L');
 $pdf->Cell(65,10,"Billing Address",0,0,'L');
$pdf->Cell(65,10,"Shipping Address",0,1,'L');

$table = array(array($row1['business_name'], $row1['b_address_line1'], $row1['s_address_line1']),
array($row1['mobile'], $row1['b_address_line2'], $row1['s_address_line2']),array($row1['email'], $row1['b_city']."-".$row1['b_Pincode'], $row1['s_city']."-".$row1['s_Pincode']),array("Place of Supply: ".$row1['b_state'], $row1['b_state'], $row1['s_state']));
$lineheight = 5;
$fontsize = 10;
$widths = array(65,65,65);
$aligns = array('L','L','L');
$border=0;
$pdf->plot_table($widths, $lineheight, $table,$border,$aligns);
// $pdf->Cell(18,10,"Date: ",0,0);
// $pdf->Cell(0,10,$date1,0,1);
// $pdf->Ln(1);
// $pdf->Cell(35,10,"Quotation No. : ",0,0);
// $pdf->Cell(98,10,$invoice_code,0,0);
// $pdf->Cell(15,10,"Date: ",0,0);
// $pdf->Cell(0,10,$date1,0,1);
}
$pdf->Ln(20);
$pdf->SetFont("Arial","B",8);
$pdf->SetTextColor(0,0,0,0);
$pdf->SetDrawColor(0,0,0,0);
$pdf->SetLineWidth(0);

// $pdf->Cell(10,10,"Sl.No.",1,0);
// $pdf->CellFitScale(70,10,"products",1,0,'',1);

// $pdf->CellFitScale(70,10,"proddesc",1,0,'',1);
$pdf->Cell(6,10,"#",1,0,'C');
 $pdf->Cell(78,10,"Product Description",1,0,'C');
$pdf->Cell(12,10,"GST",1,0,'C');
$pdf->Cell(15,10,"RATE",1,0,'C');
$pdf->Cell(10, 10, "QTY", 1, 0, 'C');
$pdf->Cell(19, 10, "Taxable Amt", 1, 0, 'C');

$pdf->Cell(15, 10, "CGST", 1, 0, 'C');
$pdf->Cell(15, 10, "SGST", 1, 0, 'C');
$pdf->Cell(21, 10, "TOTAL", 1, 1, 'C'); 

$cgsttotal =0;
$sgsttotal = 0;
$pricevaltot =0;
$tot_total =0;
$tot_qty=0;

foreach ($_POST["products"] as $key => $val)
 {
$tot =0;
  // $product_price=mysqli_real_escape_string($conn,$_POST['price'][$key]);

   $itemnum=mysqli_real_escape_string($conn,$_POST['itemnum'][$key]);
   $products=mysqli_real_escape_string($conn,$_POST['products'][$key]);
  $proddesc=mysqli_real_escape_string($conn,$_POST['proddesc'][$key]);
     $qtyvalue = floatval(mysqli_real_escape_string($conn, $_POST['qtyvalue'][$key])); 
    $priceval = floatval(mysqli_real_escape_string($conn, $_POST['priceval'][$key])); 
    $gstval = floatval(mysqli_real_escape_string($conn, $_POST['gstval'][$key])); 
    $cgstval = floatval(mysqli_real_escape_string($conn, $_POST['cgstval'][$key])); 
    $sgstval = floatval(mysqli_real_escape_string($conn, $_POST['sgstval'][$key])); 
    $netprice = floatval(mysqli_real_escape_string($conn, $_POST['netpriceval'][$key]));
    $total = floatval(mysqli_real_escape_string($conn, $_POST['total'][$key]));
   
$productid = mysqli_real_escape_string($conn,$_POST['productids'][$key]);
$in_ex_gst = mysqli_real_escape_string($conn,$_POST['in_ex_gst_val'][$key]);
$cgsttotal += $cgstval; // Convert $cgstval to integer before adding
$sgsttotal += $sgstval; // Convert $sgstval to integer before adding
$pricevaltot += ($priceval); 
$tot_total += ($total);
$tot = ($total) + ($cgstval) + ($sgstval);
 $tot_formatted = number_format($tot, 0, '.', ''); 


$tot_qty += $qtyvalue;

// $table = array(array($itemnum,$products."\n". $proddesc,$gstval,$priceval, $qtyvalue, $priceval,$cgstval,$sgstval,$tot));
$table = array(array($itemnum,$products."\n". $proddesc,$gstval,$priceval, $qtyvalue, $priceval,$cgstval,$sgstval,$tot));


$lineheight = 7;
$fontsize = 10;
$widths = array(6,78,12,15,10,19,15,15,21);
$aligns = array('C','L','C','C','C','C','C','C','C');
$border=1;
$pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

 $result2=mysqli_query($conn,"select id from quotation_items where id=(select max(id) from quotation_items)");
   if($row2=mysqli_fetch_array($result2))
   {
     $id1=$row2['id']+1;
   }
$line_tot = $qtyvalue * $priceval ;
$gstamt = number_format(($gstval / 2) * ($total) / 100, 2, '.', '');




 mysqli_query($conn,"INSERT INTO `quotation_items` (`id`, `itemno`, `quotation_id`, `product_id`, `product`, `prod_desc`, `qty`, `price`, `line_total`, `gst`, `gst_amt`, `total`, `created_by`) VALUES ('$id1', '$itemnum', '$id', '$productid', '$products','$proddesc','$qtyvalue','$priceval', '$line_tot', '$gstval', '$gstamt', '$tot_formatted', '$created_by')");

  
  }

$gsttot=($cgsttotal + $sgsttotal);
$tot_amt = $gsttot + $tot_total;
// $tot_amt_formatted = number_format($tot_amt, 0, '.', ''); 
$pdf->Cell(111,10,"Grand Total",1,0,'C');
$pdf->Cell(10,10,"$qtyvalue",1,0,'C');
$pdf->Cell(19,10,"$pricevaltot",1,0,'C');
$pdf->Cell(15,10,"$cgsttotal",1,0,'C');
$pdf->Cell(15,10,"$sgsttotal",1,0,'C');

$pdf->Cell(21,10,$tot_amt,1,1,'C');

$pdf->SetFont("Arial","",8);
$pdf->Cell(34,10,"Bank Name",1,0,'L');
$pdf->Cell(77,10,"IDFC BANK LIMITED ",1,0,'L');
$pdf->Cell(59,10,"Sub total",1,0,'C');
$pdf->Cell(21,10,$pricevaltot,1,1,'C');

$pdf->Cell(34,10,"Account Name",1,0,'L');
$pdf->Cell(77,10,"KRIKA MKB CORPORATION PRIVATE LIMITED",1,0,'L');
$pdf->Cell(59,10,"GST total",1,0,'C');
$pdf->Cell(21,10,$gsttot,1,1,'C');

$pdf->Cell(34,10,"Account No",1,0,'L');
$pdf->Cell(77,10,"10069839667",1,0,'L');
$pdf->Cell(59,10,"Grand total",1,0,'C');
$pdf->Cell(21,10,$tot_amt,1,1,'C');

// Cell for IFSC Code
$pdf->Cell(34, 10, "IFSC Code", 1, 0, 'L');
$pdf->Cell(77, 10, "IDFB0080177", 1, 0, 'L');
$pdf->SetFont("Arial","B",);

$pdf->MultiCell(80, 10, "For KRIKA MKB CORPORATION PRIVATE LIMITED \n\n Authorised Signatory", 1, 'L');
// $pdf->SetY(160); // Adjust the Y-coordinate according to your layout
$pdf->SetY($pdf->GetY() - 20); 

if (empty($terms)) {
    $terms = " "; // Set a space to ensure the cell has some content
}
$pdf->MultiCell(111, 10, "Terms and Condition:\n$terms", 1, 'L');


// $pdf->Cell(34,10,"IFSC Code",1,0,'L');
// $pdf->Cell(77,10,"IDFB0080177",1,0,'L');
// $pdf->MultiCell(70,10,"For KRIKA MKB CORPORATION PRIVATE LIMITED",1,1,'L');
// // $pdf->Cell(21,10,$total,1,1,'C');

// $pdf->Cell(111,10,"Terms and Condition",1,1,'L');
 // $pdf->Cell(77,10,"term",1,0,'L');
// $pdf->Cell(50,10,"For",1,0,'C');
// $pdf->Cell(0,10,$total,1,1,'C');


$pdf->Cell(0,10,"Thank you for your Business!",0,1,'C');


ob_end_clean();

//$file = $pdf->Output("","S");
//file_put_contents($file_name, $file);

 // header('Content-type: "application/pdf"; charset="iso-8859-1"');
 // header('Content-disposition: attachment; filename="mypdf.mypdf"');
 // readfile('path_to_pdfs' . $pdf->Output());
 
 
// Attachment file 
//$file1 = $file/$filename; 

// Boundary  
// $semi_rand = md5(time());  


// a random hash will be necessary to send mixed content
$separator = md5(time());

// carriage return type (we use a PHP end of line constant)
$eol = PHP_EOL;

// attachment name
$filename = "pdf/".$file_name;


// encode data (puts attachment in proper format)
 $pdfdoc = $pdf->Output('S');
// $pf = $pdf->Output();

file_put_contents($filename, $pdfdoc);
// require 'PHPMailer/src/phpmailer.php';
// require 'PHPMailer/src/smtp.php';


// Create a new PHPMailer instance
// $mail = new PHPMailer;

// SMTP Configuration
// $mail->isSMTP();
// $mail->Host = 'smtp.titan.email';
// $mail->Port = 465;
// $mail->SMTPAuth = true;
// $mail->SMTPSecure = 'ssl';
// $mail->Username = 'bhagath.koduri@iiiqbets.com';
// $mail->Password = 'Bhagath@123$';

// Email content
// $mail->setFrom('bhagath.koduri@iiiqbets.com', 'iiiQbets Admin');
// $mail->addAddress('soumyacn16@gmail.com', 'Dr Spine Admin');
// $mail->addAddress($customer_email, 'iiiQbets Admin');

// $mail->Subject = 'Quotation from iiiQbets';
// $mail->isHTML(true); // Set email format to HTML

// $mail->Body = '<table width="100%" style="background-color:#dadada;border-collapse:collapse;border-spacing:0;border-collapse:collapse;border-spacing:0"><tbody><tr>
// <td align="center"><table width="682" style="border-collapse:collapse;border-spacing:0"><tbody><tr class="m_-1958935385513098443header"><td bgcolor="#eeeeee"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0"><tbody><tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="12">&nbsp;</td></tr><tr><td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left;border-bottom:3px solid #2f94d7" height="18">&nbsp;</td>
// </tr></tbody></table></td></tr><tr><td bgcolor="#ffffff"> <table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
// <tbody><tr><td width="20" style="font-size:0;line-height:0">&nbsp;</td>
// <td width="640" style="font-size:0;line-height:0"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0"><tbody><tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="15">&nbsp;</td></tr>
// <tr>
// <td style="background-color:#f8f8f8;border:1px solid #ebebeb"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
// <tbody><tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="15">&nbsp;</td>
// </tr>
// <tr>
// <td style="margin:0;color:#1e4a7b;font-size:20px;line-height:24px;font-family:Arial,Helvetica,sans-serif;font-style:normal;font-weight:normal;text-align:center">
// Greetings from iiiQbets!!!!</td>
// </tr><tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="5">&nbsp;</td>
// </tr></tbody></table></td></tr></tbody></table>

// <table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
// <tbody><tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="20">&nbsp;</td>
// </tr>
// <tr>
// <td style="vertical-align:top;margin:0;padding:0;font-size:16px;color:#231f20;line-height:24px;font-family:Arial,Helvetica,sans-serif;font-weight:normal;text-align:left">Dear '. $customer_name .' ,
// </td></tr>
// <tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="20">&nbsp;</td>
// </tr>
// <tr>
// <td style="margin:0;padding:0;font-size:16px;color:#231f20;line-height:24px;text-align:center;font-family:Arial,Helvetica,sans-serif;font-weight:normal">

// <div style="text-align:left"></div><div style="text-align:left"><span style="background-color:transparent">
// Please find the attached Quotation. If you have any queries please feel free to contact.</span>

// </div>
// </td>
// </tr>
// <tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="20">&nbsp;</td>
// </tr>
// <tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="20">&nbsp;</td>
// </tr>
// <tr>
// <td style="margin:0;padding:0;font-size:16px;color:#231f20;line-height:21px;font-family:Arial,Helvetica,sans-serif;font-weight:normal">Regards,<br><span class="il">iiiQbets<br/><a href=""></a></span> Team</td>
// </tr>
// <tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="10">&nbsp;</td>
// </tr>
// <tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="10">&nbsp;</td>
// </tr>
// </tbody></table>

// </td>
// <td width="20" style="font-size:0;line-height:0">&nbsp;</td>
// </tr>
// </tbody></table></td></tr>


// <tr>
// <td bgcolor="#eeeeee"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
// <tbody><tr>
// <td width="35">&nbsp;</td>
// <td width="557"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
// <tbody><tr>
// <td><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
// <tbody><tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:center" height="25">&nbsp;</td>
// </tr>
// </tbody></table></td>
// </tr>


// </tbody></table></td>
// <td width="35">&nbsp;</td>
// </tr>
// </tbody></table></td>
// </tr>

// </tbody></table></td>
// </tr>
// </tbody></table>';

// Attachment
// $filename = "pdf/" . $file_name;
// $mail->addAttachment($filename, $file_name); // Add attachment




  $sql="INSERT INTO `quotation` (`id`, `invoice_code`, `quotation_file`,`customer_id`, `email`, `quotation_date`, `due_date`, `total_amount`,`total_tax`, `grand_total`,  `terms_condition`, `note`,`status`, `created_by`) VALUES ('$id', '$invoice_code','$filename','$cst_mstr_id', '$customer_email', '$purchaseDate', '$dueDate', '$pricevaltot', '$gsttot','$tot_amt', '$terms', '$note','Not Converted', '$created_by')";

  if ($conn->query($sql) === TRUE) 
           {
// if ($mail->send())
//             {
  ?>

    <script>
      window.location="view-quotation.php";
        alert("Successfully Created Quotation");
    </script> 
    <?php
 // }
//  else
//             {
//               echo '<script>alert("Email sending failed. Error: '.$mail->ErrorInfo.'")</script>';
//   echo "<script type='text/javascript'> document.location ='invoice.php?patient_id=$patient_id'; </script>";
   
//             }
 }
 else{
 ?>

    <script>
      window.location="quotation.php";
        alert("Unable to create Quotation try again");
      <?php echo "Error: " . $sql . "<br>" . $conn->error;?>
    </script> 
    <?php

}

// }
// else
// {
   
// }
}
}
?>