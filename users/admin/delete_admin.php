<?php
include('checksession.php'); // Adjust as per your session management
include('../php/dbhelper.php'); // Adjust to your database helper file

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete_admin') {
    $adminId = $_POST['user_id'];
    deleteAdmin($adminId);
}

function deleteAdmin($adminId) {
    $conn = dbconnect();
    $sql = "DELETE FROM users WHERE user_id = :user_id"; // Adjust your query based on your database schema

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $adminId, PDO::PARAM_INT);
        $stmt->execute();
        $conn = null;
        
        echo "Admin deleted successfully";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        $conn = null;
    }
    exit;
}
?>
