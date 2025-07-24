


<?php
 // include("config.php");
  include("fpdf/fpdf.php");
class PDF extends FPDF{
    function plot_table($widths, $lineheight, $table, $border, $aligns=array(), $fills=array(), $links=array()){
        $func = function($text, $c_width){
            $len=strlen($text);
            $twidth = $this->GetStringWidth($text);
            $split = floor($c_width * $len / $twidth);
            $w_text = explode( "\n", wordwrap( $text, $split, "\n", true));
            return $w_text;
        };
        foreach ($table as $line){
            $line = array_map($func, $line, $widths);
            $maxlines = max(array_map("count", $line));
            foreach ($line as $key => $cell){
                $x_axis = $this->getx();
                $height = $lineheight * $maxlines / count($cell);
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
$pdf->Cell(0,10,"Quotation",0,1,'C',true);


$pdf->Ln(5);
$pdf->SetFont("Arial","",12);

$pdf->Cell(0,10,"Mahaveer Distributors",0,1,'C');
$pdf->Cell(0,10,"No. 356/357,9th Cross Industrail Area Peenya 4th Phase",0,1,'C');
$pdf->Cell(0,10,"Bnagalore-560058 MO - 9999999999",0,1,'C');
$pdf->SetFont("Arial","",12);
$pdf->SetDrawColor(221,221,221,1);
$pdf->SetFillColor(113, 163, 244 );
$pdf->Cell(0,1,"",0,1,'C',true);
// $pdf->SetLineWidth(2);
$pdf->Ln(5);
// $pdf->Cell(15,10,"Buyer :",0,0);
//$w= $pdf->GetStringWidth($customer_name)+6;
// $pdf->SetX((210-$w)/2);
// $pdf->Cell(120,10,$customer_name,0,0);

$table = array(array("Buyer : Buyer Name "));
$lineheight = 6;
$fontsize = 10;
$widths = array(200);
$border=0;
$pdf->plot_table($widths, $lineheight, $table,$border);


// $pdf->Cell(18,10,"Date: ",0,0);
// $pdf->Cell(0,10,$date1,0,1);
$pdf->Ln(1);
$pdf->Cell(35,10,"Quotation No. : ",0,0);
$pdf->Cell(86,10,"Invoice Code",0,0);
$pdf->Cell(15,10,"Date: ",0,0);
$pdf->Cell(0,10,"date format",0,1);

$pdf->Ln(20);
$pdf->SetFont("Arial","",10);
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

// foreach ($_POST["products"] as $key => $val)
//  {

//   $product_price=mysqli_real_escape_string($conn,$_POST['price'][$key]);

//    $itemnum=mysqli_real_escape_string($conn,$_POST['itemnum'][$key]);
//    $products=mysqli_real_escape_string($conn,$_POST['products'][$key]);
//   $proddesc=mysqli_real_escape_string($conn,$_POST['proddesc'][$key]);
//    $qtyvalue=mysqli_real_escape_string($conn,$_POST['qtyvalue'][$key]); 
//    $priceval=mysqli_real_escape_string($conn,$_POST['priceval'][$key]); 
//    $total=mysqli_real_escape_string($conn,$_POST['total'][$key]);


$table = array(array("productN", "Product Description", "qty3", "900", "qty*Price"));
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

  
  // }

$pdf->Cell(178,10,"Sub Total",1,0,'C');
// $pdf->Cell(16,10,$sub_total,1,1,'C');
$pdf->Cell(16,10,"sub_total_val",1,1,'C');

$pdf->Cell(178,10,"Tax Rate",1,0,'C');
// $pdf->Cell(16,10,$tax_rate,1,1,'C');
$pdf->Cell(16,10,"tax_rate_val",1,1,'C');


$pdf->Cell(178,10,"Tax Amount",1,0,'C');
// $pdf->Cell(16,10,$tax_amount,1,1,'C');
$pdf->Cell(16,10,"tax_amount_val",1,1,'C');

$pdf->Cell(178,10,"Packing or extra Price",1,0,'C');
// $pdf->Cell(16,10,$pack_price,1,1,'C');
$pdf->Cell(16,10,"pack_price_val",1,1,'C');

$pdf->Cell(178,10,"Grand Total",1,0,'C');
// $pdf->Cell(16,10,$total_amount,1,1,'C');
$pdf->Cell(16,10,"total_amount_val",1,1,'C');

// $pdf->SetFont("Arial","",10);
 $pdf->Cell(178,10,"NOTE:",1,0);
// $table = array(array("Note:NOte",''));
// $pdf->Cell(16,10,"",1,1,'C');
// $lineheight = 8;
// $fontsize = 10;
// $widths = 178
// $border=1;
// $pdf->plot_table($widths, $lineheight, $table,$border);

$pdf->Cell(178,10,"BANK NAME:YES BANK,ACC No:",1,0);
$pdf->Cell(16,10,"",1,1);

$pdf->Cell(178,10,"BRANCH: BVK IYENGAR, IFSC Code :YESB0000538",1,0);
$pdf->Cell(16,10,"",1,1);

$pdf->Ln(20);

$pdf->Write(16,'Remark');
$pdf->Ln();
$table = array(array("ANY REMARKS"));
$lineheight = 8;
$fontsize = 10;
$widths = array(180);
$border=1;
$pdf->plot_table($widths, $lineheight, $table,$border);


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

?>