<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

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

if (isset($_POST['submit'])) {
    include("fpdf/fpdf.php");

    // Validate required fields
    if (empty($_POST['customer_name_choice']) || empty($_POST['customer_email']) || empty($_POST['invoice_code']) || empty($_POST['invoice_date']) || empty($_POST['dueDate'])) {
        echo "<script>alert('Required fields are missing. Please fill all mandatory details.'); window.history.back();</script>";
        exit();
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Escaping and sanitizing inputs
        $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name_choice']);
        $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
        $cst_mstr_id = mysqli_real_escape_string($conn, $_POST['cst_mstr_id']);
        $total_amount = floatval(mysqli_real_escape_string($conn, $_POST['total_amount']));
        $note = mysqli_real_escape_string($conn, $_POST['note']);
        $invoice_code = mysqli_real_escape_string($conn, $_POST['invoice_code']);
        $dueDate = mysqli_real_escape_string($conn, $_POST['dueDate']);
        $purchaseDate = mysqli_real_escape_string($conn, $_POST['invoice_date']);
        $terms = mysqli_real_escape_string($conn, $_POST['terms_condition']);
        $created_by = $_SESSION['name'];

        $final_cess_amount = floatval($_POST['final_cess_amount'] ?? 0);
        $final_gst_amount = floatval($_POST['final_gst_amount'] ?? 0);
        $final_taxable_amt = floatval($_POST['final_taxable_amt'] ?? 0);

        // Generate new invoice ID
        $result1 = mysqli_query($conn, "SELECT id FROM invoice ORDER BY id DESC LIMIT 1");
        $id = ($row1 = mysqli_fetch_array($result1)) ? $row1['id'] + 1 : 1;

$filename="";
        // Insert main invoice
        $sql = "INSERT INTO `invoice` (`id`, `invoice_code`, `customer_id`, `customer_name`, `customer_email`, `invoice_date`, `due_date`, `total_amount`, `total_gst`, `total_cess`, `grand_total`, `terms_condition`, `note`, `invoice_file`, `status`, `branch_id`, `created_by`) 
                VALUES ('$id', '$invoice_code', '$cst_mstr_id', '$customer_name', '$customer_email', '$purchaseDate', '$dueDate', '$final_taxable_amt', '$final_gst_amount', '$final_cess_amount', '$total_amount', '$terms', '$note', '$filename', 'Not Converted', '$branch_id', '$created_by')";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to insert invoice: " . $conn->error);
        }

        // Insert invoice items
        if (!empty($_POST['products'])) {
            foreach ($_POST["products"] as $key => $product) {
                $qtyvalue = floatval(mysqli_real_escape_string($conn, $_POST['qtyvalue'][$key]));
                $priceval = floatval(mysqli_real_escape_string($conn, $_POST['priceval'][$key]));
                $gstval = floatval(mysqli_real_escape_string($conn, $_POST['gstval'][$key]));
                $discountval = floatval(mysqli_real_escape_string($conn, $_POST['discountval'][$key]));
                $cessrateval = mysqli_real_escape_string($conn, $_POST['cessrateval'][$key]);
                $cessamountval = mysqli_real_escape_string($conn, $_POST['cessamountval'][$key]);
                $in_ex_gst_val = mysqli_real_escape_string($conn, $_POST['in_ex_gst_val'][$key]);
                $cgstval = mysqli_real_escape_string($conn, $_POST['cgstval'][$key]);
                $sgstval = mysqli_real_escape_string($conn, $_POST['sgstval'][$key]);
                $igstval = mysqli_real_escape_string($conn, $_POST['igstval'][$key]);
                $proddesc = mysqli_real_escape_string($conn, $_POST['proddesc'][$key]);

                $line_tot = $qtyvalue * $priceval;

                $sql = "INSERT INTO `invoice_items` (`invoice_id`, `itemno`, `product`, `prod_desc`, `qty`, `price`, `discount`, `gst`, `cgst`, `sgst`, `igst`, `cess_rate`, `cess_amount`, `total`, `in_ex_gst`) 
                        VALUES ('$id', '$key', '$product', '$proddesc', '$qtyvalue', '$priceval', '$discountval', '$gstval', '$cgstval', '$sgstval', '$igstval', '$cessrateval', '$cessamountval', '$line_tot', '$in_ex_gst_val')";

                if (!$conn->query($sql)) {
                    throw new Exception("Failed to insert invoice items: " . $conn->error);
                }
            }
        }

        // Insert additional charges
        if (isset($_POST['additionalCharges']['charge_type']) && isset($_POST['additionalCharges']['charge_price'])) {
    foreach ($_POST['additionalCharges']['charge_type'] as $key => $chargeType) {
        $chargePrice = isset($_POST['additionalCharges']['charge_price'][$key]) 
            ? floatval($_POST['additionalCharges']['charge_price'][$key]) 
            : 0; // Default to 0 if not set

        $chargeType = mysqli_real_escape_string($conn, $chargeType);

        // Debugging
        error_log("Charge Type: $chargeType, Charge Price: $chargePrice");

        $sql = "INSERT INTO invoice_additional_charges (invoice_id, charge_type, charge_price, created_on)
                VALUES ('$id', '$chargeType', '$chargePrice', NOW())";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to save additional charges: " . $conn->error);
        }
    }
}


        // Insert transportation details
        $transportMode = $_POST['transportMode'] ?? 'None';
        //$sql = ""; // Default transportation query

        switch ($transportMode) {
            case 'Road':
                $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, vehicle_number, driver_name, license_number, insurance_details, permit_number, driver_contact, distance) 
                        VALUES ('$id', 'Road', '" . ($_POST['roadFreightCharges'] ?? '') . "', '" . ($_POST['roadVehicleNumber'] ?? '') . "', '" . ($_POST['driverName'] ?? '') . "', '" . ($_POST['licenseNumber'] ?? '') . "', '" . ($_POST['roadInsurance'] ?? '') . "', '" . ($_POST['roadPermit'] ?? '') . "', '" . ($_POST['roadContact'] ?? '') . "', '" . ($_POST['roadDistance'] ?? '') . "')";
                break;
            case 'Rail':
                $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, train_number, departure_station, arrival_station, booking_reference, coach_number, seat_number, departure_time) 
                        VALUES ('$id', 'Rail', '" . ($_POST['railFreightCharges'] ?? '') . "', '" . ($_POST['trainNumber'] ?? '') . "', '" . ($_POST['railwayStation'] ?? '') . "', '" . ($_POST['arrivalStation'] ?? '') . "', '" . ($_POST['railwayBooking'] ?? '') . "', '" . ($_POST['railwayCoach'] ?? '') . "', '" . ($_POST['railwaySeat'] ?? '') . "', '" . ($_POST['railDepartureTime'] ?? '') . "')";
                break;
            case 'Air':
                $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, flight_number, departure_airport, arrival_airport, airway_bill, cargo_type, airline_name, estimated_arrival) 
                        VALUES ('$id', 'Air', '" . ($_POST['airFreightCharges'] ?? '') . "', '" . ($_POST['flightNumber'] ?? '') . "', '" . ($_POST['departureAirport'] ?? '') . "', '" . ($_POST['arrivalAirport'] ?? '') . "', '" . ($_POST['airwayBill'] ?? '') . "', '" . ($_POST['airCargoType'] ?? '') . "', '" . ($_POST['airlineName'] ?? '') . "', '" . ($_POST['airETA'] ?? '') . "')";
                break;
            case 'Ship':
                $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, vessel_name, voyage_number, container_number, bill_of_lading, port_of_loading, port_of_discharge, estimated_arrival) 
                        VALUES ('$id', 'Ship', '" . ($_POST['shipFreightCharges'] ?? '') . "', '" . ($_POST['shipVesselName'] ?? '') . "', '" . ($_POST['shipVoyageNumber'] ?? '') . "', '" . ($_POST['shipContainerNumber'] ?? '') . "', '" . ($_POST['shipBillOfLading'] ?? '') . "', '" . ($_POST['shipPortOfLoading'] ?? '') . "', '" . ($_POST['shipPortOfDischarge'] ?? '') . "', '" . ($_POST['shipEstimatedArrival'] ?? '') . "')";
                break;
            // default:
            //     $sql = "INSERT INTO transportation_details (invoice_id, mode) VALUES ('$id', 'None')";
            //     break;
        }

        if (!$conn->query($sql)) {
            throw new Exception("Failed to save transportation details: " . $conn->error);
        }

        // Insert other details
        $sql = "INSERT INTO invoice_other_details (invoice_id, po_number, po_date, challan_number, due_date, ewaybill_number, sales_person, reverse_charge, tcs_value, tcs_type, created_on) 
                VALUES ('$id', '" . ($_POST['other_poNumber'] ?? '') . "', '" . ($_POST['other_poDate'] ?? '') . "', '" . ($_POST['challanNumber'] ?? '') . "', '" . ($_POST['other_dueDate'] ?? '') . "', '" . ($_POST['ewayBill'] ?? '') . "', '" . ($_POST['salesPerson'] ?? '') . "', '" . ($_POST['reverseCharge'] ?? '0') . "', '" . ($_POST['tcsValue'] ?? '0') . "', '" . ($_POST['tcsTax'] ?? '') . "', NOW())";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to save other details: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();

        echo "<script>alert('Invoice created successfully'); window.location = 'view-invoices.php';</script>";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo "<script>alert('Failed to create invoice: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>






