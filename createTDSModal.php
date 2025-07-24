<?php
error_reporting(E_ALL);
ini_set('display_errors',-1);
// if($_SERVER['REQUEST_METHOD'] === 'POST')
// {
    
//     $title= mysqli_real_escape_string($conn, $_POST['title']);
//     $name= mysqli_real_escape_string($conn, $_POST['name']);
//     $entity_type= mysqli_real_escape_string($conn, $_POST['entity_type']);
//     $mobile_number= mysqli_real_escape_string($conn, $_POST['mobile_number']);
//     $email = mysqli_real_escape_string($conn, $_POST['email']);
//     $customer_gstin = mysqli_real_escape_string($conn, $_POST['customer_gstin']);
//     $customer_registered_name = mysqli_real_escape_string($conn, $_POST['customer_registered_name']);
//     $business_name = mysqli_real_escape_string($conn, $_POST['business_name']);
//     $display_name = mysqli_real_escape_string($conn, $_POST['display_name']);
//     $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
//     $fax = mysqli_real_escape_string($conn, $_POST['fax']);
//     $account_number = mysqli_real_escape_string($conn, $_POST['account_number']);
//     $account_name = mysqli_real_escape_string($conn, $_POST['account_name']);
//     $bank_name = mysqli_real_escape_string($conn, $_POST['bank_name']);
//     $ifsc_code= mysqli_real_escape_string($conn, $_POST['ifsc_code']);
//     $account_type= mysqli_real_escape_string($conn, $_POST['account_type']);
//     $branch_name= mysqli_real_escape_string($conn, $_POST['branch_name']);
//     $pan= mysqli_real_escape_string($conn, $_POST['pan']);
//     $tan= mysqli_real_escape_string($conn, $_POST['tan']);
//     $tds_slab_rate= mysqli_real_escape_string($conn, $_POST['tds_slab_rate']);
//     $currency= mysqli_real_escape_string($conn, $_POST['currency']);
//     $terms_of_payment= mysqli_real_escape_string($conn, $_POST['terms_of_payment']);
//     $reverse_charge= mysqli_real_escape_string($conn, $_POST['reverse_charge']);
//     $export_type= mysqli_real_escape_string($conn, $_POST['export_type']);
//     $bill_address_line1= mysqli_real_escape_string($conn, $_POST['bill_address_line1']);
//     $bill_address_line2= mysqli_real_escape_string($conn, $_POST['bill_address_line2']);
//     $bill_city= mysqli_real_escape_string($conn, $_POST['bill_city']);
//     $bill_pin_code= mysqli_real_escape_string($conn, $_POST['bill_pin_code']);
//     $bill_state= mysqli_real_escape_string($conn, $_POST['bill_state']);
//     $bill_country= mysqli_real_escape_string($conn, $_POST['bill_country']);
//     $bill_branch_name= mysqli_real_escape_string($conn, $_POST['bill_branch_name']);
//     $bill_gstin= mysqli_real_escape_string($conn, $_POST['bill_gstin']);

// $checkbox_name = mysqli_real_escape_string($conn,$_POST['checkbox_name']);

//     if(isset($_POST['checkbox_name']))
//     {
//         $ship_address_line1= mysqli_real_escape_string($conn, $_POST['bill_address_line1']);
//     $ship_address_line2 = mysqli_real_escape_string($conn, $_POST['bill_address_line2']);
//     $ship_city = mysqli_real_escape_string($conn,$_POST['bill_city']);
//     $ship_pin_code = mysqli_real_escape_string($conn, $_POST['bill_pin_code']);
//     $ship_state = mysqli_real_escape_string($conn, $_POST['bill_state']);
//     $ship_country = mysqli_real_escape_string($conn, $_POST['bill_country']);
//     $ship_branch_name = mysqli_real_escape_string($conn, $_POST['bill_branch_name']);
//     $ship_gstin = mysqli_real_escape_string($conn, $_POST['bill_gstin']);
        
