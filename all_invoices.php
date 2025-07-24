<?php
include("config.php");
session_start(); // Start session

// ✅ Step 1: If a template is selected, update the database
if (isset($_GET['template'])) {
    $temp_name = $_GET['template'];
      echo "<script>alert('Error updating template: " . $temp_name . "');</script>";
    $status = 'active';

    // ✅ Set all templates to inactive before activating the selected one
    $updateAll = "UPDATE invoice_temp SET status = 'inactive'";
    $conn->query($updateAll); // No need to check separately

    // ✅ Update only the selected template to active
    $query = "UPDATE invoice_temp SET status = '$status' WHERE temp_name = '$temp_name'";
    
    if ($conn->query($query) === TRUE) {
        $_SESSION['template'] = $temp_name; // Store in session for immediate use
        echo "<script>alert('Template \"$temp_name\" activated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating template: " . $conn->error . "');</script>";
    }
}

// ✅ Step 2: Fetch active template from DB
$query_fetch = "SELECT temp_name FROM invoice_temp WHERE status = 'active' LIMIT 1";
$result = $conn->query($query_fetch);
$active_template = 'template1'; // Default template

if ($result && $row = $result->fetch_assoc()) {
    $active_template = $row['temp_name'];
}

// ✅ Store active template in session for immediate effect
$_SESSION['template'] = $active_template;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include("header_link.php");?>
    <title>All Invoices</title>
    <style>
        .invoice-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }
        .invoice-container img {
            width: 230px;
            height: 300px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.3s;
        }
        .invoice-container img:hover {
            border-color: #007bff;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
            color: #333;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
         /* Basic styles for each template box */
        .template-box {
            display: inline-block;
            margin: 10px;
            padding: 10px;
            border: 2px solid #ccc;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        /* Highlight the selected template */
        .selected {
            background-color: #4CAF50; /* Green background for the selected template */
            color: white;
            transform: scale(1.1); /* Slightly zoom in for emphasis */
        }

        /* Add hover effect for templates */
        .template-box:hover {
            background-color: #f0f0f0;
        }
        .selected p {
    color: black; /* Set the text color to black for the selected template */
    font-size: 25px; /* Set the font size to 25px for the selected template */
}


        /* Add a default background color for images */
        img {
            width: 100px;
            height: auto;
        }
    </style>
    
</head>
<body>
     
     <?php include("menu.php");?>
    
<!-- [ Main Content ] start -->
<section class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <!-- <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">Dashboard</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top: -40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr> -->


<h2>Select Your Invoice Template</h2>

 
 <div class="invoice-container">
    <div class="template-box <?php echo ($active_template === 'template1') ? 'selected' : ''; ?>">
        <a href="?template=template1">
            <img src="invoice-images/invoice1.png" alt="Invoice Template 1">
            <p>Template 1</p>
        </a>
    </div>

    <div class="template-box <?php echo ($active_template === 'template2') ? 'selected' : ''; ?>">
        <a href="?template=template2">
            <img src="invoice-images/invoice2.png" alt="Invoice Template 2">
            <p>Template 2</p>
        </a>
    </div>

    <div class="template-box <?php echo ($active_template === 'template3') ? 'selected' : ''; ?>">
        <a href="?template=template3">
            <img src="invoice-images/invoice3.png" alt="Invoice Template 3">
            <p>Template 3</p>
        </a>
    </div>

    <div class="template-box <?php echo ($active_template === 'template4') ? 'selected' : ''; ?>">
        <a href="?template=template4">
            <img src="invoice-images/invoice4.png" alt="Invoice Template 4">
            <p>Template 4</p>
        </a>
    </div>
</div>

    </div>

        


      
</section>       
   
</body>
</html>
