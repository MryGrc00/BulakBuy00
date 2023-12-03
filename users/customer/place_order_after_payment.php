<?php
session_start();
include '../php/dbhelper.php';

$selectedProducts = json_decode(urldecode($_GET['selected_products']), true);
$selectedPayment = isset($_SESSION['selected_payment']) ? $_SESSION['selected_payment'] : '';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $customer_id = $_SESSION['user_id']; 

    // Retrieve the user's address and zipcode from the database
    $user = get_record('users', 'user_id', $user_id);

    if ($user) {
        $address = $user['address']; // Replace 'address' with the actual column name
        $zipcode = $user['zipcode']; // Replace 'zipcode' with the actual column name
        $phone = $user['phone']; // Replace 'zipcode' with the actual column name
    }
}

// Add the sales record to the database
function add_sales_record($salesdetailsId, $productID, $shopID, $customer_id, $quantity, $productPrice, $sales_date, $status, $paymode) {
    $sales_date = date("Y-m-d H:i:s"); // Get current date and time in MySQL format

    $conn = dbconnect();
    $amount = calculate_total_amount($productPrice, $quantity);

    $sql = "INSERT INTO sales (salesdetails_id, product_id, shop_id, customer_id, amount, sales_date, status, paymode) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$salesdetailsId, $productID, $shopID, $customer_id, $amount, $sales_date, $status, $paymode]);
        $conn = null;
        return true;
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}


// Function to calculate the total amount based on price and quantity
function calculate_total_amount($price, $quantity) {
    return $price * $quantity;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedProducts = json_decode($_POST['selected_products'], true);

    foreach ($selectedProducts as $selectedProduct) {
        $productID = $selectedProduct['product_id'];
        $shopID = $selectedProduct['shop_id'];
        $customer_id = $_SESSION['user_id'];
        $quantity = $selectedProduct['quantity'];
        $productPrice = $selectedProduct['product_price'];
        $salesdetailsId = $selectedProduct['salesdetails_id'];
        $amount = calculate_total_amount($productPrice, $quantity);
        $status = "Pending";
        $paymode = "gcash"; // Set paymode to "cod"

        // Add the sales record to the database
        $success = add_sales_record($salesdetailsId, $productID, $shopID, $customer_id, $quantity, $productPrice, $amount, $status, $paymode);

        if (!$success) {
            // Handle the case where the insertion fails
            echo json_encode(["success" => false, "message" => "Failed to place the order."]);
            exit();
        }
    }

    // Send a JSON response
    echo json_encode(["success" => true, "message" => "Order placed successfully!"]);
    exit();
}

?>

