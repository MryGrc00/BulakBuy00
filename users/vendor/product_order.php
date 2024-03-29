<?php 
session_start();
include '../php/dbhelper.php';
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/vendor.css">
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
.wrapper {
	display: flex;
	flex-direction: column;
	align-items: center;
	margin-top: 30px;
}
.button-container {
	display: flex;
	justify-content: center;
	margin-top:10px;
	margin-bottom:40px;
	align-items: center;
	gap:200px;
}
.button-container .active{
	background-color: #65a5a5;
	color: white;
	border: none;
	outline: none;
}
.products-btn{
	margin-right: 20%;
	border:none;
	padding:10px 50px;
	border:1px solid #65a5a5;
	background-color: transparent;
	border-radius: 10px;
}

.products-btn:focus{
	border:none;
	outline:none;
}

/*single card for one data*/

.single-card {
	display: flex;
	flex-wrap: wrap;
	box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.2);
	overflow: hidden;
	background: white;
	border-radius: 5px;
	margin-bottom:20px;
	width: 700px;
	height: 140px;
	
}

.img-area {
	flex: 1;
  	margin-left:-115px;
	text-align: center;
	
}
.img-area img {
	height: 105px;
	width: 130px;
	object-fit: cover;
 	margin-top:17px;
 
}

.flower-type{
	display: flex;
	gap:10px;
	margin-top:10px;
	margin-left:-130px;
}
.flower{
	font-size: 14px;
	color:#666;
}
.type{
	font-size: 14px;
	color:#666;
}
.ribbon-color{
	display: flex;
	gap:10px;
	margin-top:-5px;
	margin-left:-130px;
}
.ribbon{
	font-size: 14px;
	color:#666;
}
.color{
	font-size: 14px;
	color:#666;
}



.product-accept, .product-deliver, .product-done{
	background-color: #65A5A5;
	color: white;
	padding: 5px 15px;
	border-radius: 8px;
	margin-right: 10px;
	border: none;
	margin-top: 10%;
	font-size: 13px;
	font-weight: 400;

}

.accept:focus, .done:focus, .transit:focus, .product-accept:focus, .product-deliver:focus, .product-done:focus{
	outline: none;
	border:none;
}
.accept:hover, .done:hover,  .transit:hover, .product-accept:hover, .product-deliver:hover, .product-done:hover{
	background-color: #65a5a5d0;
}

.info {
	padding: 10px;
	color: black;
	flex: 1;
	
}
/*Price*/

.info .price{
	font-size: 14px;
	color:#666;
	margin-left:-130px;
	margin-top:-5px;
	
}
.cat {
	font-size: 14px;
	color:#666;
	margin-left:-130px;
	margin-top:1px;
	
}
.info {
	display: flex;
	
}

/*Address*/

.text-left .ad {
	margin-left:-130px;
	font-size: 14px;
	color:#666;
	font-weight: 400;
	margin-top:13px;
	margin-bottom:13px;


}
/*Name*/

.text-left h3 {
	font-size: 15px;
	color:#666;
	margin-left:-130px;
	margin-top:5px;
	font-weight: 400;
}
.text-right {
	flex: 1;
}
/*Count*/

.text-right .count {
	margin-top: 5px;
	text-align: right;
	margin-right: 13px;
	font-size: 15px;
	color:#666;
}
.o-date-time {
	font-size: 13px;
	margin-top: -5px;
	margin-bottom: 15px;
	margin-left: -160px;
	color:#666;
}
.date {
	margin-right: 25px;
	margin-left: 29px;
}
.interval{
	font-size: 15px;
	margin-top: 5px;
	margin-right:13px;
	color:#666;
}

.btn{
	display: flex;
	margin-top: 10%;
	margin-bottom: 10%;
}


