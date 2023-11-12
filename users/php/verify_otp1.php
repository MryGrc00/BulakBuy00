<?php
session_start();
include_once "php/dbhelper.php";

$conn = dbconnect();

if (isset($_GET['email']) && isset($_POST['submit'])) {
    if (!empty($_POST['verificationCode'])) {
        $enteredOTP = $_POST['verificationCode'];
        $email = $_GET['email'];

        try {
            // Validate entered OTP against the database
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND otp = :otp");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':otp', $enteredOTP);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // OTP is correct, update email status in the database
                $updateStmt = $conn->prepare("UPDATE users SET e_status = 'Verified' WHERE email = :email");
                $updateStmt->bindParam(':email', $email);
                $updateStmt->execute();

                // Check if the email status was successfully updated
                if ($updateStmt->rowCount() > 0) {
                    // OTP is correct, and email status is updated to 'Verified'
                    header("Location: success.php");
                    exit();
                } else {
                    // Database error, handle it accordingly
                    echo "Database error. Email status could not be updated.";
                }
            } else {
                // Invalid OTP, display error message or handle it accordingly
                echo "Invalid OTP.";
            }
        } catch (PDOException $e) {
            // Handle database errors
            echo "Database Error: " . $e->getMessage();
        }
    } else {
        // Invalid OTP, display error message or handle it accordingly
        echo "Invalid OTP.";
    }
}
?>
