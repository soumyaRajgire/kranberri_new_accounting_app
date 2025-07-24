<!DOCTYPE html>
<?php
session_start(); 
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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contact_type = $_POST['contact_type'];
    $title = $_POST['title'];
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $created_on = date('Y-m-d');
    $country = $_POST['country'];
    $state = ($country === "India") ? $_POST['state_dropdown'] : $_POST['state_input'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $city = $_POST['city'];
    $pincode = $_POST['pincode'];
    $status = $_POST['status'];

    // Set variables based on contact type
    if ($contact_type == 'Promoter') {
        $pan = $_POST['pan'];
        $aadhaar = $_POST['aadhaar'];
        $dob = $_POST['dob'];
        $designation = $_POST['designation'];  // Use 'designation' but store it in entityType
        $phone = $_POST['phone'];
        $citizenship = $_POST['citizenship'];

        // Insert into customer_master table for Promoter
        $query1 = "INSERT INTO customer_master 
                  (title, customerName, mobile, email, pan, aadhaar, dob, entityType, phone_no, citizenship, status, contact_type, created_on) 
                  VALUES 
                  ('$title', '$name', '$mobile', '$email', '$pan', '$aadhaar', '$dob', '$designation', '$phone', '$citizenship', '$status', '$contact_type', '$created_on')";
    } elseif ($contact_type == 'Creditor' || $contact_type == 'Debtor') {
        $gstin = $_POST['gstin'];

        // Insert into customer_master table for Creditor/Debtor
        $query1 = "INSERT INTO customer_master 
                  (title, customerName, mobile, email, gstin, contact_type, status, created_on) 
                  VALUES 
                  ('$title', '$name', '$mobile', '$email', '$gstin', '$contact_type', '$status', '$created_on')";
    }

    // Execute the first insert and retrieve customer_master_id
    if (mysqli_query($conn, $query1)) {
        $customer_master_id = mysqli_insert_id($conn);

        // Insert into address_master table
        $query2 = "INSERT INTO address_master 
                  (s_address_line1, s_address_line2, s_city, s_Pincode, s_state, s_country, customer_master_id) 
                  VALUES 
                  ('$address1', '$address2', '$city', '$pincode', '$state', '$country', '$customer_master_id')";

        if (mysqli_query($conn, $query2)) {
            echo "<script>alert('Contact added successfully!'); window.location.href='other_contacts.php';</script>";
        } else {
            echo "<script>alert('Error adding address: " . mysqli_error($conn) . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Error adding contact: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
}
?>
<html lang="en">
<head>
    <title>iiiQbets</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
</head>

<body class="">
    <!-- [ Pre-loader ] start -->
     
     <?php include("menu.php");?>
    
    
    <!-- [ Header ] end -->
    

    

<!-- [ Main Content ] start -->
<section class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h4 class="m-b-10">Other Contacts</h4>
                        </div>
                       <!--  <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">View Customers</a></li>
                            <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> 
                        </ul> -->
                    </div>
                </div>
            </div>
        </div>


            <div class="row">
                <div class="col-sm-9">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            ADD
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#" data-value="promoter">Promoter</a>
                                            <a class="dropdown-item" href="#" data-value="creditor">Creditor</a>
                                            <a class="dropdown-item" href="#" data-value="debtor">Debtor</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Table for Other Contacts -->
                        <div class="card-body">
                        <table id="dataTables-example" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>SL No.</th> <!-- Serial Number Column -->
            <th>Name</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Contact Type</th>
            <th>Created On</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch only Promoter, Creditor, and Debtor from customer_master table and order by created_on in descending order
        $result = mysqli_query($conn, "SELECT id, customerName, mobile, email, contact_type, created_on FROM customer_master WHERE contact_type IN ('Promoter', 'Creditor', 'Debtor')");
        $serial_no = 1; // Initialize serial number counter
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$serial_no}</td> <!-- Display serial number -->
                    <td><a href='other_contact-details.php?id={$row['id']}'>{$row['customerName']}</a></td>
                    <td>{$row['mobile']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['contact_type']}</td>
                    <td>{$row['created_on']}</td>
                  </tr>";
            $serial_no++; // Increment serial number
        }
        ?>
    </tbody>
