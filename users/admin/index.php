<?php
session_start();
include('../php/dbhelper.php'); 

$alert = "";

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $pword = $_POST["password"]; // Consider a more secure hashing method

    try {
        $conn = dbconnect();

        // Prepare and execute SQL statement for the admin table
        $stmt = $conn->prepare("SELECT * FROM `admin` WHERE email = :email AND password = :password");
        $stmt->execute(['email' => $email, 'password' => $pword]);

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set session variables
            $_SESSION["admin_id"] = $user["admin_id"]; 

            // Redirect to an admin dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $alert = "Invalid username or password.";
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
    <?php if (!empty($alert_message)) { ?>
      .alert {
        background-color:lightpink;
        color: white;
        padding: 10px;
        width:70%;
        border-radius: 15px;
        border: 2px solid lightpink;
        background-color: transparent;
        margin-top:40px;
        margin-bottom:-2px;
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
        <?php if (!empty($alert_message)) { ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <?php echo $alert_message; ?>
          </div>
        <?php } ?>
        <br>
        <form action="" method="post"> 
          <div class="form-group">
            <input type="text" class="input form-control" name="email" id="email" required placeholder="Email"> 
          </div>
          <div class="form-group">
            <input type="password" class="input form-control" name="password" id="password" required placeholder="Password"> 
            <i class="bi bi-eye-slash" id="togglePassword"></i>
          </div>
          <div class="text-center">
            <button type="submit" class="btn" name="login" value="LOGIN">Login</button>
          </div>
        </form>
      </div>
    </div>  
    <script>
      //event listener for the close button
      document.querySelector('.close').addEventListener('click', function() {
        this.parentNode.parentNode.removeChild(this.parentNode);
      });
    </script>
    <script>
      const togglePassword = document.querySelector("#togglePassword");
      const password = document.querySelector("#password");

      togglePassword.addEventListener("click", function () {
        // toggle the type attribute
        const type = password.getAttribute("type") === "password" ? "text" : "password";
        password.setAttribute("type", type);

        // toggle the icon
        this.classList.toggle("bi-eye");
      });

      // prevent form submit only when the eye icon is clicked
      togglePassword.addEventListener('click', function (e) {
        e.preventDefault();
      });
    </script> 
</body>
</html>