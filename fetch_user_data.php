<?php

if (isset($_SESSION['id']) && isset($_SESSION['business_id'])) {
    $userId = $_SESSION['id'];
    $businessId = $_SESSION['business_id'];
 $branchId = $_SESSION['branch_id'];
 ?>
   <script type="text/javascript"> 
 //  alert("Business ID: " + "<?php echo $_SESSION['business_id']; ?>" +           "\nBranch ID: " + "<?php echo $_SESSION['branch_id']; ?>" +            "\nGSTIN: " + "<?php echo $_SESSION['sel_gstin']; ?>");
</script> 
<?php
      if (isset($_SESSION['branch_id'])) {
        // Branch-specific query to get address from the branch table
        $branchId = $_SESSION['branch_id'];


                //  $sql = "SELECT u.id, u.name, u.gstin, u.email, u.phone, br.address_line1 AS address,br.state AS state
                // FROM user_login AS u
                // JOIN add_branch AS br ON u.branch_id = br.branch_id
                // WHERE u.id = ? AND u.business_id = ? AND br.branch_id = ?";
                
      $sql="SELECT br.* from add_branch AS br WHERE br.business_id = ? AND br.branch_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii",  $businessId, $branchId);
    } else {
        // Business-wide query to get address from the business table
        // $sql = "SELECT u.id, u.name, u.gstin, u.email, u.phone, b.address_line1 AS address,b.state AS state
        //         FROM user_login AS u
        //         JOIN add_business AS b ON u.business_id = b.business_id
        //         WHERE u.id = ? AND u.business_id = ?";
      $sql="SELECT br.* from add_branch AS br WHERE business_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i",  $businessId);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
   // print_r($user);

    
    

  }   else {
    // Handle the case where the user or business is not set in the session
    echo "User ID or Business ID not found in the session.";
    exit;
}
?>