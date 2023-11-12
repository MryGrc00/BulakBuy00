<?php
session_start();
include_once "dbhelper.php"; // Include dbhelper.php which contains the dbconnect() function

$conn = dbconnect(); // Establish database connection using dbconnect() function from dbhelper.php

$outgoing_id = $_SESSION['user_id'];
$searchTerm = $_POST['searchTerm'];

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE NOT user_id = :outgoing_id AND (first_name LIKE :searchTerm OR last_name LIKE :searchTerm)");
    $searchTerm = "%" . $searchTerm . "%";
    $stmt->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_INT);
    $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();

    $output = "";
    if ($stmt->rowCount() > 0) {
        include_once "data.php";
    } else {
        $output .= 'No user found related to your search term';
    }

    echo $output;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>