<?php
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;

// session_start(); 
// if(!isset($_SESSION['name'])) {
  
//     header("Location:login.php");
//     exit();
// }

// // Check if a business is selected
// if(!isset($_SESSION['business_id'])){
//     header("Location:dashboard.php");
//     exit();
// } else {
//  // Set up variables for selected business and branch
//     $_SESSION['url'] = $_SERVER['REQUEST_URI'];
//     $business_id = $_SESSION['business_id'];
//     // Check if a specific branch is selected
//     if (isset($_SESSION['branch_id'])) {
//         $branch_id = $_SESSION['branch_id'];
//         // Branch-specific code or logic here
//     } 
// }

// include("config.php");

// if(isset($_POST['submit'])) {
//     include("fpdf/fpdf.php");

//     $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name_choice']);
//     $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
//     $cst_mstr_id = mysqli_real_escape_string($conn, $_POST['cst_mstr_id']);
//     // $sub_total = floatval(mysqli_real_escape_string($conn, $_POST['sub_total']));
//     // $pack_price = floatval(mysqli_real_escape_string($conn, $_POST['pack_price']));
//     $total_amount = floatval(mysqli_real_escape_string($conn, $_POST['total_amount']));
//     // $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);
//     $note = mysqli_real_escape_string($conn, $_POST['note']);
//     $invoice_code = mysqli_real_escape_string($conn, $_POST['invoice_code']);
//     $dueDate = mysqli_real_escape_string($conn, $_POST['dueDate']);
//     $purchaseDate = mysqli_real_escape_string($conn, $_POST['invoice_date']);
//     $terms = mysqli_real_escape_string($conn, $_POST['terms_condition']);
//     $created_by = $_SESSION['name'];
// $final_cess_amount = $_POST['final_cess_amount'];
// $final_gst_amount = $_POST['final_gst_amount'];
// $final_taxable_amt = $_POST['final_taxable_amt'];

