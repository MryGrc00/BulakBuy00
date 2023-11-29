<?php

session_start();
include '../php/dbhelper.php';
$pdo = dbconnect();
if (isset($_SESSION["user_id"]) && isset($_SESSION["role"])) {
    $user_id = $_SESSION["user_id"];
    $role = $_SESSION["role"];

    $users = get_record_by_user($user_id) ;

    $service_order =  get_service_details_intransit('servicedetails','services', 'users', $user_id);
}
else {
    // Handle cases where the user is not logged in or role is not set
    echo "User not logged in or role not set.";
    // Optional: Redirect to login page or show a login link
}
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In Transit</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/arranger.css">

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
                            <div id="search-results">In Transit</div>
                            </a>
                            
                        </form>
                    </li>     
                </ul>
            </div>
        </nav><hr class="nav-hr">
    </header>

  <div class="wrapper">
    <div class="button-container">
        <button class="products-btn active">Products</button>
        <button class="services-button">Services</button>
    </div>
    <div class="products-card" id="productsCard">
        <div class="single-card ">
            <div class="img-area">
                <img src="https://assets.florista.ph/uploads/product-pics/5022_86_5022.webp" alt="">
            </div>
            <div class="info">
                <div class="text-left">
                    <h3>Winter Green</h3>
                    <div class="flower-type">
                        <p class="flower">Flower:</p>
                        <p class="type">Anthurium , White Rose, Assorted Mums, Rose </p>
                    </div>
                    <div class="ribbon-color">
                        <p class="ribbon">Ribbon:</p>
                        <p class="color"> Violet </p>
                    </div>
                    <p class="price">₱ 1000.00</p>
                </div>
                <div class="text-right">
                    <p class="count">x 20</p>
                    <button class="done">Done</button>
                </div>
            </div>
        </div>
        <div class="single-card ">
            <div class="img-area">
                <img src="https://assets.florista.ph/uploads/product-pics/5022_86_5022.webp" alt="">
            </div>
            <div class="info">
                <div class="text-left">
                    <h3>Winter Green</h3>
                    <div class="flower-type">
                        <p class="flower">Flower:</p>
                        <p class="type">Anthurium , White Rose, Assorted Mums, Rose </p>
                    </div>
                    <div class="ribbon-color">
                        <p class="ribbon">Ribbon:</p>
                        <p class="color"> Violet </p>
                    </div>
                    <p class="price">₱ 1000.00</p>
                </div>
                <div class="text-right">
                    <p class="count">x 20</p>
                    <button class="done">Done</button>
                </div>
            </div>
        </div>
        <div class="single-card ">
            <div class="img-area">
                <img src="https://assets.florista.ph/uploads/product-pics/5022_86_5022.webp" alt="">
            </div>
            <div class="info">
                <div class="text-left">
                    <h3>Winter Green</h3>
                    <div class="flower-type">
                        <p class="flower">Flower:</p>
                        <p class="type">Anthurium , White Rose, Assorted Mums, Rose </p>
                    </div>
                    <div class="ribbon-color">
                        <p class="ribbon">Ribbon:</p>
                        <p class="color"> Violet </p>
                    </div>
                    <p class="price">₱ 1000.00</p>
                </div>
                <div class="text-right">
                    <p class="count">x 20</p>
                    <button class="done">Done</button>
                </div>
            </div>
        </div>
    </div>
    <div class="service-list" id="service-container">
 <?php foreach ($service_order as $order):?>
        <div class="single-card ">
            <div class="img-area">
                 <img src="<?php echo $order["customer_profile"]?>" alt="">
            </div>
            <div class="info">
                <div class="text-left">
                <h3><?php echo $order["customer_first_name"]. " " . $order["customer_last_name"]; ?></h3>
                    <p class="ad"><?php echo $order["customer_address"]?></p>
                    <div class="o-date-time">
                        <span class="date"><?php echo $order["date"]?></span>
                        <span class="time"><?php echo $order["time"]?></span>
                    </div>
                    <p class="price"><?php echo $order["amount"]?></p>
                </div>
                <div class="text-right">
                <form method="post" action="update_service_status.php">
                        <input type="hidden" name="service_detail_id" value="<?php echo $order['servicedetails_id']; ?>">
                        <button type="submit" name="action" value="completed" class="service-done">Done</button>
                </form>
                </div>
            </div>
        </div>
        <?php endforeach;?>
        
    </div>
  </div>
  <script>
        // Get the products and services elements
        const products = document.querySelector('.products-card');
        const services = document.querySelector('.service-list');

        // Get the products and product buttons
        const productsBtn = document.querySelector('.products-btn');
        const productBtn = document.querySelector('.services-button');

        // Function to set the active page in localStorage
        function setActivePage(page) {
            localStorage.setItem('activePage', page);
        }products

        // Function to get the active page from localStorage
        function getActivePage() {
            return localStorage.getItem('activePage');
        }

        // Function to handle button clicks
        function handleButtonClick(page) {
            setActivePage(page);

            if (page === 'products') {
                // Show the products and hide the services
                products.style.display = 'block';
                services.style.display = 'none';

                // Make the products button active and deactivate the product button
                productsBtn.classList.add('active');
                productBtn.classList.remove('active');
            } else if (page === 'services') {
                // Show the services and hide the products
                products.style.display = 'none';
                services.style.display = 'block';

                // Make the product button active and deactivate the products button
                productBtn.classList.add('active');
                productsBtn.classList.remove('active');
            }
        }

        // Check if there's an active page in localStorage and set it
        const activePage = getActivePage();
        if (activePage === 'services') {
            handleButtonClick('services');
        } else {
            handleButtonClick('products'); // Default to products if no active page is found
        }

        // Add click event listeners to the products and services buttons
        productsBtn.addEventListener('click', () => handleButtonClick('products'));
        productBtn.addEventListener('click', () => handleButtonClick('services'));
    </script>
<script>
    function goBack() {
        window.history.back();
    }
  </script>
    
</body>
</html>