</table>




                        </div>
                    </div>
                </div>

                <!-- Right Panel showing Total Contacts -->
<?php

// Get total contacts count from customer_master
$query_total = "SELECT COUNT(*) as total FROM customer_master WHERE contact_type IN ('Promoter', 'Creditor', 'Debtor')";
$result_total = mysqli_query($conn, $query_total);
$row_total = mysqli_fetch_assoc($result_total);
$total_contacts = $row_total['total'];

// Get promoter count
$query_promoters = "SELECT COUNT(*) as promoters FROM customer_master WHERE contact_type = 'Promoter'";
$result_promoters = mysqli_query($conn, $query_promoters);
$row_promoters = mysqli_fetch_assoc($result_promoters);
$promoter_count = $row_promoters['promoters'];

// Get creditor count
$query_creditors = "SELECT COUNT(*) as creditors FROM customer_master WHERE contact_type = 'Creditor'";
$result_creditors = mysqli_query($conn, $query_creditors);
$row_creditors = mysqli_fetch_assoc($result_creditors);
$creditor_count = $row_creditors['creditors'];

// Get debtor count
$query_debtors = "SELECT COUNT(*) as debtors FROM customer_master WHERE contact_type = 'Debtor'";
$result_debtors = mysqli_query($conn, $query_debtors);
$row_debtors = mysqli_fetch_assoc($result_debtors);
$debtor_count = $row_debtors['debtors'];
?>


                <!-- Design for displaying the count in a single card -->
<div class="col-sm-3">
    <div class="card shadow-sm p-3">
        <ul class="list-unstyled">
            <li class="d-flex justify-content-between">
                <span><b>Total Contacts</b></span>
                <span class="text-primary"><b><?php echo $total_contacts; ?></b></span>
            </li>
            <hr>
            <li class="d-flex justify-content-between">
                <span>Promoters</span>
                <span class="text-success"><?php echo $promoter_count; ?></span>
            </li>
            <br>
            <li class="d-flex justify-content-between">
                <span>Creditors</span>
                <span class="text-success"><?php echo $creditor_count; ?></span>
            </li>
            <br>
            <li class="d-flex justify-content-between">
                <span>Debtors</span>
                <span class="text-danger"><?php echo $debtor_count; ?></span>
            </li>
        </ul>
    </div>
</div>
            </div>
        </div>
    </section>

    <!-- Modals for Adding Contacts -->

    <!-- Promoter Modal -->
    <!-- Promoter Modal -->
