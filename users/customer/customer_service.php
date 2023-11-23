<?php
session_start();
include '../php/dbhelper.php';
include '../php/checksession.php';

if (isset($_GET['service_id']) && isset($_SESSION['user_id'])) {
    $serviceID = $_GET['service_id'];
    $userID = $_SESSION['user_id'];

    // Keep this line as per your requirement
    $services = get_services('services', 'users');

    // Connect to the database
    $pdo = dbconnect();

    // Retrieve service details
    $stmt = $pdo->prepare("SELECT * FROM services WHERE service_id = :serviceID");
    $stmt->execute(['serviceID' => $serviceID]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($service) {
        // Retrieve shop details based on arranger_id in the service record
        $stmt = $pdo->prepare("SELECT * FROM shops WHERE owner_id = :arrangerID");
        $stmt->execute(['arrangerID' => $service['arranger_id']]);
        $shop = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($shop) {
            // Retrieve user details (owner of the shop)
            $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :ownerID");
            $stmt->execute(['ownerID' => $shop['owner_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $user['user_id'] == $userID) {
                // Service, Shop, and User exist, and the shop belongs to the logged-in user
            } else {
            }
        } else {
            echo "Shop not found";
        }
    } else {
        echo "Service not found";
    }
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/service.css">
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
                <div class="column1">
                    <div class="image-container">
                        <div id="myCarousel" class="carousel slide" >
                    <?php if (isset($user) && isset($service)): ?>
                            <div class="carousel-inner">
                                <div class="carousel-item active" data-target="image1">
                                <?php
                                    echo '<img src="' . $user['profile_img'] . '" alt="' . $user['last_name'] . '">';
                                ?>                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column2">
                         <!-- Service and Shop Owner Details -->
                        <p class="p-name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
                        <p class="p-price">₱ <?php echo htmlspecialchars($service['service_rate']); ?> / hr</p>
                        <p class="p-ratings">4.5 ratings & 35 reviews</p>
                        <!-- Buttons and Actions -->
                        <div class="btn-container">
                            <div class="add-btn">
                                <a href="service_request.php?service_id=<?php echo htmlspecialchars($service['service_id']); ?>">
                                    <button class="add"><i class="bi bi-person-plus"></i>&nbsp;&nbsp;&nbsp;Add Request</button>
                                </a>
                                <a href="../chat.php?user_id=<?php echo htmlspecialchars($user['user_id']); ?>">
                                    <button class="messenger"><i class="bi bi-messenger"></i></button>
                                </a>
                            </div>
                        </div>
                        <hr class="nav-hr">
                        <p class="p-desc-label">Description</p>
                        <p class="p-desc"><?php echo htmlspecialchars($service['service_description']); ?></p>   
                    <?php endif; ?>

                    <div class="shop">
                        <div class="shop-pic">
                            <img src="<?php echo $shop['shop_img']; ?>" alt="Shop Profile">
                        </div>
                        <div class="shop-info">
                            <div class="info">
                                <p class="s-name"><?php echo $shop['shop_name']; ?></p>
                                <p class="s-location"><i class="bi bi-geo-alt"></i> <?php echo $shop['shop_address']; ?></p>
                                <a href="arranger_shop.php?shop_id=<?php echo $shop['shop_id']; ?>">
                                    <button class="view-shop"><i class="bi bi-shop-window"></i>View Shop</button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="reviews">
                        <p class="r-label">Service Ratings</p>
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
                    <hr>
                    <a href="see_all_reviews.html" class="all">
                        <p>See All Reviews</p>
                    </a>
                </div>
            </div>
            <section>
                <div class="label">
                    <p class="other">Other Services</p>
                </div>
                <div class="service-list" id="service-container">
                    <div class="service">
                        <a href="service.html">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0V0dWxknmztw6-3Kea1Cr6s1qNe-MdqJ-5k7k99JKt04adfSN5iGni2uYZ1jLqwjRR5c&usqp=CAU" alt="Product 1">
                            <div class="service-name">Service 1</div>
                            <div class="service-category">Flower Arranger</div>
                            <div class="p">
                                <div class="service-price">₱ 350 / hr</div>
                                <div class="service-ratings">4.5 stars</div>
                            </div>
                        </a>
                    </div>
                    <div class="service">
                        <a href="service.html">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0V0dWxknmztw6-3Kea1Cr6s1qNe-MdqJ-5k7k99JKt04adfSN5iGni2uYZ1jLqwjRR5c&usqp=CAU" alt="Product 1">
                            <div class="service-name">Service 1</div>
                            <div class="service-category">Flower Arranger</div>
                            <div class="p">
                                <div class="service-price">₱ 350 / hr</div>
                                <div class="service-ratings">4.5 stars</div>
                            </div>
                        </a>
                    </div>
                    <div class="service">
                        <a href="service.html">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0V0dWxknmztw6-3Kea1Cr6s1qNe-MdqJ-5k7k99JKt04adfSN5iGni2uYZ1jLqwjRR5c&usqp=CAU" alt="Product 1">
                            <div class="service-name">Service 1</div>
                            <div class="service-category">Flower Arranger</div>
                            <div class="p">
                                <div class="service-price">₱ 350 / hr</div>
                                <div class="service-ratings">4.5 stars</div>
                            </div>
                        </a>
                    </div>
                    <div class="service">
                        <a href="service.html">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0V0dWxknmztw6-3Kea1Cr6s1qNe-MdqJ-5k7k99JKt04adfSN5iGni2uYZ1jLqwjRR5c&usqp=CAU" alt="Product 1">
                            <div class="service-name">Service 1</div>
                            <div class="service-category">Flower Arranger</div>
                            <div class="p">
                                <div class="service-price">₱ 350 / hr</div>
                                <div class="service-ratings">4.5 stars</div>
                            </div>
                        </a>
                    </div>
                    <div class="service">
                        <a href="service.html">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0V0dWxknmztw6-3Kea1Cr6s1qNe-MdqJ-5k7k99JKt04adfSN5iGni2uYZ1jLqwjRR5c&usqp=CAU" alt="Product 1">
                            <div class="service-name">Service 1</div>
                            <div class="service-category">Flower Arranger</div>
                            <div class="p">
                                <div class="service-price">₱ 350 / hr</div>
                                <div class="service-ratings">4.5 stars</div>
                            </div>
                        </a>
                    </div>
                    <div class="service">
                        <a href="service.html">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0V0dWxknmztw6-3Kea1Cr6s1qNe-MdqJ-5k7k99JKt04adfSN5iGni2uYZ1jLqwjRR5c&usqp=CAU" alt="Product 1">
                            <div class="service-name">Service 1</div>
                            <div class="service-category">Flower Arranger</div>
                            <div class="p">
                                <div class="service-price">₱ 350 / hr</div>
                                <div class="service-ratings">4.5 stars</div>
                            </div>
                        </a>
                    </div>
                    <div class="service">
                        <a href="service.html">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0V0dWxknmztw6-3Kea1Cr6s1qNe-MdqJ-5k7k99JKt04adfSN5iGni2uYZ1jLqwjRR5c&usqp=CAU" alt="Product 1">
                            <div class="service-name">Service 1</div>
                            <div class="service-category">Flower Arranger</div>
                            <div class="p">
                                <div class="service-price">₱ 350 / hr</div>
                                <div class="service-ratings">4.5 stars</div>
                            </div>
                        </a>
                    </div>
                    <div class="service">
                        <a href="service.html">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0V0dWxknmztw6-3Kea1Cr6s1qNe-MdqJ-5k7k99JKt04adfSN5iGni2uYZ1jLqwjRR5c&usqp=CAU" alt="Product 1">
                            <div class="service-name">Service 1</div>
                            <div class="service-category">Flower Arranger</div>
                            <div class="p">
                                <div class="service-price">₱ 350 / hr</div>
                                <div class="service-ratings">4.5 stars</div>
                            </div>
                        </a>
                    </div>
                    <div class="service">
                        <a href="service.html">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0V0dWxknmztw6-3Kea1Cr6s1qNe-MdqJ-5k7k99JKt04adfSN5iGni2uYZ1jLqwjRR5c&usqp=CAU" alt="Product 1">
                            <div class="service-name">Service 1</div>
                            <div class="service-category">Flower Arranger</div>
                            <div class="p">
                                <div class="service-price">₱ 350 / hr</div>
                                <div class="service-ratings">4.5 stars</div>
                            </div>
                        </a>
                    </div>
                    <div class="service">
                        <a href="service.html">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0V0dWxknmztw6-3Kea1Cr6s1qNe-MdqJ-5k7k99JKt04adfSN5iGni2uYZ1jLqwjRR5c&usqp=CAU" alt="Product 1">
                            <div class="service-name">Service 1</div>
                            <div class="service-category">Flower Arranger</div>
                            <div class="p">
                                <div class="service-price">₱ 350 / hr</div>
                                <div class="service-ratings">4.5 stars</div>
                            </div>
                        </a>
                    </div>
                    <div class="service">
                        <a href="service.html">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0V0dWxknmztw6-3Kea1Cr6s1qNe-MdqJ-5k7k99JKt04adfSN5iGni2uYZ1jLqwjRR5c&usqp=CAU" alt="Product 1">
                            <div class="service-name">Service 1</div>
                            <div class="service-category">Flower Arranger</div>
                            <div class="p">
                                <div class="service-price">₱ 350 / hr</div>
                                <div class="service-ratings">4.5 stars</div>
                            </div>
                        </a>
                    </div>
                    <div class="service">
                        <a href="service.html">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0V0dWxknmztw6-3Kea1Cr6s1qNe-MdqJ-5k7k99JKt04adfSN5iGni2uYZ1jLqwjRR5c&usqp=CAU" alt="Product 1">
                            <div class="service-name">Service 1</div>
                            <div class="service-category">Flower Arranger</div>
                            <div class="p">
                                <div class="service-price">₱ 350 / hr</div>
                                <div class="service-ratings">4.5 stars</div>
                            </div>
                        </a>
                    </div>
                </div>
            </section>
            <p class="p-end">No more services found</p>
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
            function goBack() {
                window.history.back();
            }
          </script>
    </body>
</html>