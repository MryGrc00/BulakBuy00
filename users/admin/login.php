<?php
session_start();
include "dbhelper.php";

$alert = "";
if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Retrieve user data from database
    $password = md5($password);
    $sql = "SELECT * FROM `users` WHERE username = ? AND password = ? ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) == 1) {
        // Set the user attribute value in the session
        $_SESSION["usertype"] = $user["usertype"];
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["fname"] = $user["fname"];
        $_SESSION["lname"] = $user["lname"];
        $_SESSION["address"] = $user["address"];
        $_SESSION["gender"] = $user["gender"];
        $_SESSION["dob"] = $user["dob"];
        $_SESSION["pnumber"] = $user["pnumber"];

        // Redirect the user to the appropriate dashboard
        if ($user["usertype"] == "Seller") {
            header("Location: seller_product.php");
            exit();
        } else {
            header("Location: home.php");
            exit();
        }
    } else {
        // User does not exist, show an error message
        $alert_message = "Invalid username or password.";
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
  <link rel="stylesheet" href="css/login1.css">
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
          <img src='images/logo.png' alt="BulakBuy Logo">
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
            <input type="text" class="input form-control" name="username" id="username" required placeholder="Username"> 
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