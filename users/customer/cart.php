<?php
session_start();
include '../php/dbhelper.php';

$products = array(); // Initialize an array to store the product details
$_SESSION['selected_products'] = $products;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Retrieve the product IDs added to the cart by the user from the sales_details table
    $productIDs = get_product_ids_in_cart($user_id);

    // Fetch product details for each product in the cart
    foreach ($productIDs as $productID) {
        $product = get_product_details($productID);
        if ($product) {
            $products[] = $product;
        }
    }
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
    
}

// Function to get product IDs added to the cart by the user
function get_product_ids_in_cart($user_id) {
    $table = 'salesdetails';
    $where = 'customer_id';
    $data = $user_id;

    $productIDs = array();

    $conn = dbconnect();
    $sql = "SELECT product_id FROM $table WHERE $where = ?";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$data]);
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        $productIDs = array_merge($productIDs, $result);
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
    
    $conn = null;

    return $productIDs;
}

// Function to get product details by joining the sales_details and products tables
function get_product_details($productID) {
    $conn = dbconnect();
    $sql = "SELECT p.*, sd.quantity FROM products p 
    JOIN salesdetails sd ON p.product_id = sd.product_id
    WHERE p.product_id = ?";


    
try {
    $stmt = $conn->prepare($sql);
    $stmt->execute([$productID]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
    return false;
}

    $conn = null;

    return $product;
}
function getCurrentQuantityFromDatabase($product_id) {
    $user_id = $_SESSION['user_id'];
    $conn = dbconnect();
    $sql = "SELECT quantity FROM salesdetails WHERE customer_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $product_id]);
    $result = $stmt->fetchColumn();
    return $result;
}

// Get the image file names (or paths) stored in the database
$imageFileNames = explode(',', $product['product_img']);


$imageBasePath = 'images'; // Adjust the path as needed
$isArranger = $user['role'] == 'arranger';
$productAddedByArranger = $product['product_id'] == $user_id;

?>

<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cart</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/cart.css">
        <style>
             .number{
                font-size: 13px;
                margin-top: 25px;
            }
            .cart-item {
                display: flex;
                align-items: center;
                padding:20px;
                margin-top: 30px;
            }
        
            .item-details h2 {
                font-size: 16px;
                color:#555;
                margin-top: -65px;
            }
            .all-items .item-checkbox {
                margin-left: 10px;
            }
            .sub-price{
                color:#555;
                margin-left:200px;
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
                                    <div id="search-results">Shopping Cart</div>
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
                            <input type="checkbox" class="item-checkbox" id="selectAllItems" data-product-price="<?= $totalPriceOfAllItems ?>">
                            <h6 class="items-label">All Items</h6>
                            <button class="edit"><i class="bi bi-trash"></i></button>
                        </div>
                      
                            <div class="cart-items">
     
                           
                                        <?php
                                        $productsByShop = array();

                                        // Loop through each product and organize them by shop_id
                                        foreach ($products as $product) {
                                            $shopId = $product['shop_owner'];
                                            if (!isset($productsByShop[$shopId])) {
                                                $productsByShop[$shopId] = array();
                                            }
                                            $productsByShop[$shopId][] = $product;
                                        }
                                        
                                        // Loop through each shop and display its products
                                        foreach ($productsByShop as $shopId => $shopProducts) {
                                            // Retrieve shop details from the database based on shop_id
                                            $shop = get_record('shops', 'shop_id', $shopId);
                                        
                                            // Display shop details
                                            if ($shop) {
                                                echo '<div class="shop-info">';
                                                echo '<img src="' . $shop['shop_img'] . '" alt="Shop Image">';
                                                echo '<div class="shop-name">';
                                                echo '<a href="../vendor/vendor_shop.html">';
                                                echo '<h3>' . $shop['shop_name'] . '</h3>';
                                                echo '<i class="fa fa-angle-right" aria-hidden="true"></i>';
                                                echo '</a>';
                                                echo '</div>';
                                                echo '</div>';
                                            } else {
                                                echo "Shop details not found.";
                                            }
                                        
                                            // Display products for the current shop
                                            echo '<hr class="cart-hr">';
                                            echo '<div class="cart-items">';
                                            foreach ($shopProducts as $product) {
                                                echo '<div class="cart-item">';
                                                echo '<div class="custom-checkbox" style="margin-top: -30px">';
                                                echo '<input type="checkbox" class="item-checkbox" data-shopId="' . $shopId . '" data-productId="' . $product['product_id'] . '" data-productName="' . $product['product_name'] . '" data-productPrice="' . $product['product_price'] . '" data-quantity="' . $product['quantity'] . '">';
                                                echo '<img src="' . $product['product_img'] . '" alt="' . $product['product_name'] . '">';
                                                echo '</div>';
                                        
                                                // Rest of your product display code...
                                                echo '<div class="item-details">';
                                                echo '<h2>' . $product['product_name'] . '</h2>';
                                                echo '<p class="price">₱ ' . $product['product_price'] . '</p>';
                                                echo '<div class="quantity-control">';
                                                echo '<button class="quantity-button" data-product-id="' . $product['product_id'] . '" data-action="decrease">-</button>';
                                                echo '<input type="text" id="quantity' . $product['product_id'] . '" value="' . getCurrentQuantityFromDatabase($product['product_id']) . '">';
                                                echo '<button class="quantity-button" data-product-id="' . $product['product_id'] . '" data-action="increase">+</button>';
                                                echo '</div>';
                                                echo '</div>';
                                                echo '</div>'; // End of cart-item
                                            }
                                        
                                            echo '</div>'; // End of cart-items
                                            echo '<div class="border"></div>';
                                        }
                                        ?>
                                 

                            
                                <!-- Assuming this is your existing HTML structure -->
                                
                                <!-- End of loop -->
                               
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
                                <p class="sub-label">Sub-total</p>
                                <p class="sub-price" id="subTotalPrice">₱ 0</p>
                            </div>
                        </div>
                        <div class="button-container">
                       
                            <button class="checkout" onclick="goToPlaceOrder()">Checkout</button>
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
            // JavaScript to handle quantity changes for each product
            <?php foreach ($products as $product) { ?>
                const quantityInput<?= $product['product_id'] ?> = document.getElementById('quantity<?= $product['product_id'] ?>');
                const decreaseButton<?= $product['product_id'] ?> = document.querySelector('button[data-product-id="<?= $product['product_id'] ?>"][data-action="decrease"]');
                const increaseButton<?= $product['product_id'] ?> = document.querySelector('button[data-product-id="<?= $product['product_id'] ?>"][data-action="increase"]');

                decreaseButton<?= $product['product_id'] ?>.addEventListener('click', () => {
                    decreaseQuantity<?= $product['product_id'] ?>();
                });

                increaseButton<?= $product['product_id'] ?>.addEventListener('click', () => {
                    increaseQuantity<?= $product['product_id'] ?>();
                });

                function decreaseQuantity<?= $product['product_id'] ?>() {
                let currentQuantity = parseInt(quantityInput<?= $product['product_id'] ?>.value);
                if (currentQuantity > 1) {
                    currentQuantity--;
                    quantityInput<?= $product['product_id'] ?>.value = currentQuantity;
                    updateQuantityOnServer('decrease', <?= $product['product_id'] ?>, currentQuantity);
                    updateSubtotal();
                }
            }

            function increaseQuantity<?= $product['product_id'] ?>() {
                let currentQuantity = parseInt(quantityInput<?= $product['product_id'] ?>.value);
                currentQuantity++;
                quantityInput<?= $product['product_id'] ?>.value = currentQuantity;
                updateQuantityOnServer('increase', <?= $product['product_id'] ?>, currentQuantity);
                updateSubtotal();
            }

            // Function to send an AJAX request to update the quantity on the server
            function updateQuantityOnServer(action, productId, newQuantity) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../php/update_quantity.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        // Quantity updated successfully on the server
                    }
                };

                const data = `action=${action}&product_id=${productId}&quantity=${newQuantity}`;
                xhr.send(data);
            }
        <?php } ?>
    </script>

        <script>
            const selectedProducts = [];
                        // Function to save the checkbox state in local storage
                function saveCheckboxState(id, isChecked) {
                    localStorage.setItem(id, isChecked ? 'checked' : 'unchecked');
                }

                // Function to load the checkbox state from local storage
                function loadCheckboxState(id) {
                    const state = localStorage.getItem(id);
                    return state === 'checked';
                }

                // JavaScript to toggle all checkboxes when "All Items" is clicked
                const selectAllItemsCheckbox = document.getElementById('selectAllItems');
                const itemCheckboxes = document.querySelectorAll('.item-checkbox');

                selectAllItemsCheckbox.addEventListener('change', function () {
                    const isChecked = this.checked;

                    itemCheckboxes.forEach(checkbox => {
                        checkbox.checked = isChecked;
                        saveCheckboxState(checkbox.id, isChecked); // Save state in local storage
                    });

                    updateSubtotal();
                });

                // Handle checkbox changes
                itemCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        saveCheckboxState(checkbox.id, this.checked); // Save state in local storage
                        updateSubtotal();
                    });
                });

                // JavaScript to reset checkboxes on page load and load their state from local storage
                window.addEventListener('load', function () {
                    itemCheckboxes.forEach(checkbox => {
                        const isChecked = loadCheckboxState(checkbox.id);
                        checkbox.checked = isChecked; // Set checkboxes based on local storage
                    });

                    updateSubtotal();
                });
            // JavaScript to handle quantity changes for all products
            const quantityButtons = document.querySelectorAll('.quantity-button');

            quantityButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const quantityInput = this.parentNode.querySelector('input[type="text"]');
                    let currentQuantity = parseInt(quantityInput.value);

                    if (this.classList.contains('quantity-increase')) {
                        currentQuantity++;
                    } else if (this.classList.contains('quantity-decrease')) {
                        if (currentQuantity > 1) {
                            currentQuantity--;
                        }
                    }

                    quantityInput.value = currentQuantity;
                    updateSubtotal();
                });
            });

            // JavaScript to calculate and update the sub-total price
            function updateSubtotal() {
                let subtotal = 0;

                itemCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const itemContainer = checkbox.closest('.cart-item');
                        const priceElement = itemContainer.querySelector('.price');
                        const price = parseFloat(priceElement.textContent.replace('₱ ', ''));
                        const quantityElement = itemContainer.querySelector('input[type="text"]');
                        const quantity = parseInt(quantityElement.value);

                        subtotal += price * quantity;
                    }
                });

                const subTotalPriceElement = document.getElementById('subTotalPrice');
                subTotalPriceElement.textContent = '₱ ' + subtotal.toFixed(2); // Format the subtotal price
            }

            // Handle checkbox changes
            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    updateSubtotal();
                });
            });
            
        </script>
        <script>
            function goToPlaceOrder() {
                const selectedProducts = getSelectedProducts();
                const selectedProductsParam = encodeURIComponent(JSON.stringify(selectedProducts));
                window.location.href = 'place_order.php?selected_products=' + selectedProductsParam;
            }

            function getSelectedProducts() {
                const itemCheckboxes = document.querySelectorAll('.item-checkbox');
                const selectedProducts = [];

                itemCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const shopId = checkbox.getAttribute('data-shopId'); // Add this line
                        const productId = checkbox.getAttribute('data-productId');
                        const productName = checkbox.getAttribute('data-productName');
                        const productPrice = parseFloat(checkbox.getAttribute('data-productPrice'));
                        const quantity = parseInt(checkbox.getAttribute('data-quantity'));

                        const productDetails = {
                            shopId: shopId, // Add this line
                            productId: productId,
                            productName: productName,
                            productPrice: productPrice,
                            quantity: quantity
                        };
                        selectedProducts.push(productDetails);
                    }
                });

                return selectedProducts;
            }










            
        </script>

            
        


        <script>
            function goBack() {
                window.history.back();
            }
          </script>
            
    </body>
</html>