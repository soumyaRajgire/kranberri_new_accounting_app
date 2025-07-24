<!DOCTYPE html>
<html lang="en">
<?php include("config.php");?>
<head>
    <title>iiiQbets</title>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
    	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    	<![endif]-->
   <?php include("header_link.php");?>

   <style type="text/css">

    input[type="file"] {
  position: relative;
  outline: none;
  padding: 4px;
  margin: -4px;
}

input[type="file"]:focus-within::file-selector-button,
input[type="file"]:focus::file-selector-button {
  outline: 2px solid #0964b0;
  outline-offset: 2px;
}

      
    #preview {
      max-width: 100%;
      margin-top: 10px;
    }
    #preview img{
        width: 200px;
    }
     #preview1 {
      max-width: 100%;
      margin-top: 10px;
    }
    #preview1 img{
        width: 200px;
    }
    #error-message {
      color: red;
      margin-top: 10px;
    }
    .imagePreview {
        margin-top: 20px;
width: 150px;
    }
   </style>
</head>
<body class="">
	
	<?php include("menu.php");?>
	
	

<!-- [ Main Content ] start -->
<section class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Profile</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Profile</a></li>
                            <!-- <li class="breadcrumb-item"><a href="#!">Form Elements</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">
           
            <!-- [ form-element ] start -->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Update Firm</h5>
                    </div>
                    <div class="card-body">
                     
                        <h5 class="mt-5">Profile</h5> 
                        <hr>
                        <?php
                           $sql="select * from user_login where id=1";
                                $result=$conn->query($sql);
                                if($row = mysqli_fetch_assoc($result)) 
                                {
                        ?>
<form action="profiledb.php" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
    <div class="row col-md-12">
    <div class="col-md-4">
        <label for="file-input" >Add Logo</label> 
    <input type="file" class="form-control imageInput" id="logo" name="logo" value="<?php echo $row['logoimage'];?>" accept="image/*">
    
   <img class="imagePreview" src="<?php echo $row['logoimage'];?>" alt="Image Preview">
    <div id="error-message"></div>
                                    <!-- <input type="file" name="logo" class="form-control"> -->
    </div>
    <div class="row col-md-8">
        <div class="col-md-6 mb-3">
        <label for="b_name">Business Name</label>
        <input type="text" class="form-control" id="b_name" name="b_name" placeholder="Business name" value="<?php echo $row['name']?>" required>
         <div class="valid-feedback">Looks good!</div>
    </div>
                                 <div class="col-md-6 mb-3">
                                    <label for="gstin">GSTIN</label>
                                    <input type="text" class="form-control" id="gstin" name="gstin" placeholder="GSTIN" value="<?php echo $row['gstin']?>">
                                    <div class="invalid-feedback">
                                        Please provide a valid GSTIN.
                                    </div>
                                </div>

                                 <div class="col-md-6 mb-3">
                                    <label for="email">Email</label>
                                    <!-- <div class="input-group"> -->
                                        
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" aria-describedby="inputGroupPrepend" value="<?php echo $row["email"];?>" required>
                                        <div class="invalid-feedback">
                                            Please Enter correct Email id.
                                        </div>
                                    <!-- </div> -->
                                </div>

                                   <div class="col-md-6 mb-3">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" value="<?php echo $row["phone"];?>" required>
                                    <div class="invalid-feedback">
                                        Please provide a valid Phone number.
                                    </div>
                                </div>
                                </div>
                            </div>
                           
                            <h5 class="mt-5">Business Details</h5>
                             <hr/>
            <div class="row col-md-12">
            <div class="row col-md-8">
                 <div class="col-md-6 mb-3">
                    <label for="b_address">Business Address</label>
                      <textarea class="form-control" name="b_address" id="b_address" value="<?php echo $row['address']?>"><?php echo $row['address']?></textarea>
                       <div class="valid-feedback"> Looks good!</div>
                </div>
                 <div class="col-md-6 mb-3">
                    <label for="pincode">Pincode</label>
                   <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode" value="<?php echo $row['pincode'];?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                   <label for="state">State</label>
                       <?php include("states.php");?>
                 </div>
                                
                <div class="col-md-6 mb-3">
                    <label for="b_desc">Business Description</label>
                    <input type="text" class="form-control" id="b_desc" name="b_desc" placeholder="Business Description" value="<?php echo $row['Business_desc']?>">
                </div>
                                
                <div class="col-md-6 mb-3">
                <label for="business_type">Business Type</label>
                    <select class="form-control" name="business_type" >
                        <option value="<?php echo $row['business_type']?>"><?php echo $row['business_type']?></option>
                        <option value="None">None</option>
                        <option value="Retail">Retail</option>
                        <option value="Wholesale">Wholesale</option>
                        <option value="Distributors">Distributors</option>
                        <option value="Service">Service</option>
                        <option value="Manufacturing">Manufacturing</option>
                        <option value="Others">Others</option>
                    </select>
                </div>

                 <div class="col-md-6 mb-3">
    <label for="business_cat">Business Category</label>
    <select class="form-control" name="business_cat">
        <option value="<?php echo $row['business_cat']?>"><?php echo $row['business_cat']?></option>
        <option value="Accounting & CA">Accounting & CA</option>
        <option value="Interior Designer">Interior Designer</option>
        <option value="Automobiles">Automobiles/ Auto parts</option>
        <option value="Salon & Spa">Salon & Spa</option>
        <option value="Liquor Store">Liquor Store</option>
        <option value="Book / Stationary store">Book / Stationary store</option>
        <option value="Computer Equipments & Softwares">Computer Equipments & Softwares</option>
        <option value="Tailoring/ Boutique">Tailoring/ Boutique</option>
        <option value="Kirana/ General Merchant">Kirana/ General Merchant</option>
        <option value="Pharmacy/ Medical">Pharmacy/ Medical</option>
        <option value="Jewellery & Gems">Jewellery & Gems</option>
        <option value="Mobile & Accessories">Mobile & Accessories</option>
        <option value="Real Estate">Real Estate</option>
        <option value="Others">Others</option>
     </select>
    </div>
            </div>
            <div class="col-md-4">
                
                <label for="file-input1">Add Signature</label>
       <input type="file" id="signature" name="signature" class="imageInput" accept="image/*">
    <!-- <label for="file-input1" style="cursor: pointer;border: 1px dashed #e3d8d8;padding: 10px;">Add Signature</label> -->
     <img class="imagePreview" src="<?php echo $row['signature']?>" alt="Image Preview" >
  
            </div>

            </div>
         </div>
                           
                            <input class="btn  btn-primary" type="submit" value="Submit">
                        </form>
                        <?php
                    }

                        ?>
                        <script>
                            // Example starter JavaScript for disabling form submissions if there are invalid fields
                            (function() {
                                'use strict';
                                window.addEventListener('load', function() {
                                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                                    var forms = document.getElementsByClassName('needs-validation');
                                    // Loop over them and prevent submission
                                    var validation = Array.prototype.filter.call(forms, function(form) {
                                        form.addEventListener('submit', function(event) {
                                            if (form.checkValidity() === false) {
                                                event.preventDefault();
                                                event.stopPropagation();
                                            }
                                            form.classList.add('was-validated');
                                        }, false);
                                    });
                                }, false);
                            })();
                        </script>
                       
                      
                    </div>
                </div>
                <!-- Input group -->
               
            </div>
            <!-- [ form-element ] end -->
        </div>
        <!-- [ Main Content ] end -->

    </div>
