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

// Get the contact ID from the URL
$contact_id = $_GET['id'];

// Fetch the contact details by joining customer_master and address_master tables
$query = "SELECT cm.id, cm.contact_type, cm.title, cm.customerName AS name, cm.citizenship, cm.mobile, cm.email, 
                 cm.pan, cm.aadhaar, cm.dob, cm.entityType AS designation, cm.phone_no AS phone, cm.gstin, 
                 cm.status, am.s_address_line1 AS address1, am.s_address_line2 AS address2, am.s_city AS city, 
                 am.s_state AS state, am.s_Pincode AS pincode, am.s_country AS country, cm.created_on, cm.updated_on
          FROM customer_master AS cm
          LEFT JOIN address_master AS am ON cm.id = am.customer_master_id
          WHERE cm.id = '$contact_id'";

$result = mysqli_query($conn, $query);
$contact = mysqli_fetch_assoc($result);

if (!$contact) {
    echo "No contact found.";
    exit;
}

// Array to map fields to display labels
$field_labels = [
    'contact_type' => 'Contact Type',
    'title' => 'Title',
    'name' => 'Name',
    'citizenship' => 'Citizenship',
    'mobile' => 'Mobile',
    'email' => 'Email',
    'pan' => 'PAN',
    'aadhaar' => 'Aadhaar',
    'dob' => 'Date of Birth',
    'designation' => 'Designation',
    'phone' => 'Phone',
    'gstin' => 'GSTIN',
    'status' => 'Status',
    'address1' => 'Address Line 1',
    'address2' => 'Address Line 2',
    'city' => 'City',
    'state' => 'State',
    'pincode' => 'Pincode',
    'country' => 'Country',
    'created_on' => 'Created On',
    'updated_on' => 'Updated On'
];
?>
<?php
$states = [
    "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh",
    "Goa", "Gujarat", "Haryana", "Himachal Pradesh", "Jharkhand",
    "Karnataka", "Kerala", "Madhya Pradesh", "Maharashtra", "Manipur",
    "Meghalaya", "Mizoram", "Nagaland", "Odisha", "Punjab",
    "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura",
    "Uttar Pradesh", "Uttarakhand", "West Bengal", "Andaman and Nicobar Islands",
    "Chandigarh", "Dadra and Nagar Haveli", "Daman and Diu", "Delhi", "Lakshadweep", "Puducherry"
];

$countries = [
    "India", "Afghanistan", "Albania", "Algeria", "Andorra", "Angola",
    "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria",
    "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus",
    "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina",
    "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi",
    "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic",
    // Add additional countries as needed
];
?>
<html lang="en">
<head>
    <title>iiiQbets</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
</head>

<body>
    <!-- [ Pre-loader ] start -->
    <?php include("menu.php");?>
    <!-- [ Header ] end -->

<!-- [ Main Content ] start -->
<section class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><strong><?php echo $contact['name']; ?></strong></h4>
                        <!-- Options Dropdown -->
                        <div class="dropdown">
    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Options
    </button>
    <div class="dropdown-menu">
    <button class="dropdown-item" onclick="openEditModal('<?php echo $contact['contact_type']; ?>')">Edit</button>
    <button class="dropdown-item" onclick="confirmDelete('<?php echo $contact['id']; ?>', '<?php echo $contact['contact_type']; ?>')">Delete</button>
</div>

</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow-sm p-3">
                    <ul class="list-unstyled">
                    <?php
// Loop through each field and display if not empty
foreach ($contact as $field => $value) {
    if (!empty($value) && isset($field_labels[$field])) {
        echo "<li><strong>{$field_labels[$field]}:</strong> $value</li><br>";
    }
}
?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm p-3">
                    <p><strong style="margin-right: 130px;">IIIQBETS</strong> <?php echo $contact['created_on']; ?></p>
                    <hr>
                    <p><strong>IIIQBETS</strong> has Created <?php echo $contact['contact_type']; ?> <b><?php echo $contact['name']; ?></b></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <!-- Edit Promoter Modal -->