//     }
//     else
//     {
//         $ship_address_line1= mysqli_real_escape_string($conn, $_POST['ship_address_line1']);
//     $ship_address_line2 = mysqli_real_escape_string($conn, $_POST['ship_address_line2']);
//     $ship_city = mysqli_real_escape_string($conn,$_POST['ship_city']);
//     $ship_pin_code = mysqli_real_escape_string($conn, $_POST['ship_pin_code']);
//     $ship_state = mysqli_real_escape_string($conn, $_POST['ship_state']);
//     $ship_country = mysqli_real_escape_string($conn, $_POST['ship_country']);
//     $ship_branch_name = mysqli_real_escape_string($conn, $_POST['ship_branch_name']);
//     $ship_gstin = mysqli_real_escape_string($conn, $_POST['ship_gstin']);
//     }

    
// $created_by = $_SESSION['name'];
    

//     $sqlCustomer = "INSERT INTO customer_master (title, customerName, entityType, mobile, email, gstin, gst_reg_name, business_name, display_name, phone_no, fax, account_no, account_name, bank_name, account_type,ifsc_code,branch_name, pan, tan, tds_slab_rate, currency, terms_of_payment, reverse_charge, export_or_sez,contact_type,created_by)
//                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
// $stmtCustomer = $conn->prepare($sqlCustomer);

// // Specify the correct data types for each parameter in the bind_param call
// $customerType = 'Customer'; // Create a variable for 'Customer'
// $stmtCustomer->bind_param("ssssssssssssssssssssssssss", $title, $name, $entity_type, $mobile_number, $email, $customer_gstin, $customer_registered_name, $business_name, $display_name, $phone_number, $fax, $account_number, $account_name, $bank_name, $account_type, $ifsc_code, $branch_name, $pan, $tan, $tds_slab_rate, $currency, $terms_of_payment, $reverse_charge, $export_type, $customerType, $created_by);


// if ($stmtCustomer->execute() === TRUE) {
//  $customer_id = $stmtCustomer->insert_id;

// $sqlAddress = "INSERT INTO address_master (s_address_line1, s_address_line2,s_city,s_Pincode,s_state,s_country,s_branch,s_gstin,b_address_line1,b_address_line2,b_city,b_Pincode,b_state,b_country,b_branch,b_gstin,customer_master_id )
//                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
// $stmtAddress = $conn->prepare($sqlAddress);
// $stmtAddress->bind_param("ssssssssssssssssi",$ship_address_line1,$ship_address_line2,$ship_city,$ship_pin_code,$ship_state,$ship_country,$ship_branch_name,$ship_gstin,$bill_address_line1, $bill_address_line2, $bill_city, $bill_pin_code, $bill_state, $bill_country, $bill_branch_name, $bill_gstin,$customer_id);
// if ($stmtAddress->execute() === TRUE) {

//     ?>
<script>
//                 alert("Data inserted Successfully");
//                 window.location.href = "create-estimate.php";
//             </script>
//     <?php
// }  
// } else {
//     echo "Error inserting customer: " . $stmtCustomer->error;
// }




// }

// ?>






