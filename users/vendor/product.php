<?php
include('../util/dbhelper.php');

// Fetch products from the database
$products = all_record('product');

// Function to format price with commas for thousands
function formatPrice($price) {
    // Format the price with commas for thousands
    return '₱ ' . number_format($price);
}


?>






<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Products</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="../../css/vendor_myproducts.css">
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
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <form class="form-inline my-2 my-lg-0">
                                <a href="javascript:void(0);" onclick="goBack()">
                                    <i class="back fa fa-angle-left" aria-hidden="true"></i>
                                    <div id="search-results">My Products</div>
                                  </a>
                                <a href=""><i class="fa fa-search"></i></a>
                                <input type="text"  class="form-control form-input" placeholder="Search">
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
            <hr class="nav-hr">
        </header>
        <main>
        <a href="add_product.php"><button class="add-product"> + Add Product</button></a>
        <div class="product-list" id="product-container">
            <?php foreach ($products as $product) : ?>
                <div class="product">
                    <a href="">
                        <?php
                        // Extract the first image URL from the comma-separated string
                        $imageUrls = explode(',', $product['product_img']);
                        $firstImageUrl = trim($imageUrls[0]); // Get the first image URL

                        // Display the first image
                        echo '<img src="' . $firstImageUrl . '" alt="' . $product['product_name'] . '">';
                        ?>
                        <div class="product-name"><?php echo $product['product_name']; ?></div>
                        <div class="product-category"><?php echo $product['product_category']; ?></div>
                        <div class="p">
                    
                            <div class="product-price"><?php echo formatPrice($product['product_price']); ?></div>
                            <div class="product-actions">
                                <a href="edit_product.php?product_id=<?php echo $product['product_id']; ?>">
                                    <button class="edit-button"><i class="bi bi-pen"></i></button>
                                </a> 
                                <a href="delete_product.php?product_id=<?php echo $product['product_id'];?>">                               
                                    <button class="delete-button"><i class="bi bi-trash"></i></button> </div>
                                </a>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>

            <?php if (empty($products)) : ?>
                <p class="p-end">No more products found</p><br><br><br>
            <?php endif; ?>
        </div>
    </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>
            function goBack() {
                window.history.back();
            }

            $(document).ready(function() {
            $('.delete-button').click(function() {
                var productId = $(this).data('productid');

                // Confirm with the user before deleting
                if (confirm("Are you sure you want to delete this product?")) {
                    $.ajax({
                        url: 'delete_product.php', // Change this to the PHP script handling the delete operation
                        type: 'POST',
                        data: { product_id: productId },
                        success: function(response) {
                            // Refresh the page or update the product list as needed
                            location.reload();
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
            });
        });



            




            
          </script>
    </body>
</html>