<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} else {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
    <?php include("header_link.php");?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
    <!-- <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            text-align: center;
        }
        .wrapper1 {
            min-height: 500px;
            margin: 40px auto;
            padding: 20px;
            background-color: #ecf0f3;
            border-radius: 15px;
            box-shadow: 13px 13px 20px #cbced1, -13px -13px 20px #fff;
        }
        @media (max-width: 768px) {
            .check_out_form, .lunch_break_end_form {
                padding-top: 20px;
            }
        }
        /* #map {
            width: 100%;
            height: 200px;
        } */
        .logo img {
            max-width: 100%;
            height: auto;
        }
        .button-container {
            margin: 20px 0;
        }
        .button-container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 0 10px;
        }
        .button-container .check-in {
            background-color: #4CAF50;
            color: white;
        }
        .button-container .check-out {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
<?php include("menu.php");?>
    
      
<section class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="col-md-12">
            <div class="page-header-title">
                <h4 class="m-b-10">Mark Attendance</h4>
            </div>
        </div>
        <hr>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <div class="attendance-container">
                            <h1>Mark Attendance</h1>
        <div class="button-container">
        <div class="date-picker">
                                <label for="date">Select Date:</label>
                                <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                                <form action="checkin.php" method="POST" style="display:inline;">
                                    <button type="submit" name="checkin" class="check-in">Check-In</button>
                                </form>
                                <form action="checkout.php" method="POST" style="display:inline;">
                                    <button type="submit" name="checkout" class="check-out">Check-Out</button>
                                </form>
                            </div>
        <!-- <div class="col-md-6 col-xs-12 row" style="margin-left: auto; margin-right: auto; justify-content: center; padding-top: 10px;">
            <div class="col-md-4 col-xs-6">
                <form action="lunch_break_start.php" method="POST">
                    <button type="submit" name="lunch_break_starts_button" class="btn btn-primary">Lunch Break Start</button>
                </form>
            </div>
            <br>
            <div class="col-md-4 col-xs-12">
                <form action="lunch_break_end.php" method="post" class="lunch_break_end_form">
                    <button type="submit" name="lunch_break_ends_button" class="btn btn-primary">Lunch Break End</button>
                </form> 
            </div>
        </div> -->
       </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAtIjjx3BuQUC-6Mm-iCyuTIRzUqAIwXTw&libraries=places&callback=initMap" async defer></script>
    <script src="assets/js/vendor-all.min.js"></script>
<script src="assets/js/plugins/bootstrap.min.js"></script>
<script src="assets/js/pcoded.min.js"></script>
<script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
<script src="assets/js/myscript.js"></script>
</body>
</html>
