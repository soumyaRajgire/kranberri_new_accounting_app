
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

session_start(); 
if(!isset($_SESSION['email']) && $_SESSION['ROLE']!='1')
{
   header("Location:login.php");
}
else
{
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
}
include("config.php");
 // require 'PHPMailer/PHPMailerAutoload.php';

   // require 'PHPMailer-master/src/PHPMailer.php';
  // require 'PHPMailer-master/src/SMTP.php';
  // require 'PHPMailer-master/src/Exception.php';
if(isset($_POST['submit']))
{
 

  include("config.php");
  include("fpdf/fpdf.php");
  // include("clientfpdf.php");
  //  $invoice_code =mysqli_real_escape_string($conn,$_POST['invoice_code']);
  // $customer_name=mysqli_real_escape_string($conn,$_POST['customer_name']);


    // if($_POST['customer_name_choice'] === "Others")
    // {
       // $customer_name = mysqli_real_escape_string($conn,$_POST['othercustomername']);
    // }
    // else
    // {
       // $customer_name = mysqli_real_escape_string($conn,$_POST['customer_name_choice']);
    // }
  // $note = $sub_total ="";

  $patient_name = mysqli_real_escape_string($conn,$_POST['patient_name']);
  $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
  $consultant = mysqli_real_escape_string($conn, $_POST['consultant']);
  $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name']);

  $centre_type = mysqli_real_escape_string($conn, $_POST['center_type']);


   $sql = "SELECT * FROM drspine_appointment WHERE patient_id = '$patient_id'"; 
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
        $p_ph_no = $patient['contact_no'];
        $c_email = $patient['email_address'];

    } 
  // $product_choice = mysqli_real_escape_string($conn, $_POST['product_choice']);
  // $qty = mysqli_real_escape_string($conn,$_POST['qty']);
  // $price = mysqli_real_escape_string($conn,$_POST['price']);
  // $discount = mysqli_real_escape_string($conn, $_POST['discount']);
  // $gst = mysqli_real_escape_string($conn, $_POST['gst']);

  // echo $_POST['sub_total'];
  // $sub_total=mysqli_real_escape_string($conn,$_POST['sub_total']);
// $sub_total = mysqli_real_escape_string($conn,$_POST['sub_total']);

 // $customer_email=mysqli_real_escape_string($conn,$_POST['customer_email']);
   
   // $tax_rate=mysqli_real_escape_string($conn,$_POST['tax_rate']);
    // $tax_amount=mysqli_real_escape_string($conn,$_POST['tax_amount']);

   // $sub_total=mysqli_real_escape_string($conn,$_POST['sub_total']);
   // $pack_price = mysqli_real_escape_string($conn,$_POST['pack_price']);
    $total_amount = mysqli_real_escape_string($conn,$_POST['total_amount']);
    // $remarks = mysqli_real_escape_string($conn,$_POST['remarks']);
$note = mysqli_real_escape_string($conn,$_POST['note']);

    $branch_admin_name = $_SESSION['mname'];
     // $branch_name =$_SESSION['branch_name'];

// $role= 2;

 date_default_timezone_set('Asia/Kolkata');
$date1 =  date("d-m-Y");
// $time1 = date("h:i:sa");


$id="";
$id1="";
$id3="";


// if(($customer_name != "") )
// {

  $result1=mysqli_query($conn,"select id from invoices where id=(select max(id) from invoices)");
  if($row1=mysqli_fetch_array($result1))
  {
    $id=$row1['id']+1;
    $i=$row1['id'];
    $s=preg_replace("/[^0-9]/", '', $i);
    $invoice_code="INV0".($s+1);
  }
  else
  {
    $invoice_code="INV0".(1); 
  }

  // mysqli_query($conn, "INSERT INTO  quotation_list(id,invoice_code,customer_name,email,sub_total,tax_rate,ccbg,tax_amount,pack_price,total_amount,remark,note,date1,time1,sale_person_name) VALUES ('$id','$invoice_code','$customer_name','$customer_email','$sub_total','$tax_rate','$tax_amount','$pack_price','$total_amount','$remarks','$note','$date1','$time1','$sale_person_name')");

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
$pdf->SetFont("Arial","",16);

$pdf->SetDrawColor(221,221,221,1);
// $pdf->SetFillColor(51, 184, 255);
$pdf->SetFillColor(113, 163, 244 );
$pdf->Cell(0,10,"Invoice",0,1,'C',true);


$pdf->Ln(6);
$pdf->Image('dist/img/drspine_logo.png', 80, 20, 50, 22); // Adjust x, y, width, and height as needed
$pdf->Ln(20);
$pdf->SetFont("Arial","",11);