/* Adjust layout on smaller screens */
@media (min-width: 300px) and (max-width:500px) {
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
	a #search-results {
		display: block;
		font-size: 15px;
		margin-left: 20px;
		color: #555;
		margin-top: -20px;
	}
	a:hover {
		text-decoration: none;
		outline: none;
		border: none;
	}
	.back {
		display: block;
		font-size: 20px;
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
	.single-card {
		width: 370px;		
		height: 140px;		
		margin: 10px 0;	
		
		
	}
	.wrapper {
		padding: 5px 10px;
    	margin-top:60px;
    	margin-bottom:-60px;
		
	}
	.button-container {
		display: flex;
		justify-content: center;
		margin-top:10px;
		margin-bottom:20px;
		align-items: center;
		gap:30px;
   }
	.button-container .active{
		background-color: #65a5a5;
		color: white;
		border: none;
		outline: none;
   }
	.products-btn{
		margin-right: 15%;
		border:none;
		font-size: 13px;
		padding:5px 30px;
		border:1px solid #65a5a5;
		background-color: transparent;
		border-radius: 8px;
   }

	.img-area {
		flex: 1;
		margin-left:-30px;
	}
	.img-area img {
		max-width: 100px;
		height: 110px;
		margin-top:15px;
		
	}

	.flower-type{
		display: flex;
		justify-content:flex-start;
		text-align: justify;
		margin-top:-3px;
		margin-left:10px;
   }
	.flower{
		color:#666;
		font-size: 11px;
		margin-left:-53px;
   }
	.type{
		color:#666;
		font-size: 11px;
		margin-left:0px;
		width:120px;
   }
	.ribbon-color{
		display: flex;
		gap:10px;
		margin-top:-10px;
		margin-left:10px;
		margin-bottom: -24px;
   }
	.ribbon{
		font-size: 11px;
		color:#666;
		margin-left:-53px;
   }
   .color{
		font-size: 11px;
		color:#666;
		margin-left:-3px;
   }
	.info {
		max-width: 100%;
		
	}

	.btn{
		display: flex;
		margin-left: -12%;
		gap: 10px;
		margin-top: 10%;

	}

    
  
	.product-accept, .product-done, .product-deliver{
		background-color: #65A5A5;
		color: white;
		padding: 4px 0px;
		border-radius: 5px;
		font-size: 11px;
		border: none;
		margin-top: 40%;
		margin-right: 3px;
		width:67px;
		
	
	}
	.transit{
		background-color: #65A5A5;
		color: white;
		padding: 4px 0px;
		border-radius: 5px;
		font-size: 11px;
		border: none;
		margin-top: 90%;
		margin-right: 3px;
		width:60px;
		
	
	}
	
	.btn-container{
		display: flex;
		margin-top: 12%;
		position: absolute;
		left:63%;
	}

	.text-left h3 {
		font-size: 13px;
		margin-top:3px;
		margin-left:-45px;
		font-weight: 500;
		font-weight: 400;
	}
	.text-left .ad {
		margin-left:-45px;
		font-size: 12px;
		color:#666;
		font-weight: 400;
		margin-top:15px;
		margin-bottom:15px;
	
	
	}
	/*Price*/
	.info .price {
		margin-top: 10%;
		font-size: 12px;
		margin-left:-45px;
		color:#666;
	}
	.cat {
		margin-top: 30%;
		font-size: 12px;
		margin-left:-45px;
		color:#666;
	}
	/*Count*/
	.text-right .count {
		font-size: 12px;
		margin-top: 0px;
		margin-right:0px;
	}


	
	.interval{
		font-size: 12px;
		margin-top: 0px;
		margin-right:0px;
		color:#666;
	}


    
	.o-date-time {
		font-size: 12px;
		margin-top: 15px;
		margin-bottom: 15px;
		margin-left:-45px;
		color: #666;
	}
	
	.date {
		margin-left: 0px;
		margin-right: 10px;
		display: inline-block; 
		white-space: nowrap; 
	}
    .service-accept, .service-cancel {
        background-color: #65A5A5;
        color: white;
        padding: 4px 0px;
        border-radius: 5px;
        font-size: 11px;
        border: none;
        margin-top:40% !important;
        margin-right: 3px;
        width:60px;
        

            }
	

  }
  
 

    </style>

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
                            <a href="javascript:void(0);" onclick="goBack()">
                            <i class="back fa fa-angle-left" aria-hidden="true"></i>
                            <div id="search-results">Orders</div>
                            </a>
                            
                        </form>
                    </li>     
                </ul>
            </div>
        </nav><hr class="nav-hr">
    </header>
<?php
 

