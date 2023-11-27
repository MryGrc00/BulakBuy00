<?php
session_start();
include '../php/dbhelper.php';

$products = null;
$user = null;

if (isset($_GET['product_id']) && isset($_SESSION['user_id'])) {
    $productID = $_GET['product_id'];
    $userID = $_SESSION['user_id'];

    $product = get_record('products', 'product_id', $productID);
    
    // Assuming 'shop_owner' is the field in 'products' that references 'shop_id' in 'shops'
    $shop = get_record('shops', 'shop_id', $product['shop_owner']);
    
    // Retrieve user details (owner of the shop) from the database
    $user = get_record('users', 'user_id', $shop['owner_id']);

    $isArranger = isset($user['role']) && $user['role'] === 'arranger';


    } 



// Get the image file names (or paths) stored in the database
$imageFileNames = explode(',', $product['product_img']);

// Base path to the directory where images are stored
$imageBasePath = 'images'; // Adjust the path as needed

// Function to check if a product is in the cart
function get_cart_item($product_id, $customer_id) {
    $table = 'salesdetails';
    $where = 'product_id';
    $data = $product_id;
    $additional_where = 'customer_id';
    $additional_data = $customer_id;
    
    $cart_item = get_record_with_additional_where($table, $where, $data, $additional_where, $additional_data);

    return $cart_item;
}

// Function to get a record with an additional WHERE condition
function get_record_with_additional_where($table, $where, $data, $additional_where, $additional_data) {
    $row = null;
    $sql = "SELECT * FROM $table WHERE $where = ? AND $additional_where = ?";
    $conn = dbconnect();
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$data, $additional_data]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC); // Use fetch instead of fetchAll
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
    $conn = null;
    return $row; // Return a single record, not an array of records
}


    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add-to-cart"])) {
        $product_id = $_POST["product_id"];
        $customer_id = $_SESSION['user_id'];
        $selected_flower_types = $_POST["selected_flower_types"];
        $selected_ribbon_colors = $_POST["selected_ribbon_colors"];
        $message = $_POST["message"];

        update_or_add_cart_item($product_id, $customer_id, $selected_flower_types, $selected_ribbon_colors, $message);

        // Check if the product is already in the cart
        $cart_item = get_cart_item($product_id, $customer_id);

        if ($cart_item) {
            // Product is already in the cart, increment the quantity
            $quantity = $cart_item['quantity'] + 1;
            // Update the cart item directly with an SQL query
            $sql = "UPDATE salesdetails SET quantity = :quantity WHERE customer_id = :customer_id AND product_id = :product_id"; // Use correct column name 'customer_id'
            $conn = dbconnect();
            try {
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
                $stmt->bindParam(":customer_id", $customer_id, PDO::PARAM_INT); // Use correct column name 'customer_id'
                $stmt->bindParam(":product_id", $product_id, PDO::PARAM_INT);
                $stmt->execute();
            } catch (PDOException $e) {
                echo "Database Error: " . $e->getMessage();
            }
            $conn = null;
        } else {
            // Product is not in the cart, add a new record
            $fields = ['customer_id', 'product_id', 'quantity']; // Use correct column name 'customer_id'
            $data = [$customer_id, $product_id, 1]; // Initialize quantity as 1
            add_record('salesdetails', $fields, $data);
        }
    }

$productAddedByArranger = $product['shop_owner'] == $userID;

