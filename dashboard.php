<!DOCTYPE html>
<?php
session_start(); 
// Display all errors during development
ini_set('display_errors', 1);       // Enable displaying errors
ini_set('display_startup_errors', 1); // Enable startup errors
error_reporting(E_ALL);      
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
       // Report all errors

?>  
 

 <html lang="en">
<head>
    <title>iiiQbets</title>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <?php include("header_link.php");?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" ></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<!-- Your JavaScript code -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</head>
<body class="">
    <!-- [ Pre-loader ] start -->
     
     <?php //include("menu.php");?>
        <header class="navbar pcoded-header navbar-expand-lg navbar-light header-dark">
        
            
                <div class="m-header">
                    <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
                    <a href="#!" class="b-brand">
                        <!-- ========   change your logo hear   ============ -->
                        <img src="assets/images/logo.png" alt="" class="logo" width="87px">
                        <img src="assets/images/logo-icon.png" alt="" class="logo-thumb">
                    </a>
                    <a href="#!" class="mob-toggler">
                        <i class="feather icon-more-vertical"></i>
                    </a>
                </div>
                <div class="collapse navbar-collapse">
           
                    <ul class="navbar-nav ml-auto">
                   
                        <li>
                            <div class="dropdown drp-user">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="feather icon-user"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right profile-notification">
                                    <div class="pro-head">

                                         <?php
                 $sql="select * from user_login";
                  $result=$conn->query($sql);

             if($result->num_rows>0)
                {
            if($row = mysqli_fetch_assoc($result)) 
                {
                                    ?>
                                        <img src="<?php echo $row["logoimage"];?>" class="img-radius" alt="User-Profile-Image">
                                        <span><?php echo $row["name"];?></span>

                                        <?php
                                        }
                                    }
                                        ?>
                                        <!-- <a href="auth-signin.html" class="dud-logout" title="Logout">
                                            <i class="feather icon-log-out"></i>
                                        </a> -->
                                    </div>
                                    <ul class="pro-body">
                                        <li><a href="profile.php" class="dropdown-item"><i class="feather icon-user"></i> Profile</a></li>
                                        <!-- <li><a href="email_inbox.html" class="dropdown-item"><i class="feather icon-mail"></i> My Messages</a></li> -->
                                        <li><a href="manage-business.php" class="dropdown-item"><i class="feather icon-settings"></i> Settings</a></li>
                                        <?php
                                        if($_SESSION['role'] === "superadmin")
                                        {
                                        ?>
                                        <li><a href="dashboard.php" class="dropdown-item"><i class="feather icon-arrows-alt"></i> Switch Branch</a></li>
                                        <?php
                                        }
                                        ?>
                                        <li><a href="logout.php" class="dropdown-item"><i class="feather icon-lock"></i> Log Out</a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                
            
    </header>
    
    <!-- [ Header ] end -->
    

<!-- [ Main Content ] start -->
<!-- <section class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="tab-content">
                    <div id="rev_chart" class="tab-pane fade show active" style="border: 1px solid lightgray;box-shadow: 1px 2px 5px 1px lightgray;">
                        <div class="row p-4">

                      <?php
                      $sql = "SELECT b.business_name, br.branch_name
        FROM add_business AS b
        LEFT JOIN add_branch AS br ON b.business_id = br.business_id";
$result = $conn->query($sql);
  if ($result->num_rows > 0) {
                                    // Output each row
                                    while ($row = $result->fetch_assoc()) {
                                        ?><div class="col-md-7"><h5><?php echo $row["business_name"] ?></h5></div><div class="col-md-3"><a href="" class="btn btn-success">Ledger</a></div>
                                        <?php
                                    }
                                } else {
                                    echo "<li>No businesses or branches found.</li>";
                                }
                      ?>      

                        </div>
                    </div>
                </div>
            </div>   
        </div>
    </div>
</section>  -->   

<section class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="row">
            <div class="col-lg-12 col-md-12">

                <div class="tab-content">

                    <div id="rev_chart" class="tab-pane fade show active" style="border: 1px solid lightgray;box-shadow: 1px 2px 5px 1px lightgray;">

                        <div class=" p-4">
 <a href="#addBranchModal" data-toggle="modal" class="hover-effect" data-toggle="tooltip" 
   title="Add a New Branch" style="margin-left: 635px; margin-top: -5px;"><i class="feather icon-plus" style="font-weight:bold;font-size:20px;"></i></a>
<?php
// Include config file
// include_once("config.php");
// session_start();

// Retrieve user data from session
$user_id = $_SESSION['id'];

// Query to fetch business and branch data for the logged-in user
 $sql = "SELECT b.business_id, b.business_name, br.branch_id, br.branch_name,br.GST
        FROM add_business AS b
        LEFT JOIN add_branch AS br ON b.business_id = br.business_id
        WHERE b.business_id = {$_SESSION['business_id']}";
        
$result = $conn->query($sql);

