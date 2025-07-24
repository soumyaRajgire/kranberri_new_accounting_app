<?php

// Check if the user is logged in
if(!isset($_SESSION['LOG_IN'])){
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

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    
    $title= mysqli_real_escape_string($conn, $_POST['title']);
    $name= mysqli_real_escape_string($conn, $_POST['name']);
    $entity_type= mysqli_real_escape_string($conn, $_POST['entity_type']);
    $mobile_number= mysqli_real_escape_string($conn, $_POST['mobile_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $customer_gstin = mysqli_real_escape_string($conn, $_POST['customer_gstin']);
    $customer_registered_name = mysqli_real_escape_string($conn, $_POST['customer_registered_name']);
    $business_name = mysqli_real_escape_string($conn, $_POST['business_name']);
    $additional_business_name = mysqli_real_escape_string($conn, $_POST['additional_business_name']);
    $display_name = mysqli_real_escape_string($conn, $_POST['display_name']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $fax = mysqli_real_escape_string($conn, $_POST['fax']);
    $account_number = mysqli_real_escape_string($conn, $_POST['account_number']);
    $account_name = mysqli_real_escape_string($conn, $_POST['account_name']);
    $bank_name = mysqli_real_escape_string($conn, $_POST['bank_name']);
    $ifsc_code= mysqli_real_escape_string($conn, $_POST['ifsc_code']);
    $account_type= mysqli_real_escape_string($conn, $_POST['account_type']);
    $branch_name= mysqli_real_escape_string($conn, $_POST['branch_name']);
    $pan= mysqli_real_escape_string($conn, $_POST['pan']);
    $tan= mysqli_real_escape_string($conn, $_POST['tan']);
    $tds_slab_rate= mysqli_real_escape_string($conn, $_POST['tds_slab_rate']);
    $currency= mysqli_real_escape_string($conn, $_POST['currency']);
    $terms_of_payment= mysqli_real_escape_string($conn, $_POST['terms_of_payment']);
    $reverse_charge= mysqli_real_escape_string($conn, $_POST['reverse_charge']);
    $export_type= mysqli_real_escape_string($conn, $_POST['export_type']);
    $bill_address_line1= mysqli_real_escape_string($conn, $_POST['bill_address_line1']);
    $bill_address_line2= mysqli_real_escape_string($conn, $_POST['bill_address_line2']);
    $bill_city= mysqli_real_escape_string($conn, $_POST['bill_city']);
    $bill_pin_code= mysqli_real_escape_string($conn, $_POST['bill_pin_code']);
    $bill_state= mysqli_real_escape_string($conn, $_POST['bill_state']);
    $bill_country= mysqli_real_escape_string($conn, $_POST['bill_country']);
    $bill_branch_name= mysqli_real_escape_string($conn, $_POST['bill_branch_name']);
    $bill_gstin= mysqli_real_escape_string($conn, $_POST['bill_gstin']);

$checkbox_name = mysqli_real_escape_string($conn,$_POST['checkbox_name']);

    if(isset($_POST['checkbox_name']))
    {
        $ship_address_line1= mysqli_real_escape_string($conn, $_POST['bill_address_line1']);
    $ship_address_line2 = mysqli_real_escape_string($conn, $_POST['bill_address_line2']);
    $ship_city = mysqli_real_escape_string($conn,$_POST['bill_city']);
    $ship_pin_code = mysqli_real_escape_string($conn, $_POST['bill_pin_code']);
    $ship_state = mysqli_real_escape_string($conn, $_POST['bill_state']);
    $ship_country = mysqli_real_escape_string($conn, $_POST['bill_country']);
    $ship_branch_name = mysqli_real_escape_string($conn, $_POST['bill_branch_name']);
    $ship_gstin = mysqli_real_escape_string($conn, $_POST['bill_gstin']);
        
    }
    else
    {
        $ship_address_line1= mysqli_real_escape_string($conn, $_POST['ship_address_line1']);
    $ship_address_line2 = mysqli_real_escape_string($conn, $_POST['ship_address_line2']);
    $ship_city = mysqli_real_escape_string($conn,$_POST['ship_city']);
    $ship_pin_code = mysqli_real_escape_string($conn, $_POST['ship_pin_code']);
    $ship_state = mysqli_real_escape_string($conn, $_POST['ship_state']);
    $ship_country = mysqli_real_escape_string($conn, $_POST['ship_country']);
    $ship_branch_name = mysqli_real_escape_string($conn, $_POST['ship_branch_name']);
    $ship_gstin = mysqli_real_escape_string($conn, $_POST['ship_gstin']);
    }

    
$created_by = $_SESSION['name'];
    

    $sqlCustomer = "INSERT INTO customer_master (title, customerName, entityType, mobile, email, gstin, gst_reg_name, business_name, additional_business_name, display_name, phone_no, fax, account_no, account_name, bank_name, account_type,ifsc_code,branch_name, pan, tan, tds_slab_rate, currency, terms_of_payment, reverse_charge, export_or_sez,contact_type,created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmtCustomer = $conn->prepare($sqlCustomer);

// Specify the correct data types for each parameter in the bind_param call
$customerType = 'Customer'; // Create a variable for 'Customer'
$stmtCustomer->bind_param("sssssssssssssssssssssssssss", $title, $name, $entity_type, $mobile_number, $email, $customer_gstin, $customer_registered_name, $business_name, $additional_business_name, $display_name, $phone_number, $fax, $account_number, $account_name, $bank_name, $account_type, $ifsc_code, $branch_name, $pan, $tan, $tds_slab_rate, $currency, $terms_of_payment, $reverse_charge, $export_type, $customerType, $created_by);


if ($stmtCustomer->execute() === TRUE) {
 $customer_id = $stmtCustomer->insert_id;

$sqlAddress = "INSERT INTO address_master (s_address_line1, s_address_line2,s_city,s_Pincode,s_state,s_country,s_branch,s_gstin,b_address_line1,b_address_line2,b_city,b_Pincode,b_state,b_country,b_branch,b_gstin,customer_master_id )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmtAddress = $conn->prepare($sqlAddress);
$stmtAddress->bind_param("ssssssssssssssssi",$ship_address_line1,$ship_address_line2,$ship_city,$ship_pin_code,$ship_state,$ship_country,$ship_branch_name,$ship_gstin,$bill_address_line1, $bill_address_line2, $bill_city, $bill_pin_code, $bill_state, $bill_country, $bill_branch_name, $bill_gstin,$customer_id);
if ($stmtAddress->execute() === TRUE) {

    ?>
<script>
                alert("Data inserted Successfully");
                window.location.href = "create-estimate.php";
            </script>
    <?php
}  
} else {
    echo "Error inserting customer: " . $stmtCustomer->error;
}




}

function getTCSOptions($conn, $selected = null) {
    $sql = "SELECT id, rate, code, description FROM tcs_master ORDER BY rate ASC";
    $result = $conn->query($sql);
    $html = '<option value="">Select TCS Rate</option>';
    while ($row = $result->fetch_assoc()) {
        $value = $row['id'];
        $label = "{$row['rate']}% - {$row['code']} | {$row['description']}";
        $isSelected = ($selected == $value) ? 'selected' : '';
        $html .= "<option value='$value' $isSelected>$label</option>";
    }
    return $html;
}
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <title>iiiQbets</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    .tab-button.active {
    background-color: #007bff;
    color: #fff;
}
.mandatory-symbol {
    color: red;
  }
  .error {
            color: red;
            size: 80%
        }

        .hidden {
            display: none;
        }

</style>

</head>
<body class="">

<div id="addCustomersModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Customers</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="" id="addCustomersForm" method="POST">
                <input type="hidden" name="contact_type" id="contact_type" value="Customer">
                <div class="modal-body">
                    <div class="tabs">
                        <div class="text-center">
                            <button type="button" class="tab-button active btn btn-sm btn-info" onclick="openTab(event, 'tab1')">Information</button>
                            <button type="button" class="tab-button btn btn-sm btn-info" onclick="openTab(event, 'tab2')">Banking & Taxes</button>
                            <button type="button" class="tab-button btn btn-sm btn-info" onclick="openTab(event, 'tab3')">Shipping Address</button>
                            <button type="button" class="tab-button btn btn-sm btn-info" onclick="openTab(event, 'tab4')">Billing Address</button>
                        </div>
    <!-- Tab content container for all tabs -->
    <div id="tab1" class="tab-content" style="display: block;">     
    <div class="row">
        <div class="col-lg-6">
            <div class="input-group mb-1">
                <div class="input-group-prepend">
                    <div class="did-floating-label-content">
                        <select class="did-floating-select" name="title" id="title" required>
                            <option value="">Title</option>   
                            <option value="mr">Mr.</option>
                            <option value="mrs">Mrs.</option>
                            <option value="miss">Miss</option>
                            <option value="ms">Ms.</option>
                            <option value="dr">Dr.</option>
                        </select>
                        <label class="did-floating-label">Title</label>
                    </div>
                </div>
                <div class="did-floating-label-content">
                    <input type="text" class="did-floating-input" placeholder="" name="name" id="name" required>   
                    <label for="" class=" did-floating-label">Name</label>
                </div>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
            <div class="did-floating-label-content">
                <select id="entity_type" name="entity_type" class=" did-floating-select" required>
                    <option value="">Select an Entity Type</option>
                    <option value="individual">Individual</option>
                    <option value="Propritorship">Propritorship</option>
                    <option value="Partnership">Partnership</option>
                    <option value="Hindu Undivided Family">Hindu Undivided Family</option>
                    <option value="Private Limited Company">Private Limited Company</option>
                    <option value="Public Limited Company">Public Limited Company</option>
                    <option value="One Person Company">One Person Company </option>
                    <option value="Society/Club/Trust/Association of Persons">Society/Club/Trust/Association of Persons</option>
                    <option value="Government Department">Government Department</option>
                    <option value="Public Sector Undertaking">Public Sector Undertaking</option>
                    <option value="Unlimited Company">Unlimited Company</option>
                    <option value="Limited Liability Partnership">Limited Liability Partnership</option> 
                    <option value="Local Authority">Local Authority</option>
                    <option value="Statutory Body">Statutory Body</option>
                    <option value="Foreign Limited Liability Partnership">Foreign Limited Liability Partnership</option>
                    <option value="Foreign Company Registered(in india)">Foreign Company Registered(in india)</option>
                    <option value="Others">Others</option>
                </select>
                 <label for="entity_type" class="did-floating-label">Entity Type</label>
        </div> 
    </div>
    
    <div class="mb-1 col-lg-6">
        <div class="did-floating-label-content">
            <input type="text" id="mobile_number" name="mobile_number" class="did-floating-input" placeholder="" required>
             <label for="mobile_number" class="did-floating-label">Mobile Number</label>
        </div>
    </div>
    <div class="mb-1 col-lg-6">
        <div class="did-floating-label-content">
            <input type="email" id="email" name="email" class="did-floating-input" placeholder="" required>
             <label for="email" class="did-floating-label">Email</label>
        </div>
    </div>
    <div class="mb-1 col-lg-6">
        <div class="did-floating-label-content">
            <input type="text" id="customer_gstin" name="customer_gstin" class="did-floating-input" placeholder="">
             <label for="customer_gstin" class="did-floating-label">Customer GSTIN</label>
        </div>
    </div>
    <div class="mb-1 col-lg-6">
        <div class="did-floating-label-content">
        <input type="text" id="customer_registered_name" name="customer_registered_name" class="did-floating-input" placeholder="" readonly>
        <label for="customer_registered_name" class="did-floating-label">Customer GST Registered Name</label>
        </div>
    </div>
    <div class="mb-1 col-lg-6">
         <div class="did-floating-label-content">
        <input type="text" id="business_name" name="business_name" class="did-floating-input" placeholder="" required>
        <label for="business_name" class="did-floating-label">Business Name</label>
        </div>
    </div>
    <div class="mb-1 col-lg-6">
         <div class="did-floating-label-content">
        <input type="text" id="additional_business_name" name="additional_business_name" class="did-floating-input" placeholder="" required>
        <label for="additional_business_name" class="did-floating-label">Additional Business Name</label>
        </div>
    </div>
    <div class="mb-1 col-lg-6">
         <div class="did-floating-label-content">
        <input type="text" id="display_name" name="display_name" class="did-floating-input" placeholder="">
        <label for="display_name" class="did-floating-label">Display Name</label>
        </div>
    </div>
    <div class="mb-1 col-lg-6">
        <div class="did-floating-label-content">
        <input type="text" id="phone_number" name="phone_number" class="did-floating-input" placeholder="" required>
         <label for="phone_number" class="did-floating-label">Phone Number</label>
        </div>
    </div>
    <div class="mb-1 col-lg-6">
        <div class="did-floating-label-content">
        <input type="text" id="fax" name="fax" class="did-floating-input" placeholder="">
        <label for="fax" class="did-floating-label">Fax</label>
        </div>
    </div>
   
          
      </div>
      <div class="text-right">
    <button type="button" class="btn btn-info" onclick="validateTab('tab1', 'tab2')">Next</button>
</div>
    </div>



    
    <div id="tab2" class="tab-content">
        <div class="row">
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <input type="text" id="account_number" name="account_number" class="did-floating-input" placeholder="" required>
                <label for="account_number" class="did-floating-label">Account Number</label>
                </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <input type="text" id="account_name" name="account_name" class="did-floating-input" placeholder="" required>
                <label for="account_name" class="did-floating-label">Account Name</label>
            </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <select class="did-floating-select" name="bank_name" id="bank_name" required>
                    <option value="">Select Bank Name</option>
                    <option value="ABU DHABI COMMERCIAL BANK">ABU DHABI COMMERCIAL BANK</option>
                    <option value="ABHYUDAYA COOPERATIVE BANK LIMITED">ABHYUDAYA COOPERATIVE BANK LIMITED</option>
                    <option value="ALLAHABAD BANK">ALLAHABAD BANK</option>
                    <option value="ANDHRA BANK">ANDHRA BANK</option>
                    <option value="AXIS BANK">AXIS BANK</option>
                    <option value="BANK OF AMERICA">BANK OF AMERICA</option>
                    <option value="BANK OF BAHARAIN AND KUWAIT BSC">BANK OF BAHARAIN AND KUWAIT BSC</option>
                    <option value="BANK OF BARODA">BANK OF BARODA</option>
                    <option value="BANK OF CEYLON">BANK OF CEYLON</option>
                    <option value="BANK OF INDIA">BANK OF INDIA</option>
                    <option value="BANK OF MAHARASHTRA">BANK OF MAHARASHTRA</option>
                    <option value="BANK OF TOKYO MITSUBISHI LIMITED">BANK OF TOKYO MITSUBISHI LIMITED</option><option value="BARCLAYS BANK">BARCLAYS BANK</option><option value="BASSEIN CATHOLIC COOPERATIVE BANK LIMITED">BASSEIN CATHOLIC COOPERATIVE BANK LIMITED</option><option value="B N P PARIBAS">B N P PARIBAS</option><option value="CANARA BANK">CANARA BANK</option><option value="CATHOLIC SYRIAN BANK LIMITED">CATHOLIC SYRIAN BANK LIMITED</option><option value="CENTRAL BANK OF INDIA">CENTRAL BANK OF INDIA</option><option value="CHINATRUST COMMERCIAL BANK LIMITED">CHINATRUST COMMERCIAL BANK LIMITED</option><option value="CITI BANK">CITI BANK</option><option value="CITIZEN CREDIT COOPERATIVE BANK LIMITED">CITIZEN CREDIT COOPERATIVE BANK LIMITED</option><option value="CITY UNION BANK LIMITED">CITY UNION BANK LIMITED</option><option value="CORPORATION BANK">CORPORATION BANK</option><option value="CREDIT AGRICOLE CORPORATE AND INVESTMENT BANK CALYON BANK">CREDIT AGRICOLE CORPORATE AND INVESTMENT BANK CALYON BANK</option><option value="DEVELOPMENT BANK OF SINGAPORE">DEVELOPMENT BANK OF SINGAPORE</option><option value="DENA BANK">DENA BANK</option><option value="DEUSTCHE BANK">DEUSTCHE BANK</option><option value="DCB BANK LIMITED">DCB BANK LIMITED</option><option value="DHANALAKSHMI BANK">DHANALAKSHMI BANK</option><option value="DEPOSIT INSURANCE AND CREDIT GUARANTEE CORPORATION">DEPOSIT INSURANCE AND CREDIT GUARANTEE CORPORATION</option><option value="DOMBIVLI NAGARI SAHAKARI BANK LIMITED">DOMBIVLI NAGARI SAHAKARI BANK LIMITED</option><option value="FIRSTRAND BANK LIMITED">FIRSTRAND BANK LIMITED</option><option value="HDFC BANK">HDFC BANK</option><option value="HSBC BANK">HSBC BANK</option><option value="ICICI BANK LIMITED">ICICI BANK LIMITED</option><option value="IDBI BANK">IDBI BANK</option><option value="INDIAN BANK">INDIAN BANK</option><option value="INDIAN OVERSEAS BANK">INDIAN OVERSEAS BANK</option><option value="INDUSIND BANK">INDUSIND BANK</option><option value="ING VYSYA BANK">ING VYSYA BANK</option><option value="JANAKALYAN SAHAKARI BANK LIMITED">JANAKALYAN SAHAKARI BANK LIMITED</option><option value="JANASEVA SAHAKARI BANK LIMITED">JANASEVA SAHAKARI BANK LIMITED</option><option value="KAPOL COOPERATIVE BANK LIMITED">KAPOL COOPERATIVE BANK LIMITED</option><option value="KARNATAKA BANK LIMITED">KARNATAKA BANK LIMITED</option><option value="KARUR VYSYA BANK">KARUR VYSYA BANK</option><option value="KOTAK MAHINDRA BANK LIMITED">KOTAK MAHINDRA BANK LIMITED</option><option value="MAHANAGAR COOPERATIVE BANK">MAHANAGAR COOPERATIVE BANK</option><option value="MAHARASHTRA STATE COOPERATIVE BANK">MAHARASHTRA STATE COOPERATIVE BANK</option><option value="MASHREQBANK PSC">MASHREQBANK PSC</option><option value="MIZUHO CORPORATE BANK LIMITED">MIZUHO CORPORATE BANK LIMITED</option><option value="NEW INDIA COOPERATIVE BANK LIMITED">NEW INDIA COOPERATIVE BANK LIMITED</option><option value="NKGSB COOPERATIVE BANK LIMITED">NKGSB COOPERATIVE BANK LIMITED</option><option value="NUTAN NAGARIK SAHAKARI BANK LIMITED">NUTAN NAGARIK SAHAKARI BANK LIMITED</option><option value="OMAN INTERNATIONAL BANK SAOG">OMAN INTERNATIONAL BANK SAOG</option><option value="ORIENTAL BANK OF COMMERCE">ORIENTAL BANK OF COMMERCE</option><option value="G P PARSIK BANK">G P PARSIK BANK</option><option value="PUNJAB AND MAHARSHTRA COOPERATIVE BANK">PUNJAB AND MAHARSHTRA COOPERATIVE BANK</option><option value="PUNJAB AND SIND BANK">PUNJAB AND SIND BANK</option><option value="PUNJAB NATIONAL BANK">PUNJAB NATIONAL BANK</option><option value="RAJKOT NAGRIK SAHAKARI BANK LIMITED">RAJKOT NAGRIK SAHAKARI BANK LIMITED</option><option value="RESERVE BANK OF INDIA">RESERVE BANK OF INDIA</option><option value="SHINHAN BANK">SHINHAN BANK</option><option value="SOCIETE GENERALE">SOCIETE GENERALE</option><option value="SOUTH INDIAN BANK">SOUTH INDIAN BANK</option><option value="STANDARD CHARTERED BANK">STANDARD CHARTERED BANK</option><option value="STATE BANK OF BIKANER AND JAIPUR">STATE BANK OF BIKANER AND JAIPUR</option><option value="STATE BANK OF HYDERABAD">STATE BANK OF HYDERABAD</option><option value="STATE BANK OF INDIA">STATE BANK OF INDIA</option><option value="STATE BANK OF MAURITIUS LIMITED">STATE BANK OF MAURITIUS LIMITED</option><option value="STATE BANK OF MYSORE">STATE BANK OF MYSORE</option><option value="STATE BANK OF PATIALA">STATE BANK OF PATIALA</option><option value="STATE BANK OF TRAVANCORE">STATE BANK OF TRAVANCORE</option><option value="SYNDICATE BANK">SYNDICATE BANK</option><option value="TAMILNAD MERCANTILE BANK LIMITED">TAMILNAD MERCANTILE BANK LIMITED</option><option value="THE BANK OF NOVA SCOTIA">THE BANK OF NOVA SCOTIA</option><option value="AHMEDABAD MERCANTILE COOPERATIVE BANK">AHMEDABAD MERCANTILE COOPERATIVE BANK</option><option value="BHARAT COOPERATIVE BANK MUMBAI LIMITED">BHARAT COOPERATIVE BANK MUMBAI LIMITED</option><option value="THE COSMOS CO OPERATIVE BANK LIMITED">THE COSMOS CO OPERATIVE BANK LIMITED</option><option value="FEDERAL BANK">FEDERAL BANK</option><option value="THE GREATER BOMBAY COOPERATIVE BANK LIMITED">THE GREATER BOMBAY COOPERATIVE BANK LIMITED</option><option value="JAMMU AND KASHMIR BANK LIMITED">JAMMU AND KASHMIR BANK LIMITED</option><option value="KALUPUR COMMERCIAL COOPERATIVE BANK">KALUPUR COMMERCIAL COOPERATIVE BANK</option><option value="THE KARANATAKA STATE COOPERATIVE APEX BANK LIMITED">THE KARANATAKA STATE COOPERATIVE APEX BANK LIMITED</option><option value="KALYAN JANATA SAHAKARI BANK">KALYAN JANATA SAHAKARI BANK</option><option value="LAXMI VILAS BANK">LAXMI VILAS BANK</option><option value="THE MEHSANA URBAN COOPERATIVE BANK">THE MEHSANA URBAN COOPERATIVE BANK</option><option value="THE NAINITAL BANK LIMITED">THE NAINITAL BANK LIMITED</option><option value="RBL Bank Limited">RBL Bank Limited</option><option value="THE ROYAL BANK OF SCOTLAND N V">THE ROYAL BANK OF SCOTLAND N V</option><option value="SARASWAT COOPERATIVE BANK LIMITED">SARASWAT COOPERATIVE BANK LIMITED</option><option value="THE SHAMRAO VITHAL COOPERATIVE BANK">THE SHAMRAO VITHAL COOPERATIVE BANK</option><option value="THE SURATH PEOPLES COOPERATIVE BANK LIMITED">THE SURATH PEOPLES COOPERATIVE BANK LIMITED</option><option value="THE TAMIL NADU STATE APEX COOPERATIVE BANK">THE TAMIL NADU STATE APEX COOPERATIVE BANK</option><option value="TJSB SAHAKARI BANK LTD">TJSB SAHAKARI BANK LTD</option><option value="THE WEST BENGAL STATE COOPERATIVE BANK">THE WEST BENGAL STATE COOPERATIVE BANK</option><option value="UCO BANK">UCO BANK</option><option value="UNION BANK OF INDIA">UNION BANK OF INDIA</option><option value="UNITED BANK OF INDIA">UNITED BANK OF INDIA</option><option value="VIJAYA BANK">VIJAYA BANK</option><option value="YES BANK">YES BANK</option><option value="THE ANDHRA PRADESH STATE COOPERATIVE BANK LIMITED">THE ANDHRA PRADESH STATE COOPERATIVE BANK LIMITED</option><option value="THE KARAD URBAN COOPERATIVE BANK LIMITED">THE KARAD URBAN COOPERATIVE BANK LIMITED</option><option value="THE NASIK MERCHANTS COOPERATIVE BANK LIMITED">THE NASIK MERCHANTS COOPERATIVE BANK LIMITED</option><option value="ALMORA URBAN COOPERATIVE BANK LIMITED">ALMORA URBAN COOPERATIVE BANK LIMITED</option><option value="APNA SAHAKARI BANK LIMITED">APNA SAHAKARI BANK LIMITED</option><option value="AUSTRALIA AND NEW ZEALAND BANKING GROUP LIMITED">AUSTRALIA AND NEW ZEALAND BANKING GROUP LIMITED</option><option value="CAPITAL SMALL FINANCE BANK LIMITED">CAPITAL SMALL FINANCE BANK LIMITED</option><option value="CREDIT SUISEE AG">CREDIT SUISEE AG</option><option value="JALGAON JANATA SAHAKARI BANK LIMITED">JALGAON JANATA SAHAKARI BANK LIMITED</option><option value="JANATA SAHAKARI BANK LIMITED">JANATA SAHAKARI BANK LIMITED</option><option value="KALLAPPANNA AWADE ICHALKARANJI JANATA SAHAKARI BANK LIMITED">KALLAPPANNA AWADE ICHALKARANJI JANATA SAHAKARI BANK LIMITED</option><option value="THE MUMBAI DISTRICT CENTRAL COOPERATIVE BANK LIMITED">THE MUMBAI DISTRICT CENTRAL COOPERATIVE BANK LIMITED</option><option value="PRIME COOPERATIVE BANK LIMITED">PRIME COOPERATIVE BANK LIMITED</option><option value="RABOBANK INTERNATIONAL">RABOBANK INTERNATIONAL</option><option value="THE THANE BHARAT SAHAKARI BANK LIMITED">THE THANE BHARAT SAHAKARI BANK LIMITED</option><option value="THE A.P. MAHESH COOPERATIVE URBAN BANK LIMITED">THE A.P. MAHESH COOPERATIVE URBAN BANK LIMITED</option><option value="THE GUJARAT STATE COOPERATIVE BANK LIMITED">THE GUJARAT STATE COOPERATIVE BANK LIMITED</option><option value="KARNATAKA VIKAS GRAMEENA BANK">KARNATAKA VIKAS GRAMEENA BANK</option><option value="THE MUNICIPAL COOPERATIVE BANK LIMITED">THE MUNICIPAL COOPERATIVE BANK LIMITED</option><option value="NAGPUR NAGARIK SAHAKARI BANK LIMITED">NAGPUR NAGARIK SAHAKARI BANK LIMITED</option><option value="THE KANGRA CENTRAL COOPERATIVE BANK LIMITED">THE KANGRA CENTRAL COOPERATIVE BANK LIMITED</option><option value="THE RAJASTHAN STATE COOPERATIVE BANK LIMITED">THE RAJASTHAN STATE COOPERATIVE BANK LIMITED</option><option value="THE SURAT DISTRICT COOPERATIVE BANK LIMITED">THE SURAT DISTRICT COOPERATIVE BANK LIMITED</option><option value="THE VISHWESHWAR SAHAKARI BANK LIMITED">THE VISHWESHWAR SAHAKARI BANK LIMITED</option><option value="WOORI BANK">WOORI BANK</option><option value="SUTEX COOPERATIVE BANK LIMITED">SUTEX COOPERATIVE BANK LIMITED</option><option value="GURGAON GRAMIN BANK">GURGAON GRAMIN BANK</option><option value="COMMONWEALTH BANK OF AUSTRALIA">COMMONWEALTH BANK OF AUSTRALIA</option><option value="PRATHAMA BANK">PRATHAMA BANK</option><option value="NORTH MALABAR GRAMIN BANK">NORTH MALABAR GRAMIN BANK</option><option value="THE VARACHHA COOPERATIVE BANK LIMITED">THE VARACHHA COOPERATIVE BANK LIMITED</option><option value="SHRI CHHATRAPATI RAJASHRI SHAHU URBAN COOPERATIVE BANK LIMITED">SHRI CHHATRAPATI RAJASHRI SHAHU URBAN COOPERATIVE BANK LIMITED</option><option value="SBER BANK">SBER BANK</option><option value="TUMKUR GRAIN MERCHANTS COOPERATIVE BANK LIMITED">TUMKUR GRAIN MERCHANTS COOPERATIVE BANK LIMITED</option><option value="VASAI VIKAS SAHAKARI BANK LIMITED">VASAI VIKAS SAHAKARI BANK LIMITED</option><option value="VASAI VIKAS SAHAKARI BANK LTD">VASAI VIKAS SAHAKARI BANK LTD</option><option value="WESTPAC BANKING CORPORATION">WESTPAC BANKING CORPORATION</option><option value="ANDHRA PRAGATHI GRAMEENA BANK">ANDHRA PRAGATHI GRAMEENA BANK</option><option value="SUMITOMO MITSUI BANKING CORPORATION">SUMITOMO MITSUI BANKING CORPORATION</option><option value="THE SEVA VIKAS COOPERATIVE BANK LIMITED">THE SEVA VIKAS COOPERATIVE BANK LIMITED</option><option value="THE THANE DISTRICT CENTRAL COOPERATIVE BANK LIMITED">THE THANE DISTRICT CENTRAL COOPERATIVE BANK LIMITED</option><option value="JP MORGAN BANK">JP MORGAN BANK</option><option value="THE GADCHIROLI DISTRICT CENTRAL COOPERATIVE BANK LIMITED">THE GADCHIROLI DISTRICT CENTRAL COOPERATIVE BANK LIMITED</option><option value="THE AKOLA DISTRICT CENTRAL COOPERATIVE BANK">THE AKOLA DISTRICT CENTRAL COOPERATIVE BANK</option><option value="THE KURMANCHAL NAGAR SAHAKARI BANK LIMITED">THE KURMANCHAL NAGAR SAHAKARI BANK LIMITED</option><option value="THE JALGAON PEOPELS COOPERATIVE BANK LIMITED">THE JALGAON PEOPELS COOPERATIVE BANK LIMITED</option><option value="NATIONAL AUSTRALIA BANK LIMITED">NATIONAL AUSTRALIA BANK LIMITED</option><option value="SAHEBRAO DESHMUKH COOPERATIVE BANK LIMITED">SAHEBRAO DESHMUKH COOPERATIVE BANK LIMITED</option><option value="BANK INTERNASIONAL INDONESIA">BANK INTERNASIONAL INDONESIA</option><option value="SOLAPUR JANATA SAHAKARI BANK LIMITED">SOLAPUR JANATA SAHAKARI BANK LIMITED</option><option value="INDUSTRIAL AND COMMERCIAL BANK OF CHINA LIMITED">INDUSTRIAL AND COMMERCIAL BANK OF CHINA LIMITED</option><option value="UNITED OVERSEAS BANK LIMITED">UNITED OVERSEAS BANK LIMITED</option><option value="ZILA SAHAKRI BANK LIMITED GHAZIABAD">ZILA SAHAKRI BANK LIMITED GHAZIABAD</option><option value="JANASEVA SAHAKARI BANK BORIVLI LIMITED">JANASEVA SAHAKARI BANK BORIVLI LIMITED</option><option value="THE DELHI STATE COOPERATIVE BANK LIMITED">THE DELHI STATE COOPERATIVE BANK LIMITED</option><option value="RAJGURUNAGAR SAHAKARI BANK LIMITED">RAJGURUNAGAR SAHAKARI BANK LIMITED</option><option value="NAGAR URBAN CO OPERATIVE BANK">NAGAR URBAN CO OPERATIVE BANK</option><option value="AKOLA JANATA COMMERCIAL COOPERATIVE BANK">AKOLA JANATA COMMERCIAL COOPERATIVE BANK</option><option value="BHARATIYA MAHILA BANK LIMITED">BHARATIYA MAHILA BANK LIMITED</option><option value="HSBC BANK OMAN SAOG">HSBC BANK OMAN SAOG</option><option value="THE KANGRA COOPERATIVE BANK LIMITED">THE KANGRA COOPERATIVE BANK LIMITED</option><option value="THE ZOROASTRIAN COOPERATIVE BANK LIMITED">THE ZOROASTRIAN COOPERATIVE BANK LIMITED</option><option value="SHIKSHAK SAHAKARI BANK LIMITED">SHIKSHAK SAHAKARI BANK LIMITED</option><option value="THE HASTI COOP BANK LTD">THE HASTI COOP BANK LTD</option><option value="KERALA GRAMIN BANK">KERALA GRAMIN BANK</option><option value="PRAGATHI KRISHNA GRAMIN BANK">PRAGATHI KRISHNA GRAMIN BANK</option><option value="DOHA BANK QSC">DOHA BANK QSC</option><option value="DOHA BANK">DOHA BANK</option><option value="EXPORT IMPORT BANK OF INDIA">EXPORT IMPORT BANK OF INDIA</option><option value="TJSB SAHAKARI BANK LIMITED">TJSB SAHAKARI BANK LIMITED</option><option value="BANDHAN BANK LIMITED">BANDHAN BANK LIMITED</option><option value="SURAT NATIONAL COOPERATIVE BANK LIMITED">SURAT NATIONAL COOPERATIVE BANK LIMITED</option><option value="IDFC BANK LIMITED">IDFC BANK LIMITED</option><option value="INDUSTRIAL BANK OF KOREA">INDUSTRIAL BANK OF KOREA</option><option value="SBM BANK MAURITIUS LIMITED">SBM BANK MAURITIUS LIMITED</option><option value="NATIONAL BANK OF ABU DHABI PJSC">NATIONAL BANK OF ABU DHABI PJSC</option><option value="KEB Hana Bank">KEB Hana Bank</option><option value="THE PANDHARPUR URBAN CO OP. BANK LTD. PANDHARPUR">THE PANDHARPUR URBAN CO OP. BANK LTD. PANDHARPUR</option><option value="SAMARTH SAHAKARI BANK LTD">SAMARTH SAHAKARI BANK LTD</option><option value="SHIVALIK MERCANTILE CO OPERATIVE BANK LTD">SHIVALIK MERCANTILE CO OPERATIVE BANK LTD</option><option value="EQUITAS SMALL FINANCE BANK LIMITED">EQUITAS SMALL FINANCE BANK LIMITED</option><option value="JANATHA SEVA COOPERATIVE BANK LIMITED">JANATHA SEVA COOPERATIVE BANK LIMITED</option><option value="SARDAR VALLABHBHAI CO. SAHAKARI BANK">SARDAR VALLABHBHAI CO. SAHAKARI BANK</option><option value="UJJIVAN SMALL FINANCE BANK">UJJIVAN SMALL FINANCE BANK</option></select>
                 <label for="bank_name" class="did-floating-label">Bank Name</label>
            </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <input type="text" id="ifsc_code" name="ifsc_code" class="did-floating-input" placeholder="" required>
                <label for="ifsc_code" class="did-floating-label">IFSC Code</label>
                </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <select class="did-floating-select" id="account_type" name="account_type" required>
                    <!-- <option value="" disabled>Account Type</option> -->
                    <!-- <option value="">Please Select</option> -->
                    <option value="current account">Current Account</option>
                    <option value="savings account" selected="">Savings Account</option>
                    <option value="overdraft account">Overdraft Account</option>

                </select>
                 <label for="account_type" class="did-floating-label">Account Type</label>
            </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <input type="text" id="branch_name" name="branch_name" class="did-floating-input" placeholder="" required>
                <label for="branch_name" class="did-floating-label">Branch Name</label>
            </div>
            </div>
            <h5 class="fs-4 p-4 col-lg-12">Tax information</h5>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <input type="text" id="pan" name="pan" class="did-floating-input" placeholder="" required>
                <label for="pan" class="did-floating-label">PAN</label>
            </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <input type="text" id="tan" name="tan" class="did-floating-input" placeholder="">
                <label for="tan" class="did-floating-label">TAN</label>
                </div>
            </div>
          

            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                    <select class="did-floating-select" name="tcs_slab_rate" id="tcs_slab_rate">
                         <option value="">TCS Not Applicable</option>
                        <?php echo getTCSOptions($conn); ?>
                    </select>
                    <label for="tcs_slab_rate" class="did-floating-label">TCS Slab Rate</label>
                </div>
            </div>


            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
               <select class="did-floating-select" name="currency" id="currency">
                    <!-- <option disabled>Currency</option> -->
                    <option value="Indian Rupee" selected="">Indian Rupee</option>
                    <option value="US Dollar">US Dollar</option>
                    <option value="Euro">Euro</option>
                    <option value="British Pound">British Pound</option>
                    <option value="Australian Dollar">Australian Dollar</option>
                    <option value="Canadian Dollar">Canadian Dollar</option>
                    <option value="Singapore Dollar">Singapore Dollar</option>
                    <option value="Swiss Franc">Swiss Franc</option>
                    <option value="Malaysian Ringgit">Malaysian Ringgit</option>
                    <option value="Japanese Yen">Japanese Yen</option>
                    <option value="Chinese Yuan Renminbi">Chinese Yuan Renminbi</option>
                </select>
                <label for="currency" class="did-floating-label">Currency</label>
            </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <select class="did-floating-select" name="terms_of_payment" id="terms_of_payment">
                    <option value="" selected=""> - Select Terms of Payment - </option>
                    <option value="PIA - Payment in advance">PIA - Payment in advance</option>
                    <option value="Net 7 - Payment seven days after invoice date">Net 7 - Payment seven days after invoice date</option>
                    <option value="Net 10 - Payment ten days after invoice date">Net 10 - Payment ten days after invoice date</option>
                    <option value="Net 30 - Payment 30 days after invoice date">Net 30 - Payment 30 days after invoice date</option>
                    <option value="Net 60 - Payment 60 days after invoice date">Net 60 - Payment 60 days after invoice date</option>
                    <option value="Net 90 - Payment 90 days after invoice date">Net 90 - Payment 90 days after invoice date</option>
                    <option value="EOM - End of month">EOM - End of month</option>
                    <option value="21 MFI - 21st of the month following invoice date">21 MFI - 21st of the month following invoice date</option>
                    <option value="COD - Cash on delivery">COD - Cash on delivery</option>
                    <option value="Cash account - Account conducted on a cash basis, no credit">Cash account - Account conducted on a cash basis, no credit</option>
                    <option value="CND - Cash next delivery">CND - Cash next delivery</option>
                    <option value="CBS - Cash before shipment">CBS - Cash before shipment</option>
                    <option value="CIA - Cash in advance">CIA - Cash in advance</option>
                    <option value="CWO - Cash with order">CWO - Cash with order</option>
                </select>
                <label for="terms_of_payment" class="did-floating-label">Terms of Payment</label>
            </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <select id="reverse_charge" name="reverse_charge" class="did-floating-select">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
                <label for="reverse_charge" class="did-floating-label">Apply Reverse Charge by Default?</label>
            </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
               <select class="did-floating-select" id="export_type" name="export_type">
                    <option value="not applicable">Not Applicable</option>
                    <option value="exports">Exports</option>
                    <option value="deemed exports">Deemed Exports</option>
                    <option value="sez unit">SEZ Unit/Developer</option>
                </select>
                 <label for="export_type" class="did-floating-label">Export or SEZ Developer</label>
            </div>
            </div>
   
        </div>
        <div class="text-right">
    <button type="button" class="btn btn-info" onclick="validateTab('tab2', 'tab3')">Next</button>
</div>
    </div>
    <div id="tab3" class="tab-content">
    <div class="row">
        <div class="mb-1 col-lg-6">
            <div class="did-floating-label-content">
            <input type="text" id="bill_address_line1" name="bill_address_line1" class="did-floating-input" placeholder="" required>
            <label for="bill_address_line1" class="did-floating-label">Address Line 1</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
            <div class="did-floating-label-content">
            <input type="text" id="bill_address_line2" name="bill_address_line2" class="did-floating-input" placeholder="">
            <label for="bill_address_line2" class="did-floating-label">Address Line 2</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <input type="text" id="bill_city" name="bill_city" class="did-floating-input" placeholder="" required>
                <label for="bill_city" class="did-floating-label">City</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <input type="text" id="bill_pin_code" name="bill_pin_code" class="did-floating-input" placeholder="" required>
                <label for="bill_pin_code" class="did-floating-label">Pin Code</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <select id="bill_state" name="bill_state" class="did-floating-select" required>
                    <option value="">Select a State</option>
                    <option value="Andhra Pradesh">Andhra Pradesh</option>
                    <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                    <option value="Assam">Assam</option>
                    <option value="Bihar">Bihar</option>
                    <option value="Chhattisgarh">Chhattisgarh</option>
                    <option value="Goa">Goa</option>
                    <option value="Gujarat">Gujarat</option>
                    <option value="Haryana">Haryana</option>
                    <option value="Himachal Pradesh">Himachal Pradesh</option>
                    <option value="Jharkhand">Jharkhand</option>
                    <option value="Karnataka">Karnataka</option>
                    <option value="Kerala">Kerala</option>
                    <option value="Madhya Pradesh">Madhya Pradesh</option>
                    <option value="Maharashtra">Maharashtra</option>
                    <option value="Manipur">Manipur</option>
                    <option value="Meghalaya">Meghalaya</option>
                    <option value="Mizoram">Mizoram</option>
                    <option value="Nagaland">Nagaland</option>
                    <option value="Odisha">Odisha</option>
                    <option value="Punjab">Punjab</option>
                    <option value="Rajasthan">Rajasthan</option>
                    <option value="Sikkim">Sikkim</option>
                    <option value="Tamil Nadu">Tamil Nadu</option>
                    <option value="Telangana">Telangana</option>
                    <option value="Tripura">Tripura</option>
                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                    <option value="Uttarakhand">Uttarakhand</option>
                    <option value="West Bengal">West Bengal</option>
                </select>
                 <label for="bill_state" class="did-floating-label">State</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
            <div class="did-floating-label-content">
                <select id="bill_country" name="bill_country" class="did-floating-select" required>
                    <option value="">Select a Country</option>
                    <option value="Afghanistan">Afghanistan</option>
                    <option value="Albania">Albania</option>
                    <option value="Algeria">Algeria</option>
                    <option value="Andorra">Andorra</option>
                    <option value="Angola">Angola</option>
                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                    <option value="Argentina">Argentina</option>
                    <option value="Armenia">Armenia</option>
                    <option value="Australia">Australia</option>
                    <option value="Austria">Austria</option>
                    <option value="Azerbaijan">Azerbaijan</option>
                    <option value="Bahamas">Bahamas</option>
                    <option value="Bahrain">Bahrain</option>
                    <option value="Bangladesh">Bangladesh</option>
                    <option value="Barbados">Barbados</option>
                    <option value="Belarus">Belarus</option>
                    <option value="Belgium">Belgium</option>
                    <option value="Belize">Belize</option>
                    <option value="Benin">Benin</option>
                    <option value="Bhutan">Bhutan</option>
                    <option value="Bolivia">Bolivia</option>
                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                    <option value="Botswana">Botswana</option>
                    <option value="Brazil">Brazil</option>
                    <option value="Brunei">Brunei</option>
                    <option value="Bulgaria">Bulgaria</option>
                    <option value="Burkina Faso">Burkina Faso</option>
                    <option value="Burundi">Burundi</option>
                    <option value="Cabo Verde">Cabo Verde</option>
                    <option value="Cambodia">Cambodia</option>
                    <option value="Cameroon">Cameroon</option>
                    <option value="Canada">Canada</option>
                    <option value="Central African Republic">Central African Republic</option>
                    <option value="Chad">Chad</option>
                    <option value="Chile">Chile</option>
                    <option value="China">China</option>
                    <option value="Colombia">Colombia</option>
                    <option value="Comoros">Comoros</option>
                    <option value="Congo, Democratic Republic of the">Congo, Democratic Republic of the</option>
                    <option value="Congo, Republic of the">Congo, Republic of the</option>
                    <option value="Costa Rica">Costa Rica</option>
                    <option value="Côte d'Ivoire">Côte d'Ivoire</option>
                    <option value="Croatia">Croatia</option>
                    <option value="Cuba">Cuba</option>
                    <option value="Cyprus">Cyprus</option>
                    <option value="Czech Republic">Czech Republic</option>
                    <option value="Denmark">Denmark</option>
                    <option value="Djibouti">Djibouti</option>
                    <option value="Dominica">Dominica</option>
                    <option value="Dominican Republic">Dominican Republic</option>
                    <option value="Ecuador">Ecuador</option>
                    <option value="Egypt">Egypt</option>
                    <option value="El Salvador">El Salvador</option>
                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                    <option value="Eritrea">Eritrea</option>
                    <option value="Estonia">Estonia</option>
                    <option value="Eswatini">Eswatini</option>
                    <option value="Ethiopia">Ethiopia</option>
                    <option value="Fiji">Fiji</option>
                    <option value="Finland">Finland</option>
                    <option value="France">France</option>
                    <option value="Gabon">Gabon</option>
                    <option value="Gambia">Gambia</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Germany">Germany</option>
                    <option value="Ghana">Ghana</option>
                    <option value="Greece">Greece</option>
                    <option value="Grenada">Grenada</option>
                    <option value="Guatemala">Guatemala</option>
                    <option value="Guinea">Guinea</option>
                    <option value="Guinea-Bissau">Guinea-Bissau</option>
                    <option value="Guyana">Guyana</option>
                    <option value="Haiti">Haiti</option>
                    <option value="Honduras">Honduras</option>
                    <option value="Hungary">Hungary</option>
                    <option value="Iceland">Iceland</option>
                    <option value="India">India</option>
                    <option value="Indonesia">Indonesia</option>
                    <option value="Iran">Iran</option>
                    <option value="Iraq">Iraq</option>
                    <option value="Ireland">Ireland</option>
                    <option value="Israel">Israel</option>
                    <option value="Italy">Italy</option>
                    <option value="Jamaica">Jamaica</option>
                    <option value="Japan">Japan</option>
                    <option value="Jordan">Jordan</option>
                    <option value="Kazakhstan">Kazakhstan</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Kiribati">Kiribati</option>
                    <option value="Korea, North">Korea, North</option>
                    <option value="Korea, South">Korea, South</option>
                    <option value="Kosovo">Kosovo</option>
                    <option value="Kuwait">Kuwait</option>
                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                    <option value="Laos">Laos</option>
                    <option value="Latvia">Latvia</option>
                    <option value="Lebanon">Lebanon</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Liberia">Liberia</option>
                    <option value="Libya">Libya</option>
                    <option value="Liechtenstein">Liechtenstein</option>
                    <option value="Lithuania">Lithuania</option>
                    <option value="Luxembourg">Luxembourg</option>
                    <option value="Madagascar">Madagascar</option>
                    <option value="Malawi">Malawi</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Maldives">Maldives</option>
                    <option value="Mali">Mali</option>
                    <option value="Malta">Malta</option>
                    <option value="Marshall Islands">Marshall Islands</option>
                    <option value="Mauritania">Mauritania</option>
                    <option value="Mauritius">Mauritius</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Micronesia">Micronesia</option>
                    <option value="Moldova">Moldova</option>
                    <option value="Monaco">Monaco</option>
                    <option value="Mongolia">Mongolia</option>
                    <option value="Montenegro">Montenegro</option>
                    <option value="Morocco">Morocco</option>
                    <option value="Mozambique">Mozambique</option>
                    <option value="Myanmar">Myanmar</option>
                    <option value="Namibia">Namibia</option>
                    <option value="Nauru">Nauru</option>
                    <option value="Nepal">Nepal</option>
                    <option value="Netherlands">Netherlands</option>
                    <option value="New Zealand">New Zealand</option>
                    <option value="Nicaragua">Nicaragua</option>
                    <option value="Niger">Niger</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="North Macedonia">North Macedonia</option>
                    <option value="Norway">Norway</option>
                    <option value="Oman">Oman</option>
                    <option value="Pakistan">Pakistan</option>
                    <option value="Palau">Palau</option>
                    <option value="Palestine">Palestine</option>
                    <option value="Panama">Panama</option>
                    <option value="Papua New Guinea">Papua New Guinea</option>
                    <option value="Paraguay">Paraguay</option>
                    <option value="Peru">Peru</option>
                    <option value="Philippines">Philippines</option>
                    <option value="Poland">Poland</option>
                    <option value="Portugal">Portugal</option>
                    <option value="Qatar">Qatar</option>
                    <option value="Romania">Romania</option>
                    <option value="Russia">Russia</option>
                    <option value="Rwanda">Rwanda</option>
                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                    <option value="Saint Lucia">Saint Lucia</option>
                    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                    <option value="Samoa">Samoa</option>
                    <option value="San Marino">San Marino</option>
                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
                    <option value="Senegal">Senegal</option>
                    <option value="Serbia">Serbia</option>
                    <option value="Seychelles">Seychelles</option>
                    <option value="Sierra Leone">Sierra Leone</option>
                    <option value="Singapore">Singapore</option>
                    <option value="Slovakia">Slovakia</option>
                    <option value="Slovenia">Slovenia</option>
                    <option value="Solomon Islands">Solomon Islands</option>
                    <option value="Somalia">Somalia</option>
                    <option value="South Africa">South Africa</option>
                    <option value="South Sudan">South Sudan</option>
                    <option value="Spain">Spain</option>
                    <option value="Sri Lanka">Sri Lanka</option>
                    <option value="Sudan">Sudan</option>
                    <option value="Suriname">Suriname</option>
                    <option value="Sweden">Sweden</option>
                    <option value="Switzerland">Switzerland</option>
                    <option value="Syria">Syria</option>
                    <option value="Taiwan">Taiwan</option>
                    <option value="Tajikistan">Tajikistan</option>
                    <option value="Tanzania">Tanzania</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Timor-Leste">Timor-Leste</option>
                    <option value="Togo">Togo</option>
                    <option value="Tonga">Tonga</option>
                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                    <option value="Tunisia">Tunisia</option>
                    <option value="Turkey">Turkey</option>
                    <option value="Turkmenistan">Turkmenistan</option>
                    <option value="Tuvalu">Tuvalu</option>
                    <option value="Uganda">Uganda</option>
                    <option value="Ukraine">Ukraine</option>
                    <option value="United Arab Emirates">United Arab Emirates</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="United States">United States</option>
                    <option value="Uruguay">Uruguay</option>
                    <option value="Uzbekistan">Uzbekistan</option>
                    <option value="Vanuatu">Vanuatu</option>
                    <option value="Vatican City">Vatican City</option>
                    <option value="Venezuela">Venezuela</option>
                    <option value="Vietnam">Vietnam</option>
                    <option value="Yemen">Yemen</option>
                    <option value="Zambia">Zambia</option>
                    <option value="Zimbabwe">Zimbabwe</option>
                </select>
                <label for="bill_country" class="did-floating-label">Country</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <input type="text" id="bill_branch_name" name="bill_branch_name" class="did-floating-input" placeholder="">
                <label for="bill_branch_name" class="did-floating-label">Branch Name</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <input type="text" id="bill_gstin" name="bill_gstin" class="did-floating-input" placeholder="" required>
                <label for="bill_gstin" class="did-floating-label">GSTIN</label>
            </div>
        </div>
    </div>
    <div class="text-right">
    <button type="button" class="btn btn-info" onclick="validateTab('tab3', 'tab4')">Next</button>
</div>
    </div>
    <div id="tab4" class="tab-content">
        <div class="row">
      <div class="text-center">
        <label class="form-check-label">
        <input type="checkbox" id="checkbox_id" name="checkbox_name" class="form-check-input" checked>
        Shipping address is same as billing address</label>
        </div>
        </div>

        <div id="addressForm">
            <div class="row">
        <div class="mb-1 col-lg-6">
            <div class="did-floating-label-content">
            <input type="text" id="ship_address_line1" name="ship_address_line1" class="did-floating-input" placeholder="" required>
            <label for="ship_address_line1" class="did-floating-label">Address Line 1</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
            <div class="did-floating-label-content">
            <input type="text" id="ship_address_line2" name="ship_address_line2" class="did-floating-input" placeholder="">
            <label for="ship_address_line2" class="did-floating-label">Address Line 2</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <input type="text" id="ship_city" name="ship_city" class="did-floating-input" placeholder="" required>
                <label for="ship_city" class="did-floating-label">City</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <input type="text" id="ship_pin_code" name="ship_pin_code" class="did-floating-input" placeholder="" required>
                <label for="ship_pin_code" class="did-floating-label">Pin Code</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <select id="ship_state" name="ship_state" class="did-floating-select" required> 
                    <option value="">Select a State</option>
                    <option value="Andhra Pradesh">Andhra Pradesh</option>
                    <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                    <option value="Assam">Assam</option>
                    <option value="Bihar">Bihar</option>
                    <option value="Chhattisgarh">Chhattisgarh</option>
                    <option value="Goa">Goa</option>
                    <option value="Gujarat">Gujarat</option>
                    <option value="Haryana">Haryana</option>
                    <option value="Himachal Pradesh">Himachal Pradesh</option>
                    <option value="Jharkhand">Jharkhand</option>
                    <option value="Karnataka">Karnataka</option>
                    <option value="Kerala">Kerala</option>
                    <option value="Madhya Pradesh">Madhya Pradesh</option>
                    <option value="Maharashtra">Maharashtra</option>
                    <option value="Manipur">Manipur</option>
                    <option value="Meghalaya">Meghalaya</option>
                    <option value="Mizoram">Mizoram</option>
                    <option value="Nagaland">Nagaland</option>
                    <option value="Odisha">Odisha</option>
                    <option value="Punjab">Punjab</option>
                    <option value="Rajasthan">Rajasthan</option>
                    <option value="Sikkim">Sikkim</option>
                    <option value="Tamil Nadu">Tamil Nadu</option>
                    <option value="Telangana">Telangana</option>
                    <option value="Tripura">Tripura</option>
                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                    <option value="Uttarakhand">Uttarakhand</option>
                    <option value="West Bengal">West Bengal</option>
                </select>
                 <label for="ship_state" class="did-floating-label">State</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
            <div class="did-floating-label-content">
                <select id="ship_country" name="ship_country" class="did-floating-select" required>
                    <option value="">Select a Country</option>
                    <option value="Afghanistan">Afghanistan</option>
                    <option value="Albania">Albania</option>
                    <option value="Algeria">Algeria</option>
                    <option value="Andorra">Andorra</option>
                    <option value="Angola">Angola</option>
                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                    <option value="Argentina">Argentina</option>
                    <option value="Armenia">Armenia</option>
                    <option value="Australia">Australia</option>
                    <option value="Austria">Austria</option>
                    <option value="Azerbaijan">Azerbaijan</option>
                    <option value="Bahamas">Bahamas</option>
                    <option value="Bahrain">Bahrain</option>
                    <option value="Bangladesh">Bangladesh</option>
                    <option value="Barbados">Barbados</option>
                    <option value="Belarus">Belarus</option>
                    <option value="Belgium">Belgium</option>
                    <option value="Belize">Belize</option>
                    <option value="Benin">Benin</option>
                    <option value="Bhutan">Bhutan</option>
                    <option value="Bolivia">Bolivia</option>
                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                    <option value="Botswana">Botswana</option>
                    <option value="Brazil">Brazil</option>
                    <option value="Brunei">Brunei</option>
                    <option value="Bulgaria">Bulgaria</option>
                    <option value="Burkina Faso">Burkina Faso</option>
                    <option value="Burundi">Burundi</option>
                    <option value="Cabo Verde">Cabo Verde</option>
                    <option value="Cambodia">Cambodia</option>
                    <option value="Cameroon">Cameroon</option>
                    <option value="Canada">Canada</option>
                    <option value="Central African Republic">Central African Republic</option>
                    <option value="Chad">Chad</option>
                    <option value="Chile">Chile</option>
                    <option value="China">China</option>
                    <option value="Colombia">Colombia</option>
                    <option value="Comoros">Comoros</option>
                    <option value="Congo, Democratic Republic of the">Congo, Democratic Republic of the</option>
                    <option value="Congo, Republic of the">Congo, Republic of the</option>
                    <option value="Costa Rica">Costa Rica</option>
                    <option value="Côte d'Ivoire">Côte d'Ivoire</option>
                    <option value="Croatia">Croatia</option>
                    <option value="Cuba">Cuba</option>
                    <option value="Cyprus">Cyprus</option>
                    <option value="Czech Republic">Czech Republic</option>
                    <option value="Denmark">Denmark</option>
                    <option value="Djibouti">Djibouti</option>
                    <option value="Dominica">Dominica</option>
                    <option value="Dominican Republic">Dominican Republic</option>
                    <option value="Ecuador">Ecuador</option>
                    <option value="Egypt">Egypt</option>
                    <option value="El Salvador">El Salvador</option>
                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                    <option value="Eritrea">Eritrea</option>
                    <option value="Estonia">Estonia</option>
                    <option value="Eswatini">Eswatini</option>
                    <option value="Ethiopia">Ethiopia</option>
                    <option value="Fiji">Fiji</option>
                    <option value="Finland">Finland</option>
                    <option value="France">France</option>
                    <option value="Gabon">Gabon</option>
                    <option value="Gambia">Gambia</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Germany">Germany</option>
                    <option value="Ghana">Ghana</option>
                    <option value="Greece">Greece</option>
                    <option value="Grenada">Grenada</option>
                    <option value="Guatemala">Guatemala</option>
                    <option value="Guinea">Guinea</option>
                    <option value="Guinea-Bissau">Guinea-Bissau</option>
                    <option value="Guyana">Guyana</option>
                    <option value="Haiti">Haiti</option>
                    <option value="Honduras">Honduras</option>
                    <option value="Hungary">Hungary</option>
                    <option value="Iceland">Iceland</option>
                    <option value="India">India</option>
                    <option value="Indonesia">Indonesia</option>
                    <option value="Iran">Iran</option>
                    <option value="Iraq">Iraq</option>
                    <option value="Ireland">Ireland</option>
                    <option value="Israel">Israel</option>
                    <option value="Italy">Italy</option>
                    <option value="Jamaica">Jamaica</option>
                    <option value="Japan">Japan</option>
                    <option value="Jordan">Jordan</option>
                    <option value="Kazakhstan">Kazakhstan</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Kiribati">Kiribati</option>
                    <option value="Korea, North">Korea, North</option>
                    <option value="Korea, South">Korea, South</option>
                    <option value="Kosovo">Kosovo</option>
                    <option value="Kuwait">Kuwait</option>
                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                    <option value="Laos">Laos</option>
                    <option value="Latvia">Latvia</option>
                    <option value="Lebanon">Lebanon</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Liberia">Liberia</option>
                    <option value="Libya">Libya</option>
                    <option value="Liechtenstein">Liechtenstein</option>
                    <option value="Lithuania">Lithuania</option>
                    <option value="Luxembourg">Luxembourg</option>
                    <option value="Madagascar">Madagascar</option>
                    <option value="Malawi">Malawi</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Maldives">Maldives</option>
                    <option value="Mali">Mali</option>
                    <option value="Malta">Malta</option>
                    <option value="Marshall Islands">Marshall Islands</option>
                    <option value="Mauritania">Mauritania</option>
                    <option value="Mauritius">Mauritius</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Micronesia">Micronesia</option>
                    <option value="Moldova">Moldova</option>
                    <option value="Monaco">Monaco</option>
                    <option value="Mongolia">Mongolia</option>
                    <option value="Montenegro">Montenegro</option>
                    <option value="Morocco">Morocco</option>
                    <option value="Mozambique">Mozambique</option>
                    <option value="Myanmar">Myanmar</option>
                    <option value="Namibia">Namibia</option>
                    <option value="Nauru">Nauru</option>
                    <option value="Nepal">Nepal</option>
                    <option value="Netherlands">Netherlands</option>
                    <option value="New Zealand">New Zealand</option>
                    <option value="Nicaragua">Nicaragua</option>
                    <option value="Niger">Niger</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="North Macedonia">North Macedonia</option>
                    <option value="Norway">Norway</option>
                    <option value="Oman">Oman</option>
                    <option value="Pakistan">Pakistan</option>
                    <option value="Palau">Palau</option>
                    <option value="Palestine">Palestine</option>
                    <option value="Panama">Panama</option>
                    <option value="Papua New Guinea">Papua New Guinea</option>
                    <option value="Paraguay">Paraguay</option>
                    <option value="Peru">Peru</option>
                    <option value="Philippines">Philippines</option>
                    <option value="Poland">Poland</option>
                    <option value="Portugal">Portugal</option>
                    <option value="Qatar">Qatar</option>
                    <option value="Romania">Romania</option>
                    <option value="Russia">Russia</option>
                    <option value="Rwanda">Rwanda</option>
                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                    <option value="Saint Lucia">Saint Lucia</option>
                    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                    <option value="Samoa">Samoa</option>
                    <option value="San Marino">San Marino</option>
                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
                    <option value="Senegal">Senegal</option>
                    <option value="Serbia">Serbia</option>
                    <option value="Seychelles">Seychelles</option>
                    <option value="Sierra Leone">Sierra Leone</option>
                    <option value="Singapore">Singapore</option>
                    <option value="Slovakia">Slovakia</option>
                    <option value="Slovenia">Slovenia</option>
                    <option value="Solomon Islands">Solomon Islands</option>
                    <option value="Somalia">Somalia</option>
                    <option value="South Africa">South Africa</option>
                    <option value="South Sudan">South Sudan</option>
                    <option value="Spain">Spain</option>
                    <option value="Sri Lanka">Sri Lanka</option>
                    <option value="Sudan">Sudan</option>
                    <option value="Suriname">Suriname</option>
                    <option value="Sweden">Sweden</option>
                    <option value="Switzerland">Switzerland</option>
                    <option value="Syria">Syria</option>
                    <option value="Taiwan">Taiwan</option>
                    <option value="Tajikistan">Tajikistan</option>
                    <option value="Tanzania">Tanzania</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Timor-Leste">Timor-Leste</option>
                    <option value="Togo">Togo</option>
                    <option value="Tonga">Tonga</option>
                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                    <option value="Tunisia">Tunisia</option>
                    <option value="Turkey">Turkey</option>
                    <option value="Turkmenistan">Turkmenistan</option>
                    <option value="Tuvalu">Tuvalu</option>
                    <option value="Uganda">Uganda</option>
                    <option value="Ukraine">Ukraine</option>
                    <option value="United Arab Emirates">United Arab Emirates</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="United States">United States</option>
                    <option value="Uruguay">Uruguay</option>
                    <option value="Uzbekistan">Uzbekistan</option>
                    <option value="Vanuatu">Vanuatu</option>
                    <option value="Vatican City">Vatican City</option>
                    <option value="Venezuela">Venezuela</option>
                    <option value="Vietnam">Vietnam</option>
                    <option value="Yemen">Yemen</option>
                    <option value="Zambia">Zambia</option>
                    <option value="Zimbabwe">Zimbabwe</option>
                </select>
                <label for="ship_country" class="did-floating-label">Country</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <input type="text" id="ship_branch_name" name="ship_branch_name" class="did-floating-input" placeholder="">
                <label for="ship_branch_name" class="did-floating-label">Branch Name</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <input type="text" id="ship_gstin" name="ship_gstin" class="did-floating-input" placeholder="">
                <label for="ship_gstin" class="did-floating-label">GSTIN</label>
            </div>
        </div>
    </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div> 
    </div>
</div>

        </div>

       
    </form>
        </div>
    </div>
</div>
<script>
    function validateTab(currentTabId, nextTabId) {
        var currentTab = document.getElementById(currentTabId);
        var inputs = currentTab.querySelectorAll('input[required], select[required]');
        var valid = true;

        inputs.forEach(function(input) {
            if (!input.value) {
                input.reportValidity();
                valid = false;
                return false;
            }
        });

        if (valid) {
            openTab(event, nextTabId);
        }
    }

    function openTab(event, tabId) {
        // Hide all tab contents
        var tabContents = document.getElementsByClassName("tab-content");
        for (var i = 0; i < tabContents.length; i++) {
            tabContents[i].style.display = "none";
        }
        // Remove active class from all tab buttons
        var tabButtons = document.getElementsByClassName("tab-button");
        for (var i = 0; i < tabButtons.length; i++) {
            tabButtons[i].classList.remove("active");
        }
        // Show the selected tab and add active class to the button
        document.getElementById(tabId).style.display = "block";
        event.currentTarget.classList.add("active");
    }
</script>
<!-- Add this script before the closing </body> tag -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var checkbox = document.getElementById("checkbox_id");
        var addressForm = document.getElementById("addressForm");

        function toggleAddressForm() {
            if (checkbox.checked) {
                addressForm.style.display = "none";
            } else {
                addressForm.style.display = "block";
            }
        }

        toggleAddressForm(); // Initial call to set the form's display

        checkbox.addEventListener("change", toggleAddressForm);

        // Add the following code to handle modal show event
        $('#addCustomersModal').on('show.bs.modal', function (event) {
            // Clear any existing values in the modal form
            document.getElementById("addCustomersForm").reset();
        });
    });
</script>

<script>
    // document.addEventListener("DOMContentLoaded", function() {
    //     var checkbox = document.getElementById("checkbox_id");
    //     var addressForm = document.getElementById("addressForm");

    //     function toggleAddressForm() {
    //         if (checkbox.checked) {
    //             addressForm.style.display = "none";
    //         } else {
    //             addressForm.style.display = "block";
    //         }
    //     }

    //     toggleAddressForm(); // Initial call to set the form's display

    //     checkbox.addEventListener("change", toggleAddressForm);
    // });
</script>
<script src="assets/js/myscript.js"></script>
<script src="assets/js/vendor-all.min.js"></script>
<script src="assets/js/plugins/bootstrap.min.js"></script>
<script src="assets/js/pcoded.min.js"></script>
</body>
</html>