<div id="promoterModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Promoter</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST">
    <input type="hidden" name="contact_type" value="Promoter">
                <div class="modal-body">
                    <div class="row">
                        <!-- Title Dropdown -->
                        <div class="mb-1 col-lg-2">
                            <div class="did-floating-label-content">
                                <select id="title" name="title" class="did-floating-select modal-select" required>
                                    <option value="Mr.">Mr.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Ms.">Ms.</option>
                                </select>
                                <label for="title" class="did-floating-label">Title</label>
                            </div>
                        </div>
                        
                        <!-- Promoter Name -->
                        <div class="mb-1 col-lg-4">
                            <div class="did-floating-label-content">
                                <input type="text" id="name" name="name" class="did-floating-input modal-input" placeholder="" required>
                                <label for="name" class="did-floating-label">Promoter Name</label>
                            </div>
                        </div>

                       <!-- Citizenship Dropdown -->
                        <!-- Citizenship Dropdown with Country Names as Values -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <select id="citizenship" name="citizenship" class="did-floating-select modal-select"  placeholder="" required>
                                    <option value="India">India</option>
                                    <option value="Afghanistan">Afghanistan</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
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
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="Brunei">Brunei</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
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
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guinea-Bissau">Guinea-Bissau</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
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
                                    <option value="North Korea">North Korea</option>
                                    <option value="South Korea">South Korea</option>
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
                                    <option value="Macau">Macau</option>
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
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau">Palau</option>
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
                                <label for="citizenship" class="did-floating-label">Citizenship</label>
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="mobile" name="mobile" class="did-floating-input modal-input" placeholder="" required>
                                <label for="mobile" class="did-floating-label">Mobile</label>
                            </div>
                        </div>                       

                        <!-- Email -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="email" id="email" name="email" class="did-floating-input modal-input" placeholder="" required>
                                <label for="email" class="did-floating-label">Email</label>
                            </div>
                        </div>

                        <!-- PAN -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="pan" name="pan" class="did-floating-input modal-input" placeholder="">
                                <label for="pan" class="did-floating-label">PAN</label>
                            </div>
                        </div>

                        <!-- Aadhaar -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="aadhaar" name="aadhaar" class="did-floating-input modal-input" placeholder="">
                                <label for="aadhaar" class="did-floating-label">Enter Aadhaar Number, if available</label>
                            </div>
                        </div>

                        <!-- Date of Birth -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="date" id="dob" name="dob" class="did-floating-input modal-input" placeholder="" required>
                                <label for="dob" class="did-floating-label">Date of Birth (dd/mm/yyyy)</label>
                            </div>
                        </div>

                            

                        <!-- Designation -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="designation" name="designation" class="did-floating-input modal-input" placeholder="">
                                <label for="designation" class="did-floating-label">Designation</label>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="mb-1 col-lg-6">
                                <div class="did-floating-label-content">
                                <input type="text" id="phone" name="phone" class="did-floating-input modal-input" placeholder="" required>
                                <label for="phone" class="did-floating-label">Phone</label>
                            </div>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <select id="status" name="status" class="did-floating-select modal-select">
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <label for="status" class="did-floating-label">Status</label>
                            </div>
                        </div>
                        
                        <!-- Address Section -->
                        <h5 class="mt-3 mb-3 col-12">Address as per Residence Proof</h5>
                        
                        <!-- Address Line 1 -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="address1" name="address1" class="did-floating-input modal-input" placeholder="">
                                <label for="address1" class="did-floating-label">Address Line 1</label>
                            </div>
                        </div>

                        <!-- Address Line 2 -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="address2" name="address2" class="did-floating-input modal-input" placeholder="">
                                <label for="address2" class="did-floating-label">Address Line 2</label>
                            </div>
                        </div>

                        <!-- City -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="city" name="city" class="did-floating-input modal-input" placeholder="">
                                <label for="city" class="did-floating-label">City</label>
                            </div>
                        </div>

                        <!-- Pincode -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="pincode" name="pincode" class="did-floating-input modal-input" placeholder="">
                                <label for="pincode" class="did-floating-label">Pincode</label>
                            </div>
                        </div>

                        <!-- State -->
                        <!-- State Dropdown -->
                        <div class="mb-1 col-lg-6" id="stateDropdownPromoter" style="display:block;">
    <div class="did-floating-label-content">
    <select id="stateDropdownPromoterSelect" name="state_dropdown" class="did-floating-select modal-select">
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
                                    <option value="Tamil Nadu" selected>Tamil Nadu</option>
                                    <option value="Telangana">Telangana</option>
                                    <option value="Tripura">Tripura</option>
                                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                                    <option value="Uttarakhand">Uttarakhand</option>
                                    <option value="West Bengal">West Bengal</option>
                                    <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                    <option value="Chandigarh">Chandigarh</option>
                                    <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                                    <option value="Daman and Diu">Daman and Diu</option>
                                    <option value="Delhi">Delhi</option>
                                    <option value="Lakshadweep">Lakshadweep</option>
                                    <option value="Puducherry">Puducherry</option>
                                </select>
                                <label for="state" class="did-floating-label">State</label>
                            </div>
                        </div>

                        <!-- State Input for other countries -->
                       <!-- State Text Input for Other Countries -->
