

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
    <link rel="stylesheet" href="../../css/arranger.css">
    <style>
        @media (min-width: 300px) and (max-width:500px) {
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
    .single-card {
		width: 350px;		
		height: 140px;	
        padding-left:5px;	
		margin-top: 10px 0;	
	
		
		
	}
    .img-area {
		flex: 1;
		margin-right:8px;
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
    session_start();
    include '../php/dbhelper.php';
    
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
        echo '<h2>' . $order['product_name'] . '</h2>';

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
        }

        echo '<p class="price">₱ ' . number_format($order['product_price'], 2) . '</p>';
        echo '</div>';
        echo '<div class="text-right">';

        // Fetch and display the quantity from the sales_details table
        $quantity = get_quantity_for_product($order['product_id'], $seller_id);
        $quantityText = $quantity ? 'x ' . $quantity : 'Quantity not available';
        echo '<p class="count">' . $quantityText . '</p>';
        echo '<div class="btn-container order" style="margin-top:-1%;">';
        echo '<button class="service-accept accept" data-sales-id="' . $order['sales_id'] .'"data-product-id"'.$order['product_id']. '" data-customer-id="' . $order['customer_id'] . '">Accept</button>';
        echo '<button class="service-cancel" data-sales-id="' . $order['sales_id'] .'"data-product-id"'.$order['product_id']. '" data-customer-id="' . $order['customer_id'] . '">Cancel</button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo' <p class="p-end" style="color: #bebebe;
    font-size: 15px;
    text-align: center;
    margin-top: 300px;">No products found</p>';
}
    }

    // Function to fetch seller orders from the sales table// Function to fetch seller orders from the sales table with status "Pending"
    function get_seller_orders($seller_id) {
        // Establish a database connection
        $conn = dbconnect();

        // Updated SQL query to fetch order details including sales_id
        $sql = "SELECT s.sales_id, s.amount, s.sales_date, s.paymode, p.product_id, p.product_name,p.flower_type,p.ribbon_color, p.product_img, p.product_price, s.customer_id
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
    
    
      
   
    </div>
  </div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
    $(".service-accept").click(function() {
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