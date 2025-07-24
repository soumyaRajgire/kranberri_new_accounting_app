

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

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
  // include("clientfpdf.php");
  //  $invoice_code =mysqli_real_escape_string($conn,$_POST['invoice_code']);
  // $customer_name=mysqli_real_escape_string($conn,$_POST['customer_name']);


    // if($_POST['customer_name_choice'] === "Others")
    // {
    //    $customer_name = mysqli_real_escape_string($conn,$_POST['othercustomername']);
    // }
    // else
    // {
       $customer_name = mysqli_real_escape_string($conn,$_POST['customer_name_choice']);
    // }
   
 $customer_email=mysqli_real_escape_string($conn,$_POST['customer_email']);
   
   $tax_rate=mysqli_real_escape_string($conn,$_POST['tax_rate']);
    $tax_amount=mysqli_real_escape_string($conn,$_POST['tax_amount']);

   $sub_total=mysqli_real_escape_string($conn,$_POST['sub_total']);
   $pack_price = mysqli_real_escape_string($conn,$_POST['pack_price']);
    $total_amount = mysqli_real_escape_string($conn,$_POST['total_amount']);
    $remarks = mysqli_real_escape_string($conn,$_POST['remarks']);
$note = mysqli_real_escape_string($conn,$_POST['note']);

    // $sale_person_name = $_SESSION['name'];
    //  $sales_person_phone =$_SESSION['phone'];

$role= 2;

 date_default_timezone_set('Asia/Kolkata');
$date1 =  date("d-m-Y");
$time1 = date("h:i:sa");


$id="";
$id1="";
$id3="";


