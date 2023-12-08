<?php
session_start();
require_once '../php/dbhelper.php'; // Using require_once ensures the script stops if the file is missing.

// Redirect non-sellers or unauthenticated users to the login page
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "customer") {
    header("Location: ../login.php");
    exit(); // Stop script execution after a header redirect
}

$userId = $_SESSION["user_id"];

function getProductSales($userId) {
    $pdo = dbConnect(); 
    $sql = "SELECT sales.*, 
                   CONCAT(owners.first_name, ' ', owners.last_name) AS merchant_name,
                   DATE_FORMAT(sales.sales_date, '%Y-%m-%d') AS sale_date, 
                   DATE_FORMAT(sales.sales_date, '%H:%i:%s') AS sale_time
            FROM sales 
            JOIN shops ON sales.shop_id = shops.shop_id 
            JOIN users AS owners ON shops.owner_id = owners.user_id 
            WHERE sales.customer_id = :userId";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getServiceSales($userId) {
    $pdo = dbConnect(); // Assuming dbConnect() returns a PDO connection
    $sql = "SELECT servicedetails.*, 
                   CONCAT(arrangers.first_name, ' ', arrangers.last_name) AS merchant_name,
                   DATE_FORMAT(servicedetails.date, '%Y-%m-%d') AS service_date, 
                   DATE_FORMAT(servicedetails.time, '%H:%i:%s') AS service_time
            FROM servicedetails 
            JOIN services ON servicedetails.service_id = services.service_id 
            JOIN users AS arrangers ON services.arranger_id = arrangers.user_id
            WHERE servicedetails.customer_id = :userId";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$products = getProductSales($userId);
$services = getServiceSales($userId);

foreach ($products as $key => $value) {
    $products[$key]['type'] = 'product';
    $products[$key]['datetime'] = $products[$key]['sale_date'] . ' ' . $products[$key]['sale_time']; // Combine date and time
}

// Add a type identifier to each service
foreach ($services as $key => $value) {
    $services[$key]['type'] = 'service';
    $services[$key]['datetime'] = $services[$key]['service_date'] . ' ' . $services[$key]['service_time']; // Combine date and time
}

// Merge and sort the arrays by datetime
$allTransactions = array_merge($products, $services);
usort($allTransactions, function($a, $b) {
    return strtotime($b['datetime']) - strtotime($a['datetime']); // Sort by descending order
});
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
                                <a href="customer_profile.php" id="back-link"><i class="back fa fa-angle-left" aria-hidden="true"></i></a>
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
                <div class="column1">
                <?php foreach ($allTransactions as $transaction): ?>
                    <div class="column1">
                        <div class="transaction-details">
                            <!-- Check transaction type and display appropriate image -->
                            <?php if ($transaction['type'] == 'product'): ?>
                                <?php if (isset($transaction['paymode']) && $transaction['paymode'] == 'gcash'): ?>
                                    <img src="../php/images/gcash.png" alt="GCash Logo">
                                <?php else: ?>
                                    <!-- Display a default image for COD or other paymodes -->
                                    <img src="../php/images/cod.jpg" alt="Default Image">
                                <?php endif; ?>
                            <?php else: ?>
                                <!-- Display a generic image for services -->
                                <img src="https://logos-download.com/wp-content/uploads/2020/06/GCash_Logo.png" alt="Transaction Image">
                            <?php endif; ?>

                            <div class="text-content">
                                <!-- Transaction details based on type -->
                                <div class="transact">
                                <span class="transaction-status">Sent</span>
                                    <span class="transaction-price">₱ <?php echo htmlspecialchars($transaction['amount'] ?? 'N/A'); ?></span>
                                </div>
                                <div class="status">
                                    <span class="transaction-description">To <?php echo htmlspecialchars($transaction['merchant_name'] ?? 'N/A'); ?></span>
                                    <span class="transact-status" style="color:#666">Successful</span>
                                </div>
                                <div class="o-date-time">
                                    <!-- Display date and time -->
                                    <span class="transaction-date"><?php echo htmlspecialchars($transaction['datetime'] ?? 'N/A'); ?></span>
                                </div>
                            </div>
                        </div>
                        <hr class="transaction-hr">
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