//     date_default_timezone_set('Asia/Kolkata');
//     $date1 = date("d-m-Y");
//     $time1 = date("h:i:sa");


// $other_poNumber = $_POST['other_poNumber'] ?? '';
// $other_poDate = $_POST['other_poDate'] ?? '';
// $challanNumber = $_POST['challanNumber'] ?? '';
// $other_dueDate = $_POST['other_dueDate'] ?? '';
// $ewayBill = $_POST['ewayBill'] ?? '';
// $salesPerson = $_POST['salesPerson'] ?? '';
// $tcsTax=$_POST['tcsTax'] ?? '';
// $tcsValue =$_POST['tcsValue'] ?? '';
// $reverseCharge =$_POST['reverseCharge'] ?? 0;

//  ;   // Generate ID for the new quotation
//     $result1 = mysqli_query($conn, "SELECT id FROM invoice ORDER BY id DESC LIMIT 1");
//     $id = ($row1 = mysqli_fetch_array($result1)) ? $row1['id'] + 1 : 1;

//    foreach ($_POST["products"] as $key => $product) {
//     $qtyvalue = floatval(mysqli_real_escape_string($conn, $_POST['qtyvalue'][$key]));
//     $priceval = floatval(mysqli_real_escape_string($conn, $_POST['priceval'][$key]));
//     $gstval = floatval(mysqli_real_escape_string($conn, $_POST['gstval'][$key]));
//     $discountval = floatval(mysqli_real_escape_string($conn, $_POST['discountval'][$key]));
//     $cessrateval = mysqli_real_escape_string($conn, $_POST['cessrateval'][$key]);
//     $cessamountval = mysqli_real_escape_string($conn, $_POST['cessamountval'][$key]);
//     $in_ex_gst_val = mysqli_real_escape_string($conn, $_POST['in_ex_gst_val'][$key]);
//     $cgstval = mysqli_real_escape_string($conn, $_POST['cgstval'][$key]);
//     $sgstval = mysqli_real_escape_string($conn, $_POST['sgstval'][$key]);
//     $igstval = mysqli_real_escape_string($conn, $_POST['igstval'][$key]);
//     $gstamountval = mysqli_real_escape_string($conn, $_POST['gstamountval'][$key]);
//     $productid = mysqli_real_escape_string($conn, $_POST['productids'][$key]);
//     $proddesc = mysqli_real_escape_string($conn, $_POST['proddesc'][$key]);

