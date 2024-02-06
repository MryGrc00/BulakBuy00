<?php
session_start();
require_once '../php/dbhelper.php'; // Using require_once ensures the script stops if the file is missing.

// Redirect non-sellers or unauthenticated users to the login page
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "arranger") {
    header("Location: ../index.php");
    exit(); // Stop script execution after a header redirect
}

$userId = $_SESSION["user_id"];

function getProductSales( $userId) {
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
function getServiceSales($userId) {
    $pdo = dbConnect(); // Assuming dbConnect() returns a PDO connection
    $sql = "SELECT servicedetails.*, 
                   CONCAT(customers.first_name, ' ', customers.last_name) AS customer_name,
                   DATE_FORMAT(servicedetails.date, '%Y-%m-%d') AS service_date, 
                   DATE_FORMAT(servicedetails.time, '%H:%i:%s') AS service_time
            FROM servicedetails 
            JOIN services ON servicedetails.service_id = services.service_id 
            JOIN users AS customers ON servicedetails.customer_id = customers.user_id 
            JOIN users AS arrangers ON services.arranger_id = arrangers.user_id
            WHERE arrangers.user_id = :userId";

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

function getUnifiedDate($item) {
    // Checking the type of transaction and returning the appropriate date
    if ($item['type'] == 'product') {
        return $item['sale_date'];
    } else if ($item['type'] == 'service') {
        return $item['service_date'];
    } else {
        // Assuming subscription type
        return $item['start_date'];
    }
}

$products = getProductSales($userId);
$subscriptions = getSubscriptions($userId);
$services = getServiceSales($userId);

// Add a type identifier to each item
foreach ($products as $key => $value) {
    $products[$key]['type'] = 'product';
}
foreach ($services as $key => $value) {
    $services[$key]['type'] = 'service';
}
foreach ($subscriptions as $key => $value) {
    $subscriptions[$key]['type'] = 'subscription';
}

// Merge the arrays
$allTransactions = array_merge($products, $services, $subscriptions);

usort($allTransactions, function($a, $b) {
    $dateA = getUnifiedDate($a);
    $dateB = getUnifiedDate($b);
    return strtotime($dateB) - strtotime($dateA); // Compare in reverse order
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
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;   700&display=swap");
* {
	margin: 0;
	padding: 0;
	box-sizing: border-box;
	font-family: "Poppins", sans-serif;
}
.navbar img {
	padding: 0;
	width: 195px;
	height: 100px;
	margin-top: -10px;
	margin-left: 186%;
}
.form {
	position: relative;
	color: #8e8e8e;
	left: 130px;
}
.form-inline .fa-search {
	position: absolute;
	top: 43px;
	left: 78%;
	color: #9ca3af;
	font-size: 22px;
}
.form-input[type="text"] {
	height: 50px;
	width: 500px;
	background-color: #f0f0f0;
	border-radius: 10px;
	margin-left: 430px;
	margin-top: -10px;
}
.nav-hr {
	width: 60%;
	margin: auto;
	margin-top: -6px;
}
#search-results {
	display: none;
}
.back {
	display: none;
}
.container {
	margin-top: 40px;
	display: flex;
	flex-direction: column;
	gap: 20px;
	box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
	border-radius: 10px;
}
.column1 {
	display: flex;
	flex-direction: column;
	margin: 0;
	margin-left: 0;
	margin-bottom: 40px;
}
.transaction-details {
	display: flex;
}
.transaction-details img {
	max-width: 80px;
	max-height: 75px;
	margin-right: 0px;
	margin-top: 25px;
	margin-left: 0px;
}
.text-content {
	display: flex;
	flex-direction: column;
	align-items: flex-start;
	margin-top: 20px;
	margin-left: 10px;
	color: #666;
	flex: 1;
	padding-left: 10px;
}
.transaction-status, .transaction-description, .o-date-time {
	margin-bottom: 5px;
}           
.transaction-status {
	color: #222;
	margin-top: 10px;
	font-size: 15px;
}
.transaction-price {
	position: absolute;
	transform: translateX(50%);
	left: 73%;
}
.transact-status {
	position: absolute;
	transform: translateX(50%);
	left: 72%;
	margin-top: 3px;
	font-size: 13px;
	color: #FF7E95;
}
.transaction-description {
	margin-left: -2px;
	font-size: 13px;
	text-align: justify;
}
.o-date-time {
	font-size: 12px;
	margin-bottom: 20px;
}
.transaction-date {
	margin-right: 19px;
}
.transaction-hr {
	width: 100%;
	margin: auto;
	margin-bottom: -10px;
	margin-top: 20px;
}
@media (max-width: 768px) {
	.navbar {
		position: fixed;
		background-color: white;
		width: 100%;
		z-index: 100;
	}
	.navbar img {
		display: none;
	}
	.form-input[type="text"] {
		display: none;
	}
	.nav-hr {
		width: 100%;
	}
	#search-results {
		display: block;
		font-size: 15px;
		margin-left: 15px;
	}
	.back {
		display: block;
		font-size: 20px;
	}
	.back:focus {
		text-decoration: none;
	}
	.form-inline .fa-search {
		display: none;
	}
	.form-inline .back {
		text-decoration: none;
		color: #666;
	}
	.form-inline .fa-angle-left:focus {
		text-decoration: none;
		outline: none;
	}
	.container {
		margin-top: 50px;
		display: flex;
		flex-direction: column;
		gap: 20px;
	}
	.column1 {
		display: flex;
		flex-direction: column;
		margin: 0;
		margin-left: 0;
	}
	.transaction-details {
		display: flex;
	}
	.transaction-details img {
		max-width: 65px;
		max-height: 55px;
		margin-right: 0px;
		margin-top: 25px;
		margin-left: 0px;
	}
	.text-content {
		display: flex;
		flex-direction: column;
		align-items: flex-start;
		margin-top: 20px;
		color: #666;
		flex: 1;
		padding-left: 10px;
	}
}
.transact {
                    white-space:nowrap;
                    margin-top:5px;
                    text-align:right;
} 
	.transaction-status, .transaction-description, .o-date-time {
		margin-bottom: 5px;
	}
	.transaction-status {
		color: #222;
		margin-top: 10px;
		font-size: 13px;
	}
	.transaction-price {
		margin-left:-70px;
        display: inline-block;
        width: 100px; /* Adjust the width as needed */
		font-size: 13px;
	}
	.transact-status {
		position: absolute;
		transform: translateX(50%);
		left: 70%;
		margin-top: 3px;
		font-size: 12px;
		color: #FF7E95;
	}
	.transaction-description {
		margin-left: -2px;
		font-size: 12px;
		text-align: justify;
	}
	.o-date-time {
		font-size: 10px;
		margin-bottom: 20px;
	}
	.transaction-date {
		margin-right: 19px;
	}
	.transaction-hr {
		width: 100%;
		margin: auto;
		margin-bottom: -10px;
		margin-top: 0px;
	}
}
        </style>
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg">
                <!-- Logo -->
                <a class="navbar-brand d-flex align-items-center" href="arranger_home.php">
                    <img src="../php/images/logo.png" alt="BulakBuy Logo" class="img-fluid logo">
                </a>
                <!-- Search Bar -->
                <div class="navbar-collapse justify-content-md-center">
                    <ul class="navbar-nav dib">
                        <li class="nav-item">
                            <form class="form-inline my-2 my-lg-0">
                                <a href=""><i class="fa fa-search"></i></a>
                                <input type="text"  class="form-control form-input" placeholder="Search" style="text-align:left;padding-left: 15px;font-size: 16px;">
                                <a href="arranger_home.php" id="back-link"><i class="back fa fa-angle-left" aria-hidden="true"></i></a>
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
            <?php foreach ($allTransactions as $transaction): ?>
                    <div class="column1">
                        <div class="transaction-details">
                            <!-- Check transaction type and display appropriate image -->
                            <?php if ($transaction['type'] == 'product' && $transaction['paymode'] == 'gcash'): ?>
                                <img src="../php/images/gcash.png" alt="GCash Logo">
                            <?php elseif ($transaction['type'] == 'product' && $transaction['paymode'] == 'cod'): ?>
                                <!-- Display a default image for COD or other paymodes -->
                                <img src="../php/images/cod.jpg" alt="Default Image">
                            <?php else: ?>
                                <!-- Display a generic image for services and subscriptions -->
                                <img src="../php/images/cod.jpg" alt="Default Image">
                            <?php endif; ?>
                            
                            <div class="text-content">
                                <!-- Transaction details based on type -->
                                <div class="transact">
                                    <span class="transaction-status"><?php echo $transaction['type'] == 'subscription' ? 'Sent' : 'Receive'; ?></span>
                                    <span class="transaction-price">₱ <?php echo htmlspecialchars($transaction['amount'] ?? 249); // Default amount for subscriptions ?></span>
                                </div>
                                <div class="status">
                                    <span class="transaction-description"><?php echo htmlspecialchars($transaction['customer_name'] ?? 'To BulakBuy'); // Default for subscriptions ?></span>
                                    <span class="transact-status" style="color:#666">Successful</span>
                                </div>
                                <div class="o-date-time">
                                    <!-- Display date and time based on transaction type -->
                                    <span class="transaction-date"><?php echo htmlspecialchars($transaction['sale_date'] ?? $transaction['service_date'] ?? $transaction['start_date']); ?></span>
                                    <span class="transaction-time"><?php echo htmlspecialchars($transaction['sale_time'] ?? $transaction['service_time'] ?? $transaction['start_time']); ?></span>
                                </div>
                            </div>
                        </div>
                        <hr class="transaction-hr">
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
        <!-- ...
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    </body>
</html>