if (isset($_SESSION['user_id'])) {
    $seller_id = $_SESSION['user_id'];

    // Fetch orders from the sales table for the specific seller
    $orders = get_seller_orders($seller_id);

    if ($orders) {
        // Loop through the orders and display them
        foreach ($orders as $order) {
            echo '<div class="wrapper">';
            echo '<div class="products-card">';
            echo '<div class="single-card">';
            echo '<div class="img-area">';
            echo '<img src="' . $order['product_img'] . '" alt="">';
            echo '</div>';
            echo '<div class="info">';
            echo '<div class="text-left">';
            echo '<h3>' . $order['product_name'] . '</h3>';
            echo '<p class="price">₱ ' . number_format($order['product_price'], 2) . '</p>';
            echo '<p class="price">'.$order['paymode'].'</p>';
            
            // Fetch and display the quantity from the sales_details table
            $quantity = get_quantity_for_product($order['product_id'], $seller_id);
            echo '<p class="price">₱ ' . number_format($order['product_price'] * $quantity, 2) . '</p>';
            
            echo '</div>';
            echo '<div class="text-right">';
            
            // Check if the 'quantity' key exists in the order array
            $quantity = isset($quantity) ? $quantity : 'Quantity not available';
            
            echo '<p class="count">x ' . $quantity . '</p>';
            echo '<button class="product-accept accept" data-sales-id="' . $order['sales_id'] . '" data-customer-id="' . $order['customer_id'] . '">Accept</button>';
            echo '<button class="service-cancel" data-sales-id="' . $order['sales_id'] . '" data-customer-id="' . $order['customer_id'] . '">Cancel</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "No orders found for this seller.";
    }
}

// Function to fetch seller orders from the sales table// Function to fetch seller orders from the sales table with status "Pending"
function get_seller_orders($seller_id) {
    // Establish a database connection
    $conn = dbconnect();

    // Updated SQL query to fetch order details including sales_id
    $sql = "SELECT s.sales_id, s.amount, s.sales_date, s.paymode, p.product_id, p.product_name, p.product_img, p.product_price, s.customer_id
            FROM sales s
            JOIN products p ON s.product_id = p.product_id
            JOIN shops sh ON p.shop_owner = sh.shop_id
            WHERE sh.owner_id = ? AND s.status = 'Pending'";

    try {
        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);
        $stmt->execute([$seller_id]);

        // Fetch and return the orders
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $orders;
    } catch (PDOException $e) {
        // Handle any exceptions (log the error and return false)
        error_log("Database query error: " . $e->getMessage()); // Log the error
        return false; // Return false indicating failure
    } finally {
        // Close the database connection
        $conn = null;
    }
}



// Function to get quantity from sales_details table
function get_quantity_for_product($product_id, $seller_id) {
    $conn = dbconnect();
    $sql = "SELECT quantity FROM salesdetails sd
            JOIN products p ON sd.product_id = p.product_id
            JOIN shops sh ON p.shop_owner = sh.shop_id
            WHERE p.product_id = ? AND sh.owner_id = ?";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$product_id, $seller_id]);
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




    <!-- Add this script in the head or before the closing body tag -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
    $(".product-accept").click(function() {
        var salesId = $(this).data("sales-id");
        var customerId = $(this).data("customer-id");

        // Send AJAX request to update the status
        $.ajax({
            url: '../php/update_status.php',
            method: 'POST',
            data: { salesId: salesId, customerId: customerId }, 
            success: function(response) {
                // Handle the response if needed
                console.log(response);

                // Reload the page after the status is updated
                location.reload();
            },
            error: function(error) {
                // Handle the error if needed
                console.error(error);
            }
        });
    });

});

</script>
<script>
    $(document).ready(function() {
    $(".service-cancel").click(function() {
        var salesId = $(this).data("sales-id");
        var customerId = $(this).data("customer-id");

        // Send AJAX request to update the status
        $.ajax({
            url: '../php/product_cancel.php',
            method: 'POST',
            data: { salesId: salesId, customerId: customerId }, 
            success: function(response) {
                // Handle the response if needed
                console.log(response);

                // Reload the page after the status is updated
                location.reload();
            },
            error: function(error) {
                // Handle the error if needed
                console.error(error);
            }
        });
    });

});

</script>

  <script>
    function goBack() {
        window.history.back();
    }
  </script>
    
</body>
</html>