//     $line_tot = $qtyvalue * $priceval;

//     $sql = "INSERT INTO `invoice_items` (`invoice_id`, `itemno`, `product`, `prod_desc`, `qty`, `price`, `discount`, `gst`, `cgst`, `sgst`, `igst`, `cess_rate`, `cess_amount`, `total`, `in_ex_gst`) 
//             VALUES ('$id', '$key', '$product', '$proddesc', '$qtyvalue', '$priceval', '$discountval', '$gstval', '$cgstval', '$sgstval', '$igstval', '$cessrateval', '$cessamountval', '$line_tot', '$in_ex_gst_val')";

//     if (!$conn->query($sql)) {
//         throw new Exception("Failed to insert invoice items: " . $conn->error);
//     }
// }

   
// if (isset($_POST['additionalCharges']['charge_type']) && isset($_POST['additionalCharges']['charge_price'])) {
//     foreach ($_POST['additionalCharges']['charge_type'] as $key => $chargeType) {
//         $chargePrice = floatval($_POST['additionalCharges']['charge_price'][$key]);
//         $chargeType = mysqli_real_escape_string($conn, $chargeType);

//         $sql = "INSERT INTO invoice_additional_charges (invoice_id, charge_type, charge_price, created_on)
//                 VALUES ('$id', '$chargeType', '$chargePrice', NOW())";

//         if (!$conn->query($sql)) {
//             throw new Exception("Failed to save additional charges: " . $conn->error);
//         }
//     }
// }


// $transportMode = $_POST['transportMode'] ?? 'None';

// switch ($transportMode) {
//     case 'Road':
//         $vehicleNumber = $_POST['roadVehicleNumber'] ?? '';
//         $driverName = $_POST['driverName'] ?? '';
//         $licenseNumber = $_POST['licenseNumber'] ?? '';
//         $roadFreightCharges = $_POST['roadFreightCharges'] ?? '';
//         $insuranceDetails = $_POST['roadInsurance'] ?? '';
//         $permitNumber = $_POST['roadPermit'] ?? '';
//         $driverContact = $_POST['roadContact'] ?? '';
//         $roadDistance = $_POST['roadDistance'] ?? '';

//         // Insert query for Road
//         $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, vehicle_number, driver_name, license_number, insurance_details, permit_number, driver_contact, distance) 
//                 VALUES ('$id', 'Road', '$roadFreightCharges', '$vehicleNumber', '$driverName', '$licenseNumber', '$insuranceDetails', '$permitNumber', '$driverContact', '$roadDistance')";
//         break;

