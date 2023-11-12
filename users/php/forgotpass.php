<?php
session_start();
include_once "dbhelper.php";
include_once "mail.php"; // Include the file with the sendOTP function

// Establish database connection
$conn = dbconnect();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $checkStmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $checkStmt->bindParam(':email', $email);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
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
                header("Location: ../verify_password.php");
                exit();
            } else {
                // Failed to send OTP, display error message or handle it accordingly
                echo "Failed to send OTP.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Email does not exist in the database, display error message
        echo "Email does not exist. Please enter a valid email address.";
    }
}

function generateOTP() {
    // Logic to generate a new OTP, for example:
    $otp = rand(100000, 999999);
    return $otp;
}
?>