<div id="editPromoterModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Promoter</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="update_other_contacts.php">
                <input type="hidden" name="contact_type" value="Promoter">
                <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                <input type="hidden" name="promoter_id" value="<?php echo $contact['id']; ?>">
                <div class="modal-body">
                    <div class="row">
                        <!-- Title Dropdown -->
                        <div class="mb-1 col-lg-2">
                            <div class="did-floating-label-content">
                                <select id="editTitle" name="title" class="did-floating-select modal-select" required>
                                    <option value="Mr." <?php if($contact['title'] == 'Mr.') echo 'selected'; ?>>Mr.</option>
                                    <option value="Mrs." <?php if($contact['title'] == 'Mrs.') echo 'selected'; ?>>Mrs.</option>
                                    <option value="Ms." <?php if($contact['title'] == 'Ms.') echo 'selected'; ?>>Ms.</option>
                                </select>
                                <label for="editTitle" class="did-floating-label">Title</label>
                            </div>
                        </div>
                        
                        <!-- Promoter Name -->
                        <div class="mb-1 col-lg-4">
                            <div class="did-floating-label-content">
                                <input type="text" id="editName" name="name" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['name']); ?>" required>
                                <label for="editName" class="did-floating-label">Promoter Name</label>
                            </div>
                        </div>

                        <!-- Citizenship Dropdown -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                            <select id="editCitizenship" name="citizenship" class="did-floating-select modal-select" required>
            <?php
            // Array of country options
            $countries = [
                "India", "Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", 
                "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", 
                "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", 
                "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", 
                "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", 
                "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", 
                "Congo", "Costa Rica", "Côte d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", 
                "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", 
                "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", "Finland", "France", 
                "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guam", "Guatemala", 
                "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hong Kong", "Hungary", "Iceland", 
                "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", 
                "Kazakhstan", "Kenya", "Kiribati", "North Korea", "South Korea", "Kuwait", "Kyrgyzstan", 
                "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", 
                "Luxembourg", "Macau", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", 
                "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", 
                "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", 
                "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Norway", "Oman", "Pakistan", 
                "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", 
                "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", 
                "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", 
                "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", 
                "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Sudan", "Spain", "Sri Lanka", 
                "Sudan", "Suriname", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", 
                "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", 
                "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", 
                "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", 
                "Yemen", "Zambia", "Zimbabwe"
            ];

            // Generate option elements with the selected attribute if it matches $contact['citizenship']
            foreach ($countries as $country) {
                $selected = ($country == $contact['citizenship']) ? 'selected' : '';
                echo "<option value=\"$country\" $selected>$country</option>";
            }
            ?>
        </select>
        <label for="editCitizenship" class="did-floating-label">Citizenship</label>
    </div>
                        </div>

                        <!-- Mobile -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="editMobile" name="mobile" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['mobile']); ?>" required>
                                <label for="editMobile" class="did-floating-label">Mobile</label>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="email" id="editEmail" name="email" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['email']); ?>" required>
                                <label for="editEmail" class="did-floating-label">Email</label>
                            </div>
                        </div>
                    <!-- PAN -->
                    <div class="mb-1 col-lg-6">
                        <div class="did-floating-label-content">
                            <input type="text" id="pan" name="pan" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['pan']); ?>" placeholder="">
                            <label for="pan" class="did-floating-label">PAN</label>
                        </div>
                    </div>

                    <!-- Aadhaar -->
                    <div class="mb-1 col-lg-6">
                        <div class="did-floating-label-content">
                            <input type="text" id="aadhaar" name="aadhaar" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['aadhaar']); ?>" placeholder="">
                            <label for="aadhaar" class="did-floating-label">Enter Aadhaar Number, if available</label>
                        </div>
                    </div>

                    <!-- Date of Birth -->
                    <div class="mb-1 col-lg-6">
                        <div class="did-floating-label-content">
                            <input type="date" id="dob" name="dob" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['dob']); ?>" required>
                            <label for="dob" class="did-floating-label">Date of Birth (dd/mm/yyyy)</label>
                        </div>
                    </div>

                    <!-- Designation -->
                    <div class="mb-1 col-lg-6">
                        <div class="did-floating-label-content">
                            <input type="text" id="designation" name="designation" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['designation']); ?>" placeholder="">
                            <label for="designation" class="did-floating-label">Designation</label>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="mb-1 col-lg-6">
                        <div class="did-floating-label-content">
                            <input type="text" id="phone" name="phone" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['phone']); ?>" placeholder="" required>
                            <label for="phone" class="did-floating-label">Phone</label>
                        </div>
                    </div>

                    <!-- Status Dropdown -->
                    <div class="mb-1 col-lg-6">
                        <div class="did-floating-label-content">
                            <select id="status" name="status" class="did-floating-select modal-select">
                                <option value="Active" <?php if ($contact['status'] == 'Active') echo 'selected'; ?>>Active</option>
                                <option value="Inactive" <?php if ($contact['status'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
                            </select>
                            <label for="status" class="did-floating-label">Status</label>
                        </div>
                    </div>
                    <!-- Address Section -->
                    <h5 class="mt-3 mb-3 col-12">Address as per Residence Proof</h5>
                                            
                    <!-- Address Line 1 -->
                    <div class="mb-1 col-lg-6">
                        <div class="did-floating-label-content">
                            <input type="text" id="address1" name="address1" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['address1']); ?>" placeholder="">
                            <label for="address1" class="did-floating-label">Address Line 1</label>
                        </div>
                    </div>

                    <!-- Address Line 2 -->
                    <div class="mb-1 col-lg-6">
                        <div class="did-floating-label-content">
                            <input type="text" id="address2" name="address2" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['address2']); ?>" placeholder="">
                            <label for="address2" class="did-floating-label">Address Line 2</label>
                        </div>
                    </div>

                    <!-- City -->
                    <div class="mb-1 col-lg-6">
                        <div class="did-floating-label-content">
                            <input type="text" id="city" name="city" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['city']); ?>" placeholder="">
                            <label for="city" class="did-floating-label">City</label>
                        </div>
                    </div>

                    <!-- Pincode -->
                    <div class="mb-1 col-lg-6">
                        <div class="did-floating-label-content">
                            <input type="text" id="pincode" name="pincode" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['pincode']); ?>" placeholder="">
                            <label for="pincode" class="did-floating-label">Pincode</label>
                        </div>
                    </div>

                    
                    <!-- State Dropdown for India -->
                    <div class="mb-1 col-lg-6" id="stateDropdownPromoter" style="display:<?php echo ($contact['country'] == 'India') ? 'block' : 'none'; ?>;">
                            <div class="did-floating-label-content">
                                <select id="stateDropdownPromoterSelect" name="state_dropdown" class="did-floating-select modal-select">
                                    <?php foreach ($states as $state): ?>
                                        <option value="<?php echo $state; ?>" <?php echo ($contact['state'] == $state) ? 'selected' : ''; ?>><?php echo $state; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="state" class="did-floating-label">State</label>
                            </div>
                        </div>

                        <!-- State Text Input for Other Countries -->
                        <div class="mb-1 col-lg-6" id="stateInputPromoter" style="display:<?php echo ($contact['country'] != 'India') ? 'block' : 'none'; ?>;">
                            <div class="did-floating-label-content">
                                <input type="text" id="stateInputPromoterText" class="did-floating-input modal-input" name="state_input" value="<?php echo htmlspecialchars($contact['state']); ?>" placeholder="Enter State">
                                <label for="stateInputPromoterText" class="did-floating-label">State</label>
                            </div>
                        </div>

                        <!-- Country Dropdown -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <select id="countryPromoter" name="country" class="did-floating-select modal-select" required onchange="toggleStateField('Promoter')">
                                    <?php foreach ($countries as $country): ?>
                                        <option value="<?php echo $country; ?>" <?php echo ($contact['country'] == $country) ? 'selected' : ''; ?>><?php echo $country; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="countryPromoter" class="did-floating-label">Country</label>
                            </div>
                        </div>

                        <!-- Additional fields (PAN, Aadhaar, etc.) should also use $contact array -->
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editCreditorModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Creditor</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="update_other_contacts.php">
                <input type="hidden" name="contact_type" value="Creditor">
                <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                <input type="hidden" name="creditor_id" value="<?php echo $contact['id']; ?>">
                
                <div class="modal-body">
                    <div class="row">
                        <!-- Title Dropdown -->
                        <div class="mb-1 col-lg-2">
                            <div class="did-floating-label-content">
                                <select id="title" name="title" class="did-floating-select modal-select" required>
                                    <option value="Mr." <?php echo ($contact['title'] == 'Mr.') ? 'selected' : ''; ?>>Mr.</option>
                                    <option value="Mrs." <?php echo ($contact['title'] == 'Mrs.') ? 'selected' : ''; ?>>Mrs.</option>
                                    <option value="Ms." <?php echo ($contact['title'] == 'Ms.') ? 'selected' : ''; ?>>Ms.</option>
                                </select>
                                <label for="title" class="did-floating-label">Title</label>
                            </div>
                        </div>

                        <!-- Creditor Name -->
                        <div class="mb-1 col-lg-4">
                            <div class="did-floating-label-content">
                                <input type="text" id="name" name="name" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['name']); ?>" required>
                                <label for="name" class="did-floating-label">Creditor Name</label>
                            </div>
                        </div>

                        <!-- GSTIN -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="gstin" name="gstin" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['gstin']); ?>">
                                <label for="gstin" class="did-floating-label">GSTIN</label>
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="mobile" name="mobile" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['mobile']); ?>" required>
                                <label for="mobile" class="did-floating-label">Mobile</label>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="email" id="email" name="email" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['email']); ?>" required>
                                <label for="email" class="did-floating-label">Email</label>
                            </div>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <select id="status" name="status" class="did-floating-select modal-select">
                                    <option value="Active" <?php echo ($contact['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="Inactive" <?php echo ($contact['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                                <label for="status" class="did-floating-label">Status</label>
                            </div>
                        </div>

                        <!-- Address Section Heading -->
                        <h5 class="mt-3 mb-3 col-12">Address as per Residence Proof</h5>

                        <!-- Address Line 1 -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="address1" name="address1" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['address1']); ?>">
                                <label for="address1" class="did-floating-label">Address Line 1</label>
                            </div>
                        </div>

                        <!-- Address Line 2 -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="address2" name="address2" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['address2']); ?>">
                                <label for="address2" class="did-floating-label">Address Line 2</label>
                            </div>
                        </div>

                        <!-- City -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="city" name="city" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['city']); ?>">
                                <label for="city" class="did-floating-label">City</label>
                            </div>
                        </div>

                        <!-- Pincode -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="pincode" name="pincode" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['pincode']); ?>">
                                <label for="pincode" class="did-floating-label">Pincode</label>
                            </div>
                        </div>

                        

                        <!-- State Dropdown for India -->
                        <div class="mb-1 col-lg-6" id="stateDropdownCreditor" style="display:<?php echo ($contact['country'] == 'India') ? 'block' : 'none'; ?>;">
                            <div class="did-floating-label-content">
                                <select id="stateDropdownCreditorSelect" name="state_dropdown" class="did-floating-select modal-select">
                                    <?php foreach ($states as $state): ?>
                                        <option value="<?php echo $state; ?>" <?php echo ($contact['state'] == $state) ? 'selected' : ''; ?>><?php echo $state; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="state" class="did-floating-label">State</label>
                            </div>
                        </div>

                        <!-- State Text Input for Other Countries -->
                        <div class="mb-1 col-lg-6" id="stateInputCreditor" style="display:<?php echo ($contact['country'] != 'India') ? 'block' : 'none'; ?>;">
                            <div class="did-floating-label-content">
                                <input type="text" id="stateInputCreditorText" class="did-floating-input modal-input" name="state_input" value="<?php echo htmlspecialchars($contact['state']); ?>" placeholder="Enter State">
                                <label for="stateInputCreditorText" class="did-floating-label">State</label>
                            </div>
                        </div>

                        <!-- Country Dropdown -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <select id="countryCreditor" name="country" class="did-floating-select modal-select" required onchange="toggleStateField('Creditor')">
                                    <?php foreach ($countries as $country): ?>
                                        <option value="<?php echo $country; ?>" <?php echo ($contact['country'] == $country) ? 'selected' : ''; ?>><?php echo $country; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="countryCreditor" class="did-floating-label">Country</label>
                            </div>
                        </div>


                       

                        
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Edit Debtor Modal -->
<div id="editDebtorModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Debtor</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="update_other_contacts.php">
                <input type="hidden" name="contact_type" value="Debtor">
                <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                <input type="hidden" name="debtor_id" value="<?php echo $contact['id']; ?>">
                <div class="modal-body">
                    <div class="row">
                        <!-- Title Dropdown -->
                        <div class="mb-1 col-lg-2">
                            <div class="did-floating-label-content">
                                <select id="title" name="title" class="did-floating-select modal-select" required>
                                    <option value="Mr." <?php echo ($contact['title'] == 'Mr.') ? 'selected' : ''; ?>>Mr.</option>
                                    <option value="Mrs." <?php echo ($contact['title'] == 'Mrs.') ? 'selected' : ''; ?>>Mrs.</option>
                                    <option value="Ms." <?php echo ($contact['title'] == 'Ms.') ? 'selected' : ''; ?>>Ms.</option>
                                </select>
                                <label for="title" class="did-floating-label">Title</label>
                            </div>
                        </div>

                        <!-- Debtor Name -->
                        <div class="mb-1 col-lg-4">
                            <div class="did-floating-label-content">
                                <input type="text" id="name" name="name" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['name']); ?>" required>
                                <label for="name" class="did-floating-label">Debtor Name</label>
                            </div>
                        </div>

                        <!-- GSTIN -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="gstin" name="gstin" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['gstin']); ?>">
                                <label for="gstin" class="did-floating-label">GSTIN</label>
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="mobile" name="mobile" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['mobile']); ?>" required>
                                <label for="mobile" class="did-floating-label">Mobile</label>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="email" id="email" name="email" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['email']); ?>" required>
                                <label for="email" class="did-floating-label">Email</label>
                            </div>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <select id="status" name="status" class="did-floating-select modal-select">
                                    <option value="Active" <?php echo ($contact['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="Inactive" <?php echo ($contact['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                                <label for="status" class="did-floating-label">Status</label>
                            </div>
                        </div>

                        <!-- Address Section Heading -->
                        <h5 class="mt-3 mb-3 col-12">Address as per Residence Proof</h5>

                        <!-- Address Line 1 -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="address1" name="address1" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['address1']); ?>">
                                <label for="address1" class="did-floating-label">Address Line 1</label>
                            </div>
                        </div>

                        <!-- Address Line 2 -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="address2" name="address2" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['address2']); ?>">
                                <label for="address2" class="did-floating-label">Address Line 2</label>
                            </div>
                        </div>

                        <!-- City -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="city" name="city" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['city']); ?>">
                                <label for="city" class="did-floating-label">City</label>
                            </div>
                        </div>

                        <!-- Pincode -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="pincode" name="pincode" class="did-floating-input modal-input" value="<?php echo htmlspecialchars($contact['pincode']); ?>">
                                <label for="pincode" class="did-floating-label">Pincode</label>
                            </div>
                        </div>

                        <!-- Country and State with Conditional Display -->
                        
                        
                                   <div class="mb-1 col-lg-6" id="stateDropdownDebtor" style="display:<?php echo ($contact['country'] == 'India') ? 'block' : 'none'; ?>;">
                            <div class="did-floating-label-content">
                                <select id="stateDropdownDebtorSelect" name="state_dropdown" class="did-floating-select modal-select">
                                    <?php foreach ($states as $state): ?>
                                        <option value="<?php echo $state; ?>" <?php echo ($contact['state'] == $state) ? 'selected' : ''; ?>><?php echo $state; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="state" class="did-floating-label">State</label>
                            </div>
                        </div>

                        <!-- State Text Input for Other Countries -->
                        <div class="mb-1 col-lg-6" id="stateInputDebtor" style="display:<?php echo ($contact['country'] != 'India') ? 'block' : 'none'; ?>;">
                            <div class="did-floating-label-content">
                                <input type="text" id="stateInputDebtorText" class="did-floating-input modal-input" name="state_input" value="<?php echo htmlspecialchars($contact['state']); ?>" placeholder="Enter State">
                                <label for="stateInputDebtorText" class="did-floating-label">State</label>
                            </div>
                        </div>

                        <!-- Country Dropdown -->
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <select id="countryDebtor" name="country" class="did-floating-select modal-select" required onchange="toggleStateField('Debtor')">
                                    <?php foreach ($countries as $country): ?>
                                        <option value="<?php echo $country; ?>" <?php echo ($contact['country'] == $country) ? 'selected' : ''; ?>><?php echo $country; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="countryDebtor" class="did-floating-label">Country</label>
                            </div>
                        </div>

                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

