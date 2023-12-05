<?php

session_start();
include '../php/dbhelper.php';
include '../php/checksession.php';

if (isset($_SESSION["user_id"])) {
    $seller_id = $_SESSION["user_id"];

    // Initialize variables
    $min_price_input = $max_price_input = null;

    // Check if the form is submitted and the variables are set
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['min_price'])) {
            $min_price_input = $_POST['min_price'];
        }
        if (isset($_POST['max_price'])) {
            $max_price_input = $_POST['max_price'];
        }

        // Fetch products within the inputted price range
        if ($min_price_input !== null && $max_price_input !== null) {
            $products = filter_products_by_price($min_price_input, $max_price_input);
        } else {
            $products = get_latest_products_by_id('products','shops','subscription');
        }
    } else {
        $products = get_latest_products_by_id('products','shops','subscription');
    }

    list($min_price, $max_price) = get_price_range_products();
}

function filter_products_by_price($min, $max) {
    $conn = dbconnect();
    $query = "SELECT * FROM products WHERE product_price BETWEEN :min AND :max";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':min', $min, PDO::PARAM_INT);
    $stmt->bindParam(':max', $max, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Products</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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

.upper{
    display: flex;
    margin-top: 15px;
}
.history{
    color:#bebebe;
    margin-left:20%;
}
 .filter{
    margin-top: -5px;
     margin-left:49%;
     font-size: 25px;
     color: #8e8e8e;
}
 .filter {
     cursor: pointer;
}
 .close-modal {
     background-color:transparent;
     border: none;
     cursor: pointer;
     border-radius: 5px;
     margin-top: 12px;
     margin-left:88%;
     font-size: 20px;
     color: #bebebe;
     transform: translateX(-50%);
     position: absolute;
}
 .close-modal:hover {
     background-color: transparent;
     color: #ccc;
}
 .close-modal:focus{
     outline: none;
}
 .modal-overlay {
     display: none;
     position: fixed;
     top: 0;
     left: 0;
     width: 100%;
     height: 100%;
     background-color: rgba(0, 0, 0, 0.3);
     z-index: 1;
     opacity: 0;
     transition: opacity 0.3s ease;
}
 .modal-overlay-active {
     display: block;
     opacity: 1;
}
 .filter-modal {
     display: none;
     position: fixed;
     top: 33%;
     left: 50%;
     transform: translateX(-50%);
     width: 25%;
     height: auto;
     justify-content: center;
     align-items: center;
     z-index: 2;
     transition: bottom 0.5s ease-in-out, opacity 0.5s ease-in-out;
}
 .filter-modal-content {
     background-color: #fff;
     padding: 25px;
     border-radius: 10px;
     box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
}
 .p-filter{
    color:#666;
    font-size: 17px;
    text-align: center;
    letter-spacing: 0.1rem;
    margin-top: 15px;
}
 .f-label{
    color:#666;
    font-size: 15px;
    letter-spacing: 0.1rem;
    margin-top: 3px;
}
.price {
    display: inline-block;
    
    
}
 .price input{
     text-align: center;
     border-radius: 10px;
     color: #666;
}
 .m-price{
     border: 1px solid #65A5A5;
     padding:5px;
     width:46%;
     
}
 .m-price:focus {
     border: 2px solid #65A5A5;
     outline: none;
}
 .m-price + .m-price {
     margin-left: 25px;
    /* Adjust the value as needed */
}
 .m-price::placeholder{
     text-align: center;
     font-size: 14px;
     color:#bebebe;
}
 
 .f-apply {
     display: flex;
     justify-content: center;
     align-items: center;
     margin: auto;
     text-align: center;
     margin-top: 10px;
}
 .apply {
     background-color: #65a5a5;
     color: white;
     border: none;
     padding: 12px;
     width: 450px;
     border-radius: 10px;
     margin-top: 30px;
     margin-bottom: 20px;
     font-size"15px;
}
 .apply:focus {
     border: none;
     outline: none;
}
.product-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    gap: 20px;
    max-width: 1140px;
    margin: 0 auto;
    margin-top: 20px;
}

.product {
    flex: 0 0 calc(4%);
    margin-top: -20px;
    margin-bottom: 20px;
    padding: 10px;
    border: 1px solid #ddd;
    display: flex;
    flex-direction: column;
    text-align: left;
    box-sizing: border-box;
}

.product a:hover {
    text-decoration: none;
}

.product:hover {
    transform: scale(1.05);
}

.product a img {
    width: 150px;
    height: 150px;
    flex-grow: 1;
}

.product .product-info {
    padding: 10px;
}

