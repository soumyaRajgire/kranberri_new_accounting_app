<?php
session_start();
if (!isset($_SESSION['LOG_IN'])) {
    header("Location:login.php");
} else {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
}
include("config.php");
?>

<?php
if (isset($_POST['submit'])) {
    include("config.php");
    $title = mysqli_escape_string($conn, $_POST["title"]);
    $customer_name = mysqli_escape_string($conn, $_POST["customer_name"]);
    $entity_type = mysqli_escape_string($conn, $_POST["entity_type"]);
    $mobile_number = mysqli_escape_string($conn, $_POST["mobile_number"]);
    $email = mysqli_escape_string($conn, $_POST["email"]);
    $gstin = mysqli_escape_string($conn, $_POST["gstin"]);
    $email = mysqli_escape_string($conn, $_POST["gst_reg_name"]);
    $business_name = mysqli_escape_string($conn, $_POST["business_name"]);
    $display_name = mysqli_escape_string($conn, $_POST["display_name"]);
    $phone_no = mysqli_escape_string($conn, $_POST["phone_no"]);
    $fax = mysqli_escape_string($conn, $_POST["fax"]);
    $fax = mysqli_escape_string($conn, $_POST["fax"]);
    $fax = mysqli_escape_string($conn, $_POST["fax"]);
    $billing_address = mysqli_escape_string($conn, $_POST["billing_address"]);
    $shipping_address = mysqli_escape_string($conn, $_POST["shipping_address"]);
    $account_no = mysqli_escape_string($conn, $_POST["account_no"]);
    $account_name = mysqli_escape_string($conn, $_POST["account_name"]);
    $bank_name = mysqli_escape_string($conn, $_POST["bank_name"]);
    $ifsc_code = mysqli_escape_string($conn, $_POST["ifsc_code"]);
    $branch_name = mysqli_escape_string($conn, $_POST["branch_name"]);
    $pan = mysqli_escape_string($conn, $_POST["pan"]);
    $tax = mysqli_escape_string($conn, $_POST["tax"]);
    $tds_slab_rate = mysqli_escape_string($conn, $_POST["tds_slab_rate"]);
    $currency = mysqli_escape_string($conn, $_POST["currency"]);
    $terms_of_payment = mysqli_escape_string($conn, $_POST["terms_of_payment"]);
    $created_by =  $_SESSION['name'];


    $sql = "INSERT INTO customer_master(
        title, customer_name, entity_type, mobile_number, email, gstin, gst_reg_name,
        business_name, display_name, phone_no, fax, billing_address, shipping_address,
        account_no, account_name, bank_name, ifsc_code, branch_name, pan, tax, tds_slab_rate,
        currency, terms_of_payment, createdBy
    ) VALUES (
        '$title', '$customer_name', '$entity_type', '$mobile_number', '$email', '$gstin', '$gst_reg_name',
        '$business_name', '$display_name', '$phone_no', '$fax', '$billing_address', '$shipping_address',
        '$account_no', '$account_name', '$bank_name', '$ifsc_code', '$branch_name', '$pan', '$tax', '$tds_slab_rate',
        '$currency', '$terms_of_payment', '$created_by'
    )";

    if ($conn->query($sql) === TRUE) {
?>
        <script>
            alert("Customer details Added Successfully");
            window.location = "view-customers.php";
        </script>
<?php
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>



<div id="addCustomersModal1" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Customers</h4>

                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>


            <div class="tabContainer">
                <div class="buttonContainer text-center">
                    <button onclick="showPanel(0,'#F5F5F5')" class="btn btn-secondary">Information</button>
                    <button onclick="showPanel(1,'#F5F5F5')" class="btn btn-secondary">Banking & Taxes</button>
                </div>


                <div class="modal-body">
                    <div class="tabPanel">
                        <form action="" method="post" class="mt-4">
                            <div class="row">
                                <div class="mb-3 col-lg-2">
                                    <label for="title" class="form-label">Title</label>
                                    <select id="title" name="title" class="form-select" style="width: 100%;height:35px;" required>
                                        <option value="" disabled selected>Select a Title</option>
                                        <option value="mr">Mr.</option>
                                        <option value="mrs">Mrs.</option>
                                        <option value="miss">Miss</option>
                                        <option value="ms">Ms.</option>
                                        <option value="dr">Dr.</option>
                                    </select>
                                </div>

                                <div class="mb-3 col-lg-5">
                                    <label for="customer_name" class="form-label">Customer Name</label>
                                    <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="Customer Name" required>
                                </div>


                                <div class="mb-3 col-lg-5">
                                    <label for="entity_type" class="form-label d-block">Entity Type</label>
                                    <div class="d-flex">
                                        <select id="entity_type" name="entity_type" class="form-select flex-grow-1" style="width: 100%;height:35px;" required>
                                            <option value="" disabled selected>Select an Entity Type</option>
                                            <option value="individual">Individual</option>
                                            <option value="company">Company</option>
                                            <option value="organization">Organization</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="mb-3 col-lg-6">
                                    <label for="mobile_number" class="form-label">Mobile Number</label>
                                    <input type="tel" id="mobile_number" name="mobile_number" class="form-control" placeholder="Mobile Number" required>
                                </div>

                                <div class="mb-3 col-lg-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                                </div>

                                <div class="mb-3 col-lg-6">
                                    <label for="gstin" class="form-label"> GSTIN</label>
                                    <input type="text" id="gstin" name="gstin" class="form-control" placeholder="Customer GSTIN" required>
                                </div>

                                <div class="mb-3 col-lg-6">
                                    <label for="gst_reg_name" class="form-label">Customer GST Registered Name</label>
                                    <input type="text" id="gst_reg_name" name="gst_reg_name" class="form-control" placeholder="Customer GST Registered Name" required>
                                </div>

                                <div class="mb-3 col-lg-6">
                                    <label for="business_name" class="form-label">Business Name</label>
                                    <input type="text" id="business_name" name="business_name" class="form-control" placeholder="Business Name" required>
                                </div>

                                <div class="mb-3 col-lg-6">
                                    <label for="display_name" class="form-label">Display Name</label>
                                    <input type="text" id="display_name" name="display_name" class="form-control" placeholder="Display Name" required>
                                </div>


                                <div class="mb-3 col-lg-6">
                                    <label for="phone_no" class="form-label">Phone Number</label>
                                    <input type="tel" id="phone_no" name="phone_no" class="form-control" placeholder="Phone Number" required>
                                </div>

                                <div class="mb-3 col-lg-6">
                                    <label for="fax" class="form-label">Fax</label>
                                    <input type="tel" id="fax" name="fax" class="form-control" placeholder="Fax" required>
                                </div>

                    



                    </div>
                    <div class="tabContainer">
                        <div class="buttonContainer text-center">
                            <button class="btn btn-secondary">Billing Address</button>

                        </div>


                        <div class="modal-body">




                            <form action="" method="post" class="mt-4">
                                <div class="row">
                                    <div class="mb-3 col-lg-6">
                                        <label for="address_line1" class="form-label">Address Line 1</label>
                                        <input type="text" id="address_line1" name="address_line1" class="form-control" placeholder="Address Line 1" required>
                                    </div>

                                    <div class="mb-3 col-lg-6">
                                        <label for="address_line2" class="form-label">Address Line 2</label>
                                        <input type="text" id="address_line2" name="address_line2" class="form-control" placeholder="Address Line 2" required>
                                    </div>

                                    <div class="mb-3 col-lg-6">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" id="city" name="city" class="form-control" placeholder="City" required>
                                    </div>

                                    <div class="mb-3 col-lg-6">
                                        <label for="pin_code" class="form-label">Pin Code</label>
                                        <input type="text" id="pin_code" name="pin_code" class="form-control" placeholder="Pin Code" required>
                                    </div>

                                    <div class="mb-3 col-lg-6">
                                        <label for="state" class="form-label">State</label>
                                        <select id="state" name="state" class="form-select" style="width: 100%;height:35px;" required>
                                            <option value="" disabled selected>Select a State</option>
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
                                    </div>




                                    <div class="mb-3 col-lg-6">
                                        <label for="country" class="form-label">Country</label>
                                        <select id="country" name="country" class="form-select" style="width: 100%;height:35px;" required>
                                            <option value="" disabled selected>Select a Country</option>
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
                                    </div>

                                    <div class="mb-3 col-lg-6">
                                        <label for="branch_name" class="form-label">Branch Name</label>
                                        <input type="text" id="branch_name" name="branch_name" class="form-control" placeholder="Branch Name" required>
                                    </div>

                                    <div class="mb-3 col-lg-6">
                                        <label for="gstin" class="form-label">GSTIN</label>
                                        <input type="text" id="gstin" name="gstin" class="form-control" placeholder="GSTIN" required>
                                    </div>


                                </div>
                         
                        </div>
                    </div>



                    <div class="text-center">
                        <button class="btn btn-secondary ">Shipping Address</button>
                        <div class="mb-3 col-lg-6 text-center" style="padding-left:80px;">
                            <center><label class="form-check-label">
                                    <input type="checkbox" id="checkbox_id" name="checkbox_name" class="form-check-input" checked>
                                    Shipping address is same as billing address
                                </label></center>
                        </div>

                    </div>

                    <!-- <form action="" method="post" class="mt-4" id="addressForm"> -->
                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="address_line1" class="form-label">Address Line 1</label>
                                <input type="text" id="address_line1" name="address_line1" class="form-control" placeholder="Address Line 1" required>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="address_line2" class="form-label">Address Line 2</label>
                                <input type="text" id="address_line2" name="address_line2" class="form-control" placeholder="Address Line 2" required>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="city" class="form-label">City</label>
                                <input type="text" id="city" name="city" class="form-control" placeholder="City" required>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="pin_code" class="form-label">Pin Code</label>
                                <input type="text" id="pin_code" name="pin_code" class="form-control" placeholder="Pin Code" required>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="state" class="form-label">State</label>
                                <select id="state" name="state" class="form-select" style="width: 100%;height:35px;" required>
                                    <option value="" disabled selected>Select a State</option>
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
                            </div>




                            <div class="mb-3 col-lg-6">
                                <label for="country" class="form-label">Country</label>
                                <select id="country" name="country" class="form-select" style="width: 100%;height:35px;" required>
                                    <option value="" disabled selected>Select a Country</option>
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
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="branch_name" class="form-label">Branch Name</label>
                                <input type="text" id="branch_name" name="branch_name" class="form-control" placeholder="Branch Name" required>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="gstin" class="form-label">GSTIN</label>
                                <input type="text" id="gstin" name="gstin" class="form-control" placeholder="GSTIN" required>
                            </div>


                        </div>
                    
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
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
                        });
                    </script>



                </div>
            </div>




            <div class="modal-body">
                <div class="tabPanel">



                    <form action="" method="post" class="mt-4">
                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="account_number" class="form-label">Account Number</label>
                                <input type="text" id="account_number" name="account_number" class="form-control" placeholder="Account Number" required>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="account_name" class="form-label">Account Name</label>
                                <input type="text" id="account_name" name="account_name" class="form-control" placeholder="Account Name" required>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="bank_name" class="form-label">Bank Name</label>
                                <select id="bank_name" name="bank_name" class="form-select" style="width: 100%; height: 35px;" required>
                                    <option value="" disabled selected>Select a Bank Name</option>
                                    <option value="bank1">Bank of America</option>
                                    <option value="bank2">JP Morgan Chase</option>
                                    <option value="bank3">Wells Fargo</option>
                                    <option value="bank4">Citibank</option>
                                    <option value="bank5">HSBC</option>
                                    <option value="bank6">Barclays</option>
                                    <option value="bank7">UBS</option>
                                    <option value="bank8">Standard Chartered</option>
                                    <option value="bank9">Deutsche Bank</option>

                                </select>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="ifsc_code" class="form-label">IFSC Code</label>
                                <input type="text" id="ifsc_code" name="ifsc_code" class="form-control" placeholder="IFSC Code" required>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="account_type" class="form-label">Account Type</label>
                                <select id="account_type" name="account_type" class="form-select" style="width: 100%; height: 35px;" required>
                                    <option value="" disabled selected>Select an Account Type</option>
                                    <option value="savings">Savings</option>
                                    <option value="checking">Checking</option>
                                    <option value="credit">Credit</option>
                                </select>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="branch_name" class="form-label">Branch Name</label>
                                <input type="text" id="branch_name" name="branch_name" class="form-control" placeholder="Branch Name" required>
                            </div>

                            <h2 class="fs-4 p-4 col-lg-12">Tax information</h2>

                            <div class="mb-3 col-lg-6">
                                <label for="pan" class="form-label">PAN</label>
                                <input type="text" id="pan" name="pan" class="form-control" placeholder="PAN" required>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="tax" class="form-label">Tax</label>
                                <input type="text" id="tax" name="tax" class="form-control" placeholder="Tax" required>
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="tds_slab_rate" class="form-label">TDS Slab Ratio</label>
                                <div class="d-flex">
                                    <select id="tds_slab_rate" name="tds_slab_rate" class="form-select" required style="width: 100%;height: 35px">
                                        <option value="" disabled selected>Select TDS Slab Ratio</option>
                                        <option value="10%">10%</option>
                                        <option value="15%">15%</option>
                                        <option value="20%">20%</option>
                                    </select>
                                </div>
                            </div>


                            <div class="mb-3 col-lg-6">
                                <label for="currency" class="form-label">Currency</label>
                                <select id="currency" name="currency" class="form-select" style="width: 100%; height: 35px;" required>
                                    <option value="" disabled selected>Select a Currency</option>
                                    <option value="indian rupee">Indian Rupee</option>
                                    <option value="usd">USD</option>
                                    <option value="eur">EUR</option>
                                    <option value="gbp">GBP</option>

                                </select>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="terms_of_payment" class="form-label">Terms of Payment</label>
                                <select id="terms_of_payment" name="terms_of_payment" class="form-select" style="width: 100%; height: 35px;" required>
                                    <option value="" disabled selected>Select Terms of Payment</option>
                                    <option value="net_30">Net 30 Days</option>
                                    <option value="net_60">Net 60 Days</option>
                                    <option value="net_90">Net 90 Days</option>
                                    <option value="custom">Custom Payment Terms</option>
                                </select>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="reserved_charge" class="form-label">Apply Reserved Charge by Default?</label>
                                <select id="reserved_charge" name="reserved_charge" class="form-select" style="width: 100%; height: 35px;" required>
                                    <option value="" disabled selected>Select an option</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="applicability" class="form-label">Applicability</label>
                                <select id="applicability" name="applicability" class="form-select" style="width: 100%; height: 35px;" required>
                                    <option value="" disabled selected>Select an Option</option>
                                    <option value="applicable">Applicable</option>
                                    <option value="not_applicable">Not Applicable</option>
                                </select>
                            </div>






                        </div>
                </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>

            </form>

        </div>
    </div>
</div>

<script>
    window.onload = function() {
        showPanel(0, '#F5F5F5');
    };
</script>
<script src="js/myscript.js"></script>