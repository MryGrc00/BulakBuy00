<?php
session_start();
require_once '../php/dbhelper.php'; // Using require_once ensures the script stops if the file is missing.

// Redirect non-sellers or unauthenticated users to the login page
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "seller") {
    header("Location: ../login.php");
    exit(); // Stop script execution after a header redirect
}

$userId = $_SESSION["user_id"];

function getSales( $userId) {
    $pdo = dbConnect(); // Assuming dbConnect() returns a PDO connection
    $sql = "SELECT sales.*, 
                   CONCAT(customers.first_name, ' ', customers.last_name) AS customer_name,
                   DATE_FORMAT(sales.sales_date, '%Y-%m-%d') AS sale_date, 
                   DATE_FORMAT(sales.sales_date, '%H:%i:%s') AS sale_time
            FROM sales 
            JOIN shops ON sales.shop_id = shops.shop_id 
            JOIN users AS sellers ON shops.owner_id = sellers.user_id 
            JOIN users AS customers ON sales.customer_id = customers.user_id 
            WHERE sellers.user_id = :userId";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getSubscriptions($userId) {
    $pdo = dbConnect(); 
    $sql = "SELECT 
                DATE_FORMAT(subscription.s_date, '%Y-%m-%d') AS start_date, 
                DATE_FORMAT(subscription.s_date, '%H:%i:%s') AS start_time,
                DATE_FORMAT(subscription.e_date, '%Y-%m-%d') AS end_date,
                DATE_FORMAT(subscription.e_date, '%H:%i:%s') AS end_time
            FROM subscription
            JOIN shops ON subscription.shop_id = shops.shop_id
            JOIN users ON shops.owner_id = users.user_id
            WHERE users.user_id = :userId";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$sales = getSales($userId);
$subscriptions = getSubscriptions($userId);


?>


<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Transaction History</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/transaction_history.css">
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg">
                <!-- Logo -->
                <a class="navbar-brand d-flex align-items-center" href="vendor_home.php">
                    <img src="../php/images/logo.png" alt="BulakBuy Logo" class="img-fluid logo">
                </a>
                <!-- Search Bar -->
                <div class="navbar-collapse justify-content-md-center">
                    <ul class="navbar-nav dib">
                        <li class="nav-item">
                            <form class="form-inline my-2 my-lg-0">
                                <a href=""><i class="fa fa-search"></i></a>
                                <input type="text"  class="form-control form-input" placeholder="Search" style="text-align:left;padding-left: 15px;font-size: 16px;">
                                <a href="vendor_home.php" id="back-link"><i class="back fa fa-angle-left" aria-hidden="true"></i></a>
                                <div id="search-results">Transaction History</div>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
            <hr class="nav-hr">
        </header>
        <main class="main">
            <div class="container">
            <?php foreach ($sales as $sale): ?>                    
                <div class="column1">
                    <div class="transaction-details">
                    <?php if ($sale['paymode'] == 'gcash'): ?>
                        <img src="../php/images/gcash.png" alt="GCash Logo">
                    <?php else: ?>
                        <!-- Display a default image for COD or other paymodes -->
                        <img src="../php/images/cod.jpg" alt="Default Image">
                    <?php endif; ?>                       
                     <div class="text-content">
                            <div class="transact">
                                <span class="transaction-status">Receive</span>
                                <span class="transaction-price">₱ <?php echo htmlspecialchars($sale['amount']); ?></span>
                            </div>
                            <div class="status">
                                <span class="transaction-description">From <?php echo htmlspecialchars($sale['customer_name']); ?></span>
                                <span class="transact-status">Successful</span>
                            </div>
                            <div class="o-date-time">
                                <span class="transaction-date"> <?php echo htmlspecialchars($sale['sale_date']); ?></span>
                                <span class="transaction-time"> <?php echo htmlspecialchars($sale['sale_time']); ?></span>
                            </div>
                        </div>
                    </div>
                    <hr class="transaction-hr">
                    <?php endforeach; ?>
                    <?php foreach ($subscriptions as $subscription): ?>                    
                    <div class="transaction-details">
                        <img src="https://logos-download.com/wp-content/uploads/2020/06/GCash_Logo.png" alt="Product Image">
                        <div class="text-content">
                            <div class="transact">
                                <span class="transaction-status">Sent</span>
                                <span class="transaction-price">₱ 249</span>
                            </div>
                            <div class="status">
                                <span class="transaction-description">To BulakBuy</span>
                                <span class="transact-status">Successful</span>
                            </div>
                            <div class="o-date-time">
                                <span class="transaction-date"> <?php echo htmlspecialchars($subscription['start_date']); ?></span>
                                <span class="transaction-time"><?php echo htmlspecialchars($subscription['start_time']); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    </body>
</html>