<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Place Order</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/place_order.css">
        <style>
            .modal1 {
                display: none;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
            }
            .modal-content1 {
                background-color: #65a5a5e1;
                margin: 20% auto;
                padding: 20px;
                border: none;
                border-radius: 10px;
                max-width: 300px;
                text-align: center;
                color: white;
            }
            .bi-info-circle {
                font-size: 50px;
                color: white;
                margin: auto;
                margin-top: 5%;
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
                                    <div id="search-results">Order Summary</div>
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
                    <div class="location">
                        <i class="bi bi-geo-alt"></i>
                        <div class="location-info">
                            <?php if (isset($address) && isset($zipcode) && isset($phone)) { ?>
                                <p class="loc">Delivering to: <?php echo $address; ?>, <?php echo $zipcode; ?><i class=" fa fa-angle-right" aria-hidden="true"></i></p></p>
                                <p class="number"> <?php echo $phone; ?></p>
                            <?php } else { ?>
                                <p class="loc">Address not available</p>
                            <?php } ?>
                            
                        </div>
                    </div>
                    <div class="cart-container">
                        <div class="all-items">
                            <h6 class="items-label">Payment Method</h6>
                        </div>
                        <div class="payment-method">
                            <i class="wallet bi bi-wallet2"></i>
                            <label for="cash">Cash on Delivery</label>
                            <input type="radio" id="cash" name="paymentMethod" value="cash">
                        </div>

                        <form>
                            <div class="payment-method">
                                <img src="https://logos-download.com/wp-content/uploads/2020/06/GCash_Logo.png" alt="GCash Icon">
                                <label for="gcash">GCash</label>
                                <input type="radio" name="paymentMethod" id="gcashRadio" data-shop-id="<?php echo $shop_id; ?>" <?php echo ($selectedPayment === 'gcash') ? 'checked' : ''; ?>>
                            </div>
                            <!-- Add other payment methods here -->
                        </form>
                        <div class="border"></div>
                        <!-- place_order.php -->
                        <div class="cart-items">
                       <?php
                                $selectedProducts = json_decode($_GET['selected_products'], true);
                                $selectedShopId = ''; // Initialize an empty variable
                                $selectedShopName = ''; // Initialize an empty variable

                                if (isset($_SESSION['user_id'])) {
                                    $user_id = $_SESSION['user_id'];
                                    $customer_id = $_SESSION['user_id'];

                                    // Retrieve product details for selected products
                                    $selectedProductIDs = array_map(function ($product) {
                                        return $product['productId'];
                                    }, $selectedProducts);

                                    $selectedProductsDetails = get_product_details_in_cart($selectedProducts, $user_id);


                                    // Now $selectedProductsDetails contains the details of selected products

                                    // Group selected products by shop ID
                                    foreach ($selectedProductsDetails as $productDetails) {
                                        $shopId = $productDetails['shop_owner'];
                                        $groupedProducts[$shopId][] = $productDetails;
                                    }
                                    $_SESSION['selected_products'] = $selectedProducts; // $selectedProducts contains the product information

                                    // Loop through each shop's products and display details
                                    foreach ($groupedProducts as $shop_id => $shop_products) {
                                        // Retrieve shop details from the database based on shop_id
                                        $shop = get_record('shops', 'shop_id', $shop_id);

                                        if ($shop) {
                                            $selectedShopId = $shop_id; // Store the shop ID in the variable
                                            $selectedShopName = $shop['shop_name']; // Store the shop name
                                            $selectedShopPhone = $shop['shop_phone']; // Store the shop name

                                            // Display shop details
                                            echo '<div class="shop-info">';
                                            echo '<img src="' . $shop['shop_img'] . '" alt="Shop Image">';
                                            echo '<div class="shop-name">';
                                            echo '<a href="../vendor/vendor_shop.html">';
                                            echo '<h3>' . $shop['shop_name'] . '</h3>';
                                            echo '<i class="fa fa-angle-right" aria-hidden="true"></i>';
                                            echo '</a>';
                                            echo '</div>';
                                            echo '</div>';
                                            echo '<hr class="cart-hr">';

                                            $shopTotalQuantity = 0;
                                            $totalPrice = 0;
                                            
                                            // Display product details for the current shop
                                            foreach ($shop_products as $productDetails) {
                                                displayProductDetails($productDetails);

                                                $quantity = $productDetails['quantity'];
                                                $productPrice = $productDetails['product_price'];
                                                $subtotal = $quantity * $productPrice;

                                                $totalPrice += $subtotal; // Move this line outside the inner loop
                                                $shopTotalQuantity += $productDetails['quantity'];
                                            }

                                            // Calculate total price and quantity for the shop
                                            $shopTotalPrice = array_sum(array_map(function ($product) {
                                                return $product['quantity'] * $product['product_price'];
                                            }, $shop_products));
                                            
                                            // Retrieve shop name based on selected shop ID
                                            $selectedShopName = $shop['shop_name'];
                                            

                                            echo '<hr class="cart-hr">';
                                            echo '<p class="total">Total (' . $shopTotalQuantity . ' item(s))</p>';
                                            echo '<p class="t-payment">₱ ' . number_format($shopTotalPrice, 2) . '</p>'; // Use $shopTotalPrice instead of $totalPrice

                                            // Add the border after all products of the current shop have been displayed
                                            echo '<div class="border"></div>';
                                        } else {
                                            echo "Shop details not found.";
                                        }
                                    }
                                }

                                // Function to display product details
                                function displayProductDetails($productDetails)
                                {
                                    echo '<div class="cart-item">';
                                    echo '<div class="custom-checkbox" style="margin-top:-30px">';
                                    echo '<img src="' . $productDetails['product_img'] . '" alt="' . $productDetails['product_name'] . '">';
                                    echo '</div>';
                                    echo '<div class="item-details">';
                                    echo '<h2>' . $productDetails['product_name'] . '</h2>';
                                    echo '<p class="price">₱ ' . $productDetails['product_price'] . '</p>';

                                    // Additional details specific to your application can be added here

                                    echo '<div class="quantity-control">';
                                    echo '<p class="quantity"> X ' . $productDetails['quantity'] . '</p>';
                                    echo '</div>';

                                    echo '</div>';
                                    echo '</div>';
                                }

                                function findProductDetails($products, $productId)
                                {
                                    foreach ($products as $product) {
                                        if (isset($product['product_id']) && $product['product_id'] == $productId) {
                                            return $product;
                                        }
                                    }
                                    return null;
                                }

                                // Function to get product IDs added to the cart by the user
                                function get_product_details_in_cart($selectedProducts, $userID)
                                {
                                    $conn = dbconnect();
                                    $table = 'salesdetails';
                                    $where = 'customer_id';

                                    // Extract the salesdetails_id values from selected products
                                    $selectedSalesDetailsIDs = array_map(function ($product) {
                                        return $product['salesdetailsId'];
                                    }, $selectedProducts);

                                    // Placeholder for the question marks in the SQL query
                                    $placeholders = rtrim(str_repeat('?, ', count($selectedSalesDetailsIDs)), ', ');

                                    $sql = "SELECT p.*, sd.quantity, sd.customer_id, sd.salesdetails_id 
                                            FROM products p 
                                            JOIN salesdetails sd ON p.product_id = sd.product_id
                                            WHERE sd.customer_id = ? AND sd.salesdetails_id IN ($placeholders)";

                                    try {
                                        $stmt = $conn->prepare($sql);

                                        // Bind parameters
                                        $params = array_merge([$userID], $selectedSalesDetailsIDs);
                                        $stmt->execute($params);

                                        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    } catch (PDOException $e) {
                                        echo $sql . "<br>" . $e->getMessage();
                                        return false;
                                    }

                                    $conn = null;

                                    return $products;
                                }


                                // Function to get product details by joining the sales_details and products tables
                                function get_product_details($productID, $userID)
                                {
                                    $conn = dbconnect();
                                    $sql = "SELECT p.*, sd.quantity, sd.customer_id FROM products p 
                                            JOIN salesdetails sd ON p.product_id = sd.product_id
                                            WHERE p.product_id = ? AND sd.customer_id = ?";

                                    try {
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute([$productID, $userID]);
                                        $product = $stmt->fetch(PDO::FETCH_ASSOC);
                                    } catch (PDOException $e) {
                                        echo $sql . "<br>" . $e->getMessage();
                                        return false;
                                    }

                                    $conn = null;

                                    return $product;
                                }
                                ?>


                            </div>                      
                            
                        </div>
                        
                </div>
                <div class="column2">
                    <div class="summary-container">
                        <div class="order-summary">
                            <h6 class="order-label">Order Summary</h6>
                        </div>
                        <div class="summary-items">
                            <div class="sub-total">
                                
                            <?php
                                    // Calculate total price and quantity
                                    if (!empty($selectedProducts)) {
                                        $totalPrice = 0;
                                        foreach ($selectedProductsDetails as $productDetails) {
                                                if ($productDetails['customer_id'] == $_SESSION['user_id']) {
                                                    $quantity = $productDetails['quantity'];
                                                    $productPrice = $productDetails['product_price'];
                                                    $subtotal = $quantity * $productPrice;

                                                    $totalPrice += $subtotal;
                                                }
                                            }
                                        }
                                    

                                    ?>

                                    <div class="total-payment">
                                        <p class="total">Total (<?php echo array_sum(array_column($selectedProducts, 'quantity')); ?> item(s))</p>
                                        <p class="t-payment">₱ <?php echo number_format($totalPrice, 2); ?></p>
                                    </div>

                                  


                            </div>
                        </div>
                        <div class="button-container">
                            <div class="button-container">
                            <?php
                                if (!empty($selectedProducts)) {
                                    $totalPrice = 0;

                                    foreach ($selectedProductsDetails as $productDetails) {
                                        if ($productDetails['customer_id'] == $_SESSION['user_id']) {
                                            $quantity = $productDetails['quantity'];
                                            $productPrice = $productDetails['product_price'];
                                            $subtotal = $quantity * $productPrice;

                                            $totalPrice += $subtotal;
                                        }
                                    }
                                }
                                ?>


                            <div class="total-info">
                                <p class="total-item">Total (<?php echo array_sum(array_column($selectedProducts, 'quantity')); ?> item(s))</p>
                                <p class="total-price">₱  <?php echo number_format($totalPrice, 2); ?></p>
                            </div>
                    <!-- Add a data attribute to store the product details JSON for each shop -->
                    <button class="checkout" id="placeOrderBtn" data-products='<?php echo json_encode($products); ?>' data-customer-id='<?php echo $_SESSION['user_id']; ?>'>Place Order</button>
                                <!-- Confirmation Modal -->
                                <div id="confirmationModal" class="modal">
                                    <div class="modal-content">
                                        <p class="confirm-order">Do you want to confirm your order?</p>
                                        <p class="confirm-note">Once confirmed, it cannot be canceled.</p>
                                        <div class="confirm-btn">
                                            <button class="cancel" id="cancelOrderBtn">Cancel</button>
                                            <button class="confirm" id="confirmOrderBtn">Confirm</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Thank You Modal (Initially hidden) -->
                                <div id="thankYouModal" class="modal">
                                    <div class="modal-content">
                                        <span class="close" id="closeThankYouModal">&times;</span>
                                        <i class="bi bi-check-circle"></i>
                                        <h2 class="confirmed">Order Confirmed !</h2>
                                        <p class="sucessful">Your order has been placed sucessfully. Check your orders here. <a href="customer_order.php">Orders</a></p>
                                        <a href="customer_home.php"><button class="c-shopping">Continue Shopping</button></a>
                                    </div>
                                </div>
                                <div id="paymentModal" class="modal1">
                                 <div class="modal-content1">
                                    <i class="bi bi-info-circle"></i>                                    
                                    <p class="confirm-order">No payment selected!</p>
                                </div>
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
              // Get the modal elements
            const confirmationModal = document.getElementById("confirmationModal");
            const thankYouModal = document.getElementById("thankYouModal");
            const paymentModal = document.getElementById("paymentModal");

            // Get the buttons to trigger modals
            const placeOrderBtn = document.getElementById("placeOrderBtn");
            const closeThankYouModal = document.getElementById("closeThankYouModal");
            const confirmOrderBtn = document.getElementById("confirmOrderBtn");
            const cancelOrderBtn = document.getElementById("cancelOrderBtn");
         

            // Function to close all modals
            function closeModals() {
                confirmationModal.style.display = "none";
                thankYouModal.style.display = "none";
                paymentModal.style.display = "none";
            }

            // Show the payment modal when the "Place Order" button is clicked
            placeOrderBtn.addEventListener("click", () => {
            // Check if a payment method is selected
            const selectedPaymentMethod = document.querySelector('input[name="paymentMethod"]:checked');

            if (!selectedPaymentMethod) {
                // If no payment method is selected, show the payment modal
                paymentModal.style.display = "block";

                // Automatically close the payment modal after 2 seconds
                setTimeout(() => {
                paymentModal.style.display = "none";
                }, 2000);
            } else {
                // If a payment method is selected, directly show the confirmation modal
                confirmationModal.style.display = "block";
            }
            });

            // Show the thank you modal when the order is confirmed
            confirmOrderBtn.addEventListener("click", () => {
                closeModals(); // Close all modals
                thankYouModal.style.display = "block";
            });

            // Close the thank you modal when the "Close" button is clicked
            closeThankYouModal.addEventListener("click", () => {
                closeModals();
            });

            // Close the confirmation and thank you modals when the "Cancel" button is clicked
            cancelOrderBtn.addEventListener("click", () => {
                closeModals();
            });

            // Close the modals if the user clicks outside the content
            window.addEventListener("click", (event) => {
            if (event.target === confirmationModal) {
                closeModals();
            }
            if (event.target === thankYouModal) {
                closeModals();
            }
            if (event.target === paymentModal) {
                closeModals();
            }
            });

            // Close the modals if the user presses the "Esc" key
            document.addEventListener("keydown", (event) => {
            if (event.key === "Escape") {
                closeModals();
            }
            });

        </script> 
         <script>
            document.getElementById("confirmOrderBtn").addEventListener("click", function() {
                // Gather necessary information
                var selectedProducts = <?php echo json_encode($selectedProducts); ?>;
                
                // Create an array to store product details
                var productDetails = [];
                console.log("selectedProducts:", selectedProducts);

                // Iterate through selected products and push details to the array
                for (var i = 0; i < selectedProducts.length; i++) {
                    var product = selectedProducts[i];
                    productDetails.push({
                        salesdetails_id: product.salesdetailsId, // Include salesdetails_id
                        product_id: product.productId,
                        shop_id: product.shopId,
                        quantity: product.quantity,
                        product_price: product.productPrice
                    });
                }

                // Send data to server for processing
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "", true); // Replace with the actual processing script
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        console.log(xhr.responseText); // Log the response for debugging
                        if (xhr.status == 200) {
                            // Handle the response from the server
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Redirect to a confirmation page or display a success message
                                console.log("Order placed successfully!");
                            } else {
                                // Display an error message
                                console.error("Failed to place the order.");
                            }
                        } else {
                            // Log any non-200 HTTP status
                            console.error("Unexpected HTTP status: " + xhr.status);
                        }
                    }
                };

                // Convert productDetails array to JSON string
                var productDetailsJson = JSON.stringify(productDetails);

                // Send the JSON string as a POST parameter
                xhr.send("selected_products=" + encodeURIComponent(productDetailsJson));
            });
        </script>

    

        <script>
            const gcashRadio = document.getElementById("gcashRadio");

            gcashRadio.addEventListener("change", function () {
                if (gcashRadio.checked) {
                    const selectedShopId = "<?php echo $selectedShopId; ?>";
                    const totalSales = "<?php echo $totalPrice; ?>";
                    const selectedPayment = "gcash"; // Set the selected payment method

                    // Get the selected products from the session
                    const selectedProducts = <?php echo json_encode($_SESSION['selected_products']); ?>;

                    // Encode the selected products and payment as URL parameters
                    const selectedProductsParam = encodeURIComponent(JSON.stringify(selectedProducts));
                    const selectedPaymentParam = encodeURIComponent(selectedPayment);

                    // Construct the URL with selected products, payment, and navigate to index.php
                    const indexPageUrl = `../../Payments/Payments/index.php?shop_id=${selectedShopId}&total_sales=${totalSales}&selected_products=${selectedProductsParam}&selected_payment=${selectedPaymentParam}`;
                    window.location.href = indexPageUrl;
                }
            });
        </script>


        <!-- Add this script to handle local storage -->
        <script>
            // Assume selectedProducts is an array containing your selected products
            var selectedProducts = <?php echo json_encode($selectedProducts); ?>;

            // Save selected products to local storage
            localStorage.setItem('selectedProducts', JSON.stringify(selectedProducts));
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const radioButtons = document.querySelectorAll('input[name="paymentMethod"]');

                radioButtons.forEach(function (radio) {
                    radio.addEventListener('change', function () {
                        // Uncheck all other radio buttons
                        radioButtons.forEach(function (otherRadio) {
                            if (otherRadio !== radio) {
                                otherRadio.checked = false;
                            }
                        });
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