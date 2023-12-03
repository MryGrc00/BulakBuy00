
<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/vendor.css">

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
                            <div id="search-results">Completed</div>
                            </a>
                            
                        </form>
                    </li>     
                </ul>
            </div>
        </nav><hr class="nav-hr">
    </header>
<?php
include('../php/checksession.php'); 
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
            echo '<h3>' . $order['product_name'] . '</h3>';
            echo '<p class="price">₱ ' . number_format($order['product_price'], 2) . '</p>';
            echo '<p class="price">COD</p>';
            
            // Fetch and display the quantity from the sales_details table
            $quantity = get_quantity_for_product($order['product_id'], $seller_id);
            echo '<p class="price">₱ ' . number_format($order['product_price'] * $quantity, 2) . '</p>';
            
            echo '</div>';
            echo '<div class="text-right">';
            
            // Check if the 'quantity' key exists in the order array
            $quantity = isset($quantity) ? $quantity : 'Quantity not available';
            
            echo '<p class="count">x ' . $quantity . '</p>';
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

// Function to fetch seller orders from the sales table// Function to fetch seller orders from the sales table with status "pending"
function get_seller_orders($seller_id) {
    $conn = dbconnect();
    $sql = "SELECT s.amount, s.sales_date, p.product_id, p.product_name, p.product_img, p.product_price, s.customer_id
            FROM sales s
            JOIN products p ON s.product_id = p.product_id
            JOIN shops sh ON p.shop_owner = sh.shop_id
            WHERE sh.owner_id = ? AND s.status = 'Completed'";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$seller_id]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;
        return $orders;
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
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


  <script>
    function goBack() {
        window.history.back();
    }
  </script>
    
</body>
</html>