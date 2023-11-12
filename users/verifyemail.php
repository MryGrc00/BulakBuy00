<?php
session_start();
include_once "php/dbhelper.php";
include_once "php/mail.php"; // Include the file with the sendOTP function

// Establish database connection
$conn = dbconnect();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

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




<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" /> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity= "sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>

    
    <title>Verify Email</title>
</head>
<body>
  
<div class="container">
    <main>
        <div class="container-fluid mt-5">
            <div class="row fw-semibold">
                <h2>Enter Your Email</h2>
                <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="error-text"></div>
                <div class="form-group">
                    <input type="text" class="form-control bg-light rounded" name="email" id="email" placeholder="Email" required>
                </div>
                    <div class="form-group">
                        <br>
                        <div class="button">
                            <input type="submit" name="submit" class="btn btn-primary w-100"  value="Verify"></button>
                        </div>
                        <br>
                    </div>
                </form>                


            </div>
        </div>
    </div>
    </main>




</body>
</html>
