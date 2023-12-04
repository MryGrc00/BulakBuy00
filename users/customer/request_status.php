<?php
session_start();
include '../php/dbhelper.php';

if (isset($_SESSION['user_id']) &&  isset($_GET['service_id'])){
	$user_id = $_SESSION['user_id'];
	$service_id = $_GET['service_id'];

	// Fetch product details from the product table
	$details= getServiceDetails("servicedetails", "services", "users", $service_id, $user_id);
}
?>

<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order Delivered</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/request_status.css">
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg">
                <!-- Logo -->
                <a class="navbar-brand d-flex align-items-center" href="customer_home.php">
                    <img src="../php/images/logo.png" alt="BulakBuy Logo" class="img-fluid logo">
                </a>
                <!-- Search Bar -->
                <div class="navbar-collapse justify-content-md-center">
                    <ul class="navbar-nav dib">
                        <li class="nav-item">
                            <form class="form-inline my-2 my-lg-0">
                                <a href=""><i class="fa fa-search"></i></a>
                                <input type="text"  class="form-control form-input" placeholder="Search" style="text-align:left;padding-left: 15px;font-size: 16px;">
                                <a href="javascript:void(0);" onclick="goBack()">
									<i class="back fa fa-angle-left" aria-hidden="true"></i>
									<div id="search-results">Request Status</div>
								  </a>
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
					<div class="cart-container">
						<div class="all-items">
							<h6 class="items-label">Request ID - 8475645328</h6>
						</div>
						<div class="cart-item">
							<div class="custom-checkbox" style="margin-top:-30px"><img alt="Product 1" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0V0dWxknmztw6-3Kea1Cr6s1qNe-MdqJ-5k7k99JKt04adfSN5iGni2uYZ1jLqwjRR5c&usqp=CAU"></div>
							<div class="item-details">
								<h2>John Bryan Flower Shop</h2>
								<div class="loc">
									<p class="location">Lahug, Cebu City</p>
								</div>
								<div class="num">
									<p class="number">(+63)9785654543</p>
								</div>
								<p class="price">₱ 300 / hr</p>
							</div>
						</div>
						<hr class="cart-hr">
						<div class="timeline">
							<?php

							$status = $details["status"];
						// Display the corresponding HTML based on the status
                    if ($status === 'Pending') {
                        echo '<div class="status">
                                <div class="status-icon">1</div>
                                <div class="status-info">
                                    <div class="status-text">Order Placed</div>
                                    <div class="status-date">on 23 July 20233</div>
                                </div>
                               
                            </div>';
                    } elseif ($status === 'Processing') {
                        echo '<div class="status">
                                <div class="status-icon">1</div>
                                <div class="status-info">
                                    <div class="status-text">Order Placed</div>
                                    <div class="status-date">on 23 July 20233</div>
                                </div>
                                <div class="vertical-line"></div>
                                <!-- Vertical line -->
                            </div>
                            <div class="status">
                                <div class="status-icon">2</div>
                                <div class="status-info">
                                    <div class="status-text">Processing</div>
                                    <div class="status-date">seller is processing order details</div>
                                </div>
                            
                            </div>';
                    } elseif ($status === 'To Deliver') {
                        echo '<div class="status">
                                <div class="status-icon">1</div>
                                <div class="status-info">
                                    <div class="status-text">Order Placed</div>
                                    <div class="status-date">on 23 July 20233</div>
                                </div>
                                <div class="vertical-line"></div>
                                <!-- Vertical line -->
                            </div>
                            <div class="status">
                                <div class="status-icon">2</div>
                                <div class="status-info">
                                    <div class="status-text">Processing</div>
                                    <div class="status-date">seller is processing order details</div>
                                </div>
                                <div class="vertical-line"></div>
                                <!-- Vertical line -->
                            </div>
                            <div class="status">
                                <div class="status-icon">3</div>
                                <div class="status-info">
                                    <div class="status-text">In Transit</div>
                                    <div class="status-date">order is on the way</div>
                                </div>
                            
                            </div>';
                    } elseif ($status === 'Completed') {
                        echo '<div class="status">
                                <div class="status-icon">1</div>
                                <div class="status-info">
                                    <div class="status-text">Order Placed</div>
                                    <div class="status-date">on 23 July 20233</div>
                                </div>
                                <div class="vertical-line"></div>
                                <!-- Vertical line -->
                            </div>
                            <div class="status">
                                <div class="status-icon">2</div>
                                <div class="status-info">
                                    <div class="status-text">Processing</div>
                                    <div class="status-date">seller is processing order details</div>
                                </div>
                                <div class="vertical-line"></div>
                                <!-- Vertical line -->
                            </div>
                            <div class="status">
                                <div class="status-icon">3</div>
                                <div class="status-info">
                                    <div class="status-text">In Transit</div>
                                    <div class="status-date">order is on the way</div>
                                </div>
                                <div class="vertical-line"></div>
                                <!-- Vertical line -->
                            </div>
                            <div class="status">
                                <div class="status-icon">4</div>
                                <div class="status-info">
                                    <div class="status-text">Delivered</div>
                                    <div class="status-date">September 7, 2023</div>
                                </div>
                            </div>';
                    } else {
                        echo "Invalid status.";
                    }
                
				?>
						</div>
						<div class="all-items">
							<h6 class="items-label">Request Details</h6>
						</div>
						<div class="dev-details">
							<p><?php echo $order["customer_first_name"]. " " . $order["customer_last_name"]; ?></p>
							<p><?php echo $order["customer_address"]; ?></p>
							<p><?php echo $order["customer_phone"]; ?></p>
						</div>
					</div>
				</div>
				<div class="column2">
					<div class="summary-container">
						<div class="order-summary">
							<h6 class="order-label">Request Summary</h6>
						</div>
						<div class="summary-items">
							<div class="sub-total">
								<div class="sched-date">
									<p class="sched">Date</p>
									<p class="date"><?php echo $order["date"]; ?></p>
								</div>
								<div class="sched-time">
									<p class="sched">Time</p>
									<p class="date"><?php echo $order["time"]; ?></p>
								</div>
								<div class="product-price">
									<p class="product">Service Price</p>
									<p class="order-price">₱								<div class="sched-date">
									<p class="sched">Date</p>
									<p class="date"><?php echo $order["amount"]; ?></p>
								</div></p><br>
								</div>
								<div class="service-hours">
									<p class="hours-label">Hours</p>
									<p class="hours" id="display-hour"></p>
									<br>
								</div>
								<div class="total-payment">
									<p class="total">Total</p>
									<p class="t-payment">₱ 3,000</p><br>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
		<script>
			function goBack() {
				window.history.back();
			}
		  </script>
    </body>
</html>