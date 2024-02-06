<?php
    session_start();
    include '../php/dbhelper.php';
?>

<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order Status</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/order_status.css">
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
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
 .nav-hr{
     width:60%;
     margin: auto;
     margin-top:-6px;
}
 .back{
     display: none;
}
 #search-results{
     display:none;
}
 .container {
     display: flex;
     justify-content: space-between;
     gap: 20px;
}
 .column1 {
     flex-basis: 50%;
     margin-top: 10px;
     display: flex;
     flex-direction: column;
}
 .fa.fa-angle-right {
     margin-left: 10px;
     color: #555;
     font-size: 16px;
}
 .number{
     font-size: 13px;
     margin-top: 15px;
}
 .street{
     font-size: 13px;
     margin-top: 20px;
}
 .cart-container {
     width: 120%;
     margin: 20px auto;
     background-color: #fff;
     border-radius: 5px;
     box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
     margin-bottom: 60px;
}
 .all-items{
     display: flex;
     background-color: #f0f0f0;
     padding:8px;
     top:0px;
}
 .items-label{
     font-size: 15px;
     margin-top:2%;
     margin-left:20px;
     color:#555;
}
 .payment-method {
     display: flex;
     margin-left:20px;
     margin-top:20px;
     margin-bottom: 15px;
}
 .wallet{
     color:#666;
     font-size: 20px;
     margin-left:3px;
}
 .payment-method img {
     width: 25px;
     height:20px;
}
 .payment-method input[type="radio"] {
     margin-left: auto;
     margin-right: 25px;
}
 .payment-method label {
     font-size: 14px;
     margin-left:15px;
}
 .cart-item {
     border-radius: 10px;
     padding:15px;
     width:120%;
     display: flex;
     margin-top: 0px;
}
 .cart-item img {
     max-width: 100px;
     max-height: 100px;
     margin-right: 15px;
     margin-top: 35px;
}
 .item-details h2 {
     font-size: 16px;
     color:#555;
}
 .item-details p {
     margin: 5px 0;
}
 .dev-details p{
     font-size: 14px;
     margin-left:20px;
     margin-top: 20px;
     line-height: 70%;
}
 .timeline {
     width: 100%;
     max-width: 800px;
     margin: 0 auto;
     padding: 20px;
     margin-top: 10px;
     margin-bottom: -20px;
}
 .status {
     display: flex;
     align-items: center;
     margin-bottom: 40px;
     position: relative;
    /* Added for vertical lines */
}
 .status-icon {
     width: 30px;
     height: 30px;
     border-radius: 50%;
     background-color: #65A5A5;
     color: white;
     display: flex;
     justify-content: center;
     align-items: center;
     margin-right: 20px;
}
 .status-info {
     flex: 1;
     display: flex;
     flex-direction: column;
    /* Stack text and date vertically */
}
 .status-text {
     font-size: 15px;
}
 .status-date {
     color: #777;
     font-size: 14px;
}
/* Vertical line style */
 .vertical-line {
     position: absolute;
     height: 130%;
     width: 2px;
     background-color: #65A5A5;
    /* Color of the vertical line */
     top: 35px;
     left: 15px;
    /* Adjust as needed */
}
 .flower-type{
     display: flex;
     gap:10px;
     margin-top:-5px;
}
 .flower{
     font-size: 13px;
     color:#777;
}
 .type{
     font-size: 13px;
     color:#666;
}
 .ribbon-color{
     display: flex;
     gap:10px;
     margin-top:-5px;
}
 .ribbon{
     font-size: 13px;
     color:#777;
}
 .color{
     font-size: 13px;
     color:#666;
}
 .price{
     color:#ff7e95;
     font-weight: 500;
     font-size: 15px;
     margin-bottom: 90px;
}
 .cart-hr{
     margin-top:5px;
}
 .column2 {
     flex-basis: 37%;
     margin-top:21px;
}
 .summary-container {
     width: 100%;
     margin-top: 10px ;
     background-color: #fff;
     border-radius: 5px;
     box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}
 .order-summary{
     display: flex;
     background-color: #f0f0f0;
     padding:10px;
     top:0px;
}
 .order-label{
     margin-top:5px;
     margin-left:10px;
}
 .summary-items{
     padding:20px;
}
 .product-price, .total-payment {
     display: flex;
     justify-content: space-between;
     align-items: center;
     margin-bottom: 10px;
}
 .total {
     font-weight: 600;
}
 .product, .total {
     flex: 1;
}
 .order-price, .product {
     font-size: 14px;
}
 .t-payment, .total {
     font-size: 15px;
}
 .order-price, .t-payment {
     flex: 1;
     text-align: right;
}
 .total-item{
     display:none;
}
 .total-price{
     display:none;
}
.quantity{
    font-size: 13px;
    color:#666;


}
/*Responsiveness*/
 @media (max-width: 768px) {
     .navbar{
         position: fixed;
         background-color: white;
         width:100%;
         z-index: 100;
    }
     .navbar img {
         display: none;
    }
     .form-input[type="text"] {
         display: none;
    }
     .nav-hr{
         width:100%;
    }
     a #search-results{
         display: block ;
         font-size: 15px;
         margin-left: 20px;
         color: #555;
         margin-top: -20px;
    }
    a:hover{
        text-decoration: none;
        outline: none;
        border:none;
    }
     .back{
         display: block;
         font-size: 20px;
    }
     .form-inline .fa-search {
         display: none;
    }
     .form-inline .back{
         text-decoration: none;
         color:#666;
    }
     .form-inline .fa-angle-left:focus {
         text-decoration: none;
         outline: none;
    }
     .container {
         margin-top: 40px;
         display: flex;
         flex-direction: column;
         gap: 20px;
    }
     .column1 {
         display: flex;
         flex-direction: column;
         margin:0;
         margin-left:0;
         margin-bottom: 40px;
    }
     .cart-container {
         width:100%;
         margin: 40px auto;
         background-color: #fff;
         border-radius: 5px;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }
     .all-items{
         display: flex;
         background-color: #f0f0f0;
         padding:8px;
         top:0px;
         height:30px 
    }
     .items-label{
         font-size: 13px;
         margin-top:-1px;
         margin-left:10px;
         color:#555;
    }
     .payment-method {
         display: flex;
         margin-left:20px;
         margin-top:20px;
         font-size: 13px;
    }
     .wallet{
         color:#666;
         font-size: 15px;
         margin-left:3px;
    }
     .payment-method img {
         width: 20px;
         height:15px;
    }
     .payment-method input[type="radio"] {
         margin-left: auto;
         margin-right: 25px;
    }
     .payment-method label {
         font-size: 13px;
         margin-left:15px;
    }
     .cart-item {
         display: flex;
         width:100px;
         margin-bottom: 20px;
         padding:20px;
    }
     .cart-hr{
         margin-top:5px;
         width:90%;
         margin: auto;
         margin-bottom: 20px;
    }
     .custom-checkbox {
         width: 50px;
         display: flex;
         margin-top: -20px;
    }
     .item-checkbox {
         margin-right: -1px;
         margin-top: 0px;
    }
     .cart-item img {
         max-width: 80px;
         height:85px;
         max-height: 100px;
         margin-right:-10px;
         margin-top:25px;
         margin-left:0px;
    }
     .item-details{
         margin-top: -10px;
         margin-left:-15px;
    }
	.item-details h2    {
		font-size: 13px;
		margin-top:3px;
		margin-left:60px;
		font-weight: 500;
		font-weight: 400;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        position:sticky;
        width:175px;
	}
     .item-details p {
         margin: 5px 0;
    }

    .ribbon-color{
		display: flex;
		gap:10px;
		margin-left:60px;
        
		
   }
	.ribbon{
		font-size: 12px;
		color:#666;
		
        width:160px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        position: sticky;
   }

     .price{
         color:#666;
         font-weight: 400;
         font-size: 13px;
         display: flex;
         position: absolute;
         left: 41.5%;
         transform: translateX(-47%);
    }
     .timeline {
         width: 100%;
         max-width: 800px;
         margin: 0 auto;
         padding: 20px;
         margin-top: -15px;
         margin-bottom: -5px;
    }
     .status {
         display: flex;
         align-items: center;
         margin-bottom: 20px;
         position: relative;
        /* Added for vertical lines */
    }
     .status-icon {
         width: 20px;
         height: 20px;
         border-radius: 50%;
         background-color: #65A5A5;
         color: white;
         display: flex;
         justify-content: center;
         align-items: center;
         margin-right: 15px;
         font-size: 10px;
    }
     .status-info {
         flex: 1;
         display: flex;
         flex-direction: column;
        /* Stack text and date vertically */
    }
     .status-text {
         font-size: 12px;
    }
     .status-date {
         color: #777;
         font-size: 11px;
    }
    /* Vertical line style */
     .vertical-line {
         position: absolute;
         height: 130%;
         width: 2px;
         background-color: #65A5A5;
        /* Color of the vertical line */
         top: 28px;
         left: 10px;
        /* Adjust as needed */
    }
     .fa.fa-angle-right {
         margin-left: 10px;
         color: #555;
         font-size: 15px;
    }
     .dev-details {
         margin-bottom:20px ;
    }
     .dev-details p{
         font-size: 12px;
         margin-left:20px;
         margin-top: 20px;
         line-height: 60%;
         color:#666;
    }
     .column2 {
         flex-basis: 37%;
         margin-top:21px;
    }
     .border{
         height:8px;
         margin-top:-20px;
         background-color: #f0f0f0;
    }
     .summary-container {
         width: 100%;
         margin-top: -80px ;
         background-color: #fff;
         border-radius: 5px;
         margin-bottom:50px;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }
     .order-summary{
         display: flex;
         background-color: #f0f0f0;
         padding:10px;
    }
     .cash{
         font-size: 12px;
    }
     .order-label{
         margin-top:5px;
         margin-left:10px;
         font-size: 13px;
    }
     .summary-items{
         padding:20px;
    }
     .product-price, .total-payment {
         display: flex;
         justify-content: space-between;
         align-items: center;
         margin-bottom: 10px;
    }
     .total {
         font-weight: 600;
    }
     .product, .total {
         flex: 1;
    }
     .order-price, .product {
         font-size: 13px;
    }
     .t-payment, .total {
         font-size: 14px;
    }
     .order-price, .t-payment {
         flex: 1;
         text-align: right;
    }
     .total-item{
         display:block;
    }
     .total-price{
         display:block;
    }
    .quantity{
    font-size: 12px;
    margin-right:-90%;
    transform: translateX(25%);
    color:#666;
                 }
    /* Media query to adjust alignment for smaller screens */
     @media (max-width: 768px) {
         .container {
             flex-direction: column;
        }
    }
}
 
  
        </style>
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
                                <a href="#" onclick="goBack()">
                                    <i class="back fa fa-angle-left" aria-hidden="true"></i>
                                    <div id="search-results">Order Status</div>
                                  </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav><hr class="nav-hr">
        </header>
        <main class="main">
            <div class="container">
                <div class="column1">
                    <div class="cart-container">
                       
                    <?php

                            if (isset($_SESSION['user_id'])) {
                                $user_id = $_SESSION['user_id'];
                                
                                // Retrieve the user's address and zipcode from the database
                                $user = get_record('users', 'user_id', $user_id);
                            
                                if ($user) {
                                    $address = $user['address']; // Replace 'address' with the actual column name
                                    $zipcode = $user['zipcode']; // Replace 'zipcode' with the actual column name
                                    $phone = $user['phone']; // Replace 'zipcode' with the actual column name
                                }
                            }
                            // Check if product_id is set in the URL
                            if (isset($_GET['sales_id'])) {
                                $sales_id = $_GET['sales_id'];

                                // Fetch product details from the product table
                                $product_details = get_product_details_by_sales_id($sales_id);

                                // Fetch product quantity from the sales_details table
                                $quantity = get_quantity_for_sales_id($sales_id);

                                // Display the product details
                                if ($product_details) {
                                    echo '<div class="all-items">';
                                    echo '<h6 class="items-label">Order Details</h6>';
                                    echo '</div>';
                                    echo '<div class="cart-item">';
                                    echo '<div class="custom-checkbox" style="margin-top:-30px">';
                                    echo '<img src="' . $product_details['product_img'] . '" alt="' . $product_details['product_name'] . '">';
                                    echo '</div>';
                                    echo '<div class="item-details">';
                                    echo '<h2>' . $product_details['product_name'] . '</h2>';
                                    $customization = array();
                                    
                                    // Check if flower type is available
                                    if (!empty($product_details['flower_type'])) {
                                        $customization[] = $product_details['flower_type'];
                                    }
                                
                                    // Check if ribbon color is available
                                    if (!empty($product_details['ribbon_color'])) {
                                        $customization[] = $product_details['ribbon_color'];
                                    }
                                
                                    // Display the customization details
                                    if (!empty($customization)) {
                                        echo '<div class="ribbon-color">';
                                        echo '<p class="ribbon">'. implode(', ', $customization) .'</p>';
                                        echo '</div>';
                                    }
                                
                                    if ($product_details) {
                                        // Initialize a variable to store the message
                                        $message = !empty($product_details['message']) ? $product_details['message'] : 'None';
                                    
                                        // Display the message details
                                        echo '<div class="ribbon-color">';
                                        echo '<p class="ribbon">Message: ' . $message .'</p>';
                                        echo '</div>';
                                    }
                                    echo '<p class="quantity">x ' . $quantity . '</p>';
                                    echo '<p class="price">₱ ' . number_format($product_details['product_price'], 2) . '</p>';
                                    echo '</div>';
                                    echo '</div>';
                                } else {
                                    echo "Product details not found.";
                                }
                            } else {
                                echo "Product ID not provided.";
                            }

                            // Function to fetch product details from the product table
                            function get_product_details_by_sales_id($sales_id) {
                                $conn = dbconnect();
                                $sql = "SELECT p.product_id, p.product_name, p.product_img, p.product_price, sd.quantity, sd.flower_type, sd.ribbon_color, sd.message
                                        FROM sales s
                                        JOIN products p ON s.product_id = p.product_id
                                        JOIN salesdetails sd ON s.salesdetails_id = sd.salesdetails_id
                                        WHERE s.sales_id = ?";
                                try {
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute([$sales_id]);
                                    $product_details = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $conn = null;
                                    return $product_details;
                                } catch (PDOException $e) {
                                    echo $sql . "<br>" . $e->getMessage();
                                    $conn = null;
                                    return false;
                                }
                            }
                            
                            

                            // Function to get quantity from sales_details table
                            function get_quantity_for_sales_id($sales_id) {
                                $conn = dbconnect();
                                // Joining sales and salesdetails tables to get the quantity
                                $sql = "SELECT sd.quantity 
                                        FROM salesdetails sd
                                        JOIN sales s ON sd.salesdetails_id = s.salesdetails_id
                                        WHERE s.sales_id = ?";
                                try {
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute([$sales_id]);
                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $conn = null;
                                    return $result ? $result['quantity'] : 0;
                                } catch (PDOException $e) {
                                    echo $sql . "<br>" . $e->getMessage();
                                    $conn = null;
                                    return 0;
                                }
                            }
                            
                            
                            ?>

                        <hr class="cart-hr">
                        <div class="timeline">
                        <?php
                       
                        // Check if product_id is set in the URL
                        if (isset($_GET['sales_id'])) {
                            $sales_id = $_GET['sales_id'];

                            // Fetch status from the sales table
                            $status = get_order_status_by_sales_id($sales_id);

                    // Display the corresponding HTML based on the status
                    if ($status === 'Pending') {
                        echo '<div class="status">
                                <div class="status-icon">1</div>
                                <div class="status-info">
                                    <div class="status-text">Order Placed</div>
                                </div>
                               
                            </div>';
                    }elseif ($status === 'Cancelled') {
                        echo '<div class="status">
                                <div class="status-icon">1</div>
                                <div class="status-info">
                                    <div class="status-text">Order Cancelled</div>
                                </div>
                               
                            </div>';
                    } elseif ($status === 'Processing') {
                        echo '<div class="status">
                                <div class="status-icon">1</div>
                                <div class="status-info">
                                    <div class="status-text">Order Placed</div>
                                </div>
                                <div class="vertical-line"></div>
                                <!-- Vertical line -->
                            </div>
                            <div class="status">
                                <div class="status-icon">2</div>
                                <div class="status-info">
                                    <div class="status-text">Processing</div>
                                    <div class="status-date">seller is Processing order details</div>
                                </div>
                            
                            </div>';
                    } elseif ($status === 'Intransit') {
                        echo '<div class="status">
                                <div class="status-icon">1</div>
                                <div class="status-info">
                                    <div class="status-text">Order Placed</div>
                                </div>
                                <div class="vertical-line"></div>
                                <!-- Vertical line -->
                            </div>
                            <div class="status">
                                <div class="status-icon">2</div>
                                <div class="status-info">
                                    <div class="status-text">Processing</div>
                                    <div class="status-date">seller is Processing order details</div>
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
                                </div>
                                <div class="vertical-line"></div>
                                <!-- Vertical line -->
                            </div>
                            <div class="status">
                                <div class="status-icon">2</div>
                                <div class="status-info">
                                    <div class="status-text">Processing</div>
                                    <div class="status-date">seller is Processing order details</div>
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
                                </div>
                            </div>';
                    } else {
                        echo "Invalid status.";
                    }
                } else {
                    echo "Product ID not provided.";
                }

                // Function to fetch order status from the sales table
                function get_order_status_by_sales_id($sales_id) {
                    $conn = dbconnect();
                    $sql = "SELECT status FROM sales WHERE sales_id = ?";
                    try {
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([$sales_id]);
                        $status = $stmt->fetchColumn();
                        $conn = null;
                        return $status;
                    } catch (PDOException $e) {
                        echo $sql . "<br>" . $e->getMessage();
                        $conn = null;
                        return false;
                    }
                }
                
                ?>

                        </div>
                        <div class="all-items">
                            <h6 class="items-label">Delivery Details</h6>
                        </div>
                        <div class="dev-details">
                             <?php if (isset($address) && isset($zipcode) && isset($phone)) { ?>
                                <p> <?php echo $address; ?>, <?php echo $zipcode; ?><i class=" fa fa-angle-right" aria-hidden="true"></i></p></p>
                                <p > <?php echo $phone; ?></p>
                            <?php } else { ?>
                                <p class="loc">Address not available</p>
                            <?php } ?>
                        </div>
                        <div class="all-items">
                            <h6 class="items-label">Payment Details</h6>
                        </div>
                        <div class="dev-details">
                            <div class="payment-method">
                                <i class=" wallet bi bi-wallet2"></i>
                                <label for="cash">Cash on Delivery</label>
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column2">
                <?php if ($product_details) {
   
                            $total_price = $quantity * $product_details['product_price'];

                            // Display the total price in the order summary
                            echo '<div class="summary-container">
                                    <div class="order-summary">
                                        <h6 class="order-label">Order Summary</h6>
                                    </div>
                                    <div class="summary-items">
                                        <div class="sub-total">
                                            <div class="product-price">
                                                <p class="product">Product Price</p>
                                                <p class="order-price">₱ ' . number_format($total_price, 2) . '</p>
                                                <br>
                                            </div>
                                            <div class="total-payment">
                                                <p class="total">Total</p>
                                                <p class="t-payment">₱ ' . number_format($total_price, 2) . '</p>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                        } else {
                            echo "Product details not found.";
                        }
                        ?>
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