</section>
<!-- [ Main Content ] end -->
    <!-- Warning Section start -->
    <!-- Older IE warning message -->
    <!--[if lt IE 11]>
        <div class="ie-warning">
            <h1>Warning!!</h1>
            <p>You are using an outdated version of Internet Explorer, please upgrade
               <br/>to any of the following web browsers to access this website.
            </p>
            <div class="iew-container">
                <ul class="iew-download">
                    <li>
                        <a href="http://www.google.com/chrome/">
                            <img src="assets/images/browser/chrome.png" alt="Chrome">
                            <div>Chrome</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.mozilla.org/en-US/firefox/new/">
                            <img src="assets/images/browser/firefox.png" alt="Firefox">
                            <div>Firefox</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.opera.com">
                            <img src="assets/images/browser/opera.png" alt="Opera">
                            <div>Opera</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.apple.com/safari/">
                            <img src="assets/images/browser/safari.png" alt="Safari">
                            <div>Safari</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                            <img src="assets/images/browser/ie.png" alt="">
                            <div>IE (11 & above)</div>
                        </a>
                    </li>
                </ul>
            </div>
            <p>Sorry for the inconvenience!</p>
        </div>
    <![endif]-->
    <!-- Warning Section Ends -->

    <!-- Required Js -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/custom.js"></script>




</body>

</html>
