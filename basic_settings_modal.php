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
    <?php include("header_link.php"); ?>




</head>

<body class="">
    <!-- [ Pre-loader ] start -->

    <?php include("menu.php"); ?>


    <!-- [ Header ] end -->
    <style>
    /* Custom CSS styles for the card */
    .custom-card  {
        width: 1452px; /* Adjust the width as per your preference */
        height: 730px;
        margin-left: 235px /* Center the card horizontally */
       
    }
    #info_form {
        margin-top:100px;
        width: 100%;
    }
</style>
<?php
if (isset($_POST['update-basic-settings'])) {


    $gstin = mysqli_real_escape_string($conn, $_POST["gstin"]);
    $gstin_no = mysqli_real_escape_string($conn, $_POST["gstin_no"]);
    $address_line1 = mysqli_real_escape_string($conn, $_POST["address_line1"]);
    $address_line2 = mysqli_real_escape_string($conn, $_POST["address_line2"]);
    $pincode = mysqli_real_escape_string($conn, $_POST["pincode"]);
    $city = mysqli_real_escape_string($conn, $_POST["city"]);
    $state = mysqli_real_escape_string($conn, $_POST["state"]);
    $country = mysqli_real_escape_string($conn, $_POST["country"]);

   

    $sql = "INSERT INTO basic_settings (gstin, gstin_no, address_line1, address_line2, pincode, city, state, country)
            VALUES ('$gstin', '$gstin_no', '$address_line1', '$address_line2', '$pincode', '$city', '$state', '$country')";

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Basic settings added successfully"); window.location = "basic_settings_modal.php";</script>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>




    <div class="custom-card ">
    <div class="card-header">
       <h4>Basic Settings</h4> 
    </div>
    <div class="card-body">

    <form class="" id="info_form" action="" method="post" inspfaactive="true">

            <div class="form-body" style="padding-bottom: 0;">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group mb-4">
                                                    <h6 class="control-label">Do you have GSTIN? </h6>
                                                    <select class="form-control" id="gstin" name="gstin">
                                                        <option value="" disabled="">Select</option>
                                                        <option value="Yes" selected="">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group mb-4">
                                                    <h6 class="control-label">GSTIN </h6>
                                                    <input type="text" id="gstin_no" name="gstin_no" class="form-control" placeholder="GST Number" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group  mb-4">
                                                    <h6 class="control-label">Address Line 1 <span class="required"> * </span></h6>
                                                    <input class="form-control"  type="text" value="" id="address_line1" name="address_line1" placeholder="Address Line 1">

                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group  mb-4">
                                                    <h6 class="control-label">Address Line 2</h6>
                                                    <input class="form-control"  type="text" id="address_line2" name="address_line2" value="" placeholder="Address Line 2">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group  mb-4">
                                                    <h6 class="control-label">Pin code <span class="required"> * </span></h6>
                                                    <input type="text" class="form-control" value="" id="pincode" name="pincode" placeholder="Pin code" minlength="0" oninput="numberOnly(this.id);" maxlength="6">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group  mb-4">
                                                    <h6 class="control-label">City <span class="required"> * </span></h6>
                                                    <input class="form-control" id="city" name="city" placeholder="City" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <h6 class="control-label">State<span class="required"> * </span></h6>
                                                    <select class="form-control" id="state"  name="state" required="">
                                                    <option value="">Select State</option>
                                                    <option value="ANDAMAN AND NICOBAR ISLANDS">ANDAMAN AND NICOBAR ISLANDS</option><option value="ANDHRA PRADESH">ANDHRA PRADESH</option><option value="ARUNACHAL PRADESH">ARUNACHAL PRADESH</option><option value="ASSAM">ASSAM</option><option value="BIHAR">BIHAR</option><option value="CHHATTISGARH">CHHATTISGARH</option><option value="GOA">GOA</option><option value="GUJARAT">GUJARAT</option><option value="HARYANA">HARYANA</option><option value="HIMACHAL PRADESH">HIMACHAL PRADESH</option><option value="JAMMU AND KASHMIR">JAMMU AND KASHMIR</option><option value="JHARKHAND">JHARKHAND</option><option value="KARNATAKA">KARNATAKA</option><option value="KERALA">KERALA</option><option value="MADHYA PRADESH">MADHYA PRADESH</option><option value="MAHARASHTRA">MAHARASHTRA</option><option value="MANIPUR">MANIPUR</option><option value="MEGHALAYA">MEGHALAYA</option><option value="MIZORAM">MIZORAM</option><option value="NAGALAND">NAGALAND</option><option value="PUNJAB">PUNJAB</option><option value="RAJASTHAN">RAJASTHAN</option><option value="SIKKIM">SIKKIM</option><option value="TAMIL NADU">TAMIL NADU</option><option value="TRIPURA">TRIPURA</option><option value="UTTAR PRADESH">UTTAR PRADESH</option><option value="UTTARAKHAND">UTTARAKHAND</option><option value="WEST BENGAL">WEST BENGAL</option><option value="CHANDIGARH">CHANDIGARH</option><option value="DADRA AND NAGAR HAVELI">DADRA AND NAGAR HAVELI</option><option value="DAMAN AND DIU">DAMAN AND DIU</option><option value="DELHI">DELHI</option><option value="LAKSHADWEEP">LAKSHADWEEP</option><option value="OTHER TERRITORY">OTHER TERRITORY</option><option value="TELANGANA">TELANGANA</option><option value="ODISHA">ODISHA</option><option value="INTERNATIONAL">INTERNATIONAL</option><option value="PUDUCHERRY">PUDUCHERRY</option><option value="LADAKH">LADAKH</option><option>ANDAMAN AND NICOBAR ISLANDS</option><option>ANDHRA PRADESH</option><option>ARUNACHAL PRADESH</option><option>ASSAM</option><option>BIHAR</option><option>CHHATTISGARH</option><option>GOA</option><option>GUJARAT</option><option>HARYANA</option><option>HIMACHAL PRADESH</option><option>JAMMU AND KASHMIR</option><option>JHARKHAND</option><option>KARNATAKA</option><option>KERALA</option><option>MADHYA PRADESH</option><option>MAHARASHTRA</option><option>MANIPUR</option><option>MEGHALAYA</option><option>MIZORAM</option><option>NAGALAND</option><option>PUNJAB</option><option>RAJASTHAN</option><option>SIKKIM</option><option>TAMIL NADU</option><option>TRIPURA</option><option>UTTAR PRADESH</option><option>UTTARAKHAND</option><option>WEST BENGAL</option><option>CHANDIGARH</option><option>DADRA AND NAGAR HAVELI</option><option>DAMAN AND DIU</option><option>DELHI</option><option>LAKSHADWEEP</option><option>OTHER TERRITORY</option><option>TELANGANA</option><option>ODISHA</option><option>INTERNATIONAL</option><option>PUDUCHERRY</option><option>LADAKH</option></select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <h6 class="control-label">Country<span class="required"> * </span></h6>
                                                    <select id="country" name="country" class="form-control" required="">
                                                    <option value="">Select Country</option>
                                                    <option value="AFGHANISTAN">AFGHANISTAN</option><option value="ALBANIA">ALBANIA</option><option value="ALGERIA">ALGERIA</option><option value="AMERICAN SAMOA">AMERICAN SAMOA</option><option value="ANDORRA">ANDORRA</option><option value="ANGOLA">ANGOLA</option><option value="ANGUILLA">ANGUILLA</option><option value="ANTIGUA AND BARBUDA">ANTIGUA AND BARBUDA</option><option value="ARGENTINA">ARGENTINA</option><option value="ARMENIA">ARMENIA</option><option value="ARUBA">ARUBA</option><option value="AUSTRALIA">AUSTRALIA</option><option value="AUSTRIA">AUSTRIA</option><option value="AZERBAIJAN">AZERBAIJAN</option><option value="BAHAMAS">BAHAMAS</option><option value="BAHRAIN">BAHRAIN</option><option value="BANGLADESH">BANGLADESH</option><option value="BARBADOS">BARBADOS</option><option value="BELARUS">BELARUS</option><option value="BELGIUM">BELGIUM</option><option value="BELIZE">BELIZE</option><option value="BENIN">BENIN</option><option value="BERMUDA">BERMUDA</option><option value="BHUTAN">BHUTAN</option><option value="BOLIVIA">BOLIVIA</option><option value="BOSNIA AND HERZEGOVINA">BOSNIA AND HERZEGOVINA</option><option value="BOTSWANA">BOTSWANA</option><option value="BOUVET ISLAND">BOUVET ISLAND</option><option value="BRAZIL">BRAZIL</option><option value="BRITISH INDIAN OCEAN TERRITORY">BRITISH INDIAN OCEAN TERRITORY</option><option value="BRUNEI DARUSSALAM">BRUNEI DARUSSALAM</option><option value="BULGARIA">BULGARIA</option><option value="BURKINA FASO">BURKINA FASO</option><option value="BURUNDI">BURUNDI</option><option value="CAMBODIA">CAMBODIA</option><option value="CAMEROON">CAMEROON</option><option value="CANADA">CANADA</option><option value="CAPE VERDE">CAPE VERDE</option><option value="CAYMAN ISLANDS">CAYMAN ISLANDS</option><option value="CENTRAL AFRICAN REPUBLIC">CENTRAL AFRICAN REPUBLIC</option><option value="CHAD">CHAD</option><option value="CHILE">CHILE</option><option value="CHINA">CHINA</option><option value="CHRISTMAS ISLAND">CHRISTMAS ISLAND</option><option value="COCOS (KEELING) ISLANDS">COCOS (KEELING) ISLANDS</option><option value="COLOMBIA">COLOMBIA</option><option value="COMOROS">COMOROS</option><option value="CONGO">CONGO</option><option value="CONGO, THE DEMOCRATIC REPUBLIC OF THE">CONGO, THE DEMOCRATIC REPUBLIC OF THE</option><option value="COOK ISLANDS">COOK ISLANDS</option><option value="COSTA RICA">COSTA RICA</option><option value="COTE D'IVOIRE">COTE D'IVOIRE</option><option value="CROATIA">CROATIA</option><option value="CUBA">CUBA</option><option value="CYPRUS">CYPRUS</option><option value="CZECH REPUBLIC">CZECH REPUBLIC</option><option value="DENMARK">DENMARK</option><option value="DJIBOUTI">DJIBOUTI</option><option value="DOMINICA">DOMINICA</option><option value="DOMINICAN REPUBLIC">DOMINICAN REPUBLIC</option><option value="ECUADOR">ECUADOR</option><option value="EGYPT">EGYPT</option><option value="EL SALVADOR">EL SALVADOR</option><option value="EQUATORIAL GUINEA">EQUATORIAL GUINEA</option><option value="ERITREA">ERITREA</option><option value="ESTONIA">ESTONIA</option><option value="ETHIOPIA">ETHIOPIA</option><option value="FALKLAND ISLANDS (MALVINAS)">FALKLAND ISLANDS (MALVINAS)</option><option value="FAROE ISLANDS">FAROE ISLANDS</option><option value="FIJI">FIJI</option><option value="FINLAND">FINLAND</option><option value="FRANCE">FRANCE</option><option value="FRENCH GUIANA">FRENCH GUIANA</option><option value="FRENCH POLYNESIA">FRENCH POLYNESIA</option><option value="FRENCH SOUTHERN TERRITORIES">FRENCH SOUTHERN TERRITORIES</option><option value="GABON">GABON</option><option value="GAMBIA">GAMBIA</option><option value="GEORGIA">GEORGIA</option><option value="GERMANY">GERMANY</option><option value="GHANA">GHANA</option><option value="GIBRALTAR">GIBRALTAR</option><option value="GREECE">GREECE</option><option value="GREENLAND">GREENLAND</option><option value="GRENADA">GRENADA</option><option value="GUADELOUPE">GUADELOUPE</option><option value="GUAM">GUAM</option><option value="GUATEMALA">GUATEMALA</option><option value="GUINEA">GUINEA</option><option value="GUINEA-BISSAU">GUINEA-BISSAU</option><option value="GUYANA">GUYANA</option><option value="HAITI">HAITI</option><option value="HEARD ISLAND AND MCDONALD ISLANDS">HEARD ISLAND AND MCDONALD ISLANDS</option><option value="HOLY SEE (VATICAN CITY STATE)">HOLY SEE (VATICAN CITY STATE)</option><option value="HONDURAS">HONDURAS</option><option value="HONG KONG">HONG KONG</option><option value="HUNGARY">HUNGARY</option><option value="ICELAND">ICELAND</option><option value="INDIA">INDIA</option><option value="INDONESIA">INDONESIA</option><option value="IRAN, ISLAMIC REPUBLIC OF">IRAN, ISLAMIC REPUBLIC OF</option><option value="IRAQ">IRAQ</option><option value="IRELAND">IRELAND</option><option value="ISRAEL">ISRAEL</option><option value="ITALY">ITALY</option><option value="JAMAICA">JAMAICA</option><option value="JAPAN">JAPAN</option><option value="JORDAN">JORDAN</option><option value="KAZAKHSTAN">KAZAKHSTAN</option><option value="KENYA">KENYA</option><option value="KIRIBATI">KIRIBATI</option><option value="KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF">KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF</option><option value="KOREA, REPUBLIC OF">KOREA, REPUBLIC OF</option><option value="KUWAIT">KUWAIT</option><option value="KYRGYZSTAN">KYRGYZSTAN</option><option value="LAO PEOPLE'S DEMOCRATIC REPUBLIC">LAO PEOPLE'S DEMOCRATIC REPUBLIC</option><option value="LATVIA">LATVIA</option><option value="LEBANON">LEBANON</option><option value="LESOTHO">LESOTHO</option><option value="LIBERIA">LIBERIA</option><option value="LIBYAN ARAB JAMAHIRIYA">LIBYAN ARAB JAMAHIRIYA</option><option value="LIECHTENSTEIN">LIECHTENSTEIN</option><option value="LITHUANIA">LITHUANIA</option><option value="LUXEMBOURG">LUXEMBOURG</option><option value="MACAO">MACAO</option><option value="MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF">MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF</option><option value="MADAGASCAR">MADAGASCAR</option><option value="MALAWI">MALAWI</option><option value="MALAYSIA">MALAYSIA</option><option value="MALDIVES">MALDIVES</option><option value="MALI">MALI</option><option value="MALTA">MALTA</option><option value="MARSHALL ISLANDS">MARSHALL ISLANDS</option><option value="MARTINIQUE">MARTINIQUE</option><option value="MAURITANIA">MAURITANIA</option><option value="MAURITIUS">MAURITIUS</option><option value="MAYOTTE">MAYOTTE</option><option value="MEXICO">MEXICO</option><option value="MICRONESIA, FEDERATED STATES OF">MICRONESIA, FEDERATED STATES OF</option><option value="MOLDOVA, REPUBLIC OF">MOLDOVA, REPUBLIC OF</option><option value="MONACO">MONACO</option><option value="MONGOLIA">MONGOLIA</option><option value="MONTSERRAT">MONTSERRAT</option><option value="MOROCCO">MOROCCO</option><option value="MOZAMBIQUE">MOZAMBIQUE</option><option value="MYANMAR">MYANMAR</option><option value="NAMIBIA">NAMIBIA</option><option value="NAURU">NAURU</option><option value="NEPAL">NEPAL</option><option value="NETHERLANDS">NETHERLANDS</option><option value="NETHERLANDS ANTILLES">NETHERLANDS ANTILLES</option><option value="NEW CALEDONIA">NEW CALEDONIA</option><option value="NEW ZEALAND">NEW ZEALAND</option><option value="NICARAGUA">NICARAGUA</option><option value="NIGER">NIGER</option><option value="NIGERIA">NIGERIA</option><option value="NIUE">NIUE</option><option value="NORFOLK ISLAND">NORFOLK ISLAND</option><option value="NORTHERN MARIANA ISLANDS">NORTHERN MARIANA ISLANDS</option><option value="NORWAY">NORWAY</option><option value="OMAN">OMAN</option><option value="PAKISTAN">PAKISTAN</option><option value="PALAU">PALAU</option><option value="PALESTINIAN TERRITORY, OCCUPIED">PALESTINIAN TERRITORY, OCCUPIED</option><option value="PANAMA">PANAMA</option><option value="PAPUA NEW GUINEA">PAPUA NEW GUINEA</option><option value="PARAGUAY">PARAGUAY</option><option value="PERU">PERU</option><option value="PHILIPPINES">PHILIPPINES</option><option value="PITCAIRN">PITCAIRN</option><option value="POLAND">POLAND</option><option value="PORTUGAL">PORTUGAL</option><option value="PUERTO RICO">PUERTO RICO</option><option value="QATAR">QATAR</option><option value="REUNION">REUNION</option><option value="ROMANIA">ROMANIA</option><option value="RUSSIAN FEDERATION">RUSSIAN FEDERATION</option><option value="RWANDA">RWANDA</option><option value="SAINT HELENA">SAINT HELENA</option><option value="SAINT KITTS AND NEVIS">SAINT KITTS AND NEVIS</option><option value="SAINT LUCIA">SAINT LUCIA</option><option value="SAINT PIERRE AND MIQUELON">SAINT PIERRE AND MIQUELON</option><option value="SAINT VINCENT AND THE GRENADINES">SAINT VINCENT AND THE GRENADINES</option><option value="SAMOA">SAMOA</option><option value="SAN MARINO">SAN MARINO</option><option value="SAO TOME AND PRINCIPE">SAO TOME AND PRINCIPE</option><option value="SAUDI ARABIA">SAUDI ARABIA</option><option value="SENEGAL">SENEGAL</option><option value="SEYCHELLES">SEYCHELLES</option><option value="SIERRA LEONE">SIERRA LEONE</option><option value="SINGAPORE">SINGAPORE</option><option value="SLOVAKIA">SLOVAKIA</option><option value="SLOVENIA">SLOVENIA</option><option value="SOLOMON ISLANDS">SOLOMON ISLANDS</option><option value="SOMALIA">SOMALIA</option><option value="SOUTH AFRICA">SOUTH AFRICA</option><option value="SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS">SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS</option><option value="SPAIN">SPAIN</option><option value="SRI LANKA">SRI LANKA</option><option value="SUDAN">SUDAN</option><option value="SURINAME">SURINAME</option><option value="SVALBARD AND JAN MAYEN">SVALBARD AND JAN MAYEN</option><option value="SWAZILAND">SWAZILAND</option><option value="SWEDEN">SWEDEN</option><option value="SWITZERLAND">SWITZERLAND</option><option value="SYRIAN ARAB REPUBLIC">SYRIAN ARAB REPUBLIC</option><option value="TAIWAN, PROVINCE OF CHINA">TAIWAN, PROVINCE OF CHINA</option><option value="TAJIKISTAN">TAJIKISTAN</option><option value="TANZANIA, UNITED REPUBLIC OF">TANZANIA, UNITED REPUBLIC OF</option><option value="THAILAND">THAILAND</option><option value="TIMOR-LESTE">TIMOR-LESTE</option><option value="TOGO">TOGO</option><option value="TOKELAU">TOKELAU</option><option value="TONGA">TONGA</option><option value="TRINIDAD AND TOBAGO">TRINIDAD AND TOBAGO</option><option value="TUNISIA">TUNISIA</option><option value="TURKEY">TURKEY</option><option value="TURKMENISTAN">TURKMENISTAN</option><option value="TURKS AND CAICOS ISLANDS">TURKS AND CAICOS ISLANDS</option><option value="TUVALU">TUVALU</option><option value="UGANDA">UGANDA</option><option value="UKRAINE">UKRAINE</option><option value="UNITED ARAB EMIRATES">UNITED ARAB EMIRATES</option><option value="UNITED KINGDOM">UNITED KINGDOM</option><option value="UNITED STATES">UNITED STATES</option><option value="UNITED STATES MINOR OUTLYING ISLANDS">UNITED STATES MINOR OUTLYING ISLANDS</option><option value="URUGUAY">URUGUAY</option><option value="UZBEKISTAN">UZBEKISTAN</option><option value="VANUATU">VANUATU</option><option value="VENEZUELA">VENEZUELA</option><option value="VIET NAM">VIET NAM</option><option value="VIRGIN ISLANDS, BRITISH">VIRGIN ISLANDS, BRITISH</option><option value="VIRGIN ISLANDS, U.S.">VIRGIN ISLANDS, U.S.</option><option value="WALLIS AND FUTUNA">WALLIS AND FUTUNA</option><option value="WESTERN SAHARA">WESTERN SAHARA</option><option value="YEMEN">YEMEN</option><option value="ZAMBIA">ZAMBIA</option><option value="ZIMBABWE">ZIMBABWE</option><option value="SERBIA">SERBIA</option><option value="ASIA PACIFIC REGION">ASIA PACIFIC REGION</option><option value="MONTENEGRO">MONTENEGRO</option><option value="ALAND ISLANDS">ALAND ISLANDS</option><option value="BONAIRE, SINT EUSTATIUS AND SABA">BONAIRE, SINT EUSTATIUS AND SABA</option><option value="CURACAO">CURACAO</option><option value="GUERNSEY">GUERNSEY</option><option value="ISLE OF MAN">ISLE OF MAN</option><option value="JERSEY">JERSEY</option><option value="KOSOVO">KOSOVO</option><option value="SAINT BARTHELEMY">SAINT BARTHELEMY</option><option value="SAINT MARTIN">SAINT MARTIN</option><option value="SINT MAARTEN">SINT MAARTEN</option><option value="SOUTH SUDAN">SOUTH SUDAN</option><option>AFGHANISTAN</option><option>ALBANIA</option><option>ALGERIA</option><option>AMERICAN SAMOA</option><option>ANDORRA</option><option>ANGOLA</option><option>ANGUILLA</option><option>ANTIGUA AND BARBUDA</option><option>ARGENTINA</option><option>ARMENIA</option><option>ARUBA</option><option>AUSTRALIA</option><option>AUSTRIA</option><option>AZERBAIJAN</option><option>BAHAMAS</option><option>BAHRAIN</option><option>BANGLADESH</option><option>BARBADOS</option><option>BELARUS</option><option>BELGIUM</option><option>BELIZE</option><option>BENIN</option><option>BERMUDA</option><option>BHUTAN</option><option>BOLIVIA</option><option>BOSNIA AND HERZEGOVINA</option><option>BOTSWANA</option><option>BOUVET ISLAND</option><option>BRAZIL</option><option>BRITISH INDIAN OCEAN TERRITORY</option><option>BRUNEI DARUSSALAM</option><option>BULGARIA</option><option>BURKINA FASO</option><option>BURUNDI</option><option>CAMBODIA</option><option>CAMEROON</option><option>CANADA</option><option>CAPE VERDE</option><option>CAYMAN ISLANDS</option><option>CENTRAL AFRICAN REPUBLIC</option><option>CHAD</option><option>CHILE</option><option>CHINA</option><option>CHRISTMAS ISLAND</option><option>COCOS (KEELING) ISLANDS</option><option>COLOMBIA</option><option>COMOROS</option><option>CONGO</option><option>CONGO, THE DEMOCRATIC REPUBLIC OF THE</option><option>COOK ISLANDS</option><option>COSTA RICA</option><option>COTE D'IVOIRE</option><option>CROATIA</option><option>CUBA</option><option>CYPRUS</option><option>CZECH REPUBLIC</option><option>DENMARK</option><option>DJIBOUTI</option><option>DOMINICA</option><option>DOMINICAN REPUBLIC</option><option>ECUADOR</option><option>EGYPT</option><option>EL SALVADOR</option><option>EQUATORIAL GUINEA</option><option>ERITREA</option><option>ESTONIA</option><option>ETHIOPIA</option><option>FALKLAND ISLANDS (MALVINAS)</option><option>FAROE ISLANDS</option><option>FIJI</option><option>FINLAND</option><option>FRANCE</option><option>FRENCH GUIANA</option><option>FRENCH POLYNESIA</option><option>FRENCH SOUTHERN TERRITORIES</option><option>GABON</option><option>GAMBIA</option><option>GEORGIA</option><option>GERMANY</option><option>GHANA</option><option>GIBRALTAR</option><option>GREECE</option><option>GREENLAND</option><option>GRENADA</option><option>GUADELOUPE</option><option>GUAM</option><option>GUATEMALA</option><option>GUINEA</option><option>GUINEA-BISSAU</option><option>GUYANA</option><option>HAITI</option><option>HEARD ISLAND AND MCDONALD ISLANDS</option><option>HOLY SEE (VATICAN CITY STATE)</option><option>HONDURAS</option><option>HONG KONG</option><option>HUNGARY</option><option>ICELAND</option><option>INDIA</option><option>INDONESIA</option><option>IRAN, ISLAMIC REPUBLIC OF</option><option>IRAQ</option><option>IRELAND</option><option>ISRAEL</option><option>ITALY</option><option>JAMAICA</option><option>JAPAN</option><option>JORDAN</option><option>KAZAKHSTAN</option><option>KENYA</option><option>KIRIBATI</option><option>KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF</option><option>KOREA, REPUBLIC OF</option><option>KUWAIT</option><option>KYRGYZSTAN</option><option>LAO PEOPLE'S DEMOCRATIC REPUBLIC</option><option>LATVIA</option><option>LEBANON</option><option>LESOTHO</option><option>LIBERIA</option><option>LIBYAN ARAB JAMAHIRIYA</option><option>LIECHTENSTEIN</option><option>LITHUANIA</option><option>LUXEMBOURG</option><option>MACAO</option><option>MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF</option><option>MADAGASCAR</option><option>MALAWI</option><option>MALAYSIA</option><option>MALDIVES</option><option>MALI</option><option>MALTA</option><option>MARSHALL ISLANDS</option><option>MARTINIQUE</option><option>MAURITANIA</option><option>MAURITIUS</option><option>MAYOTTE</option><option>MEXICO</option><option>MICRONESIA, FEDERATED STATES OF</option><option>MOLDOVA, REPUBLIC OF</option><option>MONACO</option><option>MONGOLIA</option><option>MONTSERRAT</option><option>MOROCCO</option><option>MOZAMBIQUE</option><option>MYANMAR</option><option>NAMIBIA</option><option>NAURU</option><option>NEPAL</option><option>NETHERLANDS</option><option>NETHERLANDS ANTILLES</option><option>NEW CALEDONIA</option><option>NEW ZEALAND</option><option>NICARAGUA</option><option>NIGER</option><option>NIGERIA</option><option>NIUE</option><option>NORFOLK ISLAND</option><option>NORTHERN MARIANA ISLANDS</option><option>NORWAY</option><option>OMAN</option><option>PAKISTAN</option><option>PALAU</option><option>PALESTINIAN TERRITORY, OCCUPIED</option><option>PANAMA</option><option>PAPUA NEW GUINEA</option><option>PARAGUAY</option><option>PERU</option><option>PHILIPPINES</option><option>PITCAIRN</option><option>POLAND</option><option>PORTUGAL</option><option>PUERTO RICO</option><option>QATAR</option><option>REUNION</option><option>ROMANIA</option><option>RUSSIAN FEDERATION</option><option>RWANDA</option><option>SAINT HELENA</option><option>SAINT KITTS AND NEVIS</option><option>SAINT LUCIA</option><option>SAINT PIERRE AND MIQUELON</option><option>SAINT VINCENT AND THE GRENADINES</option><option>SAMOA</option><option>SAN MARINO</option><option>SAO TOME AND PRINCIPE</option><option>SAUDI ARABIA</option><option>SENEGAL</option><option>SEYCHELLES</option><option>SIERRA LEONE</option><option>SINGAPORE</option><option>SLOVAKIA</option><option>SLOVENIA</option><option>SOLOMON ISLANDS</option><option>SOMALIA</option><option>SOUTH AFRICA</option><option>SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS</option><option>SPAIN</option><option>SRI LANKA</option><option>SUDAN</option><option>SURINAME</option><option>SVALBARD AND JAN MAYEN</option><option>SWAZILAND</option><option>SWEDEN</option><option>SWITZERLAND</option><option>SYRIAN ARAB REPUBLIC</option><option>TAIWAN, PROVINCE OF CHINA</option><option>TAJIKISTAN</option><option>TANZANIA, UNITED REPUBLIC OF</option><option>THAILAND</option><option>TIMOR-LESTE</option><option>TOGO</option><option>TOKELAU</option><option>TONGA</option><option>TRINIDAD AND TOBAGO</option><option>TUNISIA</option><option>TURKEY</option><option>TURKMENISTAN</option><option>TURKS AND CAICOS ISLANDS</option><option>TUVALU</option><option>UGANDA</option><option>UKRAINE</option><option>UNITED ARAB EMIRATES</option><option>UNITED KINGDOM</option><option>UNITED STATES</option><option>UNITED STATES MINOR OUTLYING ISLANDS</option><option>URUGUAY</option><option>UZBEKISTAN</option><option>VANUATU</option><option>VENEZUELA</option><option>VIET NAM</option><option>VIRGIN ISLANDS, BRITISH</option><option>VIRGIN ISLANDS, U.S.</option><option>WALLIS AND FUTUNA</option><option>WESTERN SAHARA</option><option>YEMEN</option><option>ZAMBIA</option><option>ZIMBABWE</option><option>SERBIA</option><option>ASIA PACIFIC REGION</option><option>MONTENEGRO</option><option>ALAND ISLANDS</option><option>BONAIRE, SINT EUSTATIUS AND SABA</option><option>CURACAO</option><option>GUERNSEY</option><option>ISLE OF MAN</option><option>JERSEY</option><option>KOSOVO</option><option>SAINT BARTHELEMY</option><option>SAINT MARTIN</option><option>SINT MAARTEN</option><option>SOUTH SUDAN</option></select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="text-align: right;">
                                    <button id="update-basic-settings" type="submit" name="update-basic-settings" class="btn btn-success">
    <i class="fa fa-plus"></i><span> Update</span>
</button>
            </div>
        </form>
    </div>
</div>

    <!-- <script src="assets/js/bootstrap.min.js"></script> -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>

    <script src="assets/js/myscript.js"></script>


</body>

</html>