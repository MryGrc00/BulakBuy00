<?php
session_start();
include_once "../php/dbhelper.php"; // Include dbhelper.php which contains the dbconnect() function

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
        <title>Seller Settings</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/settings.css">
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg">
                <!-- Logo -->
                <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="../../images/logo.png" alt="BulakBuy Logo" class="img-fluid logo">
                </a>
                <!-- Search Bar -->
                <div class="navbar-collapse justify-content-md-center">
                    <ul class="navbar-nav dib">
                        <li class="nav-item">
                            <form class="form-inline my-2 my-lg-0">
                                <a href=""><i class="fa fa-search"></i></a>
                                <input type="text"  class="form-control form-input" placeholder="Search">
                                <a href="vendor_home.php"><i class="back fa fa-angle-left" aria-hidden="true"></i></a>
                                <div id="search-results">Settings</div>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
            <hr class="nav-hr">
        </header>
        <main class="main">
            <section>
                <div class="vertical-container">
                    <a href="#">
                        <div class="link-content">
                            <i class="bi bi-key"></i>
                            <span class="label1">Change Password</span>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="manage_address.html">
                        <div class="link-content">
                            <i class="bi bi-house-door"></i>
                            <span class="label1">Manage Address</span>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="../php/logout.php?logout_id=<?php echo $row['user_id']; ?>">
                        <div class="link-content">
                            <i class="bi bi-box-arrow-right logout"></i>
                            <span class="label1">Logout</span>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>

                </div>
            </section>
        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>