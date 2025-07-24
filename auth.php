<?php
// session_start(); // Start the session
// require 'config.php'; // Database connection

// $gspappid = "79536E39F216449883720CCD53643D8F";
// $gspappsecret = "EE5EFAACG8434G43E8GA90EG9660E98C3D71";

// // Check if access token is already stored in session
// if (isset($_SESSION['access_token']) && isset($_SESSION['expires_at']) && strtotime($_SESSION['expires_at']) > time()) {
//     $access_token = $_SESSION['access_token']; // Use existing token
// } else {
//     // Fetch from the database
//     $query = "SELECT access_token, expires_at FROM gsp_api WHERE gspappid = ? AND gspappsecret = ? ORDER BY expires_at DESC LIMIT 1";
//     $stmt = $conn->prepare($query);
//     $stmt->bind_param('ss', $gspappid, $gspappsecret);
//     $stmt->execute();
//     $stmt->bind_result($access_token, $expires_at);
//     $stmt->fetch();
//     $stmt->close();

//     if ($access_token && strtotime($expires_at) > time()) {
//         $_SESSION['access_token'] = $access_token; // Store in session
//         $_SESSION['expires_at'] = $expires_at;
//     } else {
//         // If token is expired, generate a new one
//         require 'generate_token.php'; // Call the script to generate a new token
//         $access_token = $_SESSION['access_token']; // Get the new token
//     }
// }

?>
<?php
session_start(); // Start the session
require 'config.php'; // Database connection

$gspappid = "79536E39F216449883720CCD53643D8F";
$gspappsecret = "EE5EFAACG8434G43E8GA90EG9660E98C3D71";


//Live/Prod env
// $gspappid = "771CB8E5C27049A48B38426439175284";

// $gspappsecret = "818DBFA5GC86CG4542G8F72GF9F93DD0D49F";


if (isset($_SESSION['access_token']) && isset($_SESSION['expires_at']) && strtotime($_SESSION['expires_at']) > time()) {
    // Prepare the data to be displayed in alert and console
    $accessToken = $_SESSION['access_token'];
    $expiresAt = $_SESSION['expires_at'];

    // Display in alert and console
    // echo "<script>             console.log('Access Token: ' + '$accessToken'); console.log('Expires At: ' + '$expiresAt');               alert('Token stored in session: Access Token = ' + '$accessToken' + ', Expires At = ' + '$expiresAt');
      //     </script>";
}


else {
   //   echo "<script>alert('token NOT stored in session ')</script>";
    // Fetch from the database
    $query = "SELECT access_token, expires_at FROM gsp_api WHERE gspappid = ? AND gspappsecret = ? ORDER BY expires_at DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $gspappid, $gspappsecret);
      if ($stmt->execute()) {
    

           
            echo "<script>alert('checking Token expiartion time')</script>";
        }
        else
        {
          echo "<script>alert('Error in checking Token expiartion time')</script>";
        }

    $stmt->bind_result($access_token, $expires_at);
    $stmt->fetch();
    $stmt->close();

    if ($access_token && strtotime($expires_at) > time()) {
        echo "<script>alert('No need to  generate a new token')</script>";
        $_SESSION['access_token'] = $access_token; // Store in session
        $_SESSION['expires_at'] = $expires_at;
    } else {

    echo "<script>alert('need to  generate new token')</script>";

        // If token is expired, generate a new one
        require 'generate_token.php'; // Call the script to generate a new token
        $access_token = $_SESSION['access_token']; // Get the new token
    }
}

?>


