<?php
session_start();
include '../php/dbhelper.php'; // Make sure this path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Check user login and role
    if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "arranger") {
        header("Location: login.php"); 
        exit(); 
    }

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

    $flowerTypes = isset($_POST['flowerTypes']) ? json_decode($_POST['flowerTypes']) : [];
    $ribbonColors = isset($_POST['ribbonColors']) ? json_decode($_POST['ribbonColors']) : [];
    $flowerTypeStr = implode(",", $flowerTypes);
    $ribbonColorStr = implode(",", $ribbonColors);

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
        $sql = "INSERT INTO products (shop_owner, product_img, product_name, product_category, product_price, product_desc, flower_type, ribbon_color) VALUES (:shop_id, :product_img, :product_name, :product_category, :product_price, :product_desc,:flower_type, :ribbon_color)";

        // Insert product data into the database using PDO
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':shop_id', $shopId);
            $stmt->bindParam(':product_img', $uploadFile);
            $stmt->bindParam(':product_name', $productName);
            $stmt->bindParam(':product_category', $productCategory);
            $stmt->bindParam(':product_price', $productPrice);
            $stmt->bindParam(':product_desc', $productDesc);
            $stmt->bindParam(':flower_type', $flowerTypeStr);
            $stmt->bindParam(':ribbon_color', $ribbonColorStr);
            $stmt->execute();

            echo "Product added successfully!";
            header("Location: arranger_product.php"); // Redirect as needed
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
    <style>
        @media (min-width: 300px) and (max-width:500px) {

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
<form action="" method="post" enctype="multipart/form-data" onsubmit="clearLocalStorage()">
        <div class="form-container">
            <h3 class="prodimgT">Product Image   </h3> 
                            
            <div class="image-upload-container">
                <div class="image-upload">
                    <input type="file" name ="product_img" id="product_img" class="image-input" accept="image/*" required> 
                    <label for="product_img"><p class="plus"></p class="add-plus"> + Add Image</label>
                </div>
                <div class="image-preview" id="imagePreview">
                    <!-- Selected images will be displayed here -->
                </div>
            </div> 
            <h3 class="prodnaT" style="margin-top:20px">Product Name:</h3>
            <input type="text" name="product_name" id="product_name" required>
            <div class="form-row ">
                <div class="form-group col-md-6 prodca">
                    <h3 class="prodcaT">Product Category</h3>
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
    
            <h3 class="proddeT">Product Description:</h3>
            <textarea name="product_desc" id="product_desc" rows="4" required></textarea>
            <h3 class="custom">Customization</h3>

            <div class="form-row">
            <div class="container">
                <label for="flowerTextBox">Flower Type(s)</label>
                <input type="text" id="flowerTextBox">
                <button type="button" onclick="addFlowerType('flowerTextBox', 'flowerDisplayContainer')">Add Flower Type</button>
                <div class="display" id="flowerDisplayContainer"></div>
            </div>
            <div class="container">
                <label for="ribbonTextBox">Ribbon Color</label>
                <input type="text" id="ribbonTextBox">
                <button type="button" onclick="addRibbonColor('ribbonTextBox', 'ribbonDisplayContainer')">Add Ribbon Color</button>
                <div class="display" id="ribbonDisplayContainer"></div>
            </div>

                <input type="hidden" name="flowerTypes" id="flowerTypes" value="">
                <input type="hidden" name="ribbonColors" id="ribbonColors" value="">

            </div>


            <div class="submit-btn">
                <button class="addbtn" type="submit" name="submit" id="submit">Add Product</button>
            </div>
        </div>
    </form>
</div>

<script>
    function checkCategory(select) {
        const otherCategoryDiv = document.getElementById("other-category");

        if (select.value === "Other") {
            otherCategoryDiv.style.display = "block";
            document.getElementById("other-category-input").setAttribute("required", "required");
        } else {
            otherCategoryDiv.style.display = "none";
            document.getElementById("other-category-input").removeAttribute("required");
        }
    }

    // Function to add flower type
    function addFlowerType(textBoxId, displayContainerId) {
        const textBox = document.getElementById(textBoxId);
        const displayContainer = document.getElementById(displayContainerId);

        if (textBox.value.trim() !== "") {
            const newTextBox = document.createElement("div");
            newTextBox.className = "input-box";
            newTextBox.textContent = textBox.value;
            displayContainer.appendChild(newTextBox);
            textBox.value = ""; // Clear the input field
            updateHiddenField("flowerTypes", displayContainer);
        }
    }

    // Function to add ribbon color
    function addRibbonColor(textBoxId, displayContainerId) {
        const textBox = document.getElementById(textBoxId);
        const displayContainer = document.getElementById(displayContainerId);

        if (textBox.value.trim() !== "") {
            const newTextBox = document.createElement("div");
            newTextBox.className = "input-box";
            newTextBox.textContent = textBox.value;
            displayContainer.appendChild(newTextBox);
            textBox.value = ""; // Clear the input field
            updateHiddenField("ribbonColors", displayContainer);
        }
    }

    // Function to update the hidden input field with the displayed data
    function updateHiddenField(hiddenInputId, displayContainer) {
        const hiddenInput = document.getElementById(hiddenInputId);
        const values = Array.from(displayContainer.children).map(function (item) {
            return item.textContent;
        });

        hiddenInput.value = JSON.stringify(values);
    }

    // Function to check for Enter key press
// Function to add flower type
function addFlowerType(textBoxId, displayContainerId) {
    const textBox = document.getElementById(textBoxId);
    const displayContainer = document.getElementById(displayContainerId);

    if (textBox.value.trim() !== "") {
        const newTextBox = document.createElement("div");
        newTextBox.className = "input-box";
        newTextBox.textContent = textBox.value;
        displayContainer.appendChild(newTextBox);
        textBox.value = ""; // Clear the input field
        updateHiddenField("flowerTypes", displayContainer);
    }
}

// Function to add ribbon color
function addRibbonColor(textBoxId, displayContainerId) {
    const textBox = document.getElementById(textBoxId);
    const displayContainer = document.getElementById(displayContainerId);

    if (textBox.value.trim() !== "") {
        const newTextBox = document.createElement("div");
        newTextBox.className = "input-box";
        newTextBox.textContent = textBox.value;
        displayContainer.appendChild(newTextBox);
        textBox.value = ""; // Clear the input field
        updateHiddenField("ribbonColors", displayContainer);
    }
}

    
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js">
</script> 
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js">
</script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js">
</script> 

<script src="../js/imagePreview.js"></script>



<script >
	function goBack() {
		window.history.back();
	}
</script> 
<script >
	function clearLocalStorage() {
		localStorage.removeItem("selectedImages");
	} 
</script>

   
</body>

</html>