function update_or_add_cart_item($product_id, $customer_id, $flower_types, $ribbon_colors, $message) {
    $conn = dbconnect(); // Ensure you have a function to connect to your database

    // Check if the product is already in the cart
    $sql = "SELECT * FROM salesdetails WHERE product_id = ? AND customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$product_id, $customer_id]);
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart_item) {
        // Product is already in the cart, update the existing cart item
        $update_sql = "UPDATE salesdetails SET flower_type = :flower_types, ribbon_color = :ribbon_colors, message = :message WHERE product_id = :product_id AND customer_id = :customer_id";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->execute([
            ':flower_types' => $flower_types,
            ':ribbon_colors' => $ribbon_colors,
            ':message' => $message,
            ':product_id' => $product_id,
            ':customer_id' => $customer_id
        ]);
    } else {
        // Product is not in the cart, add a new cart item
        $insert_sql = "INSERT INTO salesdetails (product_id, customer_id, flower_type, ribbon_color, message) VALUES (:product_id, :customer_id, :flower_types, :ribbon_colors, :message)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->execute([
            ':product_id' => $product_id,
            ':customer_id' => $customer_id,
            ':flower_types' => $flower_types,
            ':ribbon_colors' => $ribbon_colors,
            ':message' => $message
        ]);
    }
}



?>



