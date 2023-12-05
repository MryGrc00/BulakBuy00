<?php
session_start();

include_once "php/dbhelper.php";

// Establish database connection using dbconnect() function
$conn = dbconnect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the new password and confirm password from the form submission
    $newPassword = $_POST["password"];
    $confirmPassword = $_POST["c_password"];

    // Check if the new password matches the confirm password
    if ($newPassword === $confirmPassword) {
        $hashedPassword = md5($newPassword);

        // Retrieve the email from the session variable
        $email = $_SESSION["email"];

        // Update the user's password using PDO prepared statement
        $stmt = $conn->prepare("UPDATE users SET password = :password WHERE email = :email");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            // Password updated successfully

            // Retrieve the user's role from the session variable
            $userRole = $_SESSION["role"];

            // Redirect based on the user's role
            if ($userRole == "admin") {
                header("Location: admin/index.php");
            } else {
                header("Location: login.php");
            }
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
    <style>
         body{
            background-color:#f5f5f5;
        }
        .container{
            width:500px;
            margin:auto;
            margin-top: 180px;
            font-family: 'Poppins';
            background-color: white;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding:20px;
            padding-bottom:70px;
        }

        .row img{
            margin:auto;
            width:230px;
            height:90px;
        }
        .enter{
            color:#666;
            font-size: 17px;
            text-align: center;
             letter-spacing: 0.1rem;
            margin-top: 15px;
            font-weight: 500;
        }
        .form-group{
            margin-top: 20px;
        }
        .form-control {
            /* Add general styling for form controls here */
            padding: 20px;
            border:none;
            width: 440px;
            background-color: #F5F5F5;
            border-radius:10px;
             letter-spacing: 0.1rem;
            color:#888;
            margin-top: 10px;
            outline: none !important;
        }
        .form-control::placeholder {
            font-size: 15px;
            color:#A0A0A0;
        }
        .form-control:focus {
            border:1px solid #fefefe;
            outline:none;
        }
        .btn{
            background-color: #65A5A5;
            color:white;
            width:440px;
            padding:7px;
            border-radius:10px;
            margin-top: 10px;
            margin-top: 10px;
             letter-spacing: 0.1rem;
            font-size: 16px;
        }
        .btn:hover{
            color:#fefefe;
        }
        .password-input-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 45%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        /* Eye icon styles */
        .toggle-password i {
            font-size: 18px;
            color: #999;
        }

        /* Style the eye icon when password is revealed */
        .password-revealed i {
            color: #33b5e5;
        }
        .button{
            margin-top: -20px;
        }
        .container1{
            margin-top: -10px;
        }
        .container1 .row a{
            color:#888;
            font-size: 14px;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
        }
        .error-text{
            color: #721c24;
            padding: 8px 10px;
            text-align: center;
            border-radius: 5px;
            background: #f8d7da;
            font-size: 15px;
            border: 1px solid #f5c6cb;
            margin-top: 20px;
            margin-bottom: 20px;
            display: none;
            font-weight: 300;
        }
        @media (max-width: 768px) {
            body{
            background-color:transparent;
            
        }
        .container{
            margin:auto;
            margin-top:70px;
            font-family: 'Poppins';
            padding:20px;
            padding-bottom:30px;
            width: 375px;
            box-shadow: none;
            border-radius: none;
      
        }

        .row img{
            margin:auto;
            width:200px;
            height:80px;
        }
        .enter{
            color:#666;
            font-size: 15px;
            text-align: center;
             letter-spacing: 0.1rem;
            margin-top: 10px;
            font-weight: 500;
        }
        .form-control {
            /* Add general styling for form controls here */
            padding: 20px;
            border:none;
            width: 310px;
            background-color: #F5F5F5;
            border-radius:10px;
             letter-spacing: 0.1rem;
            color:#888;
            margin-top: 20px;
            outline: none !important;
            font-size: 13px;
            border:none;
        }
        .form-control::placeholder {
            font-size: 13px;
            color:#A0A0A0;
        }
        .form-control:focus {
            border:1px solid #fefefe;
            outline:none;
        }
        .button{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .btn{
            background-color: #65A5A5;
            color:white;
            width:350px;
            padding:7px;
            border-radius:10px;
            margin-top: 30px;
             letter-spacing: 0.1rem;
            font-size: 13px;
        }
        .btn:hover{
            color:#fefefe;
        }
        .password-input-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 45%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        /* Eye icon styles */
        .toggle-password i {
            font-size: 18px;
            color: #999;
        }

        /* Style the eye icon when password is revealed */
        .password-revealed i {
            color: #33b5e5;
        }
        .button{
            margin-top: -20px;
        }
        .container1{
            margin-top: -10px;
        }
        .container1 .row a{
            color:#888;
            font-size: 13px;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
        }
        .error-text{
            color: #721c24;
            padding: 8px 10px;
            text-align: center;
            border-radius: 5px;
            background: #f8d7da;
            font-size: 13px;
            border: 1px solid #f5c6cb;
            margin-bottom: 25px;
            display: none;
            font-weight: 300;
        }
        }
    </style>
</head>
<body>
  
<div class="container">
    <main>
        <div class="container-fluid mt-5">
            <div class="row fw-semibold">
                <h2 class="enter">Enter New Password</h2>
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
                             <input type="submit" class="btn " name="submit" value="Register">
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
