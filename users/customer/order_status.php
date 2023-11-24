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
            .quantity{
                font-size: 13px;
                color:#666;
            

            }
            @media (min-width: 300px) and (max-width:500px) {
                .quantity{
                    font-size: 11px;
                    margin-right:-100%;
                    transform: translateX(25%);
                    color:#666;
                 }
            }
        </style>
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg">
                <!-- Logo -->
                <a class="navbar-brand d-flex align-items-center" href="#">
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
                            session_start();
                            include '../php/dbhelper.php';

                            if (isset($_SESSION['user_id']) &&  isset($_GET['service_id'])){
                                $user_id = $_SESSION['user_id'];
                                $service_id = $_GET['service_id'];
                            
                                // Fetch product details from the product table
                                $details= getServiceDetails("servicedetails", "services", "users", $service_id, $user_id);


                                // Display the product details
                                if ($details) {
                                    echo '<div class="all-items">';
                                    echo '<h6 class="items-label">Order id- 8475645328</h6>';
                                    echo '</div>';
                                    echo '<div class="cart-item">';
                                    echo '<div class="custom-checkbox" style="margin-top:-30px">';
                                    echo '<img src="' . $product_details['product_img'] . '" alt="' . $product_details['product_name'] . '">';
                                    echo '</div>';
                                    echo '<div class="item-details">';
                                    echo '<h2>' . $product_details['product_name'] . '</h2>';
                                    echo '<div class="flower-type">';
                                    echo '<p class="flower">Flower:</p>';
                                    echo '<p class="type">' . $product_details['flower_type'] . '</p>';
                                    echo '</div>';
                                    echo '<div class="ribbon-color">';
                                    echo '<p class="ribbon">Ribbon:</p>';
                                    echo '<p class="color">' . $product_details['ribbon_color'] . '</p>';
                                    echo '</div>';
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
                            function get_product_details($product_id) {
                                $conn = dbconnect();
                                $sql = "SELECT * FROM product WHERE product_id = ?";
                                try {
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute([$product_id]);
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
                            function get_quantity_for_product($product_id) {
                                $conn = dbconnect();
                                $sql = "SELECT quantity FROM sales_details WHERE product_id = ?";
                                try {
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute([$product_id]);
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
                        if (isset($_GET['product_id'])) {
                            $product_id = $_GET['product_id'];

                            // Fetch status from the sales table
                            $status = get_order_status($product_id);

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
                } else {
                    echo "Product ID not provided.";
                }

                // Function to fetch order status from the sales table
                function get_order_status($product_id) {
                    $conn = dbconnect();
                    $sql = "SELECT status FROM sales WHERE product_id = ?";
                    try {
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([$product_id]);
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