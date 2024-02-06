<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

include '../php/dbhelper.php';
$pdo = dbconnect();

// Fetch shop details based on shop_id from URL
$shop_id = isset($_GET['shop_id']) ? $_GET['shop_id'] : null;

if ($shop_id) {
    $shop = get_record('shops', 'shop_id', $shop_id);
    $products = get_products_by_shop($shop_id, $pdo);
} else {
    echo "Shop ID not provided";
    exit();
}

function get_products_by_shop($shop_id, $pdo) {
    $sql = "SELECT * FROM products WHERE shop_owner = :shop_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':shop_id', $shop_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_gallery_images_by_shop($shop_id, $pdo) {
    $sql = "SELECT gallery.image
            FROM gallery
            JOIN shop ON gallery.arranger_id = shop.owner_id
            WHERE shop.shop_id = :shop_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':shop_id', $shop_id);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$gallery = get_gallery_images_by_shop($shop_id,$pdo);
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
            <?php if (isset($shop) && is_array($shop)): ?>
                <img src="<?php echo htmlspecialchars(!empty($shop['shop_img']) ? $shop['shop_img'] : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png'); ?>" alt="Seller Image" class="seller-image">
                <div class="seller-details">
                    <div class="seller-name">
                        <i class="bi bi-person" aria-hidden="true"></i><?php echo htmlspecialchars($shop['shop_name']); ?>
                    </div>
                    <div class="seller-contact">
                        <i class="bi bi-geo-alt" aria-hidden="true"></i> <?php echo htmlspecialchars($shop['shop_address']); ?>
                    </div>
                    <div class="seller-contact">
                        <i class="bi bi-telephone" aria-hidden="true"></i> <?php echo htmlspecialchars($shop['shop_phone']); ?>
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
                    <?php foreach ($gallery as $images): ?>
                            <img class="image" src="<?php echo $images['image'];?>" alt="Image 1">
                        <?php endforeach; ?>
                    </div>
                    <div id="addProductContainer">
                        <a href="add_image.php"><button class="add-product">+ Add Image</button></a>
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