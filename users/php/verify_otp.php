<?php
session_start();
include_once "dbhelper.php"; // Include dbhelper.php which contains the dbconnect() function

$conn = dbconnect(); // Establish database connection using dbconnect() function from dbhelper.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if POST data is not empty
    if (!empty($_POST['verificationCode'])) {
        $verificationCode = $_POST['verificationCode'];
        $email = $_SESSION['email'];

        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND otp = :otp");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':otp', $verificationCode);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $updateStmt = $conn->prepare("UPDATE users SET e_status = 'Verified' WHERE email = :email");
                $updateStmt->bindParam(':email', $email);
                $updateStmt->execute();

                // Check if e_status is 'Verified'
                $checkStatusStmt = $conn->prepare("SELECT e_status FROM users WHERE email = :email");
                $checkStatusStmt->bindParam(':email', $email);
                $checkStatusStmt->execute();
                $result = $checkStatusStmt->fetch(PDO::FETCH_ASSOC);

                if ($result['e_status'] === 'Verified') {
                    echo json_encode(array("success" => true, "message" => "OTP verified successfully. Email status updated."));
                } else {
                    echo json_encode(array("success" => false, "message" => "Email status not verified."));
                }
            } else {
                echo json_encode(array("success" => false, "message" => "Invalid OTP. Please try again."));
            }
        } catch (PDOException $e) {
            echo json_encode(array("success" => false, "message" => "Database Error: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Invalid POST data."));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid request method."));
}

// Close the database connection
$conn = null;
?>
