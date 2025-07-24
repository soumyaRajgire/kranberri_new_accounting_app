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
                                        <li><a href="logout.php" class="dropdown-item"><i class="feather icon-lock"></i> Lock Screen</a></li>
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

<?php
// Include config file
// include_once("config.php");
// session_start();

// Retrieve user data from session
$user_id = $_SESSION['id'];

// Query to fetch business and branch data for the logged-in user
 $sql = "SELECT b.business_id, b.business_name, br.branch_id, br.branch_name
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
            $branches[] = ['branch_id' => $row['branch_id'], 'branch_name' => $row['branch_name']];
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
    <?php if (count($branches) > 1) { ?>
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