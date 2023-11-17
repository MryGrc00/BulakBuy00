<?php
session_start();
include_once "php/dbhelper.php";
include_once "php/mail.php"; // Include the file with the sendOTP function

// Establish database connection
$conn = dbconnect();

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    try {
        // Generate a new OTP
        $newOTP = generateOTP(); // Assuming generateOTP() is a function in mail.php to generate a new OTP

        // Update the OTP in the database for the given email
        $updateStmt = $conn->prepare("UPDATE users SET otp = :otp WHERE email = :email");
        $updateStmt->bindParam(':otp', $newOTP);
        $updateStmt->bindParam(':email', $email);
        $updateStmt->execute();

        // Send the new OTP via email using the sendOTP function from mail.php
        if (sendOTP($email, $newOTP)) {
            // Redirect to verification.php with the email parameter
            $_SESSION['email'] = $email; // Store email in session if needed for future use
            header("Location: verification_email.php");
            exit();
        } else {
            // Failed to send OTP, display error message or handle it accordingly
            echo "Failed to send OTP.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function generateOTP() {
    // Logic to generate a new OTP, for example:
    $otp = rand(100000, 999999);
    return $otp;
}
?>


