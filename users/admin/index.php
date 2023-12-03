<?php
session_start();
include('../php/dbhelper.php'); 

$alert = "";

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $pword = md5($_POST["password"]); 

    try {
        $conn = dbconnect();

        // Prepare and execute SQL statement for the admin table
        $stmt = $conn->prepare("SELECT * FROM `users` WHERE email = :email AND password = :password");
        $stmt->execute(['email' => $email, 'password' => $pword]);

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set session variables
            $_SESSION["user_id"] = $user["user_id"]; 
            $_SESSION["role"] = $user["role"]; // Set the user's role in the session

            // Redirect based on the user's role
            if ($user['role'] != 'admin') {
                $alert = "Access denied. You are not authorized to access this page.";
            } else {
                // Define status
                $status = 'Active Now';

                // Update last login time and status
                $updateStmt = $conn->prepare("UPDATE `users` SET status = :status WHERE user_id = :user_id");
                $updateStmt->execute(['user_id' => $user["user_id"], 'status' => $status]);

                // Redirect to an admin dashboard
                header("Location: dashboard.php");
                exit();
            }
        } else {
            $alert = "Invalid email or password.";
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

?>






<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">  
  <script src="https://code.jquery.com/jquery-3.4.0.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" /> 
  <link rel="stylesheet" href="../../css/login1.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
    <?php if (!empty($alert)) { ?>
      .alert {
        background-color: #f44336; /* Red background color for error */
        color: white;
        padding: 10px;
        width: 70%;
        border-radius: 15px;
        border: 2px solid #d32f2f; /* Darker red border color */
        margin-top: 40px;
        margin-bottom: -2px;
      }

      .alert button.close {
        color: white;
      }
    <?php } ?>
</style>

<body>
    <div class="content-container">
      <div class="input-box">
        <header>
          <h3>Welcome to</h3>
          <img src='../php/images/logo.png' alt="BulakBuy Logo">
        </header>
        <?php if (!empty($alert)) { ?>
            <div id="alert" class="alert alert-danger alert-dismissible fade show" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <?php echo $alert; ?>
            </div>
          <?php } ?>
        <br>
        <form action="" method="post"> 
          <div class="form-group">
            <input type="text" class="input form-control" name="email" id="email" required placeholder="Email"> 
          </div>
          <div class="form-group">
          <input type="password" class="input form-control" name="password" id="password" placeholder="Password" required>
              <span class="toggle-password" id="togglePassword">
                  <i class="bi bi-eye-slash"></i>
              </span>
          </div>
          <div class="text-center">
            <button type="submit" class="btn" name="login" value="LOGIN">Login</button>
          </div>
        </form>
      </div>
    </div>  
    <script src="../js/show-hide-pass.js"></script>

  <script>
    // Function to hide the alert after 5 seconds
    function hideAlert() {
      var alert = document.getElementById('alert');
      if (alert) {
        setTimeout(function() {
          alert.style.display = 'none';
        }, 5000); // 5000 milliseconds (5 seconds)
      }
    }

    // Call the hideAlert function when the page loads
    window.onload = hideAlert;
  </script>

</body>
</html>