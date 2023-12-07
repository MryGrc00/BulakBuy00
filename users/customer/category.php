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

if (isset($_GET['category'])) {
    $selected_category = $_GET['category'];

    // Fetch products based on the selected category
    $conn = dbconnect();
    $query = "SELECT * FROM products WHERE product_category = :category";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':category', $selected_category, PDO::PARAM_STR);
    
    try {
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Handle the case when no category is selected
    echo "No category selected";
    // You might want to redirect or display an appropriate message
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

.product-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    gap: 20px;
    max-width: 1140px;
    margin: 0 auto;
    margin-top: 40px;
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
     
    .product-list {
         display: flex;
         flex-wrap: wrap;
         justify-content: center;
         margin-top:90px;
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
                                    <div id="search-results">Category</div>
                                  </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <main>
            <section>
                
                
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
                                    <div class="product-price"><?php echo '₱ ' . $product['product_price']; ?></div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>


                </section>
            <?php if (empty($products)): ?>
            <p class="p-end" style="color: #bebebe;
                font-size: 15px;
                text-align: center;
                margin-top: 300px;">No results found</p>
        <?php endif; ?>
            <br><br><br>
        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        
        <script>
            function goBack() {
                window.history.back();
            }
          </script>
    </body>
</html>