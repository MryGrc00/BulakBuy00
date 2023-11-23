<?php
session_start();
if (isset($_SESSION["user_id"]) && ($_SESSION["role"] === "seller" || $_SESSION["role"] === "arranger")) {
    $user_id = $_SESSION["user_id"];
    
    include '../php/dbhelper.php'; // Make sure this path is correct
    $pdo = dbconnect();

    // Function to get shop ID for a user
    function getShopId($user_id, $pdo) {
        $query = "SELECT shop_id FROM shops WHERE owner_id = :user_id LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['shop_id'] : null;
    }

    // Function to check subscription status
    function isUserSubscribed($shop_id, $pdo) {
        $query = "SELECT status FROM subscription WHERE shop_id = :shop_id LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':shop_id', $shop_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result && $result['status'] === 'active';
    }

    // Function to count user's products
    function countUserProducts($shop_id, $pdo) {
        $query = "SELECT COUNT(*) FROM products WHERE shop_owner = :shop_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':shop_id', $shop_id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    $shop_id = getShopId($user_id, $pdo);
    if ($shop_id) {
        $isSubscribed = isUserSubscribed($shop_id, $pdo);
        $productCount = countUserProducts($shop_id, $pdo);
        $canAddMoreProducts = $isSubscribed || $productCount < 10;

        // Fetch products from the database
        $products = get_products_by_user($user_id, $pdo); // Ensure this function uses shop_id for fetching products
    } else {
        // Handle case where shop ID is not found
        // For example, display an error or redirect
    }

} else {
    header('Location: ../login.php');
    exit();
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
                <img src="../php/images/logo.png" alt="BulakBuy Logo" class="img-fluid logo">
                </a>
                <!-- Search Bar -->
                <div class="navbar-collapse justify-content-md-center">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <form class="form-inline my-2 my-lg-0">
                                <a href="vendor_home.php">
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
        <button class="add-product" onclick="<?php echo $canAddMoreProducts ? 'window.location.href=\'add_product.php\'' : 'openModal()'; ?>">+ Add Product</button>
        <!-- Modal for Premium Subscription -->
        <div id="premiumModal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <p>You have reached your limit of 10 products. Upgrade to a premium subscription to add more products.</p>
                <button onclick="window.location.href='../../Payments/index.php'">Go Premium</button>
            </div>
        </div>

        <div class="product-list" id="product-container">
             <?php foreach ($products as $product) : ?>
                <div class="product">
                    <a href="">
                    <?php
                        echo '<img src="' . $product['product_img'] . '" alt="' . $product['product_name'] . '">';
                    ?>
                        <div class="product-name"><?php echo $product['product_name']; ?></div>
                        <div class="product-category"><?php echo $product['product_category']; ?></div>
                        <div class="p">
                    
                            <div class="product-price"><?php echo formatPrice($product['product_price']); ?></div>
                            <div class="product-actions">
                            <a href="edit_product.php?product_id=<?php echo $product['product_id']; ?>">
                                    <button class="edit-button"><i class="bi bi-pen"></i></button>
                                </a> 
                                
                                <button class="delete-button" onclick="openModal(<?php echo $product['product_id']; ?>)"><i class="bi bi-trash"></i></button>
                             </div>
                        </div>
                     </a>
                      </div>

                      <?php endforeach; ?>

                    <?php if (empty($products)) : ?>
                        <p class="p-end">No more products found</p><br><br><br>
                    <?php endif; ?>
        </div>

        <!--modal for delete--->
        <div id="deleteModal" class="modal" style="display: none;">
            <div class="modal-content">
                <p>Are you sure you want to delete this product?</p>
                <form method="POST" action="delete.php">
                    <input type="hidden" name="product_id" id="modalProductId">
                    <button type="submit" name="confirm_delete">Yes, Delete it</button>
                    <button type="button" onclick="closeModal()">Cancel</button>
                </form>
            </div>
        </div>
        
    </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <script src="js/chat.js"></script>
        <script>
        // JavaScript functions
        function openModal(productId) {
            document.getElementById('modalProductId').value = productId;
            document.getElementById('deleteModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>
        <script>
            function goBack() {
                window.history.back();
            }
          </script>

          
        <script>
            function openModal() {
                document.getElementById('premiumModal').style.display = 'block';
            }

            function closeModal() {
                document.getElementById('premiumModal').style.display = 'none';
            }

            // Close the modal if the user clicks outside of it
            window.onclick = function(event) {
                var modal = document.getElementById('premiumModal');
                if (event.target == modal) {
                    closeModal();
                }
            }
        </script>




            




            
    </body>
</html>