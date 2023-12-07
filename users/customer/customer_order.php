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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../css/customer.css">

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
            session_start();
            include '../php/dbhelper.php';

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