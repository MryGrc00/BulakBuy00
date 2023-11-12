<?php
session_start();

// Include dbhelper.php to use the dbconnect() function
include_once "php/dbhelper.php";

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    header("Location: new_password.php"); 
} else {
    header("Location: forgot_password.php"); 
    exit();
}
// Establish database connection using dbconnect() function
$conn = dbconnect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the new password and confirm password from the form submission
    $newPassword = $_POST["password"];
    $confirmPassword = $_POST["c_password"];

    // Check if the new password matches the confirm password
    if ($newPassword === $confirmPassword) {
        // Hash the password using MD5 hashing algorithm
        $hashedPassword = md5($newPassword);

        // Retrieve the email from the session variable
        $email = $_SESSION["email"];

        // Update the user's password using PDO prepared statement
        $stmt = $conn->prepare("UPDATE users SET password = :password WHERE email = :email");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            // Password updated successfully, you can redirect to a success page or login page
            header("Location: login.php");
            exit();
        } else {
            // Error updating password, handle it accordingly
            echo "Error updating password.";
        }
    } else {
        // Passwords do not match, show an error message or handle it accordingly
        echo "Passwords do not match.";
    }
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

    
    <title>Change Password</title>
</head>
<body>
  
<div class="container">
    <main>
        <div class="container-fluid mt-5">
            <div class="row fw-semibold">
                <h2>Enter New Password</h2>
                <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="error-text"></div>
                <div class="form-group">
                        <div class="password-input-container">
                            <input type="password" class="form-control bg-light rounded" name="password" id="password" placeholder="Password" required>
                            <span class="toggle-password" id="togglePassword">
                                <i class="bi bi-eye-slash"></i>
                            </span>
                        </div>
                        <br>
                        <div class="password-input-container">
                            <input type="password" class="form-control bg-light rounded" name="c_password" id="c_password" placeholder="Confirm Password" required>
                            <span class="toggle-password" id="toggleCPassword">
                                <i class="bi bi-eye-slash"></i>
                            </span>
                        </div>
                        <br><br>
                          <div class="button">
                             <input type="submit" class="btn btn-primary w-100" name="submit" value="Register">
                         </div>
                        <br>
                    </div>
                </form>                


            </div>
        </div>
    </div>
    </main>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("c_password");
    const errorText = document.querySelector(".error-text");

    document.querySelector("form").addEventListener("submit", function(event) {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (password !== confirmPassword) {
            event.preventDefault();
            errorText.textContent = "Passwords do not match.";
            errorText.style.display = "block";
        }
    });

    // Rest of your show/hide password logic (if any) can go here
});
</script>

    <script src="js/show-hide-pass.js"></script>



</body>
</html>
