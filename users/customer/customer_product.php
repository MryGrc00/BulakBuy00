<?php
session_start();
include '../php/dbhelper.php';

$products = null;

if (isset($_GET['product_id']) && isset($_SESSION['user_id'])) {
    $productID = $_GET['product_id'];
    $userID = $_SESSION['user_id'];

    // Retrieve product details from the database
    $product = get_record('products', 'product_id', $productID);
    
    // Assuming 'shop_owner' is the field in 'products' that references 'shop_id' in 'shops'
    $shop = get_record('shops', 'shop_id', $product['shop_owner']);
    
    // Retrieve user details (owner of the shop) from the database
    $user = get_record('users', 'user_id', $shop['owner_id']);

    $isArranger = isset($user['role']) && $user['role'] === 'arranger';

    if ($product && $user && $shop && $shop['owner_id'] == $userID) {
        // Product exists, user (shop owner) exists, and the product belongs to the logged-in user's shop
    } else {
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
                        <a href="cart.html">
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
                                    echo '<img src="' . $product['product_img'] . '" alt="' . $product['product_name'] . '">';
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
                                    echo '<button class="t-btn">' . htmlspecialchars(trim($type)) . '</button>';
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
                                    echo '<button class="ribbon-btn">' . htmlspecialchars(trim($color)) . '</button>';
                                }
                            }
                        ?>
                    </div>
                    <div class="p-message">
                        <h4 class="m-label">Message</h4>
                        <input type="text" class="message" placeholder="Message">
                    </div>
                <?php endif; ?>

                    <div class="btn-container">
                        <div class="add-btn">
                            <button class="add" id="addOrderBtn"><i class="bi bi-cart3"></i></i>&nbsp;&nbsp;&nbsp;Add to Cart</button>
                            <a href="../chat.php?user_id=<?php echo $shop['owner_id']; ?>">
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
                    <div class="border"></div>
                    <p class="p-desc-label">Description</p>
                    <p class="p-desc"><?php echo $product['product_desc']; ?>
                    </p>
                    <div class="border"></div>
                    <div class="shop">
                        <div class="shop-pic">
                            <img src="<?php echo $shop['shop_img']; ?>" alt="Shop Profile">
                        </div>
                        <div class="shop-info">
                            <div class="info">
                                <p class="s-name"><?php echo $shop['shop_name']; ?></p>
                                <p class="s-location"><i class="bi bi-geo-alt"></i> <?php echo $shop['shop_address']; ?></p>
                                <?php
                                // Check if the shop owner is a seller or an arranger and set the appropriate link
                                $link = ($user['role'] === 'seller') ? "vendor_shop.php?shop_id=" . $shop['shop_id'] : "arranger_shop.php?shop_id=" . $shop['shop_id'];
                                ?>
                                <a href="<?php echo $link; ?>">
                                    <button class="view-shop"><i class="bi bi-shop-window"></i>View Shop</button>
                                </a>
                            </div>
                        </div>
                    </div>

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
                    <div class="p-review">
                        <div class="review-pic">
                            <img src="https://i.pinimg.com/736x/2e/59/24/2e5924495141cd8a39ad9deca8acad0e.jpg" alt="Customer Profile">
                        </div>
                        <div class="review-info">
                            <div class="review-text">
                                <p class="c-name">N**ya</p>
                                <p class="r-star">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i> 5 stars
                                </p>
                                <p class="r-date">February 27, 2023</p>
                            </div>
                        </div>
                    </div>
                    <div class="r-details">
                        <p class="c-review">I just receive my order this afternoon, the flower was very fresh and arrived in good shape and the flower smells good, the seller is very  accommodating and the delivery is very fast. Thank you seller.</p>
                    </div>
                    <div class="image-preview">
                        <img src="https://cdn1.1800flowers.com/wcsstore/Flowers/images/catalog/148707xlx.jpg?height=456&width=418&sharpen=a0.5,r1,t1&quality=80&auto=webp&optimize={medium}" alt="Image 1">
                        <img src="https://fyf.tac-cdn.net/images/products/small/BN91208.jpg?auto=webp&quality=60&width=650" alt="Image 2" >
                        <img src="https://cdn1.1800flowers.com/wcsstore/Flowers/images/catalog/148707xlx.jpg?height=456&width=418&sharpen=a0.5,r1,t1&quality=80&auto=webp&optimize={medium}" alt="Image 1">
                        <img src="https://fyf.tac-cdn.net/images/products/small/BN91208.jpg?auto=webp&quality=60&width=650" alt="Image 2" >
                        <img src="https://cdn1.1800flowers.com/wcsstore/Flowers/images/catalog/148707xlx.jpg?height=456&width=418&sharpen=a0.5,r1,t1&quality=80&auto=webp&optimize={medium}" alt="Image 1">
                    </div>
                    <hr>
                    <div class="p-review">
                        <div class="review-pic">
                            <img src="https://i.pinimg.com/736x/2e/59/24/2e5924495141cd8a39ad9deca8acad0e.jpg" alt="Customer Profile">
                        </div>
                        <div class="review-info">
                            <div class="review-text">
                                <p class="c-name">N**ya</p>
                                <p class="r-star">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i> 5 stars
                                </p>
                                <p class="r-date">February 27, 2023</p>
                            </div>
                        </div>
                    </div>
                    <div class="r-details">
                        <p class="c-review">I just receive my order this afternoon, the flower was very fresh and arrived in good shape and the flower smells good, the seller is very  accommodating and the delivery is very fast. Thank you seller.</p>
                    </div>
                    <div class="image-preview">
                        <img src="https://cdn1.1800flowers.com/wcsstore/Flowers/images/catalog/148707xlx.jpg?height=456&width=418&sharpen=a0.5,r1,t1&quality=80&auto=webp&optimize={medium}" alt="Image 1">
                        <img src="https://fyf.tac-cdn.net/images/products/small/BN91208.jpg?auto=webp&quality=60&width=650" alt="Image 2" >
                        <img src="https://cdn1.1800flowers.com/wcsstore/Flowers/images/catalog/148707xlx.jpg?height=456&width=418&sharpen=a0.5,r1,t1&quality=80&auto=webp&optimize={medium}" alt="Image 1">
                        <img src="https://fyf.tac-cdn.net/images/products/small/BN91208.jpg?auto=webp&quality=60&width=650" alt="Image 2" >
                        <img src="https://cdn1.1800flowers.com/wcsstore/Flowers/images/catalog/148707xlx.jpg?height=456&width=418&sharpen=a0.5,r1,t1&quality=80&auto=webp&optimize={medium}" alt="Image 1">
                    </div>
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
            document.addEventListener('DOMContentLoaded', function () {
            const carousel = document.getElementById('myCarousel');
            const imageLinks = document.querySelectorAll('.image-grid img');
            const indicators = document.querySelectorAll('.carousel-indicators li');
            
            imageLinks.forEach(function (imageLink, index) {
             imageLink.addEventListener('click', function () {
               const target = this.getAttribute('data-target');
            
               const carouselItems = document.querySelectorAll('.carousel-item');
               carouselItems.forEach(function (item) {
                 item.classList.remove('active');
                 if (item.getAttribute('data-target') === target) {
                   item.classList.add('active');
                 }
               });
            
               // Update the slide indicator
               indicators.forEach(function (indicator, indicatorIndex) {
                 if (index === indicatorIndex) {
                   indicator.classList.add('active');
                 } else {
                   indicator.classList.remove('active');
                 }
               });
            
               // Add border shadow to the clicked image
               imageLinks.forEach(function (img) {
                 img.style.border = 'none';
               });
               this.style.border = '2px solid #65a5a5';
            
               // Update the border when the carousel is navigated
               carousel.addEventListener('slide.bs.carousel', function () {
                 // Remove the border from all images
                 imageLinks.forEach(function (img) {
                   img.style.border = 'none';
                 });
            
                 // Add the border to the active image
                 const activeImage = carousel.querySelector('.active img');
                 activeImage.style.border = '2px solid #65a5a5';
               });
             });
            });
            });
            
        </script> 
        <script>
            // Get the modal element
            const addModal = document.getElementById("addModal");
            
            // Get the button element
            const addOrderBtn = document.getElementById("addOrderBtn");
            
            // Event listener to show the modal when the button is clicked
            addOrderBtn.addEventListener("click", () => {
            addModal.style.display = "block";
            
            // Automatically close the modal after 2 seconds
            setTimeout(() => {
                addModal.style.display = "none";
            }, 1000); // 2000 milliseconds (2 seconds)
            });
            
            // Event listener to close the modal when the user clicks anywhere outside of it
            window.addEventListener("click", (event) => {
            if (event.target === addModal) {
                addModal.style.display = "none";
            }
            });
            
            
        </script>
        <script>
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
            
        </script>
        <script>
            function goBack() {
                window.history.back();
            }
          </script>
            
    </body>
</html>