$pdf->Cell(140,6,"Dr Spine Chiropractic Clinic's",0,0,'L');
$pdf->Cell(0,6,"contact us:  + 91 75 5070 5070",0,1,'L');
$pdf->Cell(140,6,"No. 302, 54, The Planet Whitefield Main Road",0,0,'L');
$pdf->Cell(0,6,"Email: Jay@drspine.in",0,1,'L');
$pdf->Cell(140,6,"Brooke Bond 1 Cross, Varthur,Narayanappa Garden,",0,0,'L');
$pdf->Cell(0,6,"Website - drspine.in",0,1,'L');
$pdf->Cell(0,6,"Whitefield, Bengaluru, Karnataka - 560066",0,1,'L');
$pdf->Cell(0,6,"GST- 29AAPCM1431P1ZV",0,1,'L');
$pdf->Ln(4);
$pdf->SetFont("Arial","",12);
// $pdf->SetDrawColor(221,221,221,1);
$pdf->SetDrawColor(0,0,0,1);
$pdf->SetFillColor(0, 0, 0);

// $pdf->SetFillColor(113, 163, 244 );
$pdf->Cell(0,0,"",0,1,'C',true);
// $pdf->SetLineWidth(2);
$pdf->Ln(3);
// $pdf->Cell(15,10,"Buyer :",0,0);
//$w= $pdf->GetStringWidth($customer_name)+6;
// $pdf->SetX((210-$w)/2);
// $pdf->Cell(120,10,$customer_name,0,0);


$table = array(array($patient_name, $p_ph_no,$date1));
$lineheight = 6;
$fontsize = 10;
$widths = array(90,50,35);
$border=0;
$pdf->plot_table($widths, $lineheight, $table,$border);


// $pdf->Cell(18,10,"Date: ",0,0);
// $pdf->Cell(0,10,$date1,0,1);
$pdf->Ln(3);

$pdf->SetDrawColor(0,0,0,1);
$pdf->SetFillColor(0, 0, 0);
$pdf->Cell(0,0,"",0,0,'C',true);
$pdf->Ln(2);
$pdf->Cell(140,10,"Invoice By : $consultant",0,0);
// $pdf->Cell(86,10,$invoice_code,0,0);
$pdf->Cell(0,10,"Invoice No : $invoice_code ",0,0);
// $pdf->Cell(86,10,$invoice_code,0,0);
// $pdf->Cell(15,10,"Date: ",0,0);
// $pdf->Cell(0,10,$date1,0,1);

$pdf->Ln(20);
$pdf->SetFont("Arial","",10);
$pdf->SetTextColor(0,0,0,1);
$pdf->SetDrawColor(221,221,221,1);
$pdf->SetLineWidth(0);

// $pdf->Cell(10,10,"Sl.No.",1,0);
// $pdf->CellFitScale(70,10,"products",1,0,'',1);

// $pdf->CellFitScale(70,10,"proddesc",1,0,'',1);
$pdf->Cell(78,10,"Items",1,0,'L');
 $pdf->Cell(19,10,"Quantity",1,0,'L');
$pdf->Cell(19,10,"Price",1,0,'C');
$pdf->Cell(15,10,"GST",1,0,'C');
$pdf->Cell(20,10,"Discount",1,0,'C');
$pdf->Cell(30,10,"Total",1,1,'C');