<div class="mb-1 col-lg-6" id="stateInputPromoter" style="display:none;">
    <div class="did-floating-label-content">
    <input type="text" id="stateInputPromoterText" name="state_input" class="did-floating-input modal-input" placeholder="Enter">
        <label for="state" class="did-floating-label">State</label>
    </div>
</div>
                        

                       <!-- Country Dropdown -->
                       <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <select id="countryPromoter" name="country" class="did-floating-select modal-select" required onchange="toggleStateField('Promoter')">
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
                                    <option value="Congo">Congo</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Cyprus">Cyprus</option>
                                    <option value="Czech Republic">Czech Republic</option>
                                    <option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option>
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
                                    <option value="India" selected>India</option>
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
                                    <option value="North Korea">North Korea</option>
                                    <option value="North Macedonia">North Macedonia</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau">Palau</option>
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
                                    <option value="South Korea">South Korea</option>
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
                                <label for="countryPromoter" class="did-floating-label">Country</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>



    <!-- Creditor Modal -->
<div id="creditorModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Creditor</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST">
            <input type="hidden" name="contact_type" value="Creditor">
                <div class="modal-body">
                    <div class="row">
                        <!-- Title Dropdown -->
                        <div class="mb-1 col-lg-2">
                            <div class="did-floating-label-content">
                                <select id="title" name="title" class="did-floating-select modal-select" required>
                                    <option value="Mr.">Mr.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Ms.">Ms.</option>
                                </select>
                                <label for="title" class="did-floating-label">Title</label>
                            </div>
                        </div>

                        <!-- Creditor Name -->
                        <div class="mb-1 col-lg-4">
                            <div class="did-floating-label-content">
                                <input type="text" id="name" name="name" class="did-floating-input modal-input" placeholder="" required>
                                <label for="name" class="did-floating-label">Creditor Name</label>
                            </div>
                        </div>

                        <!-- GSTIN -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="gstin" name="gstin" class="did-floating-input modal-input" placeholder="">
                                <label for="gstin" class="did-floating-label">GSTIN</label>
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="mobile" name="mobile" class="did-floating-input modal-input" placeholder="" required>
                                <label for="mobile" class="did-floating-label">Mobile</label>
                            </div>
                        </div>

                         <!-- Email -->
                         <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="email" id="email" name="email" class="did-floating-input modal-input" placeholder="" required>
                                <label for="email" class="did-floating-label">Email</label>
                            </div>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <select id="status" name="status" class="did-floating-select modal-select">
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <label for="status" class="did-floating-label">Status</label>
                            </div>
                        </div>

                       

                        <!-- Address Section Heading -->
                        <h5 class="mt-3 mb-3 col-12">Address as per Residence Proof</h5>

                        <!-- Address Line 1 -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="address1" name="address1" class="did-floating-input modal-input" placeholder="">
                                <label for="address1" class="did-floating-label">Address Line 1</label>
                            </div>
                        </div>

                        <!-- Address Line 2 -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="address2" name="address2" class="did-floating-input modal-input" placeholder="">
                                <label for="address2" class="did-floating-label">Address Line 2</label>
                            </div>
                        </div>

                        <!-- City -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="city" name="city" class="did-floating-input modal-input" placeholder="">
                                <label for="city" class="did-floating-label">City</label>
                            </div>
                        </div>

                         <!-- Pincode -->
                         <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="pincode" name="pincode" class="did-floating-input modal-input" placeholder="">
                                <label for="pincode" class="did-floating-label">Pincode</label>
                            </div>
                        </div>

                        <!-- State -->
                        <!-- State Dropdown -->
                        <!-- State Dropdown (for India) -->
                        <div class="mb-1 col-lg-6" id="stateDropdownCreditor" style="display:block;">
                                <div class="did-floating-label-content">
                                <select id="stateDropdownCreditorSelect" name="state_dropdown" class="did-floating-select modal-select">
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
                                    <option value="Tamil Nadu" selected>Tamil Nadu</option>
                                    <option value="Telangana">Telangana</option>
                                    <option value="Tripura">Tripura</option>
                                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                                    <option value="Uttarakhand">Uttarakhand</option>
                                    <option value="West Bengal">West Bengal</option>
                                    <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                    <option value="Chandigarh">Chandigarh</option>
                                    <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                                    <option value="Daman and Diu">Daman and Diu</option>
                                    <option value="Delhi">Delhi</option>
                                    <option value="Lakshadweep">Lakshadweep</option>
                                    <option value="Puducherry">Puducherry</option>
                                </select>
                                <label for="state" class="did-floating-label">State</label>
                            </div>
                        </div>

                       <!-- State Input (Other countries) -->
                       <!-- State Text Input for Other Countries -->
