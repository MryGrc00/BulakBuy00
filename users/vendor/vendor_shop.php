<?php
session_start();
include '../php/dbhelper.php'; // Ensure this path is correct


$user_id = $_SESSION["user_id"] ?? null; // Make sure to get the user_id from session

// Fetching shop details
$users = get_record('shops', 'owner_id', $user_id) ?? null;

// Fetching product details
$products = get_products_by_seller($user_id) ?? [];
?>



<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Vendor Profile</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/vendor_profile.css">
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
                        <li class="nav-item">
                            <form class="form-inline my-2 my-lg-0">
                                <a href=""><i class="fa fa-search"></i></a>
                                <input type="text"  class="form-control form-input" placeholder="Search">
                                <a href="vendor_home.php"><i class="back fa fa-angle-left" aria-hidden="true"></i></a>
                                <div id="search-results">Shop</div>
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
            <?php foreach ($products as $product) : ?>
                <div class="product-list" id="product-container">
                    <div class="product">
                        <a href="">
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
                </div>
                
                <?php endforeach; ?>

                <?php if (empty($products)) : ?>
                    <p class="p-end">No products found</p><br><br><br>
                <?php endif; ?>
                </div>
            </section>
            <br><br><br>
            <!-- Modal -->

        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        

        <!---for the blurry background -->
    </body>
</html>