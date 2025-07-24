<!-- [ Pre-loader ] start -->
	<div class="loader-bg">
		<div class="loader-track">
			<div class="loader-fill"></div>
		</div>
	</div>
	<!-- [ Pre-loader ] End -->
	<!-- [ navigation menu ] start -->
	<nav class="pcoded-navbar  ">
		<div class="navbar-wrapper  ">
			<div class="navbar-content scroll-div " >
				
				<div class="">
					 <?php
                        
                            $sql="select * from admin_login where id=1";
                                $result=$conn->query($sql);
                                if($row = mysqli_fetch_assoc($result)) 
                                {
             
        
                        
                        ?>
					<div class="main-menu-header">
						<img class="img-radius" src="<?php echo $row["image"]; ?>" alt="User-Profile-Image">
						<div class="user-details">
							<span> <?php echo $row["name"]; ?></span>
							<!-- <div id="more-details">UX Designer<i class="fa fa-chevron-down m-l-5"></i></div> -->
						</div>
					</div>
					<?php
				}
					?>
					<div class="collapse" id="nav-user-link">
						<ul class="list-unstyled">
							<li class="list-group-item"><a href="profile.php"><i class="feather icon-user m-r-5"></i>View Profile</a></li>
							<li class="list-group-item"><a href="#!"><i class="feather icon-settings m-r-5"></i>Settings</a></li>
							<li class="list-group-item"><a href="logout.php"><i class="feather icon-log-out m-r-5"></i>Logout</a></li>
						</ul>
					</div>
				</div>
				
				<!-- test menu-->
				<ul class="nav pcoded-inner-navbar ">
			<?php
			if($_SESSION['role'] == "admin")
			{
?>

					<li class="nav-item">
					    <a href="index.php" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
					</li>
					<?php
			}
			?>
					<li class="nav-item pcoded-hasmenu">
					    <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-user"></i></span><span class="pcoded-mtext">EMPLOYEES</span></a>
					    <ul class="pcoded-submenu">
					        <li><a href="employees-view.php">Manage Employees</a></li>
					    </ul>
					</li>

					<li class="nav-item pcoded-hasmenu">
					    <a href="#" class="nav-link "><span class="pcoded-micon"><i class="fas fa-user-graduate"></i></span><span class="pcoded-mtext">STUDENTS</span></a>
					    <ul class="pcoded-submenu">
					        <li><a href="view-students.php">Manage Students</a></li>
					    </ul>
					</li>

					<li class="nav-item ">
					    <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fa fa-database"></i></span><span class="pcoded-mtext">ADMISSION ENQUIRY</span></a>
					   
					</li>
					<li class="nav-item pcoded-hasmenu">
					    <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fa fa-bus"></i></span><span class="pcoded-mtext">TRANSPORTATION</span></a>
					    <ul class="pcoded-submenu">
					        <li><a href="view-routes.php">Manage Route</a></li>
					        <li><a href="view-trans-reg.php">Transportation Registration</a></li>
					    </ul>
					</li>
					<li class="nav-item pcoded-hasmenu">
					    <a href="attendance.php" class="nav-link "><span class="pcoded-micon"><i class="fas fa-users"></i></span><span class="pcoded-mtext">ATTENDANCE</span></a>
					    <ul class="pcoded-submenu">
					        <li><a href="attendance.php">Students Attendance</a></li>
					        <li><a href="employee-attendance.php">Employee Attendance</a></li>
					    </ul>
					</li>

					<li class="nav-item">
					    <a href="manage-subjects.php" class="nav-link "><span class="pcoded-micon"><i class="fas fa-chalkboard"></i></span><span class="pcoded-mtext">SUBJECTS</span></a>
					    <!-- <ul class="pcoded-submenu">
					        <li><a href="layout-vertical.html" target="_blank">Manage Students</a></li>
					    </ul> -->
					</li>

					<li class="nav-item">
					    <a href="manage-class.php" class="nav-link "><span class="pcoded-micon"><i class="fas fa-chalkboard"></i></span><span class="pcoded-mtext">MANAGE CLASS</span></a>
					    <!-- <ul class="pcoded-submenu">
					        <li><a href="layout-vertical.html" target="_blank">Manage Students</a></li>
					    </ul> -->
					</li>

					<li class="nav-item pcoded-hasmenu">
					    <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fas fa-hand-holding-usd"></i></span><span class="pcoded-mtext">ACCOUNTING</span></a>
					    <ul class="pcoded-submenu">
					        <li><a href="view-admission-fee.php">Manage Admission Fee</a></li>
					        <li><a href="manage-salary.php">Manage Salary</a></li>
					        <li><a href="manage-expenses.php">Manage Expenses</a></li>
					    </ul>
					</li>

					<li class="nav-item pcoded-hasmenu">
					    <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fa fa-book"></i></span><span class="pcoded-mtext">GRADEBOOK</span></a>
					    <ul class="pcoded-submenu">
					        <li><a href="view-student-grades.php">Manage Grades</a></li>
					    </ul>
					</li>

					<li class="nav-item">
					    <a href="#" class="nav-link "><span class="pcoded-micon"><i class="fas fa-home"></i></span><span class="pcoded-mtext">HOMEWORK & DAIRY</span></a>
					    <!-- <ul class="pcoded-submenu">
					        <li><a href="layout-vertical.html" target="_blank">Manage Students</a></li>
					    </ul> -->
					</li>

					<li class="nav-item">
					    <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fas fa-calendar-alt"></i></span><span class="pcoded-mtext">CALENDAR</span></a>
					    <!-- <ul class="pcoded-submenu">
					        <li><a href="layout-vertical.html" target="_blank">Manage Students</a></li>
					    </ul> -->
					</li>
				</ul>
				<!-- test menu end-->
			</div>
		</div>
	</nav>
	<!-- [ navigation menu ] end -->
	<!-- [ Header ] start -->
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
                 $sql="select * from admin_login";
                  $result=$conn->query($sql);

             if($result->num_rows>0)
                {
            if($row = mysqli_fetch_assoc($result)) 
                {
                                    ?>
										<img src="<?php echo $row["image"];?>" class="img-radius" alt="User-Profile-Image">
										<span><?php echo $row["f_name"];?></span>

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
										<li><a href="logout.php" class="dropdown-item"><i class="feather icon-lock"></i> Lock Screen</a></li>
									</ul>
								</div>
							</div>
						</li>
					</ul>
				</div>
				
			
	</header>
	<!-- [ Header ] end -->
	