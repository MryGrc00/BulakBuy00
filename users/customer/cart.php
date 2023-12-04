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
foreach ($productIDs as $ids) {
    $productID = $ids['product_id'];
    $salesdetailsId = $ids['salesdetails_id'];

    $product = get_product_details($productID, $salesdetailsId);
    if ($product) {
        // Add the fetched product details to the products array
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
    $conn = dbconnect(); // Connect to the database

    // Define the table and where clause
    $table = 'salesdetails';
    $where = 'customer_id';

    $productIDs = array(); // Initialize an array to hold the product details

    // Prepare the SQL query to fetch product_id and salesdetails_id
    $sql = "SELECT product_id, salesdetails_id FROM $table WHERE $where = ?";

    try {
        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Populate the productIDs array with the fetched data
        foreach ($result as $row) {
            $productIDs[] = array(
                'product_id' => $row['product_id'],
                'salesdetails_id' => $row['salesdetails_id']
            );
        }
    } catch (PDOException $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $conn = null;

    return $productIDs; // Return the array of product IDs and salesdetails IDs
}


// Function to get product details by joining the sales_details and products tables
function get_product_details($productID, $salesdetailsId) {
    $conn = dbconnect();
    // Update the SQL query to include salesdetails_id in the SELECT statement
    $sql = "SELECT p.*, sd.quantity, sd.salesdetails_id, sd.flower_type, sd.ribbon_color, sd.message
            FROM products p 
            JOIN salesdetails sd ON p.product_id = sd.product_id
            WHERE p.product_id = ? AND sd.salesdetails_id = ?";

    try {
        $stmt = $conn->prepare($sql);
        // Execute the statement with both productID and salesdetailsId
        $stmt->execute([$productID, $salesdetailsId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        return false;
    }

    $conn = null;
    return $product;
}





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
                margin-top: -20px;
            }
            .all-items .item-checkbox {
                margin-left: 10px;
            }
            .sub-price{
                color:#555;
                margin-left:200px;
            }
            .cart-item img {
                width: 100px;
                height: 230px;
                margin-right: 20px;
            }
            .flower-type{
                display: flex;
                gap:10px;
                margin-top:10px;
            }
            .flower{
                font-size: 13px;
                color:#777;
            }
            .type{
                font-size: 13px;
                color:#666;
            }
            .ribbon-color{
                display: flex;
                gap:10px;
                margin-top:-5px;
            }
            .ribbon{
                font-size: 13px;
                color:#777;
            }
            .color{
                font-size: 13px;
                color:#666;
            }

            .modal {
                display: none;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
            }
            .modal-content {
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
                <a class="navbar-brand d-flex align-items-center" href="customer_home.php">
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
                            <h6 class="items-label">Products</h6>
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
                                        if (empty($productsByShop)) {
                                            echo 'No products added to cart.';
                                        } else {
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
                                                        echo '<input type="checkbox" class="item-checkbox" data-shopId="' . $shopId . '" data-productId="' . $product['product_id'] . '" data-productName="' . $product['product_name'] . '" data-productPrice="' . $product['product_price'] . '" data-quantity="' . $product['quantity'] . '" data-salesdetails-id="' . $product['salesdetails_id'] . '">';
                                                        echo '<img src="' . $product['product_img'] . '" alt="' . $product['product_name'] . '">';
                                                        echo '</div>';
                                                
                                                        // Rest of your product display code...
                                                        echo '<div class="item-details">';
                                                        echo '<h2>' . $product['product_name'] . '</h2>';                
                                                        //Retrieve flower type and ribbon color from salesdetails table
                                                        $salesDetails = $product;

                                                        if ($salesDetails) {
                                                            // Check if flower type is available
                                                            if (!empty($salesDetails['flower_type'])) {
                                                                echo '<div class="flower-type">';
                                                                echo '<p class="flower">Flower:</p>';
                                                                echo '<p class="type">' . $salesDetails['flower_type'] . '</p>';
                                                                echo '</div>';
                                                            }

                                                            

                                                            // Check if ribbon color is available
                                                            if (!empty($salesDetails['ribbon_color'])) {
                                                                echo '<div class="ribbon-color">';
                                                                echo '<p class="ribbon">Ribbon:</p>';
                                                                echo '<p class="color">' . $salesDetails['ribbon_color'] . '</p>';
                                                                echo '</div>';
                                                            }
                                                            // Check if ribbon color is available
                                                            if (!empty($salesDetails['message'])) {
                                                                echo '<div class="ribbon-color">';
                                                                echo '<p class="ribbon">Message:</p>';
                                                                echo '<p class="color">' . $salesDetails['message'] . '</p>';
                                                                echo '</div>';
                                                            }
                                                        }

                                                                    
                                                        echo '<p class="price">₱ ' . $product['product_price'] . '</p>';
                                                        echo '<div class="quantity-control">';
                                                        echo '<button class="quantity-button" data-product-id="' . $product['product_id'] . '" data-salesdetails-id="' . $product['salesdetails_id'] . '" data-action="decrease">-</button>';
                                                        echo '<input type="text" id="quantity' . $product['product_id'] . '" value="' . $product['quantity'] . '">';
                                                        echo '<button class="quantity-button" data-product-id="' . $product['product_id'] . '" data-salesdetails-id="' . $product['salesdetails_id'] . '" data-action="increase">+</button>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>'; // End of cart-item
                                                    }
                                                
                                                    echo '</div>'; // End of cart-items
                                                    echo '<div class="border"></div>';
                                                }
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
                            <div id="confirmationModal" class="modal">
                                 <div class="modal-content">
                                    <i class="bi bi-info-circle"></i>                                    
                                    <p class="confirm-order">No products selected!</p>
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

             
                            // Function to save the checkbox state in local storage
                function saveCheckboxState(id, isChecked) {
                    localStorage.setItem(id, isChecked ? 'checked' : 'unchecked');
                }

                // Function to load the checkbox state from local storage
                function loadCheckboxState(id) {
                    const state = localStorage.getItem(id);
                    return state === 'checked';
                }

                const shopCheckboxes = {}; // Object to store shop checkboxes

                const itemCheckboxes = document.querySelectorAll('.item-checkbox');

                // Handle checkbox changes
                itemCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        const shopId = this.dataset.shopid; // Ensure camelCase here

                        // Uncheck checkboxes in other shops
                        for (const id in shopCheckboxes) {
                            if (id !== shopId) {
                                shopCheckboxes[id].forEach(otherCheckbox => {
                                    otherCheckbox.checked = false;
                                    saveCheckboxState(otherCheckbox.id, false);
                                });
                            }
                        }

                        updateSubtotal();
                    });

                    // Store checkboxes in the shopCheckboxes object
                    const shopId = checkbox.dataset.shopid; // Ensure camelCase here
                    if (!shopCheckboxes[shopId]) {
                        shopCheckboxes[shopId] = [];
                    }
                    shopCheckboxes[shopId].push(checkbox);
                });

                // JavaScript to reset checkboxes on page load and load their state from local storage
                window.addEventListener('load', function () {
                    itemCheckboxes.forEach(checkbox => {
                        const isChecked = loadCheckboxState(checkbox.id);
                        checkbox.checked = isChecked; // Set checkboxes based on local storage

                        const shopId = checkbox.dataset.shopid;
                        if (!shopCheckboxes[shopId]) {
                            shopCheckboxes[shopId] = [];
                        }
                        shopCheckboxes[shopId].push(checkbox);
                    });

                    updateSubtotal();
                });

                const quantityButtons = document.querySelectorAll('.quantity-button');

                quantityButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const quantityInput = this.parentNode.querySelector('input[type="text"]');
                        let currentQuantity = parseInt(quantityInput.value);
                        const productId = this.dataset.productId;
                        const salesdetailsId = this.dataset.salesdetailsId; // Add this line
                        const action = this.dataset.action;

                        if (action === 'increase') {
                            currentQuantity++;
                        } else if (action === 'decrease') {
                            if (currentQuantity > 1) {
                                currentQuantity--;
                            }
                        }

                        quantityInput.value = currentQuantity;
                        updateQuantityOnServer(action, productId, salesdetailsId, currentQuantity); // Update this line
                        // Call your update function here if needed
                    });
                });

                // Function to send an AJAX request to update the quantity on the server
                function updateQuantityOnServer(action, productId, salesdetailsId, newQuantity) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '../php/update_quantity.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            // Quantity updated successfully on the server
                            location.reload();                        }
                    };

                    const data = `action=${action}&product_id=${productId}&salesdetails_id=${salesdetailsId}&quantity=${newQuantity}`;
                    xhr.send(data);
                }




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

                function updateCheckoutButton() {
                    // Check if at least one product is selected
                    const isAtLeastOneProductSelected = Array.from(itemCheckboxes).some(checkbox => checkbox.checked);

                    // Disable or enable the checkout button based on the selection
                    checkoutButton.disabled = !isAtLeastOneProductSelected;

                    // Change background color of the checkout button
                    checkoutButton.style.backgroundColor = isAtLeastOneProductSelected ? '#28a745' : '#6c757d';
                }

                // Handle checkbox changes
                itemCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        updateSubtotal();
                        updateCheckoutButton();
                    });
                });

                // Function to save and load checkbox state
                function saveCheckboxState(id, isChecked) {
                    localStorage.setItem(id, isChecked ? 'checked' : 'unchecked');
                }

                function loadCheckboxState(id) {
                    const state = localStorage.getItem(id);
                    return state === 'checked';
                }
                   
            
        </script>
        <script>
           
           function goToPlaceOrder() {
            const isAtLeastOneProductSelected = Array.from(itemCheckboxes).some(checkbox => checkbox.checked);
            const confirmationModal = document.getElementById('confirmationModal');

            if (isAtLeastOneProductSelected) {
                const selectedProducts = getSelectedProducts();
                const selectedProductsParam = encodeURIComponent(JSON.stringify(selectedProducts));

                // Perform any additional actions before showing the modal if needed

                window.location.href = 'place_order.php?selected_products=' + selectedProductsParam;
            } else {
                // Display the modal
                confirmationModal.style.display = 'block';

                // Automatically close the modal after 2 seconds
                setTimeout(function () {
                    confirmationModal.style.display = 'none';
                }, 2000);
            }
        }



            function getSelectedProducts() {
                const itemCheckboxes = document.querySelectorAll('.item-checkbox');
                const selectedProducts = [];

                itemCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const shopId = checkbox.getAttribute('data-shopId');
                        const productId = checkbox.getAttribute('data-productId');
                        const productName = checkbox.getAttribute('data-productName');
                        const productPrice = parseFloat(checkbox.getAttribute('data-productPrice'));
                        const quantity = parseInt(checkbox.getAttribute('data-quantity'));
                        const salesdetailsId = checkbox.getAttribute('data-salesdetails-id');

                        const productDetails = {
                            shopId: shopId,
                            productId: productId,
                            productName: productName,
                            productPrice: productPrice,
                            quantity: quantity,
                            salesdetailsId: salesdetailsId
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