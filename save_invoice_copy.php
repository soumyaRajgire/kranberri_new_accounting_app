<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

session_start(); 
if(!isset($_SESSION['name']) && $_SESSION['ROLE'] != '1') {
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

if(isset($_POST['submit'])) {
    include("fpdf/fpdf.php");

    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name_choice']);
    $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
    $cst_mstr_id = mysqli_real_escape_string($conn, $_POST['cst_mstr_id']);
    // $sub_total = floatval(mysqli_real_escape_string($conn, $_POST['sub_total']));
    // $pack_price = floatval(mysqli_real_escape_string($conn, $_POST['pack_price']));
    $total_amount = floatval(mysqli_real_escape_string($conn, $_POST['total_amount']));
    // $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);
    $note = mysqli_real_escape_string($conn, $_POST['note']);
    $invoice_code = mysqli_real_escape_string($conn, $_POST['invoice_code']);
    $dueDate = mysqli_real_escape_string($conn, $_POST['dueDate']);
    $purchaseDate = mysqli_real_escape_string($conn, $_POST['invoice_date']);
    $terms = mysqli_real_escape_string($conn, $_POST['terms_condition']);
    $created_by = $_SESSION['name'];
$final_cess_amount = $_POST['final_cess_amount'];
$final_gst_amount = $_POST['final_gst_amount'];
$final_taxable_amt = $_POST['final_taxable_amt'];

    date_default_timezone_set('Asia/Kolkata');
    $date1 = date("d-m-Y");
    $time1 = date("h:i:sa");


//     $transportMode = $_POST['transportMode'] ?? 'None';

// switch ($transportMode) {
//     case 'Road':
//         $vehicleNumber = $_POST['roadVehicleNumber'] ?? '';
//         $driverName = $_POST['driverName'] ?? '';
//         $licenseNumber = $_POST['licenseNumber'] ?? '';
//         $roadFreightCharges = $_POST['roadFreightCharges'] ?? '';
//         $roadInsurance = $_POST['roadInsurance'] ?? '';
//         $roadPermit = $_POST['roadPermit'] ?? '';
//          $driver_contact = $_POST['driver_contact'] ?? '';
//             $roadDistance = $_POST['roadDistance'] ?? '';
//            //   $optionalField1 = $_POST['optionalField1'] ?? '';
//            // $optionalValue1 = $_POST['optionalValue1'] ?? '';

//         // Process other road-specific fields
//         break;
//     case 'Rail':
//         $trainNumber = $_POST['trainNumber'] ?? '';
//         $railwayStation = $_POST['railwayStation'] ?? '';
//         $arrivalStation = $_POST['arrivalStation'] ?? '';
//         $railwayBooking = $_POST['railwayBooking'] ?? '';
//         $railFreightCharges = $_POST['railFreightCharges'] ?? '';
//         $railwayCoach = $_POST['railwayCoach'] ?? '';
//         $railwaySeat = $_POST['railwaySeat'] ?? '';
//         $railDepartureTime = $_POST['railDepartureTime'] ?? '';
        
//         // Process other rail-specific fields
//         break;
//     case 'Air':
//         $flightNumber = $_POST['flightNumber'] ?? '';
//          $departureAirport = $_POST['departureAirport'] ?? '';
//           $arrivalAirport = $_POST['arrivalAirport'] ?? '';
//            $airwayBill = $_POST['airwayBill'] ?? '';
//             $airFreightCharges = $_POST['airFreightCharges'] ?? '';
//              $airCargoType = $_POST['airCargoType'] ?? '';
//               $airETA = $_POST['airETA'] ?? '';

//         // Process other air-specific fields
//         break;
//     case 'Ship':
      
//         break;
//     default:
//         // No transportation details
//         break;
// }

$other_poNumber = $_POST['other_poNumber'] ?? '';
$other_poDate = $_POST['other_poDate'] ?? '';
$challanNumber = $_POST['challanNumber'] ?? '';
$other_dueDate = $_POST['other_dueDate'] ?? '';
$ewayBill = $_POST['ewayBill'] ?? '';
$salesPerson = $_POST['salesPerson'] ?? '';
$tcsTax=$_POST['tcsTax'] ?? '';
$tcsValue =$_POST['tcsValue'] ?? '';
$reverseCharge =$_POST['reverseCharge'] ?? 0;

 ;   // Generate ID for the new quotation
    $result1 = mysqli_query($conn, "SELECT id FROM invoice ORDER BY id DESC LIMIT 1");
    $id = ($row1 = mysqli_fetch_array($result1)) ? $row1['id'] + 1 : 1;

    // Generate PDF using FPDF
    // class PDF extends FPDF {
    //     function plot_table($widths, $lineheight, $table, $border, $aligns = array(), $fills = array(), $links = array()) {
    //         foreach ($table as $line) {
    //             foreach ($line as $key => $cell) {
    //                 $this->Cell($widths[$key], $lineheight, $cell, $border, 0, $aligns[$key] ?? '', $fills[$key] ?? false, $links[$key] ?? '');
    //             }
    //             $this->Ln();
    //         }
    //     }
    // }

    // $pdf = new PDF('P', 'mm', 'A4');
    // $file_name = md5(rand()) . '.pdf';
    // $pdf->AddPage();
    // $pdf->SetFont("Arial", "", 14);
    // $pdf->Image('img/logo.png', 160, 20, 25, 15);
    // $pdf->Ln(28);
    // $pdf->SetFont("Arial", "", 10);
    // $pdf->Cell(0, 6, "KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)", 0, 1, 'L');
    // $pdf->Cell(0, 6, "ESTIMATE #: $invoice_code", 0, 1, 'L');
    // $pdf->Cell(0, 6, "Date : $purchaseDate", 0, 1, 'L');
    // $pdf->Cell(0, 6, "Validity Date: $dueDate", 0, 1, 'L');
    // $pdf->Cell(0, 6, "Created By : $created_by", 0, 1, 'L');
    // $pdf->Ln(3);

    // Customer, Billing, and Shipping Information
    // $pdf->SetFont("Arial", "B", 8);
    // $pdf->Cell(65, 10, "Customer", 0, 0, 'L');
    // $pdf->Cell(65, 10, "Billing Address", 0, 0, 'L');
    // $pdf->Cell(65, 10, "Shipping Address", 0, 1, 'L');

    // $result1 = mysqli_query($conn, "SELECT * FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id WHERE customer_master.id = '$cst_mstr_id'");
    // if($row1 = mysqli_fetch_array($result1)) {
    //     $table = array(
    //         array($row1['business_name'], $row1['b_address_line1'], $row1['s_address_line1']),
    //         array($row1['mobile'], $row1['b_address_line2'], $row1['s_address_line2']),
    //         array($row1['email'], $row1['b_city']."-".$row1['b_Pincode'], $row1['s_city']."-".$row1['s_Pincode']),
    //         array("Place of Supply: ".$row1['b_state'], $row1['b_state'], $row1['s_state'])
    //     );
    //     $widths = array(65, 65, 65);
    //     $pdf->plot_table($widths, 5, $table, 0, array('L','L','L'));
    // }

    // $pdf->Ln(20);
    // $pdf->Cell(6, 10, "#", 1, 0, 'C');
    // $pdf->Cell(78, 10, "Product Description", 1, 0, 'C');
    // $pdf->Cell(12, 10, "GST", 1, 0, 'C');
    // $pdf->Cell(15, 10, "RATE", 1, 0, 'C');
    // $pdf->Cell(10, 10, "QTY", 1, 0, 'C');
    // $pdf->Cell(19, 10, "Taxable Amt", 1, 0, 'C');
    // $pdf->Cell(15, 10, "CGST", 1, 0, 'C');
    // $pdf->Cell(15, 10, "SGST", 1, 0, 'C');
    // $pdf->Cell(21, 10, "TOTAL", 1, 1, 'C'); 

    // $cgsttotal = 0;
    // $sgsttotal = 0;
    // $pricevaltot = 0;
    // $tot_total = 0;
    // $tot_qty = 0;

    foreach ($_POST["products"] as $key => $val) {
        $qtyvalue = floatval(mysqli_real_escape_string($conn, $_POST['qtyvalue'][$key])); 
        $priceval = floatval(mysqli_real_escape_string($conn, $_POST['priceval'][$key])); 
        $gstval = floatval(mysqli_real_escape_string($conn, $_POST['gstval'][$key])); 
        $discountval = floatval($_POST['discountval'][$key]);
        $cessrateval = $_POST['cessrateval'];
        $cessamountval = $_POST['cessamountval'];
        $in_ex_gst_val = $_POST['in_ex_gst_val'];
        $cgstval = $_POST['cgstval']; 
        $sgstval = $_POST['sgstval'];
        $igstval = $_POST['igstval'];
        $gstamountval = $_POST['gstamountval'];
        $productids = $_POST['productids'];
        $products = $_POST['products'];
        $gstamountval = $_POST['gstamountval'];
        $proddesc = $_POST['proddesc'];
        // $cgstval = $gstval / 2; 
        // $sgstval = $gstval / 2; 
        // $total = $priceval * $qtyvalue;
        // $netprice = $total + $gstval;

        // $cgsttotal += $cgstval;
        // $sgsttotal += $sgstval;
        // $pricevaltot += $total;
        // $tot_total += $total;

        // $table = array(array($key + 1, $val, $gstval, $priceval, $qtyvalue, $total, $cgstval, $sgstval, $netprice));
        // $widths = array(6, 78, 12, 15, 10, 19, 15, 15, 21);
        // $pdf->plot_table($widths, 7, $table, 1, array('C','L','C','C','C','C','C','C','C'));

        $line_tot = $qtyvalue * $priceval;
        // $gstamt = ($gstval / 2) * $total / 100;


 mysqli_query($conn,"INSERT INTO `invoice_items` (`invoice_id`, `itemno`, `product`, `prod_desc`, `qty`, `price`, `discount`, `gst`, `cgst`, `sgst`, `igst`, `cess_rate`, `cess_amount`, `total`, `in_ex_gst`) VALUES ('$id', '$key', '$products', '$proddesc', '$qtyvalue', '$priceval', '$discountval', '$gstval', '$cgstval', '$sgstval', '$igstval', '$cessrateval', '$cessamountval', '$line_tot', '$in_ex_gst_val')");


        // mysqli_query($conn, "INSERT INTO `quotation_items` (`id`, `itemno`, `quotation_id`, `product_id`, `product`, `prod_desc`, `qty`, `price`, `line_total`, `gst`, `gst_amt`, `total`, `created_by`) VALUES (, '$key', '$id', '$productid', '$val','$proddesc','$qtyvalue','$priceval', '$line_tot', '$gstval', '$gstamt', '$netprice', '$created_by')");
    }

    // $gsttot = $cgsttotal + $sgsttotal;
    // $tot_amt = $gsttot + $tot_total;

    // $pdf->Cell(111, 10, "Grand Total", 1, 0, 'C');
    // $pdf->Cell(10, 10, "$tot_qty", 1, 0, 'C');
    // $pdf->Cell(19, 10, "$pricevaltot", 1, 0, 'C');
    // $pdf->Cell(15, 10, "$cgsttotal", 1, 0, 'C');
    // $pdf->Cell(15, 10, "$sgsttotal", 1, 0, 'C');
    // $pdf->Cell(21, 10, $tot_amt, 1, 1, 'C');

    // $filename = "pdf/".$file_name;
    // $pdfdoc = $pdf->Output('S');
    // file_put_contents($filename, $pdfdoc);

    // $sql = "INSERT INTO `quotation` (`id`, `invoice_code`, `quotation_file`, `customer_id`, `email`, `quotation_date`, `due_date`, `total_amount`, `total_tax`, `grand_total`, `terms_condition`, `note`, `status`, `created_by`) VALUES ('$id', '$invoice_code','$filename','$cst_mstr_id', '$customer_email', '$purchaseDate', '$dueDate', '$pricevaltot', '$gsttot','$tot_amt', '$terms', '$note','Not Converted', '$created_by')";


if (isset($_POST['additionalCharges']['charge_type']) && isset($_POST['additionalCharges']['charge_price'])) {
    $chargeTypes = $_POST['additionalCharges']['charge_type'];
    $chargePrices = $_POST['additionalCharges']['charge_price'];

    for ($i = 0; $i < count($chargeTypes); $i++) {
        $chargeType = mysqli_real_escape_string($conn, $chargeTypes[$i]);
        $chargePrice = floatval($chargePrices[$i]);

        $sql = "INSERT INTO invoice_additional_charges (invoice_id, charge_type, charge_price, created_on)
                VALUES ('$id', '$chargeType', '$chargePrice', NOW())";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to save additional charges: " . $conn->error);
        }
    }
}