$businessData = [];
$branches = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $businessData['business_id'] = $row['business_id'];
        $businessData['business_name'] = $row['business_name'];
        
        if ($row['branch_id']) {
            $branches[] = ['branch_id' => $row['branch_id'], 'branch_name' => $row['branch_name'],'GST' => $row['GST']];
        }
    }
}

// If only one branch, set it as default session branch
if (count($branches) == 1) {
    $_SESSION['branch_id'] = $branches[0]['branch_id'];
}
?>
<div class=" business-block">
    <!-- Business Name Button -->
    <div class="row mb-2">
    <div class="col-md-7">
        <button class="btn btn-link" style="color:black;" onclick="toggleBranchDropdown()">
            <p><?php echo ( $businessData['business_name']); ?></p>
        </button>
    </div>

    <!-- Business Ledger Button -->
    <!--<div class="col-md-3">
        <form method="post" action="set_session.php">
            <input type="hidden" name="business_id" value="<?php echo $businessData['business_id']; ?>">
            <button type="submit" name="ledger_all" class="btn btn-success">Business Ledger (All Branches)</button>
        </form>
    </div>-->
</div>


    <!-- Branch Dropdown -->
    <?php if (count($branches) >= 1) { ?>
        <div id="branchDropdown" style="display: none;" class="col-md-12 branches">
            <?php foreach ($branches as $branch) { ?>
                <div class="row branch-item">
                    <!-- <form method="post" action="set_session.php"> -->
                        <!-- <input type="hidden" name="business_id" value="<?php echo $businessData['business_id']; ?>"> -->
                        <!-- <input type="hidden" name="branch_id" value="<?php echo $branch['branch_id']; ?>"> -->
                        <div class="col-md-7">
                        <h5> <?php echo htmlspecialchars($branch['branch_name']); ?></h5>
                    </div>
                    <!-- </form> -->
                    <!-- Branch Ledger Button -->
                    <div class="col-md-3">
                    <form method="post" action="set_session.php">
                        <input type="hidden" name="business_id" value="<?php echo $businessData['business_id']; ?>">
                        <input type="hidden" name="branch_id" value="<?php echo $branch['branch_id']; ?>">
                          <input type="hidden" name="sel_gstin" value="<?php echo $branch['GST']; ?>">
                        <button type="submit" name="ledger_branch" class="btn btn-primary">Branch Ledger</button>
                    </form>
                </div>
                </div>
                <hr/>
            <?php } ?>
        </div>

    <?php } ?>
</div>

<script>
function toggleBranchDropdown() {
    var dropdown = document.getElementById("branchDropdown");
    dropdown.style.display = dropdown.style.display === "none" ? "block" : "none";
}
</script>


                        </div>
                    </div>
                </div>
            </div>   
        </div>
    </div>
</section>
<div class="modal" id="addBranchModal" tabindex="-1" role="dialog" style="margin-top: 28px;">
    <div class="modal-dialog" role="document" style="max-width: 800px;">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <!-- Tabs navigation -->
                <ul class="nav nav-tabs" id="Tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="gstin-tab" data-toggle="tab" href="#gstintab" role="tab" aria-controls="gstin" aria-selected="true">GSTIN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="false">Information</a>
                    </li>
                </ul>

                <!-- Single Form for Both Tabs -->
                <form id="addBranchForm" method="POST" action="branchdb.php">
                    <div class="tab-content mt-3" id="myTabContent">
                        <!-- GSTIN Tab -->
                        <div class="tab-pane fade show active" id="gstintab" role="tabpanel" aria-labelledby="gstin-tab">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="branch_name">Branch Name<span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="Enter Branch Name" required>
                                     <input type="text" class="form-control" id="additional_business_name" name="additional_business_name"  hidden>
                                       <input type="text" class="form-control" id="b_business_name" name="b_business_name"  hidden>
                                </div>
                                <div class="form-group col-md-6">
    <label for="email">Email<span style="color: red;">*</span></label>
    <input type="text" class="form-control" id="email1" name="bemail" placeholder="Enter Email" required>