<div id="tdsModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="col-md-8 modal-title"> TDS Payment</h4>
                <div class="col-md-3 btn-group btn-group-sm btn_filter pull-right tab_shift" role="group" aria-label="Large button group">
                   
                </div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
    <form action="receiptdb.php"  id="addreceiptForm1" method="POST" enctype="multipart/form-data" >
        <!-- <input type="hidden" name="contact_type" id="contact_type" value="Customer"> -->
    <div class="modal-body">
       <div class="tabs">
            <div class="col-md-12" id="create_tab">
              <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 10px;">
                <div class="kt-portlet__body p-3" style="padding-top: 0px !important;">
                  <div>
                   <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="display:none;">
                        <div class="kt-input-icon kt-input-icon--right row" style="margin-top:0px;">
                            <div class="col-md-6 col-lg-6">
                            <h6 style="margin-top:10px; color:black;">Date</h6>
                                <input placeholder="Date" class="form-control kt-input" type="text" id="tds_date" value="" disabled="">
                            </div>
                            <div class="col-md-6 col-lg-6">
                            <h6 style="margin-top:10px; color:black;" class="pur_txt">Invoice Number</h6>
                            <input placeholder="Purchase Number" class="form-control kt-input" type="text" id="purchase_no" value="" disabled="">  
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <div class="kt-input-icon kt-input-icon--right row" style="margin-top:0px;">
                        <div class="mt-2 col-md-6 col-lg-6">
                            <h6 style="margin-top:10px; color:black;">TDS Section</h6>
                            <select style="width: 100%;" class="form-control tds_section1 mr-2" id="tds_section1" name="param">
                            <option value="0">Select </option>
    <option data-value="0" value="194Q" data-tds_rate="0.1">0.1% - 194Q | Payment of certain sums for purchase of goods</option>
    <option data-value="0" value="194-O" data-tds_rate="1">1% - 194-O | Payment or credit of amount by the e-commerce operator to e-commerce participant</option>
    
    <option data-value="0" value="194C" data-tds_rate="1">1% - 194C | HUF/Individuals</option>
    <option data-value="0" value="194-IA" data-tds_rate="1">1% - 194-IA | Payment on transfer of certain immovable property other than agricultural land</option>
    <option data-value="0" value="194S" data-tds_rate="1">1% - 194S | TDS on payment for Virtual Digital Assets</option>
    
    <option data-value="0" value="194C" data-tds_rate="2">2% - 194C | Others</option>
    <option data-value="0" value="194-I" data-tds_rate="2">2% - 194-I | Plant and Machinery</option>
    <option data-value="0" value="194J" data-tds_rate="2">2% - 194J | Sum paid or payable towards fees for technical services</option>
    <option data-value="0" value="194J" data-tds_rate="2">2% - 194J | Sum paid or payable towards royalty in the nature of consideration for sale, distribution or exhibition of cinematographic films</option>
    <option data-value="0" value="194N" data-tds_rate="2">2% - 194N | Cash withdrawal-in excess of Rs. 1crore</option>
    <option data-value="0" value="194N" data-tds_rate="2">2% - 194N | Cash withdrawal-aggregate of the amount of withdrawal exceeds Rs. 20lakhs</option>
    <option data-value="0" value="194N" data-tds_rate="5">5% - 194N | Cash withdrawal-aggregate of the amount of withdrawal exceeds Rs. 1crore</option>
    <option data-value="0" value="194D" data-tds_rate="5">5% - 194D | Insurance commission-Individuals</option>
    <option data-value="0" value="194G" data-tds_rate="5">5% - 194G | Commission on sale of lottery tickets</option>
    <option data-value="0" value="194H" data-tds_rate="5">5% - 194H | Commission or brokerage</option>
    <option data-value="0" value="206AB" data-tds_rate="5">5% - 206AB | TDS on non-filters of ITR at higher rates</option>
    <option data-value="0" value="194DA" data-tds_rate="5">5% - 194DA | The tax shall be deducted on the amount of income comprised in insurance pay-out</option>
    <option data-value="0" value="194-IB" data-tds_rate="5">5% - 194-IB | Payment of rent by individual or HUF not liable to tax audit</option>
    <option data-value="0" value="194LB" data-tds_rate="5" style="display: none;">5% - 194LB | Payment of interest on infrastructure debt fund to Non Resident</option>
    <option data-value="0" value="194M" data-tds_rate="5">5% - 194M | Payment of commission, brokerage, contactual fee, professional fee</option>
    <option data-value="0" value="194R" data-tds_rate="10">10% - 194R | TDS on benefit or prequisite of a business or profession</option>
    <option data-value="0" value="194LBA" data-tds_rate="10">10% - 194LBA | Interest received from a SPV or income received from renting or leasing or real estate</option>
    <option data-value="0" value="194LBB" data-tds_rate="10">10% - 194LBB | Investment fund paying an income to a unit holder</option>
    <option data-value="0" value="194-IC" data-tds_rate="10">10% - 194-IC | Payment of monetary consideartion under Joint Development Agreements</option>
    
    <option data-value="0" value="194J" data-tds_rate="10">10% - 194J | Any other sum</option>
    <option data-value="0" value="194K" data-tds_rate="10">10% - 194K | Income in respect of units payable to resident person</option>
    <option data-value="0" value="194LA" data-tds_rate="10">10% - 194LA | Payment of compensation on acquisition of certain immovable property</option>
    <option data-value="0" value="192A" data-tds_rate="10">10% - 192A | Payment of accumulated balance of provident fund which is taxable in the hands of an employee</option>
    <option data-value="0" value="193" data-tds_rate="10">10% - 193 | Interest on securities</option>
    <option data-value="0" value="194" data-tds_rate="10">10% - 194 | Dividend</option>
    <option data-value="0" value="194A" data-tds_rate="10">10% - 194A | Senior Citizen</option>
    <option data-value="0" value="194A" data-tds_rate="10">10% - 194A | Others</option>
    <option data-value="0" value="194A" data-tds_rate="10">10% - 194A | Interest other than "Interest on securities"</option>
    
    <option data-value="0" value="194-I" data-tds_rate="10">10% - 194-I | Land or building or furniture or fitting</option>
    
    <option data-value="0" value="194EE" data-tds_rate="10">10% - 194EE | Payment in respect of deposit under National Savings Scheme</option>
    <option data-value="0" value="194D" data-tds_rate="10">10% - 194D | Insurance commission-Companies</option>
    <option data-value="0" value="194E" data-tds_rate="20" style="display: none;">20% - 194E | Payment to non-resident sportsmen/sports association</option>
    <option data-value="0" value="206AA" data-tds_rate="20">20% - 206AA | TDS rate in case of Non availability of PAN</option>
    <option data-value="0" value="194F" data-tds_rate="20">20% - 194F | Payment on account of repurchase of unit by Mutual Fund or Unit Trust of India</option>
    <option data-value="0" value="194LBC" data-tds_rate="25">25% - 194LBC | Income in respect of investment made in a securitisation trust-HUF/Individuals</option>
    <option data-value="0" value="194B" data-tds_rate="30">30% - 194B | Winnings from lotteries, crossword puzzles, card games and other games of any sort</option>
    <option data-value="0" value="194BB" data-tds_rate="30">30% - 194BB | Winnings from horse races</option>
    <option data-value="0" value="194LBC" data-tds_rate="30">30% - 194LBC | Income in respect of investment made in a securitisation trust-Others</option></select>
                        </div>
                        
                          <div class="mt-2 col-md-6 col-lg-6">
                              <h6 style="margin-top:10px; color:black;">TDS Deductible</h6>
                              <input placeholder="TDS Deductible" class="form-control kt-input" type="text" id="tds_deductable" value="" disabled="" data-total="47619.05">
                              
                              
                          </div> 
                      </div>
                    </div>  
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="kt-input-icon kt-input-icon--right row" style="margin-top:0px;">
                            <div class="mt-2 col-md-6 col-lg-6">
                              <h6 style="margin-top:10px; color:black;">TDS Deducted</h6>
                                <input placeholder="TDS Deducted" class="form-control" type="text" id="tds_deducted" value="" min="0" data-supname="soumya n" data-supid="1" data-qyear="2023" data-quart="Q3">
                            </div>
                          
                            <div class="mt-2 col-md-6 col-lg-6">
                              
                              
                            <h6 style="margin-top:10px; color:black;">Deduction Date</h6>
                            <input placeholder="Deduction date" class="form-control" type="date" id="deduction_date" value=""></div>
                        </div>
                    </div>   
                  </div>

   

              </div>
                </div>
              </div>
            </div>
</div>

        </div>

       <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="" class="btn btn-primary">Submit</button>
        </div> 
    </form>
        </div>
    </div>
</div>
       
<!-- Add this script before the closing </body> tag -->

<script src="assets/js/myscript.js"></script>