foreach ($_POST["packages"] as $key => $val)
 {

  // $product_price=mysqli_real_escape_string($conn,$_POST['price'][$key]);

   $itemnum=mysqli_real_escape_string($conn,$_POST['itemnum'][$key]);
   $products=mysqli_real_escape_string($conn,$_POST['packages'][$key]);
  $discount=mysqli_real_escape_string($conn,$_POST['discountval'][$key]);
   $qtyvalue=mysqli_real_escape_string($conn,$_POST['qtyvalue'][$key]); 
   $gstval = mysqli_real_escape_string($conn, $_POST['gstval'][$key]);
   $priceval=mysqli_real_escape_string($conn,$_POST['priceval'][$key]); 
   $total=mysqli_real_escape_string($conn,$_POST['total'][$key]);
   // $sub_total = mysqli_real_escape_string($conn,$_POST['sub_total'][$key]);


$table = array(array($products, $qtyvalue, $priceval, $gstval, $discount, $total));
//$pdf->Cell(10,10,$itemnum,1,0);
// $pdf->MultiCell(70,10,$products - $proddesc,1,0);
// $pdf->Cell(70,10,$products,1,0,'L');
// $pdf->SetFont('');
// $pdf->CellFitScaleForce(70,10,$products,1,0,'',1);

// $pdf->CellFitScaleForce(70,10,$proddesc,1,0,'',1);
// $pdf->Cell(70,10,$proddesc,1,0,'L');
// $pdf->Cell(10,10,$qtyvalue,1,0,'C');
// $pdf->Cell(10,10,$priceval,1,0,'C');
// $pdf->Cell(0,10,$total,1,1,'C');
$lineheight = 8;
$fontsize = 10;
$widths = array(78,19,19,15,20,30);
$aligns = array('L','L','C','C','C','C');
$border=1;
$pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

$result2=mysqli_query($conn,"select id from invoice_items where id=(select max(id) from invoice_items)");
  if($row2=mysqli_fetch_array($result2))
  {
    $id1=$row2['id']+1;
  }


  mysqli_query($conn,"INSERT INTO invoice_items(id,invoice_id,package,qty,price,gst,discount,total) VALUES ('$id1','$id','$products','$qtyvalue','$priceval','$gstval','$discount','$total')");

  
  }

// $pdf->Cell(178,10,"Sub Total",1,0,'C');
// $pdf->Cell(16,10,$sub_total,1,1,'C');

// $pdf->Cell(178,10,"GST",1,0,'C');
// $pdf->Cell(16,10,$gst,1,1,'C');


// $pdf->Cell(178,10,"Discount",1,0,'C');
// $pdf->Cell(16,10,$discount,1,1,'C');

// $pdf->Cell(178,10,"Packing or extra Price",1,0,'C');
// $pdf->Cell(16,10,$pack_price,1,1,'C');

$pdf->Cell(151,10,"Grand Total",1,0,'C');
$pdf->Cell(30,10,$total_amount,1,1,'C');

// $pdf->SetFont("Arial","",10);
// $pdf->Cell(178,10,"NOTE:",1,0);
// $pdf->Cell(25,10,"",1,1,'C');

//note code start 
// $table = array(array("Note:$note",''));
// $lineheight = 8;
// $fontsize = 10;
// $widths = array(151,30);
// $border=1;
// $pdf->plot_table($widths, $lineheight, $table,$border);

//note code end

// $pdf->Cell(151,10,"BANK NAME:YES BANK,ACC No:053887300000101",1,0);
// $pdf->Cell(30,10,"",1,1);

// $pdf->Cell(151,10,"BRANCH: BVK IYENGAR, IFSC Code :YESB0000538",1,0);
// $pdf->Cell(30,10,"",1,1);

$pdf->Ln(20);
$pdf->Cell(140,6,"",0,0,'L');
$pdf->Cell(0,6,"Dr Spine",0,1,'L');
$pdf->Cell(140,6,"",0,0,'L');
$pdf->Cell(0,6,"Electronically Signed by:",0,1,'L');
$pdf->Cell(140,6,"",0,0,'L');
$pdf->Cell(0,6,"Dr. Dr Jay",0,1,'L');
$pdf->Cell(140,6,"",0,0,'L');
$pdf->Cell(0,6,"(Reg No.: 36675)",0,1,'L');
$pdf->Cell(140,6,"",0,0,'L');
$pdf->Cell(0,6,"Chiropractor",0,1,'L');



// $pdf->Write(16,'Remark');
// $pdf->Ln();
// $table = array(array("$remarks"));
// $lineheight = 8;
// $fontsize = 10;
// $widths = array(180);
// $border=1;
// $pdf->plot_table($widths, $lineheight, $table,$border);


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
$attachment = chunk_split(base64_encode($pdfdoc));






// use PHPMailer\PHPMailer\PHPMailer;
    // include('smtp/PHPMailerAutoload.php');
// require 'PHPMailer-master/src/PHPMailerAutoload.php';
require 'PHPMailer/src/phpmailer.php';
require 'PHPMailer/src/smtp.php';


// Create a new PHPMailer instance
$mail = new PHPMailer;

// SMTP Configuration
$mail->isSMTP();
$mail->Host = 'smtp.titan.email';
$mail->Port = 465;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl';
$mail->Username = 'bhagath.koduri@iiiqbets.com';
$mail->Password = 'Bhagath@123$';

// Email content
$mail->setFrom('bhagath.koduri@iiiqbets.com', 'Dr Spine Admin');
// $mail->addAddress('soumyacn16@gmail.com', 'Dr Spine Admin');
$mail->addAddress($c_email, 'Dr Spine Admin');

