<?php

session_start();
include '../php/dbhelper.php';
$pdo = dbconnect();
if (isset($_SESSION["user_id"]) && isset($_SESSION["role"])) {
    $user_id = $_SESSION["user_id"];
    $role = $_SESSION["role"];

    $users = get_record_by_user($user_id) ;

}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $salesId = $_POST['salesId'];
    $customerId = $_POST['customerId'];

    // Update the status in the sales table for the specific product and customer
    $result = update_status($salesId, $customerId); 

    if ($result) {
        echo 'Status updated successfully.';
    } else {
        echo 'Failed to update status.';
    }
}

function update_status($salesId, $customerId) {
    $conn = dbconnect();
    $sql = "UPDATE sales SET status = 'Completed' WHERE sales_id = ? AND customer_id = ?";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$salesId, $customerId]);
        $conn = null;
        return true;
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}


function get_seller_orders($seller_id) {
    // Establish a database connection
    $conn = dbconnect();

    // Updated SQL query to fetch order details including sales_id
    $sql = "SELECT s.sales_id, s.amount, s.sales_date, s.paymode, p.product_id, p.product_name, p.product_img, p.product_price, p.flower_type, p.ribbon_color, s.customer_id
            FROM sales s
            JOIN products p ON s.product_id = p.product_id
            JOIN shops sh ON p.shop_owner = sh.shop_id
            WHERE sh.owner_id = ? AND s.status = 'Intransit'";

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

$product_order = get_seller_orders($user_id);
$service_order = get_service_details('servicedetails', 'services', 'users', 'service_package', $user_id);


function get_service_details($servicedetailsTable, $servicesTable, $usersTable, $servicePackageTable, $loggedInUserId)
{
    $conn = dbconnect();

    // SQL to join servicedetails with services, then with users to get the customer's name
    $sql = "SELECT sd.servicedetails_id, sd.*, u.first_name AS customer_first_name, u.last_name AS customer_last_name, u.address AS customer_address, sp.package_image, sp.package_name, sp.inclusions
                FROM " . $servicedetailsTable . " AS sd
                JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
                JOIN " . $usersTable . " AS u ON sd.customer_id = u.user_id
                JOIN " . $servicePackageTable . " AS sp ON sd.package_id = sp.package_id
                WHERE s.arranger_id = :loggedInUserId AND sd.status = 'Intransit'
                ORDER BY sd.servicedetails_id DESC"; // Including servicedetails_id in the selection
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Deliver</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/arranger.css">
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
.services-button{
	border:none;
	padding:10px 50px;
	border:1px solid #65a5a5;
	background-color: transparent;
	color: #666;
	border-radius: 10px;
}
.products-btn:focus{
	border:none;
	outline:none;
}
.services-button:focus{
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

/*btns of the service_process, Intransit and completed*/
.btns{
    margin-top: 2%;
}

 

/* btns of service_order*/
.accept, .done, .Intransit{
	background-color: #65A5A5;
	color: white;
	padding: 5px 15px;
	border-radius: 8px;
	margin-right: 10px;
	border: none;
	margin-top: 89%;
	font-size: 13px;
	font-weight: 400;

}


.service-Intransit, .service-done, .service-accept, .service-cancel  {
	background-color: #65A5A5;
	color: white;
	padding: 5px 15px;
	border-radius: 8px;
	margin-right: 10px;
	border: none;
	font-size: 13px;
	font-weight: 400;

}

.service-transit{
	margin-top: 10%;
}

.service-done{
	margin-top: 23%;
}

.service-accept, .service-cancel{
	margin-top: 23%;
}

.accept:focus, .done:focus, .service-done:focus, .transit:focus, .service-transit:focus,.service-accept:focus, .service-cancel:focus {
	outline: none;
	border:none;
}
.accept:hover, .done:hover, .service-done:hover, .transit:hover, .service-transit:hover,.service-accept:hover, .service-cancel:hover {
	background-color: #65a5a5d0;
}

.info {
	padding: 10px;
	color: black;
	flex: 1;
	
}
/*Price*/

.info .price {
	font-size: 14px;
	color:#666;
	margin-left:-130px;
	margin-top:-5px;
	
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
		width: 360px;		
		height: 250px;		
		margin: 10px 0;	
		
		
	}
	.wrapper {
		padding: 5px 10px;
    	margin-top:60px;
		
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
	.services-button{
		border:none;
		padding:5px 30px;
		border:1px solid #65a5a5;
		background-color: transparent;
		color: #666;
		border-radius: 8px;
		font-size: 13px;
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

	.ribbon-color{
		display: flex;
		gap:10px;
		margin-bottom:1px;

		margin-left:10px;
        
		
   }
	.ribbon{
		font-size: 11px;
        margin-top:2px;
		color:#666;
		margin-left:-53px;
        width:160px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        position: sticky;
   }
   .text-left h2    {
		font-size: 13px;
		margin-top:3px;
		margin-bottom:15px;
		margin-left:-45px;
		font-weight: 500;
		font-weight: 400;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        position:sticky;
        width:160px;
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

    
    /*btns for service_order*/
	.accept, .done, .transit{
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
	.service-transit {
		background-color: #65A5A5;
		color: white;
		padding: 4px 0px;
		border-radius: 5px;
		font-size: 11px;
		border: none;
		width:60px;
		margin-right:5% !important;
		margin-top: 40% !important;
		
	
	}
	.service-done{
		background-color: #65A5A5;
		color: white;
		padding: 4px 0px;
		border-radius: 5px;
		font-size: 11px;
		border: none;
		margin-top: 85.5%;
		margin-right: 3px;
		width:60px;
		
	
	}
	.btn-container{
		display: flex;
		margin-top: 38%;
		position: absolute;
		left:78%;
	}

	.service-accept, .service-cancel {
		background-color: #65A5A5;
		color: white;
		padding: 4px 0px;
		border-radius: 5px;
		font-size: 11px;
		border: none;
		margin-top:45% !important;
		margin-right: 3px;
		width:60px;
		
	
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
        width:160px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        position: sticky;
	
      
	}
    .text-left .inc {
		margin-left:-45px;
		font-size: 12px;
		color:#666;
		font-weight: 400;
		margin-top:15px;
		margin-bottom:15px;
        width:160px;
        text-align: justify;
        text-wrap: wrap;
	
	}
	/*Price*/
	.info .price {
		margin-top: 10%;
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
                            <a href="arranger_home.php">
                            <i class="back fa fa-angle-left" aria-hidden="true"></i>
                            <div id="search-results">To Deliver</div>
                            </a>
                            
                        </form>
                    </li>     
                </ul>
            </div>
        </nav><hr class="nav-hr">
    </header>

  <div class="wrapper">
    <div class="button-container">
        <button class="products-btn active">Products</button>
        <button class="services-button">Services</button>
    </div>
    <div class="products-card" id="productsCard" style="display: block;">
    <?php foreach ($product_order as $order):?>
        <div class="single-card " style="height:160px">
            <div class="img-area">
                <img src="<?php echo $order['product_img']?>" alt="">
            </div>
            <div class="info">
                <div class="text-left">
                    <h3><?php echo $order['product_name']?></h3>
                    <?php
                    $customization = array();
                                    
                        // Check if flower type is available
                        if (!empty($order['flower_type'])) {
                            $customization[] = $order['flower_type'];
                        }
                    
                        // Check if ribbon color is available
                        if (!empty($order['ribbon_color'])) {
                            $customization[] = $order['ribbon_color'];
                        }
                    
                        // Display the customization details
                        if (!empty($customization)) {
                            echo '<div class="ribbon-color">';
                            echo '<p class="ribbon">'. implode(', ', $customization) .'</p>';
                            echo '</div>';
                        }
                    
                        if ($order) {
                            // Initialize a variable to store the message
                            $message = !empty($order['message']) ? $order['message'] : 'None';
                        
                            // Display the message details
                            echo '<div class="ribbon-color">';
                            echo '<p class="ribbon">Message: ' . $message .'</p>';
                            echo '</div>';
                        }?>
                    <p class="price"><?php echo '₱ ' . $order['amount']?></p>
                </div>
                <div class="text-right">
                <?php 
                $quantity = get_quantity_for_product($order['product_id'], $user_id); 
                $quantityText = $quantity ? 'x ' . $quantity : 'Quantity not available';
                ?>
                <p class="count"><?php echo $quantityText; ?></p>
                <button class="product-done done" data-sales-id="<?php echo $order['sales_id']?>" data-customer-id="<?php echo $order['customer_id']?>" name="done">Done</button>           
                 </div>
            </div>
        </div>
    <?php endforeach;?>
    </div>

    <div class="service-list" id="service-container" style="display: none;">
    <?php foreach ($service_order as $order) : ?>
            <div class="products-card">
                <div class="single-card ">
                    <div class="img-area">
                        <!-- Display package image -->
                        <img src="<?php echo $order["package_image"] ?>" alt="">
                    </div>
                    <div class="info">
                        <div class="text-left">

                            <h3><?php echo $order["customer_first_name"] . " " . $order["customer_last_name"]; ?></h3>
                            <!-- Display package name -->
                            <p class="ad">Package: <?php echo $order["package_name"]; ?></p>
                            <p class="ad">Location: <?php echo $order["customer_address"] ?></p>
                            <p class="inc">Inclusions: <?php echo $order["inclusions"] ?></p>
                         
                            <div class="o-date-time">
                                <span class="date"><?php echo $order["date"] ?></span>
                                <span class="time"><?php echo $order["time"] ?></span>
                            </div>
                            <p class="price"><?php echo '₱ ' .  $order["amount"] ?></p>
                        </div>
                
                <div class="text-right">
                    <div class="btn-container order">
                    <button class="service-done done" data-servicedetails-id="<?php echo $order['servicedetails_id']; ?>" data-customer-id="<?php echo $order['customer_id'];?>">Done</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const productsBtn = document.querySelector('.products-btn');
        const servicesBtn = document.querySelector('.services-button');
        const productsSection = document.getElementById('productsCard');
        const servicesSection = document.getElementById('service-container');

        function toggleDisplay(showProducts) {
            if (showProducts) {
                productsSection.style.display = 'block';
                servicesSection.style.display = 'none';
                productsBtn.classList.add('active');
                servicesBtn.classList.remove('active');
            } else {
                productsSection.style.display = 'none';
                servicesSection.style.display = 'block';
                servicesBtn.classList.add('active');
                productsBtn.classList.remove('active');
            }
        }

        productsBtn.addEventListener('click', function() {
            toggleDisplay(true);
        });

        servicesBtn.addEventListener('click', function() {
            toggleDisplay(false);
        });

        // Initialize the default view
        toggleDisplay(true); // or false if you want services to be displayed first
    });


        </script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
    $(".product-done").click(function() {
        var salesId = $(this).data("sales-id");
        var customerId = $(this).data("customer-id"); // Add this line to get the customer ID

        // Send AJAX request to update the status
        $.ajax({
            url: 'service_intransit.php',
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
    $(".service-done").click(function() {
        var servicedetailsId = $(this).data("servicedetails-id");
        var customerId = $(this).data("customer-id"); // Add this line to get the customer ID

        // Send AJAX request to update the status
        $.ajax({
            url: 'update_service_completed.php',
            method: 'POST',
            data: { servicedetailsId: servicedetailsId, customerId: customerId }, // Include customer ID in the data
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