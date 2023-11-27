<?php
include_once "../php/dbhelper.php"; // Include your database helper

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$conn = dbconnect();

// Check if status is passed from Ajax
if (isset($_POST['status'])) {
    $status = $_POST['status'];
    $user_id = $_SESSION['user_id'];

    // Check if a status already exists for this user
    $checkSql = $conn->prepare("SELECT * FROM services WHERE arranger_id = :user_id");
    $checkSql->bindParam(':user_id', $user_id);
    $checkSql->execute();

    if ($checkSql->rowCount() > 0) {
        // If a status exists, update it
        $sql = $conn->prepare("UPDATE services SET status = :status WHERE arranger_id = :user_id");
    } else {
        // If no status exists, insert a new one
        $sql = $conn->prepare("INSERT INTO services (arranger_id, status) VALUES (:user_id, :status)");
    }

    // Bind parameters and execute the query
    $sql->bindParam(':status', $status);
    $sql->bindParam(':user_id', $user_id);
    $sql->execute();

    echo "Status updated to " . $status;
}

// Close the database connection
$conn = null;
?>