$mail->Subject = 'Invoice From Dr Spine';
$mail->isHTML(true); // Set email format to HTML

$mail->Body = '<table width="100%" style="background-color:#dadada;border-collapse:collapse;border-spacing:0;border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td align="center">
<table width="682" style="border-collapse:collapse;border-spacing:0">

<tbody><tr class="m_-1958935385513098443header">
<td bgcolor="#eeeeee"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="12">&nbsp;</td>
</tr>
<tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left;border-bottom:3px solid #2f94d7" height="18">&nbsp;</td>
</tr>
</tbody></table></td>
</tr>


<tr><td bgcolor="#ffffff"> 

<table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td width="20" style="font-size:0;line-height:0">&nbsp;</td>
<td width="640" style="font-size:0;line-height:0">

<table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="15">&nbsp;</td>
</tr>
<tr>
<td style="background-color:#f8f8f8;border:1px solid #ebebeb"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="15">&nbsp;</td>
</tr>
<tr>
<td style="margin:0;color:#1e4a7b;font-size:20px;line-height:24px;font-family:Arial,Helvetica,sans-serif;font-style:normal;font-weight:normal;text-align:center">
Greetings from Dr spine!!!!</td>
</tr><tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="5">&nbsp;</td>
</tr></tbody></table></td></tr></tbody></table>

<table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="20">&nbsp;</td>
</tr>
<tr>
<td style="vertical-align:top;margin:0;padding:0;font-size:16px;color:#231f20;line-height:24px;font-family:Arial,Helvetica,sans-serif;font-weight:normal;text-align:left">Dear '. $patient_name .' ,
</td></tr>
<tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="20">&nbsp;</td>
</tr>
<tr>
<td style="margin:0;padding:0;font-size:16px;color:#231f20;line-height:24px;text-align:center;font-family:Arial,Helvetica,sans-serif;font-weight:normal">

<div style="text-align:left"></div><div style="text-align:left"><span style="background-color:transparent">
Please find the attached Invoice. If you have any queries please feel free to contact.</span>

</div>
</td>
</tr>
<tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="20">&nbsp;</td>
</tr>
<tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="20">&nbsp;</td>
</tr>
<tr>
<td style="margin:0;padding:0;font-size:16px;color:#231f20;line-height:21px;font-family:Arial,Helvetica,sans-serif;font-weight:normal">Regards,<br><span class="il">Dr spine</span> Team</td>
</tr>
<tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="10">&nbsp;</td>
</tr>
<tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="10">&nbsp;</td>
</tr>
</tbody></table>

</td>
<td width="20" style="font-size:0;line-height:0">&nbsp;</td>
</tr>
</tbody></table></td></tr>


<tr>
<td bgcolor="#eeeeee"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td width="35">&nbsp;</td>
<td width="557"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:center" height="25">&nbsp;</td>
</tr>
</tbody></table></td>
</tr>


</tbody></table></td>
<td width="35">&nbsp;</td>
</tr>
</tbody></table></td>
</tr>

</tbody></table></td>
</tr>
</tbody></table>';

// Attachment
// $filename = "pdf/" . $file_name;
$mail->addAttachment($filename, $file_name); // Add attachment


?>
<?php
// $mail = new PHPMailer();

// // Set up SMTP settings
// $mail->isSMTP();
// $mail->Host = 'smtp.titan.email';
// $mail->Port = 465;
// $mail->SMTPAuth = true;
// $mail->SMTPSecure = 'ssl';
// $mail->Username = 'bhagath.koduri@iiiqbets.com';
// $mail->Password = 'Bhagath@123$';

// // Set up email content
// $mail->setFrom('bhagath.koduri@iiiqbets.com', 'Dr Spine Admin');
// $mail->addAddress('irctcssy@gmail.com', 'Dr Spine Admin'); 
// $mail->addAddress($c_email); // Use the submitted email address as the recipient
// $mail->Subject = 'Invoice From Dr Spine';


    
// $mail->IsHTML(true); // Set email format to HTML
// $mail->CharSet = "UTF-8"; // Set the character set


//     $message='<!-- <table width="100%" style="background-color:#dadada;border-collapse:collapse;border-spacing:0;border-collapse:collapse;border-spacing:0">
// <tbody><tr>
// <td align="center">
// <table width="682" style="border-collapse:collapse;border-spacing:0">

// <tbody><tr class="m_-1958935385513098443header">
// <td bgcolor="#eeeeee"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
// <tbody><tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="12">&nbsp;</td>
// </tr>
// <tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left;border-bottom:3px solid #2f94d7" height="18">&nbsp;</td>
// </tr>
// </tbody></table></td>
// </tr>