if(($customer_name != "") )
{

  $result1=mysqli_query($conn,"select id from quotation_list where id=(select max(id) from quotation_list)");
  if($row1=mysqli_fetch_array($result1))
  {
    $id=$row1['id']+1;
    $i=$row1['id'];
    $s=preg_replace("/[^0-9]/", '', $i);
    $invoice_code="Quotation0".($s+1);
  }

  mysqli_query($conn, "INSERT INTO  quotation_list(id,invoice_code,customer_name,email,sub_total,tax_rate,tax_amount,pack_price,total_amount,remark,note,date1,time1,sale_person_name) VALUES ('$id','$invoice_code','$customer_name','$customer_email','$sub_total','$tax_rate','$tax_amount','$pack_price','$total_amount','$remarks','$note','$date1','$time1','$sale_person_name')");

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

$pdf->Ln(6);
$pdf->Image('img/logo.png', 160, 20, 25, 18); // Adjust x, y, width, and height as needed
$pdf->Ln(20);
$pdf->SetFont("Arial","",11);
$pdf->SetDrawColor(0,0,0,1);
$pdf->SetFillColor(0, 0, 0);
$pdf->Cell(0,0,"",0,1,'C',true);
// $pdf->SetLineWidth(2);
$pdf->Ln(3);
$pdf->Cell(140,6,"KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)",0,0,'L');
$pdf->Cell(0,6,"contact us:  + 91 75 5070 5070",0,1,'L');
$pdf->Cell(140,6,"120 Newport Center Dr, Newport Beach, CA 92660",0,0,'L');
$pdf->Cell(0,6,"Email: sales.usa@iiiqbets.com",0,1,'L');
// $pdf->Cell(140,6,"Brooke Bond 1 Cross, Varthur,Narayanappa Garden,",0,0,'L');
$pdf->Cell(0,6,"Website - iiiqbets.com",0,1,'L');
// $pdf->Cell(0,6,"Whitefield, Bengaluru, Karnataka - 560066",0,1,'L');
$pdf->Cell(0,6,"GST- 29AAICK7493G1ZX",0,1,'L');
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

$table = array(array("Buyer : $customer_name "));
$lineheight = 6;
$fontsize = 10;
$widths = array(200);
$border=0;
$pdf->plot_table($widths, $lineheight, $table,$border);


// $pdf->Cell(18,10,"Date: ",0,0);
// $pdf->Cell(0,10,$date1,0,1);
$pdf->Ln(1);
$pdf->Cell(35,10,"Quotation No. : ",0,0);
$pdf->Cell(98,10,$invoice_code,0,0);
$pdf->Cell(15,10,"Date: ",0,0);
$pdf->Cell(0,10,$date1,0,1);

$pdf->Ln(20);
$pdf->SetFont("Arial","",11);
$pdf->SetTextColor(0,0,0,1);
$pdf->SetDrawColor(221,221,221,1);
$pdf->SetLineWidth(0);

// $pdf->Cell(10,10,"Sl.No.",1,0);
// $pdf->CellFitScale(70,10,"products",1,0,'',1);

// $pdf->CellFitScale(70,10,"proddesc",1,0,'',1);
$pdf->Cell(72,10,"Products",1,0,'L');
 $pdf->Cell(72,10,"Product Desc",1,0,'L');
$pdf->Cell(18,10,"Quantity",1,0,'C');
$pdf->Cell(14,10,"Price",1,0,'C');
$pdf->Cell(18,10,"Total",1,1,'C');

foreach ($_POST["products"] as $key => $val)
 {

  // $product_price=mysqli_real_escape_string($conn,$_POST['price'][$key]);

   $itemnum=mysqli_real_escape_string($conn,$_POST['itemnum'][$key]);
   $products=mysqli_real_escape_string($conn,$_POST['products'][$key]);
  $proddesc=mysqli_real_escape_string($conn,$_POST['proddesc'][$key]);
   $qtyvalue=mysqli_real_escape_string($conn,$_POST['qtyvalue'][$key]); 
   $priceval=mysqli_real_escape_string($conn,$_POST['priceval'][$key]); 
   $total=mysqli_real_escape_string($conn,$_POST['total'][$key]);


$table = array(array($products, $proddesc, $qtyvalue, $priceval, $total));
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
$lineheight = 7;
$fontsize = 10;
$widths = array(72,72,18,14,18);
$aligns = array('L','L','C','C','C');
$border=1;
$pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

// $result2=mysqli_query($conn,"select id from quotation_items where id=(select max(id) from quotation_items)");
//   if($row2=mysqli_fetch_array($result2))
//   {
//     $id1=$row2['id']+1;
//   }


  // mysqli_query($conn,"INSERT INTO quotation_items(id,itemno,quotation_list_id,product,prod_desc,qty,price,total) VALUES ('$id1','$itemnum','$id','$products','$proddesc','$qtyvalue','$priceval','$total')");

  
  }

$pdf->Cell(176,10,"Sub Total",1,0,'C');
$pdf->Cell(18,10,$sub_total,1,1,'C');

// $pdf->Cell(178,10,"Tax Rate",1,0,'C');
// $pdf->Cell(16,10,$tax_rate,1,1,'C');


$pdf->Cell(176,10,"Tax Amount",1,0,'C');
$pdf->Cell(18,10,"$tax_amount($tax_rate %)",1,1,'C');

$pdf->Cell(176,10,"Packing or extra Price",1,0,'C');
$pdf->Cell(18,10,$pack_price,1,1,'C');

$pdf->Cell(176,10,"Grand Total",1,0,'C');
$pdf->Cell(18,10,$total_amount,1,1,'C');

// $pdf->SetFont("Arial","",10);
// $pdf->Cell(178,10,"NOTE:",1,0);
// $table = array(array("Note:$note",''));
// // $pdf->Cell(16,10,"",1,1,'C');
// $lineheight = 8;
// $fontsize = 10;
// $widths = array(178,16);
// $border=1;
// $pdf->plot_table($widths, $lineheight, $table,$border);

$pdf->Ln(20);
$pdf->Cell(135,6,"",0,0,'L');
$pdf->Cell(0,6,"iiiQbets",0,1,'C');
$pdf->Cell(135,6,"",0,0,'L');
$pdf->Cell(0,6,"Electronically Signed by:",0,1,'C');
$pdf->Cell(135,6,"",0,0,'C');
$pdf->Cell(0,6,"Abhijith Mavatoor",0,1,'C');
// $pdf->Cell(140,6,"",0,0,'L');
// $pdf->Cell(0,6,"(Reg No.: 36675)",0,1,'L');
$pdf->Cell(110,6,"",0,0,'L');
$pdf->Cell(0,6,"KRIKA MKB CORPORATION PRIVATE LIMITED",0,1,'c');

// $pdf->Ln(20);

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
$pf = $pdf->Output();

file_put_contents($filename, $pdfdoc);
require 'PHPMailer/src/phpmailer.php';
require 'PHPMailer/src/smtp.php';


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

$mail->Body = '<table width="100%" style="background-color:#dadada;border-collapse:collapse;border-spacing:0;border-collapse:collapse;border-spacing:0"><tbody><tr>
<td align="center"><table width="682" style="border-collapse:collapse;border-spacing:0"><tbody><tr class="m_-1958935385513098443header"><td bgcolor="#eeeeee"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0"><tbody><tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="12">&nbsp;</td></tr><tr><td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left;border-bottom:3px solid #2f94d7" height="18">&nbsp;</td>
</tr></tbody></table></td></tr><tr><td bgcolor="#ffffff"> <table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr><td width="20" style="font-size:0;line-height:0">&nbsp;</td>
<td width="640" style="font-size:0;line-height:0"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0"><tbody><tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="15">&nbsp;</td></tr>
<tr>
<td style="background-color:#f8f8f8;border:1px solid #ebebeb"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="15">&nbsp;</td>
</tr>
<tr>
<td style="margin:0;color:#1e4a7b;font-size:20px;line-height:24px;font-family:Arial,Helvetica,sans-serif;font-style:normal;font-weight:normal;text-align:center">
Greetings from iiiQbets!!!!</td>
</tr><tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="5">&nbsp;</td>
</tr></tbody></table></td></tr></tbody></table>

<table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="20">&nbsp;</td>
</tr>
<tr>
<td style="vertical-align:top;margin:0;padding:0;font-size:16px;color:#231f20;line-height:24px;font-family:Arial,Helvetica,sans-serif;font-weight:normal;text-align:left">Dear '. $customer_name .' ,
</td></tr>
<tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="20">&nbsp;</td>
</tr>
<tr>
<td style="margin:0;padding:0;font-size:16px;color:#231f20;line-height:24px;text-align:center;font-family:Arial,Helvetica,sans-serif;font-weight:normal">

<div style="text-align:left"></div><div style="text-align:left"><span style="background-color:transparent">
Please find the attached Quotation. If you have any queries please feel free to contact.</span>

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
<td style="margin:0;padding:0;font-size:16px;color:#231f20;line-height:21px;font-family:Arial,Helvetica,sans-serif;font-weight:normal">Regards,<br><span class="il">iiiQbets<br/><a href=""></a></span> Team</td>
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


$result3=mysqli_query($conn,"select id from quotations where id=(select max(id) from quotations)");
  if($row3=mysqli_fetch_array($result3))
  {
    $id3=$row3['id']+1;
  }


  $sql="INSERT INTO quotations(id,invoice_code,customer_name,invoice,created_by,date1,time1,review) VALUES ('$id3','$invoice_code','$customer_name','$filename','$sale_person_name','$date1','$time1','Under Negotiation')";

  if ($conn->query($sql) === TRUE) 
          {
if ($mail->send())
            {
  ?>

    <script>
      window.location="view-quotation.php";
        alert("Successfully Created Quotation");
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
      window.location="quotation.php";
        alert("Unable to create Quotation try again");
       echo "Error: " . $sql . "<br>" . $conn->error;
    </script> 
    <?php

}

}
else
{
    ?> <!-- <script>
      //window.location="view-products.php";
        alert("please enter all the data");
    </script> -->
    <?php
}
}
?>