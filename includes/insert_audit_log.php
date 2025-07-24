<?php
function insertAuditLog($conn, $activity_name, $file_path = null) {
    if (!isset($_SESSION['branch_id']) || !isset($_SESSION['name'])) {
        throw new Exception("Session not found. Please login again.");
    }

    try {
        $branch_id = $_SESSION['branch_id'];
        $username = $_SESSION['name'];
        
        $insert_audit_query = "INSERT INTO audit_log (created_by, activity_name, branch_id, file_path, created_at) 
                              VALUES (?, ?, ?, ?, NOW())";
        $stmt_audit = $conn->prepare($insert_audit_query);
        $stmt_audit->bind_param("ssis", $username, $activity_name, $branch_id, $file_path);
        
        if (!$stmt_audit->execute()) {
            throw new Exception("Error inserting audit log: " . $stmt_audit->error);
        }
        
        $stmt_audit->close();
        return true;
        
    } catch (Exception $e) {
        throw new Exception("Audit Log Error: " . $e->getMessage());
    }
}
?>