$transportMode = $_POST['transportMode'] ?? 'None';

switch ($transportMode) {
    case 'Road':
        $vehicleNumber = $_POST['roadVehicleNumber'] ?? '';
        $driverName = $_POST['driverName'] ?? '';
        $licenseNumber = $_POST['licenseNumber'] ?? '';
        $roadFreightCharges = $_POST['roadFreightCharges'] ?? '';
        $insuranceDetails = $_POST['roadInsurance'] ?? '';
        $permitNumber = $_POST['roadPermit'] ?? '';
        $driverContact = $_POST['roadContact'] ?? '';
        $roadDistance = $_POST['roadDistance'] ?? '';

        // Insert query for Road
        $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, vehicle_number, driver_name, license_number, insurance_details, permit_number, driver_contact, distance) 
                VALUES ('$invoiceId', 'Road', '$roadFreightCharges', '$vehicleNumber', '$driverName', '$licenseNumber', '$insuranceDetails', '$permitNumber', '$driverContact', '$roadDistance')";
        break;

    case 'Rail':
        $trainNumber = $_POST['trainNumber'] ?? '';
        $departureStation = $_POST['railwayStation'] ?? '';
        $arrivalStation = $_POST['arrivalStation'] ?? '';
        $railwayBooking = $_POST['railwayBooking'] ?? '';
        $railFreightCharges = $_POST['railFreightCharges'] ?? '';
        $railwayCoach = $_POST['railwayCoach'] ?? '';
        $railwaySeat = $_POST['railwaySeat'] ?? '';
        $railDepartureTime = $_POST['railDepartureTime'] ?? '';

        // Insert query for Rail
        $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, train_number, departure_station, arrival_station, booking_reference, coach_number, seat_number, departure_time) 
                VALUES ('$invoiceId', 'Rail', '$railFreightCharges', '$trainNumber', '$departureStation', '$arrivalStation', '$railwayBooking', '$railwayCoach', '$railwaySeat', '$railDepartureTime')";
        break;

    case 'Air':
        $flightNumber = $_POST['flightNumber'] ?? '';
        $departureAirport = $_POST['departureAirport'] ?? '';
        $arrivalAirport = $_POST['arrivalAirport'] ?? '';
        $airwayBill = $_POST['airwayBill'] ?? '';
        $airFreightCharges = $_POST['airFreightCharges'] ?? '';
        $cargoType = $_POST['airCargoType'] ?? '';
        $airlineName = $_POST['airlineName'] ?? '';
        $estimatedArrival = $_POST['airETA'] ?? '';

        // Insert query for Air
        $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, flight_number, departure_airport, arrival_airport, airway_bill, cargo_type, airline_name, estimated_arrival) 
                VALUES ('$invoiceId', 'Air', '$airFreightCharges', '$flightNumber', '$departureAirport', '$arrivalAirport', '$airwayBill', '$cargoType', '$airlineName', '$estimatedArrival')";
        break;

    case 'Ship':
    
        $vesselName = $_POST['shipVesselName'] ?? '';
        $voyageNumber = $_POST['shipVoyageNumber'] ?? '';
        $containerNumber = $_POST['shipContainerNumber'] ?? '';
        $billOfLading = $_POST['shipBillOfLading'] ?? '';
        $portOfLoading = $_POST['shipPortOfLoading'] ?? '';
        $portOfDischarge = $_POST['shipPortOfDischarge'] ?? '';
        $shipFreightCharges = $_POST['shipFreightCharges'] ?? '';
        $estimatedArrival = $_POST['shipEstimatedArrival'] ?? '';

        // Insert query for Ship
        $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, vessel_name, voyage_number, container_number, bill_of_lading, port_of_loading, port_of_discharge, estimated_arrival) 
                VALUES ('$invoiceId', 'Ship', '$shipFreightCharges', '$vesselName', '$voyageNumber', '$containerNumber', '$billOfLading', '$portOfLoading', '$portOfDischarge', '$estimatedArrival')";
        break;

    default:
        // Default case: No transportation details
        $sql = "INSERT INTO transportation_details (invoice_id, mode) 
                VALUES ('$invoiceId', 'None')";
        break;
}

// Execute the query
if (!$conn->query($sql)) {
    throw new Exception("Failed to save transportation details: " . $conn->error);
}



    $sql = "INSERT INTO `invoice` (`id`, `invoice_code`, `invoice_file`, `customer_id`, `email`, `quotation_date`, `due_date`, `total_amount`, `total_tax`, `grand_total`, `terms_condition`, `note`, `status`, `created_by`) VALUES ('$id', '$invoice_code','$filename','$cst_mstr_id', '$customer_email', '$purchaseDate', '$dueDate', '$pricevaltot', '$gsttot','$tot_amt', '$terms', '$note','Not Converted', '$created_by')";


    if ($conn->query($sql) === TRUE) {
        ?>
        <script>
            window.location = "view-quotation.php";
            alert("Successfully Created Quotation");
        </script>
        <?php
    } else {
        ?>
        <script>
            window.location = "quotation.php";
            alert("Unable to create Quotation, try again");
        </script>
        <?php
    }
}
?>
