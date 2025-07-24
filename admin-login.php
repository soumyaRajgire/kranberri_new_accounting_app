<!DOCTYPE html>
<html lang="en">

<head>

	<title>iiiQbets</title>
	
	 <?php include("header_link.php");?>
	
<style type="text/css">
	.btn{
		padding :6px 12px;
		background-color: #14267b;
		border-color:#14267b;

	}
	.btn:hover
	{
		background-color: #0e1744;
		border-color:#0e1744;
	}
	.btn:active{
		background-color: #223071;
		border-color:#223071;	
	}
</style>

</head>

<!-- [ auth-signin ] start -->
<div class="auth-wrapper">
	<div class="auth-content text-center">
		<img src="assets/images/iiiq.png" alt="" class="img-fluid mb-4">
		<div class="card borderless" style="box-shadow:0 2px 2px 2px lightgrey">
			<div class="row align-items-center ">
				<div class="col-md-12">
					<div class="card-title" style="background-color:lightgray;padding : 1px;">
						<h4 class="mb-3 f-w-400">Login</h4>
					</div>
					<div class="card-body">
						
						<form method="POST" action="admin-logindb.php">
						<div class="form-group mb-3">
						<input type="email" class="form-control" id="Email" name="email" placeholder="Email address">
						</div>
						<div class="form-group mb-4">
						<input type="password" class="form-control" id="Password" name="password" placeholder="Password">
						</div>
						<!-- <div class="custom-control custom-checkbox text-left mb-4 mt-2">
							<input type="checkbox" class="custom-control-input" id="customCheck1">
							<label class="custom-control-label" for="customCheck1">Save credentials.</label>
						</div> -->
						<input type="submit" name="login" id="login" value="Login" class="btn btn-block btn-primary mb-4"/>
						</form>
						<!-- <hr> -->
						<!-- <p class="mb-2 text-muted">Forgot password? <a href="auth-reset-password.html" class="f-w-400">Reset</a></p> -->
					<!-- 	<p class="mb-0 text-muted">Don’t have an account? <a href="auth-signup.html" class="f-w-400">Signup</a></p> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- [ auth-signin ] end -->

<!-- Required Js -->
<script src="assets/js/vendor-all.min.js"></script>
<script src="assets/js/plugins/bootstrap.min.js"></script>

<script src="assets/js/pcoded.min.js"></script>



</body>

</html>
