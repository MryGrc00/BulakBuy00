<?php
session_start();
include_once "../php/dbhelper.php"; // Include dbhelper.php which contains the dbconnect() function

$conn = dbconnect(); // Establish database connection using dbconnect() function from dbhelper.php

if (!isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
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
        <title>Arranger Settings</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/settings.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                                <a href="arranger_home.php"><i class="back fa fa-angle-left" aria-hidden="true"></i></a>
                                <div id="search-results">Settings</div>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav><hr class="nav-hr">
        </header>
        <main class="main">
            <section>
                <div class="vertical-container">
                    <a>
                        <div class="link-content">
                            <i class="bi bi-person-plus"></i>
                            <span class="label1">Enable Service</span>
                        </div>
                        <i class="bi bi-toggle-on" id="services-toggle"></i> 
                    </a>
                    <a href="../forgot_password.php">
                        <div class="link-content">
                            <i class="bi bi-key"></i>
                            <span class="label1">Change Password</span>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="edit_shop.php">
                        <div class="link-content">
                            <i class="bi bi-house-door"></i>
                            <span class="label1">Change Shop Details</span>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="../php/logout.php?logout_id=<?php echo $row['user_id']; ?>">
                        <div class="link-content">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="label1">Logout</span>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </section>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
        // Ensure that the DOM is fully loaded before executing the script
        document.addEventListener('DOMContentLoaded', function () {
            // Select the toggle button using its ID
            var servicesToggle = document.getElementById('services-toggle');

            // Check if the element exists to avoid null reference errors
            if (servicesToggle) {
                servicesToggle.addEventListener('click', function() {
                    // Toggle the state of the button
                    servicesToggle.classList.toggle('bi-toggle-on');
                    servicesToggle.classList.toggle('bi-toggle-off');

                    // Determine the new status based on the toggle class
                    var status = servicesToggle.classList.contains('bi-toggle-on') ? 'enabled' : 'disabled';

                    // Send an Ajax request to update the status in the database
                    $.ajax({
                        type: 'POST',
                        url: 'toggle_service_status.php', // The PHP file that will handle the update
                        data: { status: status },
                        success: function(response) {
                            console.log('Response:', response); // You can handle the response here
                        },
                        error: function(xhr, status, error) {
                            console.error('An error occurred:', error); // Error handling
                        }
                    });
                });
            } else {
                console.error('services-toggle button not found');
            }
        });
    </script>

    </body>
</html>