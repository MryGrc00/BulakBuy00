<?php

session_start();
include '../php/dbhelper.php';
include '../php/checksession.php';

if (isset($_SESSION["user_id"])) {
    $seller_id = $_SESSION["user_id"];
    $products = all_record("products");
}

?>

<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../css/home.css">
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg">
                <!-- Logo -->
                <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="../images/logo.png" alt="BulakBuy Logo" class="img-fluid logo">
                </a>
                <!-- Search Bar -->
                <div class="navbar-collapse justify-content-md-center">
                    <ul class="navbar-nav">
                        <a href="cart.html">
                            <li class="cart">
                                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                <p class="num-label">1 </p>
                            </li>
                        </a>
                        <li class="nav-item">
                            <form class="form-inline my-2 my-lg-0">
                                <a href="search_results.html"><i class="fa fa-search"></i></a>
                                <input type="text"  class="form-control form-input" placeholder="Search">
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <main>
            <div class="home text-center">
            <ul class="icon-list">
                <li>                      
                    <a href="customer_home.php"><i class="fa fa-home" aria-hidden="true"></i></a>
                    <span class="h-label">Home</span>
                </li>
                <li>
                <a href="../users.php"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
                    <p class="number">1</p>
                    <span class="h-label">Messages</span>
                </li>
                <li>
                    <a href="maps.html"><i class="fa fa-map-marker" aria-hidden="true"></i></a>
                    <p class="number">1</p>
                    <span class="h-label">Maps</span>
                </li>
                <li>
                    <div class="dropdown">
                        <button class="dropbtn" id="dropdownBtn">
                            <a href="#">
                                <!-- Link to the notification page -->
                                <i class="fa fa-bell-o" aria-hidden="true"></i>
                            </a>
                            <p class="number">1</p>
                            <span class="h-label">Notifications</span>
                        </button>
                        <div class="dropdown-content">
                            <div class="notification-details">
                                <img src="https://assets.florista.ph/uploads/product-pics/1674804112_5022.png" alt="Product Image">
                                <div class="text-content">
                                    <span class="order-status">Order out for delivery!</span>
                                    <span class="order-description">Your order of a new hahahahhaha iPhone 14 Pro is on its way!</span>
                                    <div class="o-date-time">
                                        <span class="order-date">23 July 2023</span>
                                        <span class="order-time">7:20 AM</span>
                                    </div>
                                </div>
                            </div>
                            <hr class="notif-hr">
                            <div class="notification-details">
                                <img src="https://assets.florista.ph/uploads/product-pics/1674804112_5022.png" alt="Product Image">
                                <div class="text-content">
                                    <span class="order-status">Order out for delivery!</span>
                                    <span class="order-description">Your order of a new hahahahhaha iPhone 14 Pro is on its way!</span>
                                    <div class="o-date-time">
                                        <span class="order-date">23 July 2023</span>
                                        <span class="order-time">7:20 AM</span>
                                    </div>
                                </div>
                            </div>
                            <hr class="notif-hr">
                            <div class="notification-details">
                                <img src="https://assets.florista.ph/uploads/product-pics/1674804112_5022.png" alt="Product Image">
                                <div class="text-content">
                                    <span class="order-status">Order out for delivery!</span>
                                    <span class="order-description">Your order of a new hahahahhaha iPhone 14 Pro is on its way!</span>
                                    <div class="o-date-time">
                                        <span class="order-date">23 July 2023</span>
                                        <span class="order-time">7:20 AM</span>
                                    </div>
                                </div>
                            </div>
                            <hr class="notif-hr">
                            <div class="notification-details">
                                <img src="https://assets.florista.ph/uploads/product-pics/1674804112_5022.png" alt="Product Image">
                                <div class="text-content">
                                    <span class="order-status">Order out for delivery!</span>
                                    <span class="order-description">Your order of a new hahahahhaha iPhone 14 Pro is on its way!</span>
                                    <div class="o-date-time">
                                        <span class="order-date">23 July 2023</span>
                                        <span class="order-time">7:20 AM</span>
                                    </div>
                                </div>
                            </div>
                            <hr class="notif-hr">
                            <div class="notification-details">
                                <img src="https://assets.florista.ph/uploads/product-pics/1674804112_5022.png" alt="Product Image">
                                <div class="text-content">
                                    <span class="order-status">Order out for delivery!</span>
                                    <span class="order-description">Your order of a new hahahahhaha iPhone 14 Pro is on its way!</span>
                                    <div class="o-date-time">
                                        <span class="order-date">23 July 2023</span>
                                        <span class="order-time">7:20 AM</span>
                                    </div>
                                </div>
                            </div>
                            <hr class="notif-hr">
                            <div class="notification-details">
                                <img src="https://assets.florista.ph/uploads/product-pics/1674804112_5022.png" alt="Product Image">
                                <div class="text-content">
                                    <span class="order-status">Order out for delivery!</span>
                                    <span class="order-description">Your order of a new hahahahhaha iPhone 14 Pro is on its way!</span>
                                    <div class="o-date-time">
                                        <span class="order-date">23 July 2023</span>
                                        <span class="order-time">7:20 AM</span>
                                    </div>
                                </div>
                            </div>
                            <hr class="notif-hr">
                            <div class="notification-details">
                                <img src="https://assets.florista.ph/uploads/product-pics/1674804112_5022.png" alt="Product Image">
                                <div class="text-content">
                                    <span class="order-status">Order out for delivery!</span>
                                    <span class="order-description">Your order of a new hahahahhaha iPhone 14 Pro is on its way!</span>
                                    <div class="o-date-time">
                                        <span class="order-date">23 July 2023</span>
                                        <span class="order-time">7:20 AM</span>
                                    </div>
                                </div>
                            </div>
                            <hr class="notif-hr">
                            <div class="notification-details">
                                <img src="https://assets.florista.ph/uploads/product-pics/1674804112_5022.png" alt="Product Image">
                                <div class="text-content">
                                    <span class="order-status">Order out for delivery!</span>
                                    <span class="order-description">Your order of a new hahahahhaha iPhone 14 Pro is on its way!</span>
                                    <div class="o-date-time">
                                        <span class="order-date">23 July 2023</span>
                                        <span class="order-time">7:20 AM</span>
                                    </div>
                                </div>
                            </div>
                            <hr class="notif-hr">
                        </div>
                </li>
                <li>
                <a href="customer_profile.php"><i class="fa fa-user-o" aria-hidden="true"></i></a>
                <span class="h-label">Account</span>
                </li>
            </ul>
            </div>
            <section>
                <div class="container">
                    <div id="myCarousel" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                            <li data-target="#myCarousel" data-slide-to="1"></li>
                            <li data-target="#myCarousel" data-slide-to="2"></li>
                        </ol>
                        <!-- Slides -->
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="https://img.freepik.com/free-photo/pink-roses-gifts-boxes-wooden-background_23-2148268275.jpg?w=2000" alt="Image 1">
                            </div>
                            <div class="carousel-item">
                                <img src="https://img.freepik.com/free-photo/pink-roses-gifts-boxes-wooden-background_23-2148268275.jpg?w=2000" alt="Image 2">
                            </div>
                            <div class="carousel-item">
                                <img src="https://img.freepik.com/free-photo/pink-roses-gifts-boxes-wooden-background_23-2148268275.jpg?w=2000" alt="Image 3">
                            </div>
                        </div>
                        <!-- Left and right controls -->
                        <a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        </a>
                        <a class="carousel-control-next" href="#myCarousel" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        </a>
                    </div>
                </div>
            </section>
            <section>
                <div class="cat-label">
                    <p>Categories</p>
                </div>
                <div class="category-list">
                    <div class="category">
                        <a href="category.html" ><img src="https://assets.florista.ph/uploads/product-pics/1674804112_5022.png" alt="Category 1"></a>
                        <p>Bouquets</p>
                    </div>
                    <div class="category">
                        <a href="category.html" ><img src="https://pbs.twimg.com/media/D2eAHTFVYAESsQ0.jpg" alt="Category 2"></a>
                        <p>Flower Bundles</p>
                    </div>
                    <div class="category">
                        <a href="category.html" ><img src="https://img2.chinadaily.com.cn/images/202112/17/61bc1548a310cdd3d82174b3.jpeg" alt="Category 3"></a>
                        <p>Flower Arranger</p>
                    </div>
                    <div class="category">
                        <a href="category.html" ><img src="https://www.soapcraftz.com/wp-content/uploads/2021/11/candle-additives.webp" alt="Category 1"></a>
                        <p>Candles</p>
                    </div>
                    <div class="category">
                        <a href="category.html" ><img src="https://5.imimg.com/data5/SELLER/Default/2023/5/311880205/FY/ED/MT/181342412/torch-ginger-red-500x500.jpg" alt="Category 2"></a>
                        <p>Tropical Flowers</p>
                    </div>
                    <div class="category">
                        <a href="category.html" ><img src="https://flowermoxiesupply.com/cdn/shop/products/50373055808_8a22415eb2_c.jpg?v=1626454724" alt="Category 3"></a>
                        <p>Arrangement Materials</p>
                    </div>
                    <div class="category">
                        <a href="category.html" ><img src="https://lzd-img-global.slatic.net/g/p/bd2d4e33299f8da2f68553b14d145865.jpg_720x720q80.jpg" alt="Category 1"></a>
                        <p>Flower Stands</p>
                    </div>
                    <div class="category">
                        <a href="category.html" ><img src="https://casajuan.ph/cdn/shop/products/anahawnapkinring.jpg?v=1626910031" alt="Category 3"></a>
                        <p>Leaves</p>
                    </div>
                </div>
            </section>
            <section>
                <div class="label">
                    <p>Seasonal Discovery</p>
                    <a href="seasonal_discovery.html" class="all">See all<i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                </div>
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
                                <div class="product-price"><?php echo $product['product_price']; ?></div>
                                <div class="product-ratings">4.5 stars</div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
                   

                </div>
            </section>
            <p class="p-end">No more products found</p>
            <br><br><br>
        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>
            // JavaScript to handle responsive behavior
            const dropdownContent = document.querySelector(".dropdown-content");
            const notificationIcon = document.querySelector(".dropbtn a");
            const dropdownBtn = document.getElementById("dropdownBtn");
            
            // Check the screen width
            function checkScreenWidth() {
                if (window.innerWidth <= 768) { // Adjust the breakpoint as needed
                    dropdownContent.style.display = "none"; // Hide dropdown on small screens
                    dropdownBtn.addEventListener("click", redirectToNotification);
                } else {
                    dropdownContent.style.display = "none"; // Hide dropdown on larger screens
                    dropdownBtn.removeEventListener("click", redirectToNotification);
                }
            }
            
            // Redirect to the notification page
            function redirectToNotification(event) {
                event.preventDefault();
                window.location.href = "../common/notification.html";
            }
            
            // Toggle dropdown when the notification icon is clicked
            dropdownBtn.addEventListener("click", function() {
                if (dropdownContent.style.display === "none" || dropdownContent.style.display === "") {
                    dropdownContent.style.display = "block";
                } else {
                    dropdownContent.style.display = "none";
                }
            });
            
            // Initial check
            checkScreenWidth();
            
            // Listen for window resize
            window.addEventListener("resize", checkScreenWidth);
        </script>
    </body>
</html>