//     case 'Rail':
//         $trainNumber = $_POST['trainNumber'] ?? '';
//         $departureStation = $_POST['railwayStation'] ?? '';
//         $arrivalStation = $_POST['arrivalStation'] ?? '';
//         $railwayBooking = $_POST['railwayBooking'] ?? '';
//         $railFreightCharges = $_POST['railFreightCharges'] ?? '';
//         $railwayCoach = $_POST['railwayCoach'] ?? '';
//         $railwaySeat = $_POST['railwaySeat'] ?? '';
//         $railDepartureTime = $_POST['railDepartureTime'] ?? '';

//         // Insert query for Rail
//         $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, train_number, departure_station, arrival_station, booking_reference, coach_number, seat_number, departure_time) 
//                 VALUES ('$id', 'Rail', '$railFreightCharges', '$trainNumber', '$departureStation', '$arrivalStation', '$railwayBooking', '$railwayCoach', '$railwaySeat', '$railDepartureTime')";
//         break;

//     case 'Air':
//         $flightNumber = $_POST['flightNumber'] ?? '';
//         $departureAirport = $_POST['departureAirport'] ?? '';
//         $arrivalAirport = $_POST['arrivalAirport'] ?? '';
//         $airwayBill = $_POST['airwayBill'] ?? '';
//         $airFreightCharges = $_POST['airFreightCharges'] ?? '';
//         $cargoType = $_POST['airCargoType'] ?? '';
//         $airlineName = $_POST['airlineName'] ?? '';
//         $estimatedArrival = $_POST['airETA'] ?? '';

//         // Insert query for Air
//         $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, flight_number, departure_airport, arrival_airport, airway_bill, cargo_type, airline_name, estimated_arrival) 
//                 VALUES ('$id', 'Air', '$airFreightCharges', '$flightNumber', '$departureAirport', '$arrivalAirport', '$airwayBill', '$cargoType', '$airlineName', '$estimatedArrival')";
//         break;

//     case 'Ship':
    
//         $vesselName = $_POST['shipVesselName'] ?? '';
//         $voyageNumber = $_POST['shipVoyageNumber'] ?? '';
//         $containerNumber = $_POST['shipContainerNumber'] ?? '';
//         $billOfLading = $_POST['shipBillOfLading'] ?? '';
//         $portOfLoading = $_POST['shipPortOfLoading'] ?? '';
//         $portOfDischarge = $_POST['shipPortOfDischarge'] ?? '';
//         $shipFreightCharges = $_POST['shipFreightCharges'] ?? '';
//         $estimatedArrival = $_POST['shipEstimatedArrival'] ?? '';

//         // Insert query for Ship
//         $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, vessel_name, voyage_number, container_number, bill_of_lading, port_of_loading, port_of_discharge, estimated_arrival) 
//                 VALUES ('$id', 'Ship', '$shipFreightCharges', '$vesselName', '$voyageNumber', '$containerNumber', '$billOfLading', '$portOfLoading', '$portOfDischarge', '$estimatedArrival')";
//         break;

// }

// // Execute the query
// if (!$conn->query($sql)) {
//     throw new Exception("Failed to save transportation details: " . $conn->error);
// }
// $filename="";
// $sql = "INSERT INTO `invoice` (`id`, `invoice_code`, `customer_id`, `customer_name`, `customer_email`, `invoice_date`, `due_date`, `total_amount`, `total_gst`, `total_cess`, `grand_total`, `terms_condition`, `note`, `invoice_file`,`status`,`branch_id`,`created_by`) VALUES ('$id', '$invoice_code','$cst_mstr_id','$customer_name', '$customer_email', '$purchaseDate', '$dueDate', '$final_taxable_amt', '$final_gst_amount','$final_cess_amount','$total_amount', '$terms', '$note','$filename','Not Converted','$branch_id', '$created_by')";

//     if ($conn->query($sql) === TRUE) {
//         ?>
//         <script>
//             window.location = "view-invoices.php";
//             alert("Successfully Created Quotation");
//         </script>
//         <?php
//     } else {
//         ?>
//         <script>
//             window.location = "create-invoice.php";
//             alert("Unable to create Quotation, try again");
//         </script>
//         <?php
//     }
// }
?>
