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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verification Code</title>
  <link rel="stylesheet" href="../css/modal.css">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

  <style>
   /* Import Google font - Poppins */
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}
body {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #4070f4;
}
:where(.container, form, .input-field, header) {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.container {
  background: #fff;
  padding: 30px 65px;
  border-radius: 12px;
  row-gap: 20px;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
}
.container header {
  height: 65px;
  width: 65px;
  background: #4070f4;
  color: #fff;
  font-size: 2.5rem;
  border-radius: 50%;
}
.container h4 {
  font-size: 1.25rem;
  color: #333;
  font-weight: 500;
}
form .input-field {
  flex-direction: row;
  column-gap: 10px;
}
.input-field input {
  height: 45px;
  width: 42px;
  border-radius: 6px;
  outline: none;
  font-size: 1.125rem;
  text-align: center;
  border: 1px solid #ddd;
}
.input-field input:focus {
  box-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
}
.input-field input::-webkit-inner-spin-button,
.input-field input::-webkit-outer-spin-button {
  display: none;
}
form button {
  margin-top: 25px;
  width: 100%;
  color: #fff;
  font-size: 1rem;
  border: none;
  padding: 9px 0;
  cursor: pointer;
  border-radius: 6px;
  pointer-events: none;
  background: #6e93f7;
  transition: all 0.2s ease;
}
form button.active {
  background: #4070f4;
  pointer-events: auto;
}
form button:hover {
  background: #0e4bf1;
}

.error-message {
  color: red;
  font-weight: bold;
  margin-top: 10px;
  display: none;
}

.error-message.visible {
  display: block;
}

  </style>
</head>
<body>

<div class="container">
    <header>
        <i class="bx bxs-check-shield"></i>
    </header>
    <h4>Enter OTP Code</h4>
    <form id="otp-form" action="php/verify_otp1.php" method="post">
        <div class="error-text"></div>
        <div class="input-field">
            <input type="number" maxlength="1"/>
            <input type="number" maxlength="1" disabled />
            <input type="number" maxlength="1" disabled />
            <input type="number" maxlength="1" disabled />
            <input type="number" maxlength="1" disabled />
            <input type="number" maxlength="1" disabled />
        </div>
        <button type="submit" id="verify-button" >Verify OTP</button>
    </form>

      
</div>

<script src="js/verify_passOtp.js"></script>


</body>
</html>