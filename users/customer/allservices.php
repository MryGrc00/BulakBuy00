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
            $services = filter_products_by_price($min_price_input, $max_price_input);
        } else {
            $services = get_latest_services('services','users','shops','subscription');
        }
    } else {
        $services = get_latest_services('services','users','shops','subscription');

    }

    list($min_price, $max_price) = get_rate_range_service();
}

function filter_service_by_price($min, $max) {
    $conn = dbconnect();
    $query = "SELECT * FROM services WHERE service_rate BETWEEN :min AND :max";
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
        <link rel="stylesheet" href="../../css/home_see_all.css">
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
                                    <div id="search-results">Services</div>
                                  </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
            <hr class="nav-hr">
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
                            <h4 class="f-label">Price</h4>
                            <input type="text" name="min_price" class="m-price" placeholder="Min: <?php echo $min_price; ?>">
                            <input type="text" name="max_price" class="m-price" placeholder="Max: <?php echo $max_price; ?>">
                        </div>
                       
                        <div class="relevance">
                            <h4 class="f-label">Relevance</h4>
                            <button class="r-btn">Best Match</button>
                            <button class="r-btn">Lowest Price</button>
                            <button class="r-btn">Newest</button>
                            <button class="r-btn">Popular</button>
                            <button class="r-btn">Recommended</button>
                        </div>
                        <div class="ratings">
                            <h4 class="f-label">Ratings</h4>
                            <button class="ratings-btn">5 <i class="fa fa-star" aria-hidden="true"></i></button>
                            <button class="ratings-btn">4 <i class="fa fa-star" aria-hidden="true"></i></button>
                            <button class="ratings-btn">3 <i class="fa fa-star" aria-hidden="true"></i></button>
                            <button class="ratings-btn">2 <i class="fa fa-star" aria-hidden="true"></i></button>
                            <button class="ratings-btn">1 <i class="fa fa-star" aria-hidden="true"></i></button>
                        </div>
                        <div class="f-apply">
                            <button class="apply" type="submit">Apply</button>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="modal-overlay" id="modalOverlay"></div>
                <div class="filter" id="openModal">
                    <i class="fa fa-sliders" aria-hidden="true"></i>
                </div>
                </section>
                <section>
                <div class="product-list" id="product-container">
                <?php foreach ($services as $service): ?>
                    <div class="product">
                        <a href="customer_product.php?service_id=<?php echo $service['service_id']; ?>">
                        <?php
                        echo '<img src="' . $service['profile_img'] . '" alt="' . $service['last_name'] . '">';
                    ?>                        
                            <div class="product-name"><?php echo $service['first_name'] . ' ' . $service['last_name']; ?></div>
                            <div class="p">
                                <div class="product-price"><?php echo $service['service_rate']; ?></div>
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