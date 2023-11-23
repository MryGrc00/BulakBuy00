<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php"); // Adjust the path as needed
    exit(); // It's important to exit here
}

include '../php/dbhelper.php'; // Ensure this path is correct
$pdo = dbconnect();

$user_id = $_SESSION["user_id"]; // This is now safe to use

// Fetching shop details
$users = get_record('shops', 'owner_id', $user_id);

// Fetching product details
$products = get_products_by_user($user_id, $pdo);

?>



<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Arranger Profile</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/arranger_profile.css">
        <style>
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
                                    <div id="search-results">Shop</div>
                                  </a>
                                  
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <main class="main">
            <div class="seller-info">
            <?php if (isset($users) && is_array($users)): ?>
                <img src="<?php echo htmlspecialchars(!empty($users['shop_img']) ? $users['shop_img'] : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png'); ?>" alt="Seller Image" class="seller-image">
                <div class="seller-details">
                    <div class="seller-name">
                        <i class="bi bi-person" aria-hidden="true"></i><?php echo htmlspecialchars($users['shop_name']); ?><a href="edit_shop.php"><i class="bi bi-pencil-square" style="margin-left: 10px;"></i></a>
                    </div>
                    <div class="seller-contact">
                        <i class="bi bi-geo-alt" aria-hidden="true"></i> <?php echo htmlspecialchars($users['shop_address']); ?>
                    </div>
                    <div class="seller-contact">
                        <i class="bi bi-telephone" aria-hidden="true"></i> <?php echo htmlspecialchars($users['shop_phone']); ?>
                    </div>
                <?php endif; ?>
                    </div>
                </div>
            <section>
                <div class="button-container">
                    <button class="gallery-btn active">Gallery</button>
                    <button class="product-btn">Products</button>
                </div>
                <div class="image-grid" id="imageGrid">
                    <!-- Add your images here -->
                    <img class="image" src="https://quinceflowers.com/cdn/shop/products/3-Bridal-Bouquet-Petite-Package-Quince-Flowers-Toronto-Wedding-Florist_1445x.jpg?v=1655055264" alt="Image 1">
                    <img class="image" src="https://i.etsystatic.com/18889321/r/il/3a0845/3490460883/il_fullxfull.3490460883_pek8.jpg" alt="Image 2">
                    <img class="image" src="https://quinceflowers.com/cdn/shop/products/3-Bridal-Bouquet-Petite-Package-Quince-Flowers-Toronto-Wedding-Florist_1445x.jpg?v=1655055264" alt="Image 3">
                    <img class="image" src="https://quinceflowers.com/cdn/shop/products/3-Bridal-Bouquet-Petite-Package-Quince-Flowers-Toronto-Wedding-Florist_1445x.jpg?v=1655055264" alt="Image 4">
                    <img class="image" src="https://i.etsystatic.com/18889321/r/il/3a0845/3490460883/il_fullxfull.3490460883_pek8.jpg" alt="Image 5">
                    <img class="image" src="https://quinceflowers.com/cdn/shop/products/3-Bridal-Bouquet-Petite-Package-Quince-Flowers-Toronto-Wedding-Florist_1445x.jpg?v=1655055264" alt="Image 6">
                    <img class="image" src="https://quinceflowers.com/cdn/shop/products/3-Bridal-Bouquet-Petite-Package-Quince-Flowers-Toronto-Wedding-Florist_1445x.jpg?v=1655055264" alt="Image 7">
                    <img class="image" src="https://quinceflowers.com/cdn/shop/products/3-Bridal-Bouquet-Petite-Package-Quince-Flowers-Toronto-Wedding-Florist_1445x.jpg?v=1655055264" alt="Image 8">
                    <img class="image" src="https://quinceflowers.com/cdn/shop/products/3-Bridal-Bouquet-Petite-Package-Quince-Flowers-Toronto-Wedding-Florist_1445x.jpg?v=1655055264" alt="Image 9">
                    <img class="image" src="https://i.etsystatic.com/18889321/r/il/3a0845/3490460883/il_fullxfull.3490460883_pek8.jpg" alt="Image 10">
                    <img class="image" src="https://quinceflowers.com/cdn/shop/products/3-Bridal-Bouquet-Petite-Package-Quince-Flowers-Toronto-Wedding-Florist_1445x.jpg?v=1655055264" alt="Image 11">
                    <img class="image" src="https://i.etsystatic.com/18889321/r/il/3a0845/3490460883/il_fullxfull.3490460883_pek8.jpg" alt="Image 12">
                    <!-- Add more images as needed -->
                </div>
                <div id="addProductContainer">
                    <a href="add_image.html"><button class="add-product">+ Add Image</button></a>
                </div>
          
                <!-- The Modal Overlay -->
                <div class="modal-overlay" id="modalOverlay"></div>
                <!-- The Modal -->
                <div class="modal" id="myModal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <img id="modalImage" src="" alt="Modal Image">
                    </div>
                </div>
            </section>
            <section>
                <div class="product-list" id="product-container">
                <?php foreach ($products as $product) : ?>
                    <div class="product">
                    <a href="customer_product.php?product_id=<?php echo $product['product_id']; ?>">
                  <?php
                            echo '<img src="' . $product['product_img'] . '" alt="' . $product['product_name'] . '">';
                        ?>
                            <div class="product-name"><?php echo $product['product_name']; ?></div>
                            <div class="product-category"><?php echo $product['product_category']; ?></div>
                            <div class="p">
                                <div class="product-price"><?php echo formatPrice($product['product_price']); ?></div>
                                <div class="product-ratings">4.5 stars</div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
                </div>
                <?php if (empty($products)) : ?>
                    <p class="p-end">No products found</p><br><br><br>
                <?php endif; ?>

            </section>
            
            <br><br><br>
        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            // Function to open the modal with the clicked image
            function openModal(imageSrc) {
            var modal = document.getElementById("myModal");
            var modalImage = document.getElementById("modalImage");
            var modalOverlay = document.getElementById("modalOverlay");
            
            // Set the image source in the modal
            modalImage.src = imageSrc;
            
            // Display the modal and overlay
            modal.style.display = "block";
            modalOverlay.style.display = "block";
            }
            
            // Function to close the modal
            function closeModal() {
            var modal = document.getElementById("myModal");
            var modalOverlay = document.getElementById("modalOverlay");
            
            // Hide the modal and overlay
            modal.style.display = "none";
            modalOverlay.style.display = "none";
            }
            
            // Get all images with class "image"
            var images = document.querySelectorAll(".image");
            
            // Attach click event listeners to each image
            images.forEach(function (image) {
            image.addEventListener("click", function () {
            openModal(this.src); // Pass the image source to the openModal function
            });
            });
            
            // Close the modal when the modal content or overlay is clicked
            var modalContent = document.querySelector(".modal-content");
            var modalOverlay = document.querySelector(".modal-overlay");
            
            modalContent.addEventListener("click", function (event) {
            if (event.target === this) {
            closeModal();
            }
            });
            
            modalOverlay.addEventListener("click", closeModal);
            
        </script>
        <script>
            // Get the gallery and products elements
            const gallery = document.querySelector('.image-grid');
            const products = document.querySelector('.product-list');
            // Get the gallery and product buttons
            const galleryBtn = document.querySelector('.gallery-btn');
            const productBtn = document.querySelector('.product-btn');
            
            // Function to set the active page in localStorage
            function setActivePage(page) {
                localStorage.setItem('activePage', page);
            }
            
            // Function to get the active page from localStorage
            function getActivePage() {
                return localStorage.getItem('activePage');
            }
            
            // Function to handle button clicks
            function handleButtonClick(page) {
                setActivePage(page);

                if (page === 'gallery') {
                    // Show the gallery and hide the products
                    gallery.style.display = 'flex';
                    products.style.display = 'none';

                    // Make the gallery button active and deactivate the product button
                    galleryBtn.classList.add('active');
                    productBtn.classList.remove('active');

                    // Show the "Add Product" button on the Gallery page
                    document.getElementById('addProductContainer').style.display = 'block';
                } else if (page === 'products') {
                    // Show the products and hide the gallery
                    gallery.style.display = 'none';
                    products.style.display = 'flex';

                    // Make the product button active and deactivate the gallery button
                    productBtn.classList.add('active');
                    galleryBtn.classList.remove('active');

                    // Hide the "Add Product" button on the Products page
                    document.getElementById('addProductContainer').style.display = 'none';
                }
            }

            // Check if there's an active page in localStorage and set it
            const activePage = getActivePage();
            if (activePage === 'products') {
                handleButtonClick('products');
            } else {
                handleButtonClick('gallery'); // Default to gallery if no active page is found
            }

            
            // Add click event listeners to the gallery and products buttons
            galleryBtn.addEventListener('click', () => handleButtonClick('gallery'));
            productBtn.addEventListener('click', () => handleButtonClick('products'));
        </script>
        <script>
            function goBack() {
                window.history.back();
            }
          </script>
    </body>
</html>