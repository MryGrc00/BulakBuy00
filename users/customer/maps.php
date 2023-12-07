
<?php
session_start();
include '../php/dbhelper.php';
include '../php/checksession.php';

if (isset($_SESSION["user_id"])) {
    $seller_id = $_SESSION["user_id"];
    $products = get_latest_products_by_id('products','shops','subscription');
    $services = get_latest_services('services','users','shops','subscription');

}
?>
<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Maps</title>
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
.nav-item {
    list-style-type: none; /* This will remove the bullet point */
}

.nav-item form button {
    background: none; 
    border: none; 
    cursor: pointer;
    padding: 0; 
    font-size: inherit; 
    color: inherit; 
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
.num-label{
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
.home{
     display: none;
}
.number {
     background-color: #ff7e95;
     border-radius: 50px;
     width: 15px;
     height: 15px;
     font-size: 10px;
     margin-top: -10px;
     margin-left: 30px;
     position: absolute;
     top: 5px;
     left: 30%;
     transform: translateX(-50%);
}

.product-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    gap: 20px;
    max-width: 1140px;
    margin: 0 auto;
    margin-top: 40px;
   

}
.service-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    gap: 20px;
    max-width: 1140px;
    margin: 0 auto;
    margin-top: 20px;
   
    margin-bottom: 90px;
    

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

/*Responsiveness*/
 @media (max-width: 768px) {
      .navbar{
         position: fixed;
         background-color: white;
         width:100%;
         z-index: 100;
         top:0;
     }
     .history{
        display:none;
     }
     .navbar img {
         display: none;
    }
     .navbar{
         position: fixed;
         background-color: white;
         width:100%;
         z-index: 1;
         top:0;
    }
    .num-label{
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
     .form {
         text-align: left;
         
    }
     .form-input[type="text"] {
         height: 45px;
         width: 100%;
         background-color: #f0f0f0;
         border-radius: 10px;
         margin: 0;
         margin-top: 25px;
    }
     .form-inline .fa-search {
         font-size: 20px;
         margin-top: 43px;
         margin-left: 40px;
    }
     ::placeholder {
         font-size: 14px;
    }
     a #search-results{
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
         color:#666;
    }
     .nav-hr{
         display: none;
    }
     .cart {
         font-size: 20px;
         padding: 0;
         position: absolute;
         top: 24px;
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
         display: block;
    }
     .form-inline .back{
         text-decoration: none;
         color:#666;
    }
     .form-inline .fa-angle-left:focus {
         text-decoration: none;
         outline: none;
    }
    .home {
         background-color: white;
         width: 105%;
         margin: auto;
         height: 70px;
         top: 90%;
         position: fixed;
         z-index: 100;
         border-radius: 0px;
         display:block;
    }
    .number{
      color:white;
      border-radius: 50px;
      width: 15px;
      height: 15px;
      font-size: 10px;
      margin-top: -10px;
      margin-left: 30px;
      position: absolute;
      top: 23px;
      left:33.5%;
      transform: translateX(-50%);
    }
     .icon-list {
         list-style: none;
         padding: 0;
         display: flex;
         justify-content: center;
         margin-top: 18px;
         margin-left: -18px;
        
         color: #65a5a5;
         
       
    }
     .icon-list li a {
         text-align: center;
         color: #65a5a5;
         margin-top: 10px;
    }
     .icon-list i {
         font-size: 20px;
         
         margin: 0px 37px;
    }
     .h-label {
         font-size: 10px;
         display: block;
         color: color: #65a5a5;
    }
    .product-list {
         display: flex;
         flex-wrap: wrap;
         justify-content: center;
         margin-top:170px;
    }
    .service-list {
         display: flex;
         flex-wrap: wrap;
         justify-content: center;
         margin-top:20px;
         margin-bottom:90px;
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
                    <ul class="navbar-nav">
                        <a href="cart.php">
                            <li class="cart">
                                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                <p class="num-label">1 </p>
                            </li>
                        </a>
                        <li class="nav-item">
                        <li class="nav-item">
                           <form class="form-inline my-2 my-lg-0" method="GET" action="maps.php">
                              <a href="customer_home.php"><i class="back fa fa-angle-left" aria-hidden="true"></i>
                                    <div id="search-results">Maps</div>
                              </a>
                              <button ><i class="fa fa-search"></i></button>
                              <input type="text" name="search" id="search-input" class="form-control form-input" placeholder="Search">
                           
                           </form>
                        </li>

                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <main>
        

      <section>
      <?php
        $conn = dbconnect(); // Establish the database connection
        $hasResults = false;

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['search'])) {
                // Case 1: Search query is present for shops and products
                $search_query = $_GET['search'];

                // Perform a search query in the shop table
                $sql_shops = "SELECT * FROM shops WHERE shop_address LIKE :search";
                $stmt_shops = $conn->prepare($sql_shops);
                $stmt_shops->bindValue(':search', '%' . $search_query . '%', PDO::PARAM_STR);
                $stmt_shops->execute();
                $shops = $stmt_shops->fetchAll(PDO::FETCH_ASSOC);

                if ($shops) {
                    // If there are matching shops, fetch corresponding products from the product table
                    $product_container = '<div class="product-list" id="product-container">';
                    foreach ($shops as $shop) {
                        $shop_id = $shop['shop_id'];

                        $sql_products = "SELECT * FROM products WHERE shop_owner = :shop_owner";
                        $stmt_products = $conn->prepare($sql_products);
                        $stmt_products->bindValue(':shop_owner', $shop_id, PDO::PARAM_INT);
                        $stmt_products->execute();
                        $products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($products as $product) {
                            $hasResults = true;
                            $product_container .= '<div class="product">';
                            $product_container .= '<a href="product.html">';
                            $product_container .= '<img src="' . $product['product_img'] . '" alt="' . $product['product_name'] . '">';
                            $product_container .= '<div class="product-name">' . $product['product_name'] . '</div>';
                            $product_container .= '<div class="product-category">' . $product['product_category'] . '</div>';
                            $product_container .= '<div class="p">';
                            $product_container .= '<div class="product-price">₱ ' . number_format($product['product_price'], 2) . '</div>';
                            // Add code to display product ratings
                            $product_container .= '</div>';
                            $product_container .= '</a>';
                            $product_container .= '</div>';
                        }
                    }
                    $product_container .= '</div>';
                    echo $product_container;
                }

                // Perform a search query in the services table
                $sql_services = "SELECT s.*, u.first_name, u.last_name, u.profile_img 
                FROM services AS s
                JOIN users AS u ON s.arranger_id = u.user_id
                WHERE (u.address LIKE :search) AND s.status = 'enabled'
                ORDER BY s.service_id DESC";
                $stmt_services = $conn->prepare($sql_services);
                $stmt_services->bindValue(':search', '%' . $search_query . '%', PDO::PARAM_STR);
                $stmt_services->execute();
                $services = $stmt_services->fetchAll(PDO::FETCH_ASSOC);

                if ($services) {
                    // If there are matching services, display them
                    $service_container = '<div class="service-list" id="service-container">';
                    foreach ($services as $service) {
                        $hasResults = true;
                        $service_container .= '<div class="product">';
                            $service_container .= '<a href="customer_service.php?service_id=' . $service['service_id'] . '">';
                            $service_container .= '<img src="' . $service['profile_img'] . '" alt="' . $service['last_name'] . '">';
                            $service_container .= '<div class="product-name">' . $service['first_name'] . ' ' . $service['last_name'] . '</div>';
                            $service_container .= '<div class="product-category">Service Category</div>';
                            $service_container .= '<div class="p">';
                            $service_container .= '<div class="product-price">Service Rate: ₱ ' . number_format($service['service_rate'], 2) . '</div>';
                            // Add code to display service details
                            $service_container .= '</div>';
                            $service_container .= '</a>';
                            $service_container .= '</div>';
                    }
                    $service_container .= '</div>';
                    echo $service_container;
                }
            } else {
                // Case 2: No search query, display all products and services
                $sql_all_products = "SELECT * FROM products";
                $stmt_all_products = $conn->prepare($sql_all_products);
                $stmt_all_products->execute();
                $all_products = $stmt_all_products->fetchAll(PDO::FETCH_ASSOC);

                if ($all_products) {
                    $hasResults = true;
                    $product_container = '<div class="product-list" id="product-container">';
                    foreach ($all_products as $product) {
                        $product_container .= '<div class="product">';
                        $product_container .= '<a href="customer_product.php?product_id=' . $product['product_id'] . '">';
                        $product_container .= '<img src="' . $product['product_img'] . '" alt="' . $product['product_name'] . '">';
                        $product_container .= '<div class="product-name">' . $product['product_name'] . '</div>';
                        $product_container .= '<div class="product-category">' . $product['product_category'] . '</div>';
                        $product_container .= '<div class="p">';
                        $product_container .= '<div class="product-price">₱ ' . number_format($product['product_price'], 2) . '</div>';
                        $product_container .= '</div>';
                        $product_container .= '</a>';
                        $product_container .= '</div>';
                    }
                    $product_container .= '</div>';
                    echo $product_container;
                }

                // Display all services
                $sql_all_services = "SELECT s.*, u.first_name, u.last_name, u.profile_img 
                            FROM services AS s
                            JOIN users AS u ON s.arranger_id = u.user_id
                            WHERE s.status = 'enabled'
                            ORDER BY s.service_id DESC";
                $stmt_all_services = $conn->prepare($sql_all_services);
                $stmt_all_services->execute();
                $all_services = $stmt_all_services->fetchAll(PDO::FETCH_ASSOC);


                if ($all_services) {
                    $hasResults = true;
                    $service_container = '<div class="service-list" id="service-container">';
                    foreach ($all_services as $service) {
                        $service_container .= '<div class="product">';
                            $service_container .= '<a href="customer_service.php?service_id=' . $service['service_id'] . '">';
                            $service_container .= '<img src="' . $service['profile_img'] . '" alt="' . $service['last_name'] . '">';
                            $service_container .= '<div class="product-name">' . $service['first_name'] . ' ' . $service['last_name'] . '</div>';
                            $service_container .= '<div class="product-category">Service Category</div>';
                            $service_container .= '<div class="p">';
                            $service_container .= '<div class="product-price">Service Rate: ₱ ' . number_format($service['service_rate'], 2) . '</div>';
                            // Add code to display service details
                            $service_container .= '</div>';
                            $service_container .= '</a>';
                            $service_container .= '</div>';
                    }
                    $service_container .= '</div>';
                    echo $service_container;
                }
            }
        }

$conn = null; // Close the database connection
?>

<?php if (!$hasResults) : ?>
    <p class="p-end" style="color: #bebebe;
        font-size: 15px;
        text-align: center;
        margin-top: 400px;">No results found</p>
<?php endif; ?>
</section>




        
            
        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
       
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