<div class="mb-1 col-lg-6" id="stateInputCreditor" style="display:none;">
    <div class="did-floating-label-content">
    <input type="text" id="stateInputCreditorText" name="state_input" class="did-floating-input modal-input" placeholder="Enter">
        <label for="state" class="did-floating-label">State</label>
    </div>
</div>
                        

                       <!-- Country Dropdown -->
                       <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <select id="countryCreditor" name="country" class="did-floating-select modal-select" required onchange="toggleStateField('Creditor')">
                                    <option value="India" selected>India</option>
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
                                    <option value="Congo">Congo</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Cyprus">Cyprus</option>
                                    <option value="Czech Republic">Czech Republic</option>
                                    <option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option>
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
                                    <option value="North Korea">North Korea</option>
                                    <option value="North Macedonia">North Macedonia</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau">Palau</option>
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
                                    <option value="South Korea">South Korea</option>
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
                                <label for="countryCreditor" class="did-floating-label">Country</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <!-- Debtor Modal -->
    <div id="debtorModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Debtor</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST">
            <input type="hidden" name="contact_type" value="Debtor">
                <div class="modal-body">
                    <div class="row">
                        <!-- Title Dropdown -->
                        <div class="mb-1 col-lg-2">
                            <div class="did-floating-label-content">
                                <select id="title" name="title" class="did-floating-select modal-select" required>
                                    <option value="Mr.">Mr.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Ms.">Ms.</option>
                                </select>
                                <label for="title" class="did-floating-label">Title</label>
                            </div>
                        </div>

                        <!-- Creditor Name -->
                        <div class="mb-1 col-lg-4">
                            <div class="did-floating-label-content">
                                <input type="text" id="name" name="name" class="did-floating-input modal-input" placeholder="" required>
                                <label for="name" class="did-floating-label">Debitor Name</label>
                            </div>
                        </div>

                        <!-- GSTIN -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="gstin" name="gstin" class="did-floating-input modal-input" placeholder="">
                                <label for="gstin" class="did-floating-label">GSTIN</label>
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="mobile" name="mobile" class="did-floating-input modal-input" placeholder="" required>
                                <label for="mobile" class="did-floating-label">Mobile</label>
                            </div>
                        </div>

                         <!-- Email -->
                         <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="email" id="email" name="email" class="did-floating-input modal-input" placeholder="" required>
                                <label for="email" class="did-floating-label">Email</label>
                            </div>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <select id="status" name="status" class="did-floating-select modal-select">
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <label for="status" class="did-floating-label">Status</label>
                            </div>
                        </div>

                       

                        <!-- Address Section Heading -->
                        <h5 class="mt-3 mb-3 col-12">Address as per Residence Proof</h5>

                        <!-- Address Line 1 -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="address1" name="address1" class="did-floating-input modal-input" placeholder="">
                                <label for="address1" class="did-floating-label">Address Line 1</label>
                            </div>
                        </div>

                        <!-- Address Line 2 -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="address2" name="address2" class="did-floating-input modal-input" placeholder="">
                                <label for="address2" class="did-floating-label">Address Line 2</label>
                            </div>
                        </div>

                        <!-- City -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="city" name="city" class="did-floating-input modal-input" placeholder="">
                                <label for="city" class="did-floating-label">City</label>
                            </div>
                        </div>

                         <!-- Pincode -->
                         <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="pincode" name="pincode" class="did-floating-input modal-input" placeholder="">
                                <label for="pincode" class="did-floating-label">Pincode</label>
                            </div>
                        </div>

                        <!-- State -->
                        <!-- State Dropdown -->
                        <!-- State Dropdown (for India) -->
                        <div class="mb-1 col-lg-6" id="stateDropdownDebitor" style="display:block;">
                                <div class="did-floating-label-content">
                                <select id="stateDropdownDebitorSelect" name="state_dropdown" class="did-floating-select modal-select">
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
                                    <option value="Tamil Nadu" selected>Tamil Nadu</option>
                                    <option value="Telangana">Telangana</option>
                                    <option value="Tripura">Tripura</option>
                                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                                    <option value="Uttarakhand">Uttarakhand</option>
                                    <option value="West Bengal">West Bengal</option>
                                    <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                    <option value="Chandigarh">Chandigarh</option>
                                    <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                                    <option value="Daman and Diu">Daman and Diu</option>
                                    <option value="Delhi">Delhi</option>
                                    <option value="Lakshadweep">Lakshadweep</option>
                                    <option value="Puducherry">Puducherry</option>
                                </select>
                                <label for="state" class="did-floating-label">State</label>
                            </div>
                        </div>

                       <!-- State Input (Other countries) -->
                       <div class="mb-1 col-lg-6" id="stateInputDebitor" style="display:none;">
    <div class="did-floating-label-content">
    <input type="text" id="stateInputDebitorText" name="state_input" class="did-floating-input modal-input" placeholder="Enter">
        <label for="state" class="did-floating-label">State</label>
    </div>