</div>
                                <div class="form-group col-md-6">
                                    <label for="gst">GSTIN</label>
                                    <input type="text" class="form-control" id="gst" name="gst" placeholder="Enter GSTIN" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="address_line1">Address Line 1 <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="branch_address_line1" name="branch_address_line1" placeholder="Enter address line 1" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="address_line2">Address Line 2 <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="branch_address_line2" name="branch_address_line2" placeholder="Enter address line 2" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="city">City <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="Enter city" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="pincode">Pincode <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Enter pincode" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="state">State <span style="color: red;">*</span></label>
                                    <select class="form-control" id="state" name="state" required>
                                        <option value="">Select State</option>
                                    
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
                                        <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                        <option value="Chandigarh">Chandigarh</option>
                                        <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                                        <option value="Daman and Diu">Daman and Diu</option>
                                        <option value="Lakshadweep">Lakshadweep</option>
                                        <option value="Delhi">Delhi</option>
                                        <option value="Puducherry">Puducherry</option>
                                  </select>
                                   <input type="text" class="form-control" id="branch_state_code" name="branch_state_code" hidden>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="country">Country <span style="color: red;">*</span></label>
                                    <!-- <input type="text" class="form-control" id="country" name="country" placeholder="Enter country" required> -->
                                             <select id="country" name="country" class="form-control" required>
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
                                </div>
                            </div>
                            <div class="form-row">
                            <div class="form-group col-md-6">
                                    <label for="billing_scheme">Select GST Filing Scheme</label>
                                    <select class="form-control" id="billing_scheme" name="billing_scheme" >
                                        <option value="">Select Scheme</option>
                                        <option value="QRMP-Quaterly Filing">QRMP-Quaterly Filing</option>
                                        <option value="Regular">Regular</option>
                                    </select>
                            </div>
                            </div>
                       
                    </div>

                      <!-- Information Tab -->
                      <div class="tab-pane fade" id="info" role="tabpanel" aria-labelledby="info-tab">
                            <div class="form-row">
                            <div class="form-group col-md-6">
    <label for="office_email">Office Email<span style="color: red;">*</span></label>
    <input type="text" class="form-control" id="office_email" name="office_email" placeholder="Enter Office Email" >
</div>
                                <div class="form-group col-md-6">
                                    <label for="phone_number">Office Telephone Number</label>
                                    <input type="text" class="form-control" id="phone_number1" name="phone_number1" placeholder="Enter Office Number" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                <label for="nature_of_premises">Nature of Premises</label>
                                    <select class="form-control" id="nature_of_premises" name="nature_of_premises" >
                                        <option value="Nature of Premises">Select Nature of Premises</option>
                                        <option value="Leased">Leased</option>
                                        <option value="Rented">Rented</option>
                                        <option value="Consent">Consent</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nature_of_business">Select Nature of Bussiness</label>
                                    <select class="form-control" id="nature_of_business" name="nature_of_business">
                                        <option value="">Select Nature of Bussiness</option>
                                        <option value="Wholesale Business">Wholesale Business</option>
                                        <option value="Retail Business">Retail Business</option>
                                        <option value="Warehouse/Depot">Warehouse/Depot</option>
                                        <option value="Bonded Warehouse">Bonded Warehouse</option>
                                        <option value="Supplier of services">Supplier of services</option>
                                        <option value="Office/Sale Office">Office/Sale Office</option>
                                        <option value="Leasing Business">Leasing Business</option>
                                        <option value="Recipient of goods or services">Recipient of goods or services</option>
                                        <option value="EOU/ STP/ EHTP">EOU/ STP/ EHTP</option>
                                        <option value="Works Contract">Works Contract</option>
                                        <option value="Export">Export</option>
                                        <option value="Import">Import</option>
                                        <option value="Others">Others</option>
                                    </select>                                  
                                </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                    <input type="text" class="form-control" id="taluka" name="taluka" placeholder="Taluka/Block">
                                    </div>
                                    <!-- <div class="form-group col-md-6">
                                       <a href="#add-map" id="locval" data-target="#m_modal_5" data-toggle="modal" class="form-control location">Latitude and Longitude</a>
                                       <input type="hidden" name="location">
                                    </div> -->
                                </div>
                                <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="Active">Active</option>
                                        <option value="Disabled">Disabled</option>
                                    </select>
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
</div>
<script src="assets/js/stateMapping.js"></script>
<script>

    document.getElementById('gst').addEventListener('blur', function () {
        const gstin = this.value;

        if (gstin.length > 0) {
            fetch('get_gst_details.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `gstin=${gstin}`,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Populate fields dynamically
                        document.getElementById('branch_name').value = data.LegalName || '';
                        document.getElementById('b_business_name').value = data.TradeName || '';
                        document.getElementById('additional_business_name').value = data.TradeName || '';
                        // document.getElementById('display_name').value = data.LegalName || '';
                        document.getElementById('branch_address_line1').value = data.AddrBno + "," + data.AddrBnm || '';
                        document.getElementById('branch_address_line2').value = data.AddrSt || '';
                        document.getElementById('city').value = data.AddrLoc || '';
                        document.getElementById('pincode').value = data.AddrPncd || '';
                        document.getElementById('branch_state_code').value = data.StateCode || '';
                        console.log(data);

                          const stateName = stateMapping[data.StateCode] || '';
                    document.getElementById('state').value = stateName;
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching GSTIN details:', error);
                    alert('An error occurred. Please try again.');
                });
        }
    });  


</script>

<script>
  $(document).ready(function(){
    $('#Tabs a').on('click', function (e) {
      e.preventDefault();
      $(this).tab('show');
      
      // Update the URL hash with the tab ID
      var tabId = $(this).attr('href');
      window.location.hash = tabId;
    });

    // Check if there's a hash in the URL on page load
    if (window.location.hash) {
      // Show the tab based on the hash in the URL
      $('#myTabs a[href="' + window.location.hash + '"]').tab('show');
    }
  });
</script>

    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>
</body>
</html>