<?php
session_start();
if (isset($_SESSION["user_id"]) && ($_SESSION["role"] === "seller" || $_SESSION["role"] === "arranger")) {
    $user_id = $_SESSION["user_id"];
    
    include '../php/dbhelper.php'; // Make sure this path is correct
    $pdo = dbconnect();

    // Fetch products from the database
    $products = get_products_by_user($user_id, $pdo);

} else {
    echo "Access Denied. User not logged in or not authorized.";
    // Optional: Redirect to login or home page
    // header('Location: login.php');
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
        <link rel="stylesheet" href="../../css/myproduct.css">
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
 #search-results{
     display:none;
}
 .back{
     display: none;
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
 .nav-hr{
     width:60%;
     margin: auto;
     margin-top:-6px;
}
.add-product{
    background-color: #65A5A5;
    color: white;
    padding:8px 15px;
    font-size: 15px;
    border:none;
    border-radius: 10px;
    margin-top:25px;
    margin-left:72.5%;
}
.add-product:focus{
    outline:none;
    border:none;
}
.product-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    gap: 20px;
    max-width: 1140px;
    margin: 0 auto;
    margin-top: 50px;
    margin-bottom: 30px;
}
.product {
    flex: 0 0 calc(2%);
    margin-top:-20px;
    margin-bottom: 20px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    text-align: left;
    box-sizing: border-box;
}
.product a:hover{
    text-decoration: none;
}
.product:hover {
    transform: scale(1.05);
    box-shadow: 0px 0px 6px #65A5A5;
}
.product a img {
    max-width: 150px;
    height:160px;
    flex-grow: 1;
}
.product .product-info {
    padding: 10px;
}
.product .product-name {
    font-weight: 500;
    margin-top: 20px;
    margin-bottom: 5px;
    font-size: 15px;
    color: #666;
}
.product .product-category {
    color: #666;
    margin-bottom: 5px;
    font-size: 12px;
}
.product .product-price {
    color: #666;
    margin-bottom: 5px;
    font-size: 15px;
    margin-right:50px
}
/* Style for the Edit and Delete buttons */
.product-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    
}

.edit-button,
.delete-button {
    background-color:transparent;
    color: #b6b5b5;
    border: none;
    border-radius: 4px;
    padding: 5px 5px;
    margin-top: -15px;
    font-size: 14px;
 
}

.edit-button:focus,
.delete-button:focus {
    outline:none;
    border:none;
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
 @media (max-width: 768px) {
    .navbar{
        position: fixed;
        background-color: white;
        width:100%;
        z-index: 100;
    }
     .navbar img {
         display: none;
    }
     .navbar{
         position: fixed;
         background-color: white;
         width:100%;
         z-index: 1;
         top:-1px;
    }
     .form {
         text-align: left;
         
    }
     .form-input[type="text"] {
        display: none;
    }
     .form-inline .fa-search {
        display: none;
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
    .product-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 5px;
    text-align: left;
    
}
     .nav-hr{
         display: none;
    }
    .add-product{
        background-color: #65A5A5;
        color: white;
        padding:8px 15px;
        font-size: 12px;
        border:none;
        border-radius: 8px;
        margin-top:90px;
        margin-left:67%;
    }
    .add-product:focus{
        outline:none;
        border:none;
    }
    .product-list {
         display: flex;
         flex-wrap: wrap;
         justify-content: center;
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
         margin-top: 5px;
         font-size: 13px;
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
        
   }
}


 </style>
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
                                <a href="arranger_home.php">
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
                                <a href="delete.php?product_id=<?php echo $product['product_id'];?>">                               
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
          </script>
    </body>
</html>