<?php
session_start();
include_once "php/dbhelper.php"; // Include dbhelper.php which contains the dbconnect() function

$conn = dbconnect(); // Establish database connection using dbconnect() function from dbhelper.php

if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
}

$sql = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
$sql->bindParam(':user_id', $_SESSION['user_id']);
$sql->execute();

if ($sql->rowCount() > 0) {
    $row = $sql->fetch(PDO::FETCH_ASSOC);
}

// Close the database connection
$conn = null;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Messages</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
</head>

<body>

    <div class="wrapper">
    <div class="navbar-collapse justify-content-md-center">
            <ul class="navbar-nav dib">
                <li class="nav-item">
                    <form class="form-inline my-2 my-lg-0">
                    <a href="javascript:void(0);" onclick="goBack()">
                          <i class="back fa fa-angle-left" aria-hidden="true"></i>
                          <div id="search-results">Messages</div>
                        </a>
                        
                    </form>
                </li>     
            </ul>
        </div>
        <hr>
        <section class="users">
            <header>
                <div class="content">
                    <img src="images/<?php echo $row['profile_img']; ?>" alt="">
                    <div class="details">
                        <span><?php echo $row['first_name'] . " " . $row['last_name'] ?></span>
                        <p><?php echo $row['status']; ?></p>
                    </div>
                </div>
            </header>
            <div class="search">
                <span class="text">Select a user to start chat</span>
                <input type="text" placeholder="Enter name to search...">
                <button><i class="fas fa-search"></i></button>
            </div>

            <div class="users-list">

            </div>
        </section>
    </div>

    <script src="js/users.js"></script>
    <script>
      function goBack() {
          window.history.back();
      }
    </script>

</body>

</html>
