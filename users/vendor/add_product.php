<?php
session_start();
include '../php/dbhelper.php'; // Make sure this path is correct

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "seller") {
    header("Location: ../login.php"); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    $userId = $_SESSION["user_id"];

    // Retrieve shop_id
    $shopId = null;
    try {
        $pdo = dbconnect();
        $shopQuery = "SELECT shop_id FROM shops WHERE owner_id = :user_id LIMIT 1";
        $shopStmt = $pdo->prepare($shopQuery);
        $shopStmt->bindParam(':user_id', $userId);
        $shopStmt->execute();

        if ($shopStmt->rowCount() > 0) {
            $row = $shopStmt->fetch(PDO::FETCH_ASSOC);
            $shopId = $row['shop_id'];
        } else {
            echo "No shop found for this user.";
            exit();
        }
    } catch (PDOException $e) {
        echo "Error retrieving shop ID: " . $e->getMessage();
        exit();
    }

    // Retrieve form data
    $productImage = $_FILES['product_img'];
    $productName = $_POST['product_name'];
    $productCategory = $_POST['product_category'];
    $productPrice = $_POST['product_price'];
    $productDesc = $_POST['product_desc'];

    // Check if the selected category is 'Other'
    if ($productCategory == 'Other') {
        $other_category = $_POST['other_category'];
    } else {
        $other_category = ''; // Set it to an empty string if it's not 'Other'
    }

    // Process and upload product image
    $uploadDir = "../php/images/";  // Update with your images directory
    $uploadFile = $uploadDir . basename($productImage['name']);

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($productImage['type'], $allowedTypes) && move_uploaded_file($productImage['tmp_name'], $uploadFile)) {
        // Prepare SQL to insert into products table
        $sql = "INSERT INTO products (shop_owner, product_img, product_name, product_category, product_price, product_desc) VALUES (:shop_id, :product_img, :product_name, :product_category, :product_price, :product_desc)";

        // Insert product data into the database using PDO
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':shop_id', $shopId);
            $stmt->bindParam(':product_img', $uploadFile);
            $stmt->bindParam(':product_name', $productName);
            $stmt->bindParam(':product_category', $productCategory);
            $stmt->bindParam(':product_price', $productPrice);
            $stmt->bindParam(':product_desc', $productDesc);
            $stmt->execute();

            echo "Product added successfully!";
            header("Location: vendor_product.php"); // Redirect as needed
            exit();
        } catch (PDOException $e) {
            echo "Failed to add product to the database: " . $e->getMessage();
        }
    } else {
        echo "Invalid file type or upload failed.";
    }
}
?>



<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Products</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/addproduct.css">

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
                        <a href="javascript:void(0);" onclick="goBack()">
                          <i class="back fa fa-angle-left" aria-hidden="true"></i>
                          <div id="search-results">Add Products</div>
                        </a>
                        
                    </form>
                </li>     
            </ul>
        </div>
    </nav><hr class="nav-hr">
</header>

<div class="wrapper">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-container">
            <h3 class="prodimgT">Product Image</h3>                      
            <div class="image-upload-container">
                <div class="image-upload">
                    <input type="file" name ="product_img" id="product_img" class="image-input" accept="image/*" required> 
                    <label for="product_img"><p class="plus"></p class="add-plus"> + Add Image</label>
                </div>
                <div class="image-preview" id="imagePreview">
                    <!-- Selected images will be displayed here -->
                </div>
            </div> 

            <br>
            <h3 class="prodnaT">Product Name:</h3>
            <input type="text" name="product_name" id="product_name" required>
            <div class="form-row ">
                <div class="form-group col-md-6 prodca">
                    <h3 class="prodcaT">Product </h3>
                    <select name="product_category" id="product_category" required onchange="checkCategory(this)">
                        <option value="" disabled selected>--Select a Category--</option>
                        <option value="Flower Bouquets">Flower Bouquets</option>
                        <option value="Candles">Candles</option>
                        <option value="Tropical Flowers">Tropical Flowers</option>
                        <option value="Flower Bundles">Flower Bundles</option>
                        <option value="Arrangement Materials">Arrangement Materials</option>
                        <option value="Flower Stands">Flower Stands</option>
                        <option value="Leaves">Leaves</option>
                        <option value="Other">Other</option>
                    </select>
                 </div>
              <div class="form-group col-md-6 prodpr">
                <h3 class="prodprT">Product Price:</h3>
                <input type="number" name="product_price" id="product_price" required>                
              </div>
            </div>

            <div id="other-category" style="display: none;">
                <label for="other-category-input" class="prodott">Other Category:</label>
                <input type="text" name="other_category" id="other_category">
            </div>
            <br>
            <h3 class="proddeT">Product Description:</h3>
            <textarea name="product_desc" id="product_desc" rows="4" required></textarea>
            <div class="submit-btn">
                <button class ="addbtn" type="submit" name="submit" id="submit" >Add Product</button>
           </div>
        </div>
    </form>
</div>

<script src="../js/checkCategory.js"></script>
<script src="../js/imagePreview.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"> </script> 
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> 
<script>
            function goBack() {
                window.history.back();
            }
          </script>


</body>

</html>