</section>

<script src="assets/js/vendor-all.min.js"></script>
<script src="assets/js/plugins/bootstrap.min.js"></script>
<script src="assets/js/pcoded.min.js"></script>
<script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
<script>
function openEditModal(contactType) {
    if (contactType === 'Promoter') {
        $('#editPromoterModal').modal('show');
    } else if (contactType === 'Creditor') {
        $('#editCreditorModal').modal('show');
    } else if (contactType === 'Debtor') {
        $('#editDebtorModal').modal('show');
    } else {
        alert("Invalid contact type.");
    }
}

function confirmDelete(contact_id, contact_type) {
    if (confirm("Are you sure you want to delete this contact?")) {
        window.location.href = `delete_other_contacts.php?id=${contact_id}&contact_type=${contact_type}`;
    }
}

</script>
<script>
// Function to toggle state field based on the selected country and modal type
// Function to toggle state field based on the selected country and modal type
function toggleStateField(modalType) {
    var countryField = document.getElementById('country' + modalType);
    var stateDropdown = document.getElementById('stateDropdown' + modalType);
    var stateInput = document.getElementById('stateInput' + modalType);
    var stateInputText = document.getElementById('stateInput' + modalType + 'Text'); // Text input for other countries

    if (countryField) {
        var selectedCountry = countryField.value;

        // Toggle visibility and reset state input based on country selection
        if (selectedCountry === "India") {
            stateDropdown.style.display = "block";
            stateInput.style.display = "none";
        } else {
            stateDropdown.style.display = "none";
            stateInput.style.display = "block";
            if (stateInputText) stateInputText.value = ""; // Clear the text field for other countries
        }
    }
}

// Initialize toggleStateField for each modal type on page load and handle country changes
window.onload = function() {
    ['Promoter', 'Creditor', 'Debtor'].forEach(function(type) {
        toggleStateField(type); // Initial load based on saved data
        var countryElement = document.getElementById('country' + type);
        if (countryElement) {
            countryElement.addEventListener('change', function() {
                toggleStateField(type); // Update on country change
            });
        }
    });
};

</script>

</body>
</html>