// <tr><td bgcolor="#ffffff"> 

// <table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
// <tbody><tr>
// <td width="20" style="font-size:0;line-height:0">&nbsp;</td>
// <td width="640" style="font-size:0;line-height:0">

// <table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
// <tbody><tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="15">&nbsp;</td>
// </tr>
// <tr>
// <td style="background-color:#f8f8f8;border:1px solid #ebebeb"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
// <tbody><tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="15">&nbsp;</td>
// </tr>
// <tr>
// <td style="margin:0;color:#1e4a7b;font-size:20px;line-height:24px;font-family:Arial,Helvetica,sans-serif;font-style:normal;font-weight:normal;text-align:center">
// Greetings from Dr spine!!!!</td>
// </tr><tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="5">&nbsp;</td>
// </tr></tbody></table></td></tr></tbody></table>

// <table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
// <tbody><tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="20">&nbsp;</td>
// </tr>
// <tr>
// <td style="vertical-align:top;margin:0;padding:0;font-size:16px;color:#231f20;line-height:24px;font-family:Arial,Helvetica,sans-serif;font-weight:normal;text-align:left">Dear '. $patient_name .' ,
// </td></tr>
// <tr>
// <td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="20">&nbsp;</td>
// </tr>
// <tr>
// <td style="margin:0;padding:0;font-size:16px;color:#231f20;line-height:24px;text-align:center;font-family:Arial,Helvetica,sans-serif;font-weight:normal">

// <div style="text-align:left"></div><div style="text-align:left"><span style="background-color:transparent">
// Please find the attached Invoice. If you have any queries please feel free to contact.</span>

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
// <td style="margin:0;padding:0;font-size:16px;color:#231f20;line-height:21px;font-family:Arial,Helvetica,sans-serif;font-weight:normal">Regards,<br><span class="il">Dr spine</span> Team</td>
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
// </tbody></table> -->';
// // main header
// $headers  = "From: ".$from.$eol;
// // $headers .= "MIME-Version: 1.0".$eol; 
// // $headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";

// $headers = "MIME-Version: 1.0" . "\r\n";
// $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// // no more headers after this, we start the body! //

// $mail->Body  = "--".$separator.$eol;
// $mail->Body .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
// $mail->Body .= "This is a MIME encoded message.".$eol;

// // message
// $mail->Body .= "--".$separator.$eol;
// $mail->Body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
// $mail->Body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
// $mail->Body .= $message.$eol;

// // attachment
// $mail->Body .= "--".$separator.$eol;
// $mail->Body .= "Content-Type: application/octet-stream; name=\"".$file_name."\"".$eol; 
// $mail->Body .= "Content-Transfer-Encoding: base64".$eol;
// $mail->Body .= "Content-Disposition: attachment".$eol.$eol;
// $mail->Body .= $attachment.$eol;
// $mail->Body .= "--".$separator."--";
// // send message



// $mail->addAttachment($attachment);

$result3=mysqli_query($conn,"select id from invoices where id=(select max(id) from invoices)");
  if($row3=mysqli_fetch_array($result3))
  {
    $id3=$row3['id']+1;
  }


  $sql="INSERT INTO invoices(id,invoice_code,patients_id,consultant_name,centre_type,branch_name,sub_total,total,pending_amt,note,invoice,payment_status,created_by) VALUES ('$id3','$invoice_code','$patient_id','$consultant','$centre_type','$branch_name','$sub_total','$total_amount','$total_amount','$note','$filename','Due','$branch_admin_name')";

  if ($conn->query($sql) === TRUE) 
          {
            if ($mail->send())
            {
                 ?>

    <script>
      window.location="manage_invoice.php?patient_id=<?php echo $patient_id?>&branch_name=<?php echo $branch_name;?>";
        alert("Successfully Created Invoice");
    </script> 
    <?php
            }
            else
            {
              echo '<script>alert("Email sending failed. Error: '.$mail->ErrorInfo.'")</script>';
  echo "<script type='text/javascript'> document.location ='invoice.php?patient_id=$patient_id'; </script>";
   
            }
 
}
else{
 ?>

    <script>
      window.location="invoice.php?patient_id=<?php echo $patient_id?>";
        alert("Unable to create  try again");
       echo "Error: " . $sql . "<br>" . $conn->error;
    </script> 
    <?php

}

}
// else
// {
    ?> <!-- <script>
      //window.location="view-products.php";
        alert("please enter all the data");
    </script> -->
    <?php
// }
// }
?>