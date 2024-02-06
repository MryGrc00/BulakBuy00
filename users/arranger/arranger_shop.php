<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php"); // Adjust the path as needed
    exit(); // It's important to exit here
}

include '../php/dbhelper.php'; // Ensure this path is correct
$pdo = dbconnect();

$user_id = $_SESSION["user_id"]; // This is now safe to use

// Fetching shop details
$users = get_record('shops', 'owner_id', $user_id);

// Fetching product details
$products = get_products_by_user($user_id, $pdo);



function get_seller_images($user_id) {
    // Establish a database connection
    $conn = dbconnect();

    // Updated SQL query to fetch order details including sales_id
    $sql = "SELECT gallery.image
            FROM gallery
            INNER JOIN services ON gallery.service_id = services.service_id
            WHERE services.arranger_id = ?";

    try {
        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);

        // Fetch and return the orders
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $images;
    } catch (PDOException $e) {
        // Handle any exceptions (log the error and return false)
        error_log("Database query error: " . $e->getMessage()); // Log the error
        return false; // Return false indicating failure
    } finally {
        // Close the database connection
        $conn = null;
    }
}

$gallery = get_seller_images($user_id);

?>



<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Arranger Shop</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
 * {
     margin: 0;
     padding: 0;
     box-sizing: border-box;
     font-family: "Poppins", sans-serif;
}
 .navbar img {
     padding: 0;
     width: 195px;
     height: 100px;
     margin-top: -10px;
     margin-left: 186%;
}
 .form {
     position: relative;
     color: #8e8e8e;
     left: 130px;
}
 .form-inline .fa-search {
     position: absolute;
     top: 43px;
     left: 78%;
     color: #9ca3af;
     font-size: 22px;
}
 .form-input[type="text"] {
     height: 50px;
     width: 500px;
     background-color: #f0f0f0;
     border-radius: 10px;
     margin-left: 430px;
     margin-top: -10px;
}
 .cart {
     font-size: 30px;
     padding: 0;
     color: #65a5a5;
     margin-top: -5px;
     left: 50.5%;
     transform: translateX(-50%);
     position: absolute;
}
 .num-cart {
     background-color: #ff7e95;
     border-radius: 50px;
     padding: 2px;
     margin: auto;
     width: 20px;
     height: 20px;
     font-size: 10px;
     position: absolute;
     top: 5px;
     margin-left: 20.5%;
     transform: translateX(50%);
     color: white;
     text-align: center;
}
 .nav-hr{
     width:60%;
     margin: auto;
     margin-top:-6px;
}
 #search-results{
     display:none;
}
 .back{
     display: none;
}
 .seller-info {
     background-color: #f0f0f0;
     padding: 20px;
     display: flex;
     border-radius: 20px;
     flex-direction: column;
     align-items: center;
     justify-content: center;
     text-align: center;
     margin:auto;
     margin-top: 0px;
     width:60%;
     background-color: #84BEBE;
     color: white;
}
 .seller-image {
     width: 100px;
     height: 100px;
     border-radius: 50%;
     margin-bottom: 10px;
     margin-top: 10px;
}
 .seller-name {
     font-size: 17px;
     margin-bottom: 5px;
     font-weight: 300;
}
 .seller-contact {
     font-size: 17px;
     margin-bottom: 10px;
     font-weight: 300;
}
 .bi{
     margin-right: 10px;
     font-size: 20px;
     font-weight: 300;
     color: white;
}
 .button-container {
     display: flex;
     justify-content: center;
     margin-top:30px;
     align-items: center;
}
 .button-container .active{
     background-color: #65a5a5;
     color: white;
     border: none;
     outline: none;
}
#addProductContainer{
    text-align: center;
}
.add-product{
    background-color: #65A5A5;
    color: white;
    padding:10px 20px;
    font-size: 15px;
    border:none;
    border-radius: 10px;
    margin-top:30px;
    
}
.add-product:focus{
    outline:none;
    border:none;
}
 .gallery-btn{
     margin-right: 20%;
     border:none;
     padding:10px 50px;
     border:1px solid #65a5a5;
     background-color: transparent;
     border-radius: 10px;
}
 .product-btn{
     border:none;
     padding:10px 50px;
     border:1px solid #65a5a5;
     background-color: transparent;
     color: #666;
     border-radius: 10px;
}
 .gallery-btn:focus{
     border:none;
     outline:none;
}
 .product-btn:focus{
     border:none;
     outline:none;
}
 .product-list {
     display: flex;
     flex-wrap: wrap;
     justify-content: flex-start;
     gap: 20px;
     max-width: 1140px;
     margin: 0 auto;
     margin-top: 60px;
}
 .product {
     flex: calc(16.666% - 20px);
     margin-top:-20px;
     margin-bottom: 20px;
     padding: 10px;
     border: 1px solid #ccc;
     border-radius: 10px;
     display: flex;
     flex-direction: column;
     text-align: left;
     box-sizing: border-box;
     max-width: 172px;
}
 .product a:hover {
     text-decoration: none;
}
 .product:hover {
     transform: scale(1.05);
     box-shadow: 0px 0px 6px #65A5A5;
}
 .product a img {
     max-width: 150px;
     height:160px;
     flex-grow: 1;
}
 .product .product-info {
     padding: 10px;
}
 .product .product-name {
     font-weight: 500;
     margin-top: 20px;
     margin-bottom: 5px;
     font-size: 15px;
     color: #666;
}
 .product .product-category {
     color: #666;
     margin-bottom: 5px;
     font-size: 12px;
}
 .product .product-price {
     color: #666;
     margin-bottom: 5px;
     font-size: 15px;
}
 .product .product-ratings {
     color: #acaaaa;
     font-size: 11px;
     margin-top: 3px;
     margin-left: auto;
}
 .product .p {
     display: flex;
}
 .p-end {
     color: #bebebe;
     font-size: 14px;
     text-align: center;
     margin-top: 30px;
}
 .image-grid {
     display: flex;
     flex-wrap: wrap;
     justify-content: flex-start;
     gap: 4px;
     max-width: 1140px;
     margin: 0 auto;
     margin-top: 60px;
     margin-top: 60px;
}
 .image {
     margin: 2px 2px;
     cursor: pointer;
     width:182px;
     height:150px;
     border-radius: 10px;
}
 .modal {
     display: none;
     position: fixed;
     top: 0;
     left: 0;
     margin: auto;
     width: 80%;
     height: 80%;
     justify-content: center;
     align-items: center;
     z-index: 1;
     left: 50%;
     top: 50%;
     transform: translate(-50%, -50%);
     transition: background-color 0.3s ease;
}
 .modal-overlay {
     display: none;
     position: fixed;
     top: 0;
     left: 0;
     width: 100%;
     height: 100%;
     background-color: rgba(0, 0, 0, 0.5);
     z-index: 1;
}
 .modal-content {
     max-width: 80%;
     max-height: 80%;
     margin: auto;
     opacity: 1;
     box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
}
 .modal-content img {
     width: 100%;
     height: auto;
     display: block;
}
 .close {
     position: absolute;
     right: 20px;
     top: 15px;
     font-size: 30px;
     cursor: pointer;
}

 .gallery-btn.active + .image-grid, .product-btn.active + .product-list {
     display: flex;
}
 @media (min-width: 320px) and (max-width: 768px) {
     .navbar{
         position: fixed;
         background-color: white;
         width:100%;
         z-index: 10;
         top:-1px;
    }
     .navbar img {
         display: none;
    }
     .form-input[type="text"] {
         display: none;
    }
     .nav-hr{
         width:100%;
    }
     #search-results{
         display: block ;
         font-size: 15px;
         margin-left: 20px;
         color: #555;
         margin-top: -20px;
    }
    
    a:hover{
        text-decoration: none;
        outline: none;
        border:none;
    }
     .back{
         display: block;
         font-size: 20px;
    }
     .cart {
         font-size: 20px;
         padding: 0;
         position: absolute;
         top: 25px;
         left: 90%;
         transform: translateX(-50%);
         color: #65a5a5;
    }
     .num-cart {
         background-color: #ff7e95;
         border-radius: 50px;
         padding: 2px;
         margin: auto;
         width: 17px;
         height: 17px;
         font-size: 9px;
         font-weight: bold;
         position: absolute;
         top: 2px;
         margin-left: 10%;
         transform: translateX(50%);
         color: white;
         text-align: center;
    }
     .form-inline .fa-search {
         display: none;
    }
     .form-inline .back{
         text-decoration: none;
         color:#666;
    }
     .form-inline .fa-angle-left:focus {
         text-decoration: none;
         outline: none;
    }
     .seller-info {
         background-color: #f0f0f0;
         padding: 20px;
         display: flex;
         border-radius: 20px;
         flex-direction: row;
         align-items: center;
         justify-content: center;
         text-align: center;
         margin: auto;
         margin-top: 85px;
         width: 93%;
         background-color: #84BEBE;
         color: white;
    }
     .seller-image {
         width: 55px;
         height: 55px;
         border-radius: 50%;
         margin-right: 20px;
         margin-left: -45px;

    }
     .seller-details {
         display: flex;
         flex-direction: column;
         align-items: flex-start;
         font-size: 13px;
         font-weight: 300;
    }
     .seller-name {
         margin-bottom: 5px;
         font-size: 13px;
    }
     .seller-contact {
         margin-bottom: 10px;
         font-size: 13px;
    }
     .bi{
         margin-right: 10px;
         font-size: 15px;
         font-weight: 300;
         color: white;
    }
     .button-container {
         display: flex;
         justify-content: center;
         margin-top:30px;
         align-items: center;
    }
     .button-container .active{
         background-color: #65a5a5;
         color: white;
         border: none;
         outline: none;
    }
     .gallery-btn{
         margin-right: 15%;
         border:none;
         font-size: 13px;
         padding:5px 30px;
         border:1px solid #65a5a5;
         background-color: transparent;
         border-radius: 10px;
    }
     .product-btn{
         border:none;
         padding:5px 30px;
         border:1px solid #65a5a5;
         background-color: transparent;
         color: #666;
         border-radius: 10px;
         font-size: 13px;
    }

    #addProductContainer{
        text-align: center;
    }
    .add-product{
        background-color: #65A5A5;
        color: white;
        padding:8px 15px;
        font-size: 12px;
        border:none;
        border-radius: 8px;
        margin-top:40px;
      
    }
    .add-product:focus{
        outline:none;
        border:none;
    }
    .product-list {
         display: flex;
         flex-wrap: wrap;
         justify-content: center;
    }
     .product {
         flex: 0 0 calc(2%);
         padding: 10px;
         border: 1px solid #ddd;
  
         display: flex;
         flex-direction: column;
         text-align: left;
         box-sizing: border-box;
    }
     .product img a{
         width: 193px;
         height:150px;
         flex-grow: 1;
    }

     .product .product-name {
        font-size: 13px;
        color: #666;
        font-weight: 400;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    
    }
     .product .product-category {
         color: #666;
         margin-bottom: 5px;
         font-size: 12px;
    }
     .product .product-price {
         color: #666;
         margin-top: 5px;
         font-size: 13px;
    }
     .product .p {
         display: flex;
    }
     .p-end {
         color: #bebebe;
         font-size: 12px;
         text-align: center;
         margin-top: 30px;
    }
 
     .image-grid {
         display: flex;
         flex-wrap: wrap;
         justify-content: flex-start;
         margin: 0px auto;
         margin-top: 3%;
         margin-left: 1%;
         padding:0px 10px;
    }
    .image {
         margin-top: 30px;
         margin-bottom: -20px;
         cursor: pointer;
         width:108px;
         height:125px;
         border-radius: 11px;
    }
     .modal {
         display: none;
         position: fixed;
         top: 0;
         left: 0;
         margin: auto;
         width: 80%;
         height: 80%;
         justify-content: center;
         align-items: center;
         z-index: 100;
         left: 50%;
         top: 75%;
         transform: translate(-50%, -50%);
         transition: background-color 0.3s ease;
    }
     .modal-overlay {
         display: none;
         position: fixed;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         background-color: rgba(0, 0, 0, 0.5);
         z-index: 100;
    }
     .modal-content {
         max-width: 80%;
         max-height: 80%;
         margin: auto;
         opacity: 1;
         box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    }
     .modal-content img {
         width: 100%;
         height: auto;
         display: block;
    }
     .close {
         position: absolute;
         right: 15px;
         top: 10px;
         font-size: 20px;
         cursor: pointer;
    }
     .product-list {
         display: none;
    }
     .gallery-btn.active + .image-grid, .product-btn.active + .product-list {
         display: block;
    }
    /* Media query to adjust alignment for smaller screens */
    @media (max-width: 768px) {
        .product {
            width: calc(50% - 15px);
           /* Two items in a row */
       }
        .product-list{
            gap: 10px;
       }
   }
}


 
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
                                <a href="arranger_home.php">
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