<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/product.css">
        <style>
            .add-btn .add {
                background-color: #65A5A5;
                border: none;
                padding: 10px;
                width: 50%;
                color: white;
                border-radius: 20px;
                margin-top: 30px;
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
                        <a href="cart.php">
                            <li class="cart">
                                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                <p class="num-cart">1 </p>
                            </li>
                        </a>
                        <li class="nav-item">
                            <form class="form-inline my-2 my-lg-0">
                                <a href=""><i class="fa fa-search"></i></a>
                                <input type="text"  class="form-control form-input" placeholder="Search">
                                <a href="javascript:void(0);" onclick="goBack()">
                                    <i class="back fa fa-angle-left" aria-hidden="true"></i>
                                    <div id="search-results"></div>
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
                <div class="column1 mx-auto text-center">
                    <div class="image-container">
                        <div id="myCarousel" class="carousel slide" >
                            <!-- Indicators -->
                            <ol class="carousel-indicators">
                                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                            </ol>
                            <!-- Slides -->
                            <div class="carousel-inner">
                                <div class="carousel-item active" data-target="image1">
                                <?php
                                    // Extract the first image URL from the comma-separated string
                                    $imageUrls = explode(',', $product['product_img']);
                                    $firstImageUrl = trim($imageUrls[0]); // Get the first image URL

                                    // Display the first image
                                    echo '<img src="' . $firstImageUrl . '" alt="' . $product['product_name'] . '">';
                                ?>                                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column2">
                    <!-- Second column with image descriptions -->
                    <p class="p-name"><?php echo $product['product_name']; ?></p>
                    <p class="p-category"><?php echo $product['product_category']; ?></p>
                    <p class="p-price"> <?php echo $product['product_price']; ?></p>
                    <p class="p-ratings">4.5 ratings & 35 reviews</p>

                    <?php if ($isArranger): ?>
                        <div class="f-type">
                            <h4 class="type-label">Flower Type(s)</h4>
                            <?php
                                // Assuming $product['flower_type'] contains a comma-separated list of flower types
                                if (!empty($product['flower_type'])) {
                                    // Corrected variable reference from 'flower_type' to 'flower_types'
                                    $flowerTypes = explode(',', $product['flower_type']);
                                    foreach ($flowerTypes as $type) {
                                        echo '<button class="t-btn" name="selected_flower_types">' . htmlspecialchars(trim($type)) . '</button>';
                                    }
                                }
                            ?>
                        </div>
                        <div class="ribbon">
                            <h4 class="ribbon-label">Ribbon Color</h4>
                            <?php
                                // Assuming $product['ribbon_color'] contains a comma-separated list of ribbon colors
                                if (!empty($product['ribbon_color'])) {
                                    // Corrected variable reference from 'ribbon_color' to 'ribbon_colors'
                                    $ribbonColors = explode(',', $product['ribbon_color']);
                                    foreach ($ribbonColors as $color) {
                                        echo '<button class="ribbon-btn" name="selected_ribbon_colors">' . htmlspecialchars(trim($color)) . '</button>';
                                    }
                                }
                            ?>
                        </div>
                        <form method="POST" action="">
                        <div class="p-message">
                            <h4 class="m-label">Message</h4>
                            <input type="text" class="message" name="message" placeholder="Message">
                        </div>
                    <?php endif; ?>
                    <div class="btn-container">
                        <div class="add-btn">
                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                            <!-- Hidden fields for selected flower types and ribbon colors -->
                            <input type="hidden" name="selected_flower_types" id="selected_flower_types" value="">
                            <input type="hidden" name="selected_ribbon_colors" id="selected_ribbon_colors" value="">
                            <button class="add add-to-cart-button" name="add-to-cart" data-product-id="<?= $product['product_id'] ?>">Add to Cart</button>
                        </form>
                   
                                <a href="chat.php?user_id=<?php echo $user['user_id']; ?>">
                                <button class="messenger"><i class="bi bi-messenger"></i></button>
                            </a>
                        </div>
                        <div id="addModal" class="modal">
                            <div class="modal-content">
                                <i class="bi bi-check-circle"></i>
                                <p class="confirm-order">Added to Cart</p>
                            </div>
                        </div>
                    </div>
                        <p class="p-desc-label">Description</p>
                        <p class="p-desc"><?php echo $product['product_desc']; ?></p>   
                    
                    <div class="border"></div>
                    <?php 
                         // Retrieve shop details from the database based on shop_id
                        $shop = get_record('shops', 'shop_id', $product['shop_owner']);

                        // Display shop details
                        if ($shop) {
                            echo '<div class="shop">';
                            echo '<div class="shop-pic">';
                            echo '<img src="' . $shop['shop_img'] . '" alt="Shop Image">';
                            echo '</div>';
                            echo '<div class="shop-info">';
                            echo '<div class="info">';
                            echo '<p class="s-name">' . $shop['shop_name'] . '</p>';
                            echo '<p class="s-location"><i class="bi bi-geo-alt"></i> ' . $shop['shop_address'] . '</p>';
                            echo '<a href="../vendor/vendor_shop.html">';
                            echo '<button class="view-shop"><i class="bi bi-shop-window"></i>View Shop</button>';
                            echo '</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        } else {
                            echo "Shop details not found.";
                        }
                    ?>
                    <div class="reviews">
                        <p class="r-label">Product Ratings</p>
                    </div>
                    <div class="stars">
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <p>4.5 ratings & 35 Reviews</p>
                    </div>
                    <?php
                    function get_product_details($product_id) {
                        $conn = dbconnect();
                        $sql = "SELECT * FROM products WHERE product_id = ?";
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
                        
                    if (isset($_GET['product_id'])) {
                        $product_id = $_GET['product_id'];
                    
                        // Fetch product details from the product table
                        $product_details = get_product_details($product_id);
                    
                        // Display the product details
                        if ($product_details) {
                            // ... (your existing code for displaying product details)
                    
                            // Display feedback and ratings
                            $feedbackAndRatings = get_feedback_and_ratings($product_id);
                    
                            if ($feedbackAndRatings) {
                                foreach ($feedbackAndRatings as $feedback) {
                                    // Fetch customer details
                                    $customer = get_customer_details($feedback['customer_id']);
                                    $fullName = $customer['first_name'] . ' ' . $customer['last_name'];
                                    $reviewImagePath = '../images/' . $feedback['review_image'];
                                    // Display customer details if there are feedback and ratings
                                    if ($customer && $feedback['rating'] > 0) {
                                        echo '<div class="p-review">
                                                <div class="review-pic">
                                                    <img src="' . $customer['profile_img'] . '" alt="Customer Profile">
                                                </div>
                                                <div class="review-info">
                                                    <div class="review-text">
                                                        <p class="c-name">' . $fullName . '</p>
                                                        <p class="r-star">' . generate_star_rating($feedback['rating']) . '&nbsp;' . $feedback['rating'] . '&nbsp;stars</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="r-details">
                                                <p class="c-review">' . $feedback['feedback'] . '</p>';
                    
                                        // Display review image if available
                                        if (!empty($feedback['review_image'])) {
                                            echo '<div class="image-preview">
                                                    <img src="' . $reviewImagePath . '" alt="Review Image">
                                                </div>';
                                        }
                                        echo '</div>';
                                    } else {
                                        // If there are no feedback and ratings or the rating is 0, don't display user details
                                        echo "No feedback and ratings available.";
                                    }
                                }
                            } else {
                                echo "No feedback and ratings available.";
                            }
                        } else {
                            echo "Product details not found.";
                        }
                    } else {
                        echo "Product ID not provided.";
                    }
                            // Function to fetch feedback and ratings from the sales table
                            function get_feedback_and_ratings($product_id) {
                                $conn = dbconnect();
                                $sql = "SELECT * FROM sales WHERE product_id = ?";
                                try {
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute([$product_id]);
                                    $feedbackAndRatings = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    $conn = null;
                                    return $feedbackAndRatings;
                                } catch (PDOException $e) {
                                    echo $sql . "<br>" . $e->getMessage();
                                    $conn = null;
                                    return false;
                                }
                            }

                            // Function to fetch customer details
                            function get_customer_details($customer_id) {
                                $conn = dbconnect();
                                $sql = "SELECT * FROM users WHERE user_id = ?";
                                try {
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute([$customer_id]);
                                    $customerDetails = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $conn = null;
                                    return $customerDetails;
                                } catch (PDOException $e) {
                                    echo $sql . "<br>" . $e->getMessage();
                                    $conn = null;
                                    return false;
                                }
                            }

                            // Function to generate star rating HTML based on the rating value
                            function generate_star_rating($rating) {
                                $starRatingHTML = '<i class="fa fa-star" aria-hidden="true"></i>';
                                $emptyStarHTML = '<i class="fa fa-star-o" aria-hidden="true"></i>';

                                $fullStars = floor($rating);
                                $emptyStars = 5 - $fullStars;

                                $ratingHTML = str_repeat($starRatingHTML, $fullStars) . str_repeat($emptyStarHTML, $emptyStars);

                                return $ratingHTML;
                            }
                            ?>

                    <hr>
                    <a href="../common/see_all_reviews.html" class="all">
                        <p>See All Reviews</p>
                    </a>
                </div>
                <!-- Add this to your HTML -->
                <div id="imageModal" class="modal1">
                    <span class="close">&times;</span>
                    <img id="modalImage" class="modal1-content" alt="Modal Image">
                    <button id="prevButton" class="prev">&#8249;</button>
                    <button id="nextButton" class="next">&#8250;</button>
                </div>
            </div>
            <section>
                <div class="label">
                    <p class="other">Other Products</p>
                </div>
                <div class="product-list" id="product-container">
                  
                    <div class="product">
                        <a href="product.html">
                            <img src="https://casajuan.ph/cdn/shop/products/anahawnapkinring.jpg?v=1626910031" alt="Product 1">
                            <div class="product-name">Product 1</div>
                            <div class="product-category">Category 1</div>
                            <div class="p">
                                <div class="product-price">₱ 19.99</div>
                                <div class="product-ratings">4.5 stars</div>
                            </div>
                        </a>
                    </div>
                </div>
            </section>
            <p class="p-end">No more products found</p>
            <br><br><br>
            
        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            // Global declaration of addOrderBtn, if it exists
            var addOrderBtn = document.getElementById("addOrderBtn");

            // Add event listeners to "Add to Cart" buttons
            const addToCartButtons = document.querySelectorAll(".add-to-cart-button");
            addToCartButtons.forEach(function(button) {
                button.addEventListener("click", function() {
                    console.log("Button clicked");
                    addToCartAndShowModal();
                });
            });

            if (addOrderBtn) {
                // Add a click event listener to the "Add to Cart" button
                addOrderBtn.addEventListener("click", function () {
                    captureMessageInput(); // Capture the message input
                    updateSelections();

                    // Submit the form
                    document.querySelector("form").submit();
                });

                // Event listener to show the modal when the button is clicked
                addOrderBtn.addEventListener("click", () => {
                    addModal.style.display = "block";
                    
                    // Automatically close the modal after 2 seconds
                    setTimeout(() => {
                        addModal.style.display = "none";
                    }, 2000);
                });
            }

          // Function to handle the selection of a button
            function toggleSelection(button) {
                button.classList.toggle("selected");
                updateSelections();
            }

            // Function to update the hidden input fields based on the selected buttons
            function updateSelections() {
                const selectedFlowerTypes = Array.from(document.querySelectorAll('.f-type .t-btn.selected'))
                                                .map(button => button.textContent.trim())
                                                .join(',');
                const selectedRibbonColors = Array.from(document.querySelectorAll('.ribbon .ribbon-btn.selected'))
                                                .map(button => button.textContent.trim())
                                                .join(',');

                document.getElementById("selected_flower_types").value = selectedFlowerTypes;
                document.getElementById("selected_ribbon_colors").value = selectedRibbonColors;
}



            // Function to capture the message input
            function captureMessageInput() {
                var messageInput = document.querySelector(".message");
                var messageValue = messageInput.value;
                document.getElementById("message").value = messageValue;
            }

            // Get the modal and image elements
            var modal = document.getElementById('imageModal');
            var modalImage = document.getElementById('modalImage');
            var prevButton = document.getElementById('prevButton');
            var nextButton = document.getElementById('nextButton');
            var images = document.querySelectorAll('.image-preview img');
            var currentIndex = 0;

            // Function to open the modal and display the clicked image
            function openModal(index) {
                modal.style.display = 'block';
                modalImage.src = images[index].src;
                currentIndex = index;
            }

            // Function to close the modal
            function closeModal() {
                modal.style.display = 'none';
            }

            // Function to navigate to the previous image
            function prevImage() {
                if (currentIndex > 0) {
                    currentIndex--;
                    modalImage.src = images[currentIndex].src;
                }
            }

            // Function to navigate to the next image
            function nextImage() {
                if (currentIndex < images.length - 1) {
                    currentIndex++;
                    modalImage.src = images[currentIndex].src;
                }
            }

            // Add click event listeners to the images
            images.forEach(function (image, index) {
                image.addEventListener('click', function () {
                    openModal(index);
                });
            });

            // Add click event listener to the close button
            document.querySelector('.close').addEventListener('click', closeModal);

            // Add click event listeners to the next and previous buttons
            prevButton.addEventListener('click', prevImage);
            nextButton.addEventListener('click', nextImage);

            // Go back function
            function goBack() {
                window.history.back();
            }
        </script>

  
        <script>
                
            // Get the modal element
            const addModal = document.getElementById("addModal");
            
            // Get the "Add to Cart" button element
            const addOrderBtn = document.getElementById("addOrderBtn");
            
            // Event listener to show the modal when the button is clicked
            addOrderBtn.addEventListener("click", () => {
                // Collect data from the data attributes
                const productId = addOrderBtn.getAttribute("data-product-id");
                const customerId = addOrderBtn.getAttribute("data-customer-id");
                const flowerType = addOrderBtn.getAttribute("data-flower-type");
                const ribbonColor = addOrderBtn.getAttribute("data-ribbon-color");
                const message = document.querySelector(".message").value; // Get the message from the input field

                // Send the data to a PHP script using fetch or AJAX
                fetch("../php/add_to_cart.php", {
                    method: "POST",
                    body: JSON.stringify({
                        productId: productId,
                        customerId: customerId,
                        flowerType: flowerType,
                        ribbonColor: ribbonColor,
                        message: message
                    }),
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Product added to cart successfully, show the modal
                        addModal.style.display = "block";

                        // Automatically close the modal after 2 seconds
                        setTimeout(() => {
                            addModal.style.display = "none";
                        }, 2000);
                    } else {
                        // Handle the case when adding to the cart fails
                        console.error("Failed to add the product to the cart.");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });
            });
        </script>
        <script>
    // Add event listeners to "Add to Cart" buttons
    const addToCartButtons = document.querySelectorAll(".add-to-cart-button");
    addToCartButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            console.log("Button clicked");
            addToCartAndShowModal();
        });
    });
</script>

      
            
    </body>
</html>