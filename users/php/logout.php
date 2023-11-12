<?php
session_start();
include_once "dbhelper.php"; // Include dbhelper.php which contains the dbconnect() function

$conn = dbconnect(); // Establish database connection using dbconnect() function from dbhelper.php

if (isset($_SESSION['user_id'])) {
    $logout_id = $_GET['logout_id'];

    if (isset($logout_id)) {
        $status = "Offline now";
        $sql = "UPDATE users SET status = :status WHERE user_id = :logout_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':logout_id', $logout_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            session_unset();
            session_destroy();
            header("location: ../login.php");
        }
    } else {
        header("location: ../vendor_home.php");
    }
} else {
    header("location: ../login.php");
}

// Close the database connection
$conn = null;
?>
