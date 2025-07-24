<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Check if the user is logged in
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

// Check if a business is selected
if (!isset($_SESSION['business_id'])) {
    header("Location: dashboard.php");
    exit();
} else {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    $branch_id = $_SESSION['branch_id'] ?? null;
}

include("config.php");

include("fpdf/fpdf.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $receipt_no = mysqli_real_escape_string($conn, $_POST['rec_no']);
    $receipt_date = mysqli_real_escape_string($conn, $_POST['receipt_date']);
    $payment_mode = mysqli_real_escape_string($conn, $_POST['paymentMode']);
    $collected_by = mysqli_real_escape_string($conn, $_POST['collected_by']);
    $bank_name = mysqli_real_escape_string($conn, $_POST['bank_name']);
    $transaction_no = mysqli_real_escape_string($conn, $_POST['transaction_no']);
    $cheque_no = mysqli_real_escape_string($conn, $_POST['cheque_no']);
    $dd_no = mysqli_real_escape_string($conn, $_POST['dd_no']);
    $card_last_no = mysqli_real_escape_string($conn, $_POST['card_last_no']);
    $transaction_date = mysqli_real_escape_string($conn, $_POST['transaction_date']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
    
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $created_by = $_SESSION['name'];

    $invoice_pid = mysqli_real_escape_string($conn, $_POST['invoice_pid']);
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
$invoice_code = mysqli_real_escape_string($conn,$_POST['invoice_code']);
    // Fetch the total amount from the invoice table
    // $invoice_query = "SELECT grand_total FROM invoice WHERE id = '$invoice_pid'";
    // $invoice_result = mysqli_query($conn, $invoice_query);
    // if ($invoice_row = mysqli_fetch_assoc($invoice_result)) {
    //     $total_amount = $invoice_row['grand_total'];
    // } else {
    //     $total_amount = 0; // Handle the case where the invoice is not found
    // }


    $total_amount = mysqli_real_escape_string($conn,$_POST['grand_total']);
    // Fetch previously paid amount from receipts table
    $res1 = mysqli_query($conn, "SELECT SUM(paid_amount) AS paid_amount FROM receipts WHERE invoice_id = '$invoice_pid' AND invoice_code = '$invoice_code'");
    if ($row2 = mysqli_fetch_assoc($res1)) {
        $paid_amount = $row2['paid_amount'];
    } else {
        $paid_amount = 0;
    }

    $p_a = $paid_amount + $amount;
    $due_amount = $total_amount - $p_a;

    // if ($due_amount == 0) {
    //     $status = "paid";
    // } else if ($due_amount < $total_amount) {
    //     $status = "partial";
    // } else {
    //     $status = "pending";
    // }

  // Determine payment status
    if ($due_amount == 0) {
        $status = "paid";
        $reconciliation_status = "Fully Reconciled";
    } elseif ($due_amount < $total_amount) {
        $status = "partial";
        $reconciliation_status = "Partially Reconciled";
    } else {
        $status = "pending";
        $reconciliation_status = "Not Reconciled";
    }

    class PDF extends FPDF {
        function plot_table($widths, $lineheight, $table, $border, $aligns = array(), $fills = array(), $backgroundColors = array(), $links = array()) {
            $func = function($text, $c_width) {
                $len = strlen($text);
                $twidth = $this->GetStringWidth($text);
                $split = floor($c_width * $len / $twidth);
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

                    if (!empty($backgroundColor)) {
                        $this->SetFillColor($backgroundColor[0], $backgroundColor[1], $backgroundColor[2]);
                        $this->Rect($this->GetX(), $this->GetY(), array_sum($widths), $height, 'F');
                    }

                    foreach ($cell as $textline) {
                        if (is_string($textline) && file_exists($textline)) {
                            $imageWidth = 30;
                            $imageHeight = 15;
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

        function numberToWords($number) {
            $words = [
                'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten',
                'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
            ];

            $tens = [
                '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'
            ];

            if ($number < 20) {
                return $words[$number];
            } elseif ($number < 100) {
                return $tens[(int)($number / 10)] . (($number % 10 != 0) ? ' ' . $words[$number % 10] : '');
            } elseif ($number < 1000) {
                return $words[(int)($number / 100)] . ' hundred' . (($number % 100 != 0) ? ' and ' . $this->numberToWords($number % 100) : '');
            } elseif ($number < 1000000) {
                return $this->numberToWords((int)($number / 1000)) . ' thousand' . (($number % 1000 != 0) ? ' and ' . $this->numberToWords($number % 1000) : '');
            } elseif ($number < 1000000000) {
                return $this->numberToWords((int)($number / 1000000)) . ' million' . (($number % 1000000 != 0) ? ' and ' . $this->numberToWords($number % 1000000) : '');
            } elseif ($number < 1000000000000) {
                return $this->numberToWords((int)($number / 1000000000)) . ' billion' . (($number % 1000000000 != 0) ? ' and ' . $this->numberToWords($number % 1000000000) : '');
            } elseif ($number < 1000000000000000) {
                return $this->numberToWords((int)($number / 1000000000000)) . ' trillion' . (($number % 1000000000000 != 0) ? ' and ' . $this->numberToWords($number % 1000000000000) : '');
            } elseif ($number < 1000000000000000000) {
                return $this->numberToWords((int)($number / 1000000000000000)) . ' quadrillion' . (($number % 1000000000000 != 0) ? ' and ' . $this->numberToWords($number % 1000000000000000) : '');
            } else {
                return 'Number is out of range for this example.';
            }
        }
    }

    $pdf = new PDF('P', 'mm', 'A4');
    // $file_name = md5(rand()) . '.pdf';
    $file_name = 'RECEIPT-'. $receipt_no .'.pdf';

    $pdf->AddPage();
    $pdf->SetFont("Arial", "", 10);

    $pdf->SetFont('Arial', '', 9);
    $pdf->SetDrawColor(169, 169, 169);   
      $result1 = mysqli_query($conn, "SELECT *  FROM add_branch where branch_id='$branch_id'");

if ($row1 = mysqli_fetch_array($result1)) {
                                           
     $pdf->Cell(140, 6, "{$row1['branch_name']}", 'TL', 0, 'L');
    $pdf->Cell(0, 6, "RECEIPT", 'TR', 1, 'L');
     $pdf->Cell(140, 6, "{$row1['address']}", 'L', 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(0, 6, "Receipt No.#: $receipt_no", 'R', 1, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(140, 6, "Phone: 91 7550705070", 'L', 0, 'L');
    $pdf->Cell(0, 6, "Receipt Date : $receipt_date", 'R', 1, 'L');
    $pdf->Cell(140, 6, "Email: sales.usa@iiiqbets.com", 'L', 0, 'L');
    $pdf->Cell(0, 6, "Created By : $created_by", 'R', 1, 'L');
    $pdf->Cell(140, 6, "GST : 29AAICK7493G1ZX", 'LB', 0, 'L');
    $pdf->Cell(0, 6, "", 'RB', 1, 'L');
}
    $pdf->SetFont('Arial', '', 9);
    $table = array(
        array("\nPayor", $customer_name)
    );
    $lineheight = 6;
    $fontsize = 10;
    $aligns = array('L');
    $widths = array(190, 190);

    foreach ($table as $row) {
        for ($i = 0; $i < count($row); $i++) {
            if ($i == 0) {
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell($widths[$i], $lineheight, $row[$i], 'LR', 1, $aligns[$i]);
                $pdf->SetFont('Arial', '', 9);
            } else {
                $pdf->MultiCell($widths[$i], $lineheight, $row[$i], 'LR', $aligns[$i]);
            }
        }
    }

    $pdf->SetFont("Arial", "B", 8);
    $pdf->SetTextColor(0, 0, 0, 0);
    $pdf->SetDrawColor(169, 169, 169);
    $pdf->SetLineWidth(0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Cell(160, 10, "Product Description", 1, 0, 'C', 1);
    $pdf->Cell(30, 10, "TOTAL", 1, 1, 'C', 1); 

    $amnt_words = $pdf->numberToWords($amount);
    $pdf->SetFont("Arial", "", 8);
    $table = array(array("Received from $customer_name amount of Rs.$amount.00 ( $amnt_words ) through $payment_mode", $amount));
    $lineheight = 7;
    $fontsize = 10;
    $widths = array(160, 30);
    $aligns = array('L', 'L');
    $border = 1;
    $pdf->plot_table($widths, $lineheight, $table, $border, $aligns);

    $startY = $pdf->GetY();
    $currentX = $pdf->GetX();
    $pdf->MultiCell(100, 6, "$notes", 0, 'L');
    $endYFirst = $pdf->GetY();
    $pdf->SetXY($currentX + 100, $startY);
    $pdf->SetFont("Arial", "B", 8);
    $pdf->MultiCell(90, 6, "\nFor  \n\n\n Authorised Signatory\n", 0, 'R');
    $endYSecond = $pdf->GetY();
    $maxY = max($endYFirst, $endYSecond);
    $pdf->Rect($currentX, $startY, 100, $maxY - $startY, 'L');
    $pdf->Rect($currentX + 100, $startY, 90, $maxY - $startY, 'R');
    $pdf->SetY($maxY);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(0, 10, "This is a computer generated receipt. Thank you!", 1, 1, 'C');

    ob_end_clean();
    $separator = md5(time());
    $eol = PHP_EOL;
    $filename = "receipts/".$file_name;
    $pdfdoc = $pdf->Output('S');


file_put_contents($filename, $pdfdoc);

    $sql = "INSERT INTO receipts ( `recpt_id`, `receipt_date`, `customer_id`, `invoice_id`, `invoice_code`,
        `total_amount`, `paid_amount`, `pdf_file_path`,`payment_mode`, `collected_by`, `bank_name`, `transactionid`, 
        `cheque_no`, `ddno`, `card_last_no`, `transaction_date`, `reconciliation_status`, `notes`, `created_at`,`branch_id`
    ) VALUES ('$receipt_no', '$receipt_date', '$customer_id', '$invoice_pid','$invoice_code','$total_amount', '$amount', '$filename','$payment_mode', '$collected_by', '$bank_name', '$transaction_no', '$cheque_no', '$dd_no', '$card_last_no', '$transaction_date','$reconciliation_status','$notes', NOW(),'$branch_id')";

if (mysqli_query($conn, $sql)) {

      $receipt_id = mysqli_insert_id($conn);

        $sql_ledger = "INSERT INTO `ledger` (`voucher_id`, `transaction_date`, `transaction_type`, `account_id`, `account_name`, `amount`, `debit_credit`, `receipt_or_voucher_no`,`branch_id`)value('$receipt_id','$receipt_date','Receipt','$customer_id', '$customer_name','$amount','C','$receipt_no','$branch_id')";

        if (!$conn->query($sql_ledger)) {
            throw new Exception("Failed to save other details: " . $conn->error);
        }

      
        $sql_reconcile = "INSERT INTO reconciliation ( `receipt_id`, `invoice_id`, `reconciled_amount`, `reconciliation_date`
        ) VALUES ( '$receipt_id', '$invoice_pid', '$amount', NOW())";

        if (mysqli_query($conn, $sql_reconcile)) {

$sql2 = "UPDATE invoice SET  due_amount='$due_amount',status = '$status' WHERE id = '$invoice_pid'";
  if (mysqli_query($conn, $sql2)) {
                echo "<script type='text/javascript'>
                        alert('Receipt Created Successfully');
                        window.location.href = 'view-receipt-action.php?receiptId={$receipt_id}';
                      </script>";
            } else {
                echo "<script type='text/javascript'>
                        alert('Failed to update invoice status');
                        window.location.href = 'view-invoice-action.php?inv_id={$invoice_pid}';
                      </script>";
            }
        } else {
            echo "<script type='text/javascript'>
                    alert('Failed to add reconciliation record');
                    window.location.href = 'create-receipt.php';
                  </script>";
        }
    } else {
        echo "<script type='text/javascript'>
                alert('Failed to add receipt record');
                window.location.href = 'create-receipt.php';
              </script>";
    }
}
?>
