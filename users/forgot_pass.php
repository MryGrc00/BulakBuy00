

<?php
session_start();
include_once "php/dbhelper.php";
include_once "php/mail.php"; 

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
            $newOTP = generateOTP(); 

            // Update the OTP in the database for the given email
            $updateStmt = $conn->prepare("UPDATE users SET otp = :otp WHERE email = :email");
            $updateStmt->bindParam(':otp', $newOTP);
            $updateStmt->bindParam(':email', $email);
            $updateStmt->execute();

            // Send the new OTP via email using the sendOTP function from mail.php
            if (sendOTP($email, $newOTP)) {
                $_SESSION['email'] = $email; 
                $_SESSION['user_id'] = $user_id; 
                header("Location: verify_password.php");
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
    <style>
        <style>
        body{
            background-color:#f5f5f5;
        }
        .container1{
          width:500px;
          margin:auto;
          margin-top: 300px;
          font-family: 'Poppins';
          background-color: white;
          box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
          border-radius: 10px;
          padding:20px;
          padding-bottom:70px;
      }

        .enter{
            color:#666;
            font-size: 17px;
            text-align: center;
             letter-spacing: 0.1rem;
            margin-top: 15px;
            font-weight: 500;
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
            margin-top: 30px;
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
             letter-spacing: 0.1rem;
            font-size: 16px;
        }
        .btn:hover{
            color:#fefefe;
        }
       

        .error-text{
            color: #721c24;
            padding: 8px 10px;
            text-align: center;
            border-radius: 5px;
            background: #f8d7da;
            font-size: 15px;
            border: 1px solid #f5c6cb;
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
            margin-top: 30px;
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
             letter-spacing: 0.1rem;
            font-size: 13px;
        }
        .btn:hover{
            color:#fefefe;
        }
       
        .container1{
            margin-top: -10px;
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
<div class="container1">
    <main>
        <div class="container-fluid mt-5">
            <div class="row fw-semibold">
                     <h2 class="enter">Enter Your Email</h2>
                <div class="error-text"></div>
                <div class="success-text"></div>
                <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="form-group">
                    <input type="text" class="form-control bg-light rounded" name="email" id="email"     placeholder="Email" required>
                </div>
                    <div class="form-group">
                        <br>
                        <div class="button">
                            <input type="submit" name="submit" class="btn" value="Verify"></button>
                        </div>
                        <br>
                    </div>
                </form>                


            </div>
        </div>
    </div>
    </main>

    <script src="js/forgotpass.js"></script>



</body>
</html>