</div>
                        

                       <!-- Country Dropdown -->
                       <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <select id="countryDebitor" name="country" class="did-floating-select modal-select" required onchange="toggleStateField('Debitor')">
                                    <option value="India" selected>India</option>
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
                                    <option value="Congo">Congo</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Cyprus">Cyprus</option>
                                    <option value="Czech Republic">Czech Republic</option>
                                    <option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option>
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
                                    <option value="North Korea">North Korea</option>
                                    <option value="North Macedonia">North Macedonia</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau">Palau</option>
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
                                    <option value="South Korea">South Korea</option>
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
                                <label for="countryDebitor" class="did-floating-label">Country</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
</section>

    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            "order": [[5, "desc"]] // Sort by the "Created On" column (index 5) in descending order
        });
        $('.dataTables_length').addClass('bs-select');
    });
</script>
    <script type="text/javascript">
   $(document).ready(function () {
        $('.dropdown-item').click(function() {
            var selectedValue = $(this).data('value');
            if (selectedValue === "promoter") {
                $('#promoterModal').modal('show');
            } else if (selectedValue === "creditor") {
                $('#creditorModal').modal('show');
            } else if (selectedValue === "debtor") {
                $('#debtorModal').modal('show');
            }
        });
    });
</script>
<script>
// Function to toggle state field based on the selected country and modal type
function toggleStateField(modalType) {
    var countryField = document.getElementById('country' + modalType);
    var selectedCountry = countryField.value;
    var stateDropdown = document.getElementById('stateDropdown' + modalType);
    var stateInput = document.getElementById('stateInput' + modalType);

    if (selectedCountry === "India") {
        stateDropdown.style.display = "block";
        stateInput.style.display = "none";
    } else {
        stateDropdown.style.display = "none";
        stateInput.style.display = "block";
    }
}

document.addEventListener("DOMContentLoaded", function() {
    ['Promoter', 'Creditor', 'Debtor'].forEach(function(type) {
        toggleStateField(type); // Initial load
        document.getElementById('country' + type).addEventListener('change', function() {
            toggleStateField(type); // On country change
        });
    });
});

</script>

</body>
</html>
