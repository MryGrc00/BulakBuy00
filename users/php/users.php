<?php
session_start();
include_once "dbhelper.php";

$conn = dbconnect(); // Establish database connection using dbconnect() function from dbhelper.php

$outgoing_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE NOT user_id = :outgoing_id ORDER BY user_id DESC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':outgoing_id', $outgoing_id);
$stmt->execute();

$output = "";

if ($stmt->rowCount() == 0) {
    $output .= "No users are available to chat";
} elseif ($stmt->rowCount() > 0) {
    include_once "data.php"; // Assuming data.php handles the output logic
}

// Close the database connection
$conn = null;

echo $output;
?>