.product .product-name {
    margin-top: 20px;
    width: 150px;
    margin-bottom: 5px;
    font-size: 15px;
    color: #666;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

 .product .product-category {
     color: #666;
     margin-bottom: 5px;
     font-size: 13px;
}
 .product .product-price {
     color: #666;
     margin-bottom: 5px;
     font-size: 13px;
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
/*Responsiveness*/
 @media (max-width: 768px) {
    .navbar{
         position: fixed;
         background-color: white;
         width:100%;
         z-index: 100;
         top:0;
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
     a #search-results{
         display: block ;
         font-size: 15px;
         margin-left: 20px;
        margin-top: -20px;
        width: 220px;
        font-size: 15px;
        color: #666;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
         top: 24px;
         left: 80%;
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
     .filter {
         margin-top: 20px;
         margin-left: 93%;
         font-size: 20px;
         position: fixed;
         color: #8e8e8e;
         transform: translateX(-50%);
         z-index: 200;
         top: 0;
       
    }
    .close-modal {
         background-color:transparent;
         border: none;
         cursor: pointer;
         border-radius: 5px;
         margin-top: 0px;
         margin-left:87%;
         font-size: 18px;
         color: #bebebe;
         transform: translateX(-50%);
         position: absolute;
    }
     .close-modal:hover {
         background-color: transparent;
         color:#666;
    }
     .close-modal:focus{
         outline: none;
    }
     .modal-overlay {
         display: none;
         position: fixed;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         background-color: rgba(0, 0, 0, 0.2);
         z-index: 100;
         opacity: 0;
         transition: opacity 0.3s ease;
        
    }
     .modal-overlay-active {
         display: block;
         opacity: 1;
        
    }
     .filter-modal {
         display: none;
         position: fixed;
         top: 30%;
         width: 80%;
         left:50%;
         right:0;
         height: auto;
         justify-content: center;
         align-items: center;
         z-index: 200;
         transition: bottom 100.5s ease-in-out, opacity 100.5s ease-in-out;
    }
     .filter-modal-content {
         background-color: #fff;
         padding: 15px;
         border-top: 10px;
         border-radius: 10px;
      
    }
    .p-filter{
        color:#666;
        font-size: 14px;
        letter-spacing: 0.1rem;
        margin-top: 3px;
    }
    .f-label{
        color:#666;
        font-size: 13px;
        letter-spacing: 0.1rem;
        margin-top: 3;
    }
    .price {
        display: inline-block;
    }
    .price input{
        text-align: center;
        border-radius: 10px;
        color: #666;
    }
    .m-price{
        border: 1px solid #65A5A5;
        padding:5px;
        width:44%;
        font-size: 13px;
        
    }
     .m-price:focus {
         border: 2px solid #65A5A5;
         outline: none;
    }
     .m-price + .m-price {
         margin-left: 25px;
        
    }
     .m-price::placeholder{
         text-align: center;
         font-size: 12px;
    }
    
     .f-apply {
         display: flex;
         justify-content: center;
         align-items: center;
         margin: auto;
         text-align: center;
    }
     .apply {
         background-color: #65a5a5;
         color: white;
         border: none;
         padding: 7px;
         width: 330px;
         border-radius: 10px;
         margin-top: 30px;
         margin-bottom: 10px;
         font-size:13px;
    }
     .apply:focus {
         border: none;
         outline: none;
    }
    .product-list {
         display: flex;
         flex-wrap: wrap;
         justify-content: center;
         margin-top:35px;
         margin-bottom:10px;
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
         margin-bottom: 5px;
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
    /* Media query to adjust alignment for smaller screens */
     @media (max-width: 768px) {
         .product {
             width: calc(50% - 15px);
            /* Two items in a row */
        }
         .product-list{
             gap: 10px;
        }
         .btn-container .btn:nth-child(n+4) {
             display: none;
            /* Hide buttons starting from the 4th button */
        }
         .btn-container .dropdown {
             display: inline-block;
            /* Display the dropdown button */
        }
    }
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
                                    <div id="search-results">Products</div>
                                  </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <main>
            <section>
                <div class="filter-modal" id="filterModal">
                    <div class="filter-modal-content">
                        <button class="close-modal" id="closeModal"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
                        <p class="p-filter">Filters</p>
                        <hr class="f-hr">
                        <form action="allproducts.php" method="post">
                        <div class="price">
                            <p class="f-label">Price</p>
                            <input type="text" name="min_price" class="m-price" placeholder="Min: <?php echo $min_price; ?>">
                            <input type="text" name="max_price" class="m-price" placeholder="Max: <?php echo $max_price; ?>">
                        </div>
                        <div class="f-apply">
                            <button class="apply" type="submit">Apply</button>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="upper">
                    <p class="history">Home > All Products</p>
                    <div class="modal-overlay" id="modalOverlay"></div>
                    <div class="filter" id="openModal">
                        <i class="fa fa-sliders" aria-hidden="true"></i>
                    </div>
                </div>
                </section>
                <section>
                    <div class="product-list" id="product-container">
                    <?php foreach ($products as $product): ?>
                        <div class="product">
                            <a href="customer_product.php?product_id=<?php echo $product['product_id']; ?>">
                            <?php
                            echo '<img src="' . $product['product_img'] . '" alt="' . $product['product_name'] . '">';
                        ?>                        
                            <div class="product-name"><?php echo $product['product_name']; ?></div>
                                <div class="product-category"><?php echo $product['product_category']; ?></div>
                                <div class="p">
                                    <div class="product-price"><?php echo '₱ '.$product['product_price']; ?></div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                    

                    </div>
                </section>
            <br><br><br>
        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            const filterModal = document.getElementById('filterModal');
            const openModalButton = document.getElementById('openModal');
            const closeModalButton = document.getElementById('closeModal');
            const modalOverlay = document.getElementById('modalOverlay');
            
            // Function to open the filter modal
            function openModal() {
              filterModal.style.display = 'block';
              modalOverlay.classList.add('modal-overlay-active'); // Add the class to show the overlay
            
              // Add the "active" class to slide up the modal
              filterModal.classList.add('active');
            }
            
            // Function to close the filter modal
            function closeModal() {
              filterModal.style.display = 'none';
              modalOverlay.classList.remove('modal-overlay-active'); // Remove the class to hide the overlay
            
              // Remove the "active" class to slide down the modal
              filterModal.classList.remove('active');
            }
            
            // Event listeners to open and close the modal
            openModalButton.addEventListener('click', openModal);
            closeModalButton.addEventListener('click', closeModal);
            
        </script>    
        <script>
            function goBack() {
                window.history.back();
            }
          </script>
    </body>
</html>