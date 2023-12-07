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
    $sql = "UPDATE sales SET status = 'Intransit' WHERE sales_id = ? AND customer_id = ?";
    
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
            WHERE sh.owner_id = ? AND s.status = 'Processing'";

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
$service_order =  get_service_details_processing('servicedetails','services', 'users', $user_id);



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
    <title>Processing</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/arranger.css">

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
                            <div id="search-results">Processing</div>
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
        <div class="single-card ">
            <div class="img-area">
                <img src="<?php echo $order['product_img']?>" alt="">
            </div>
            <div class="info">
                <div class="text-left">
                    <h3><?php echo $order['product_name']?></h3>
                    <div class="flower-type">
                        <p class="flower">Flower:</p>
                        <p class="type"><?php echo $order['flower_type']?> </p>
                    </div>
                    <div class="ribbon-color">
                        <p class="ribbon">Ribbon:</p>
                        <p class="color"> <?php echo $order['ribbon_color']?> </p>
                    </div>
                    <p class="price"><?php echo $order['amount']?></p>
                </div>
                <div class="text-right">
                <?php 
                $quantity = get_quantity_for_product($order['product_id'], $user_id); 
                $quantityText = $quantity ? 'x ' . $quantity : 'Quantity not available';
                ?>
                <p class="count"><?php echo $quantityText; ?></p>
                <button class="product-Intransit transit" data-sales-id="<?php echo $order['sales_id']?>" data-customer-id="<?php echo $order['customer_id']?>" name="Intransit">To Deliver</button>           
                 </div>
            </div>
        </div>
    <?php endforeach;?>
    </div>

    <div class="service-list" id="service-container" style="display: none;">
        <?php foreach ($service_order as $order):?>
        <div class="single-card ">
            <div class="img-area">
                <img src="<?php echo $order["customer_profile"]?>" alt="">
            </div>
            <div class="info">
                <div class="text-left">                    
                
                <h3><?php echo $order["customer_first_name"]. " " . $order["customer_last_name"]; ?></h3>
                    <p class="ad"><?php echo $order["customer_address"]?></p>
                    <div class="o-date-time">
                        <span class="date"><?php echo $order["date"]?></span>
                        <span class="time"><?php echo $order["time"]?></span>
                    </div>
                    <p class="price"><?php echo $order["amount"]?></p>
                </div>
                
                <div class="text-right">
                    <div class="btn-container order">
                    <button class="service-Intransit transit" data-servicedetails-id="<?php echo $order['servicedetails_id']; ?>" data-customer-id="<?php echo $order['customer_id'];?>">In Transit</button>
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
    $(".product-Intransit").click(function() {
        var salesId = $(this).data("sales-id");
        var customerId = $(this).data("customer-id"); // Add this line to get the customer ID

        // Send AJAX request to update the status
        $.ajax({
            url: 'service_process.php',
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
    $(".service-Intransit").click(function() {
        var servicedetailsId = $(this).data("servicedetails-id");
        var customerId = $(this).data("customer-id"); // Add this line to get the customer ID

        // Send AJAX request to update the status
        $.ajax({
            url: 'update_service_intransit.php',
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