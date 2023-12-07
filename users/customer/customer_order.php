<?php
  session_start();
  include '../php/dbhelper.php';
?><!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
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
.request-btn{
	margin-right: 20%;
	border:none;
	padding:10px 50px;
	border:1px solid #65a5a5;
	background-color: transparent;
	border-radius: 10px;
}
.cancelled-btn{
	border:none;
	padding:10px 50px;
	border:1px solid #65a5a5;
	background-color: transparent;
	color: #666;
	border-radius: 10px;
}



.request-btn:focus, .products:focus, .cancelled-btn:focus, .services-button:focus{
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
	margin-top: 70px;
	text-align: right;
	margin-right: 13px;
	font-size: 15px;
	color:#666;
}
.bi-chevron-right {
	margin-top: 5px;
	text-align: right;
	margin-right: 13px;
	font-size: 20px;
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
	margin-top: 70px;
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
		width: 350px;		
		height: 140px;		
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
   .request-btn{
	margin-right: 15%;
	border:none;
	font-size: 13px;
	padding:5px 30px;
	border:1px solid #65a5a5;
	background-color: transparent;
	border-radius: 8px;
}
.cancelled-btn{
	border:none;
	padding:5px 30px;
	border:1px solid #65a5a5;
	background-color: transparent;
	color: #666;
	border-radius: 8px;
	font-size: 13px;
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
		margin-top: 57.5%;
		margin-right: 3px;
		width:60px;
		
	
	}
	.service-done{
		background-color: #65A5A5;
		color: white;
		padding: 4px 0px;
		border-radius: 5px;
		font-size: 11px;
		border: none;
		margin-top: 75.5%;
		margin-right: 3px;
		width:60px;
		
	
	}
	.btn-container{
		display: flex;
		margin-top: 12%;
		position: absolute;
		left:63%;
	}
	.service-accept, .service-cancel {
		background-color: #65A5A5;
		color: white;
		padding: 4px 0px;
		border-radius: 5px;
		font-size: 11px;
		border: none;
		
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
		margin-top: 75px;
		margin-right:0px;
	}
	.bi-chevron-right{
		font-size: 12px;
		margin-top: 70px;
		margin-right:0px;
	}

	
	.interval{
		font-size: 12px;
		margin-top: 75px;
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
            <a class="navbar-brand d-flex align-items-center" href="customer_home.php">
                    <img src="../php/images/logo.png" alt="BulakBuy Logo" class="img-fluid logo">
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

    <div class="wrapper">
        <div class="products-card">
            <?php
          

            if (isset($_SESSION['user_id'])) {
                $customer_id = $_SESSION['user_id'];

                // Fetch orders from the sales table for the specific customer with status "Pending"
                $orders = get_customer_orders($customer_id);

                if ($orders) {
                    // Loop through the orders and display them
                    foreach ($orders as $order) {
                        echo '<div class="single-card" onclick="redirectToOrderStatus(' . $order['sales_id'] . ')">';
                        echo '<div class="img-area">';
                        echo '<img src="' . $order['product_img'] . '" alt="">';
                        echo '</div>';
                        echo '<div class="info">';
                        echo '<div class="text-left">';
                        echo '<h3>' . $order['product_name'] . '</h3>';
                        echo '<div class="flower-type">';
                        echo '<p class="flower">Flower:</p>';
                        echo '<p class="type">' . $order['flower_type'] . '</p>';
                        echo '</div>';
                        echo '<div class="ribbon-color">';
                        echo '<p class="ribbon">Ribbon:</p>';
                        echo '<p class="color">' . $order['ribbon_color'] . '</p>';
                        echo '</div>';
                        echo '<p class="price">₱ ' . number_format($order['product_price'], 2) . '</p>';
                        echo '</div>';
                        echo '<div class="text-right">';
                        echo '<i class="bi bi-chevron-right"></i>';
                        echo '<p class="count">x ' . $order['quantity'] . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "No orders found for this customer.";
                }
            }

            // Function to fetch customer orders from the sales table with status "Pending"
            function get_customer_orders($customer_id) {
                $conn = dbconnect();
                $sql = "SELECT s.sales_id, p.product_name, p.product_img, p.product_price, SUM(sd.quantity) as quantity, sd.flower_type, sd.ribbon_color
                        FROM sales s
                        JOIN salesdetails sd ON s.salesdetails_id = sd.salesdetails_id
                        JOIN products p ON sd.product_id = p.product_id
                        WHERE s.customer_id = ? AND s.status = 'Pending'
                        GROUP BY s.sales_id, sd.flower_type, sd.ribbon_color;";
                try {
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$customer_id]);
                    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $conn = null;
                    return $orders;
                } catch (PDOException $e) {
                    echo $sql . "<br>" . $e->getMessage();
                    $conn = null;
                    return false;
                }
            }
            
            ?>
        </div>
    </div>

  <script>
    function goBack() {
        window.history.back();
    }
  </script>
   <script>
    function redirectToOrderStatus(salesId) {
        // Redirect the user to the order_status.php page with the product id as a parameter
        window.location.href = 'order_status.php?sales_id=' + salesId;
    }
</script>
    
    
</body>
</html>