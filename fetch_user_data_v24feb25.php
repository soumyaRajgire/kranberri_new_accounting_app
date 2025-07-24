<?php


// if (!isset($_SESSION['LOG_IN'])) {
//     header("Location: login.php");
//     exit;
// } else {
//     $_SESSION['url'] = $_SERVER['REQUEST_URI'];
// }

// include("config.php");

// if (isset($_SESSION['id'])) { // assuming user ID is stored in the session as 'user_id'
//     $Id = $_SESSION['id'];

//     $sql = "SELECT id, name, gstin, email, phone, address FROM user_login WHERE id = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("i", $Id);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $user = $result->fetch_assoc();

    
// } else {
//     // Handle the case where user ID is not set in the session
//     echo "User ID not found.";
//     exit;
// }

if (isset($_SESSION['id']) && isset($_SESSION['business_id'])) {
    $userId = $_SESSION['id'];
    $businessId = $_SESSION['business_id'];

      if (isset($_SESSION['branch_id'])) {
        // Branch-specific query to get address from the branch table
        $branchId = $_SESSION['branch_id'];
       /* $sql = "SELECT u.id, u.name, u.gstin, u.email, u.phone, br.b_address_line1 AS address
                FROM user_login AS u
                JOIN add_branch AS br ON u.branch_id = br.branch_id
                WHERE u.id = ? AND u.business_id = ? AND br.branch_id = ?";*/

                 $sql = "SELECT u.id, u.name, u.gstin, u.email, u.phone, br.address_line1 AS address,br.state AS state
                FROM user_login AS u
                JOIN add_branch AS br ON u.branch_id = br.branch_id
                WHERE u.id = ? AND u.business_id = ? AND br.branch_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $userId, $businessId, $branchId);
    } else {
        // Business-wide query to get address from the business table
        $sql = "SELECT u.id, u.name, u.gstin, u.email, u.phone, b.address_line1 AS address,b.state AS state
                FROM user_login AS u
                JOIN add_business AS b ON u.business_id = b.business_id
                WHERE u.id = ? AND u.business_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $businessId);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

  }   else {
    // Handle the case where the user or business is not set in the session
    echo "User ID or Business ID not found in the session.";
    exit;
}
?>