<?php
session_start();
include("config.php");

// Redirect if already logged in and a business is selected
if (isset($_SESSION['LOG_IN']) && isset($_SESSION['business_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch businesses for the dropdown
$sql = "SELECT b.business_id, b.business_name, br.branch_id, br.branch_name
        FROM add_business AS b
        LEFT JOIN add_branch AS br ON b.business_id = br.business_id";
$result = $conn->query($sql);

$businesses = [];
while ($row = $result->fetch_assoc()) {
    $businesses[$row['business_id']]['business_name'] = $row['business_name'];
    if ($row['branch_id']) {
        $businesses[$row['business_id']]['branches'][] = ['branch_id' => $row['branch_id'], 'branch_name' => $row['branch_name']];
    }
}
?>
<!DOCTYPE html>
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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

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
                    <!-- <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a href="#!" class="pop-search"><i class="feather icon-search"></i></a>
                            <div class="search-bar">
                                <input type="text" class="form-control border-0 shadow-none" placeholder="Search hear">
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="dropdown">
                                <a class="dropdown-toggle h-drop" href="#" data-toggle="dropdown">
                                    Dropdown
                                </a>
                                <div class="dropdown-menu profile-notification ">
                                    <ul class="pro-body">
                                        <li><a href="user-profile.html" class="dropdown-item"><i class="fas fa-circle"></i> Profile</a></li>
                                        <li><a href="email_inbox.html" class="dropdown-item"><i class="fas fa-circle"></i> My Messages</a></li>
                                        <li><a href="auth-signin.html" class="dropdown-item"><i class="fas fa-circle"></i> Lock Screen</a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="dropdown mega-menu">
                                <a class="dropdown-toggle h-drop" href="#" data-toggle="dropdown">
                                    Mega
                                </a>
                                <div class="dropdown-menu profile-notification ">
                                    <div class="row no-gutters">
                                        <div class="col">
                                            <h6 class="mega-title">UI Element</h6>
                                            <ul class="pro-body">
                                                <li><a href="#!" class="dropdown-item"><i class="fas fa-circle"></i> Alert</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="fas fa-circle"></i> Button</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="fas fa-circle"></i> Badges</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="fas fa-circle"></i> Cards</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="fas fa-circle"></i> Modal</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="fas fa-circle"></i> Tabs & pills</a></li>
                                            </ul>
                                        </div>
                                        <div class="col">
                                            <h6 class="mega-title">Forms</h6>
                                            <ul class="pro-body">
                                                <li><a href="#!" class="dropdown-item"><i class="feather icon-minus"></i> Elements</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="feather icon-minus"></i> Validation</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="feather icon-minus"></i> Masking</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="feather icon-minus"></i> Wizard</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="feather icon-minus"></i> Picker</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="feather icon-minus"></i> Select</a></li>
                                            </ul>
                                        </div>
                                        <div class="col">
                                            <h6 class="mega-title">Application</h6>
                                            <ul class="pro-body">
                                                <li><a href="#!" class="dropdown-item"><i class="feather icon-mail"></i> Email</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="feather icon-clipboard"></i> Task</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="feather icon-check-square"></i> To-Do</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="feather icon-image"></i> Gallery</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="feather icon-help-circle"></i> Helpdesk</a></li>
                                            </ul>
                                        </div>
                                        <div class="col">
                                            <h6 class="mega-title">Extension</h6>
                                            <ul class="pro-body">
                                                <li><a href="#!" class="dropdown-item"><i class="feather icon-file-plus"></i> Editor</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="feather icon-file-minus"></i> Invoice</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="feather icon-calendar"></i> Full calendar</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="feather icon-upload-cloud"></i> File upload</a></li>
                                                <li><a href="#!" class="dropdown-item"><i class="feather icon-scissors"></i> Image cropper</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul> -->
                    <ul class="navbar-nav ml-auto">
                        <!-- <li>
                            <div class="dropdown">
                                <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                                    <i class="icon feather icon-bell"></i>
                                    <span class="badge badge-pill badge-danger">5</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right notification">
                                    <div class="noti-head">
                                        <h6 class="d-inline-block m-b-0">Notifications</h6>
                                        <div class="float-right">
                                            <a href="#!" class="m-r-10">mark as read</a>
                                            <a href="#!">clear all</a>
                                        </div>
                                    </div>
                                    <ul class="noti-body">
                                        <li class="n-title">
                                            <p class="m-b-0">NEW</p>
                                        </li>
                                        <li class="notification">
                                            <div class="media">
                                                <img class="img-radius" src="assets/images/user/avatar-1.jpg" alt="Generic placeholder image">
                                                <div class="media-body">
                                                    <p><strong>John Doe</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>5 min</span></p>
                                                    <p>New ticket Added</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="n-title">
                                            <p class="m-b-0">EARLIER</p>
                                        </li>
                                        <li class="notification">
                                            <div class="media">
                                                <img class="img-radius" src="assets/images/user/avatar-2.jpg" alt="Generic placeholder image">
                                                <div class="media-body">
                                                    <p><strong>Joseph William</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>10 min</span></p>
                                                    <p>Prchace New Theme and make payment</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="notification">
                                            <div class="media">
                                                <img class="img-radius" src="assets/images/user/avatar-1.jpg" alt="Generic placeholder image">
                                                <div class="media-body">
                                                    <p><strong>Sara Soudein</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>12 min</span></p>
                                                    <p>currently login</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="notification">
                                            <div class="media">
                                                <img class="img-radius" src="assets/images/user/avatar-2.jpg" alt="Generic placeholder image">
                                                <div class="media-body">
                                                    <p><strong>Joseph William</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>30 min</span></p>
                                                    <p>Prchace New Theme and make payment</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="noti-footer">
                                        <a href="#!">show all</a>
                                    </div>
                                </div>
                            </div>
                        </li> -->
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
<body>
<section class="pcoded-main-container">
    <div class="pcoded-content">
        <h3>Select Your Business and Branch</h3>
        <form action="set_session.php" method="post">
            <div class="form-group">
                <label for="businessSelect">Business:</label>
                <select id="businessSelect" name="business_id" class="form-control" required onchange="updateBranchDropdown()">
                    <option value="">Select Business</option>
                    <?php foreach ($businesses as $business_id => $business): ?>
                        <option value="<?php echo $business_id; ?>"><?php echo htmlspecialchars($business['business_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="branchSelect">Branch:</label>
                <select id="branchSelect" name="branch_id" class="form-control" required>
                    <option value="">Select Branch</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Go to Dashboard</button>
        </form>
    </div>
</section>

<script>
// JavaScript to update branch dropdown based on selected business
function updateBranchDropdown() {
    const businessId = document.getElementById("businessSelect").value;
    const branches = <?php echo json_encode($businesses); ?>;
    const branchSelect = document.getElementById("branchSelect");

    branchSelect.innerHTML = '<option value="">Select Branch</option>'; // Reset

    if (businessId && branches[businessId] && branches[businessId].branches) {
        branches[businessId].branches.forEach(branch => {
            const option = document.createElement("option");
            option.value = branch.branch_id;
            option.textContent = branch.branch_name;
            branchSelect.appendChild(option);
        });
    }
}
</script>

</body>
</html>
