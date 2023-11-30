<?php
session_start();
include '../php/dbhelper.php';  

$daysLeft = null;

if (isset($_SESSION['user_id'])) {
    $pdo = dbconnect();
    $user_id = $_SESSION['user_id'];

    // First, get the shop_id for the logged-in user's shop
    $shopSql = "SELECT shop_id FROM shops WHERE owner_id = :user_id";
    $shopStmt = $pdo->prepare($shopSql);
    $shopStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $shopStmt->execute();
    $shopResult = $shopStmt->fetch(PDO::FETCH_ASSOC);

    if ($shopResult && isset($shopResult['shop_id'])) {
        $shop_id = $shopResult['shop_id'];

        // Now, fetch s_date and e_date from the subscription table using the shop_id
        $subSql = "SELECT s_date, e_date FROM subscription WHERE shop_id = :shop_id";
        $subStmt = $pdo->prepare($subSql);
        $subStmt->bindParam(':shop_id', $shop_id, PDO::PARAM_INT);
        $subStmt->execute();
        $subscription = $subStmt->fetch(PDO::FETCH_ASSOC);

        if ($subscription) {
            // Create DateTime objects for the start and end dates
            $endDate = new DateTime($subscription['e_date']);
            $today = new DateTime();

            // Calculate the interval and get the number of days left
            $interval = $today->diff($endDate);
            $daysLeft = $interval->format('%a');

            if ($daysLeft === '0' || $daysLeft === 0) {
                // Update the status to "Expired"
                $updateSql = "UPDATE subscription SET status = 'expired' WHERE shop_id = :shop_id";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->bindParam(':shop_id', $shop_id, PDO::PARAM_INT);
                $updateStmt->execute();
                $daysLeft = null;   

            }
        }
    }
} else {
    echo "No active session found. Please log in.";
}
?>


<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Subscription</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/subscription.css">
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
                                <input type="text"  class="form-control form-input" placeholder="Search" style="text-align:left;padding-left: 15px;font-size: 16px;">
                                <a href="vendor_home.php" id="back-link"><i class="back fa fa-angle-left" aria-hidden="true"></i></a>
                                <div id="search-results">Subscription</div>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
            <hr class="nav-hr">
        </header>
        <main class="main">
            <div class="container">
                <div class="column1">
                    <div class="subscription-details">
                        <i class="bi bi-cash-coin"></i>
                        <div class="text-content">
                            <div class="subscript">
                                <span class="subscription-status">Monthly Subscription</span>
                            </div>
                            <?php if (isset($daysLeft) && $daysLeft > 0) { 
                                echo "<span class='subscription-expire'>Expires in " . $daysLeft . " days</span>";
                            } ?>
                        
                            <span class="subscription-description">Boost your flower shop's visibility with BulakBuy's monthly subscription.  With featured listings and banners, vendors gain prime exposure, standing out in search results and category listings. This enhanced online presence effectively markets their shop, leading to greater customer interest and business growth.</span>
                            <hr class="subscription-hr ">
                            <div class="price-renew">
                                <span class="subscription-price">₱ 249.00</span>
                                <a href="../../Payments/Subscription/index.php">
                                    <button class="subscription-renew">Renew</button>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>