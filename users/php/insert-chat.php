<?php
session_start();
include_once "dbhelper.php";

if (isset($_SESSION['user_id'])) {
    $conn = dbconnect(); // Establish database connection using dbconnect() function from dbhelper.php

    $outgoing_id = $_SESSION['user_id'];
    $incoming_id = $_POST['incoming_id'];
    $message = $_POST['message'];

    if (!empty($message)) {
        $sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES (:incoming_id, :outgoing_id, :message)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':incoming_id', $incoming_id);
        $stmt->bindParam(':outgoing_id', $outgoing_id);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
    }

    // Close the database connection
    $conn = null;
} else {
    header("location: ../login.php");
}
?>
