<?php
session_start();
include '../php/dbhelper.php'; // Make sure this path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Check user login and role
    if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "arranger") {
        header("Location: index.php"); 
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
    $productStocks = $_POST['product_stocks'];
    $productUnit = $_POST['product_unit'];
    $productDesc = $_POST['product_desc'];

    $flowerTypes = isset($_POST['flowerTypes']) ? json_decode($_POST['flowerTypes']) : [];
    $ribbonColors = isset($_POST['ribbonColors']) ? json_decode($_POST['ribbonColors']) : [];
    $flowerTypeStr = is_array($flowerTypes) ? implode(",", $flowerTypes) : '';
    $ribbonColorStr = is_array($ribbonColors) ? implode(",", $ribbonColors) : '';
    

    // Check if the selected category is 'Other'
    if ($productCategory == 'Other') {
        $other_category = $_POST['other_category'];
    } else {
        $other_category = ''; // Set it to an empty string if it's not 'Other'
    }

    $customizationCheckbox = isset($_POST['customization_checkbox']) ? $_POST['customization_checkbox'] : 0;

    // Process and upload product image
    $uploadDir = "../php/images/";  // Update with your images directory
    $uploadFile = $uploadDir . basename($productImage['name']);

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($productImage['type'], $allowedTypes) && move_uploaded_file($productImage['tmp_name'], $uploadFile)) {
        // Retrieve the selected product status
        $productStatus = $_POST['product_status'];

            // Handle customization based on checkbox state
    if ($customizationCheckbox) {
        // Check if flower type and ribbon color input boxes are not empty
        if (!empty($flowerTypes) && !empty($ribbonColors)) {
            // Process and handle flower type and ribbon color here
            // ... (existing code for flower type and ribbon color)

            // Modify the SQL statement to include flower type and ribbon color
            $sql = "INSERT INTO products (shop_owner, product_img, product_name, product_category, product_price, product_stocks, product_unit, product_desc, flower_type, ribbon_color, product_status) VALUES (:shop_id, :product_img, :product_name, :product_category, :product_price, :product_stocks, :product_unit, :product_desc, :flower_type, :ribbon_color, :product_status)";
        } else {
            // Set flower type and ribbon color to empty strings or default values
            $flowerTypeStr = "";
            $ribbonColorStr = "";

            // Modify the SQL statement without flower type and ribbon color
            $sql = "INSERT INTO products (shop_owner, product_img, product_name, product_category, product_price, product_stocks, product_unit, product_desc, product_status) VALUES (:shop_id, :product_img, :product_name, :product_category, :product_price, :product_stocks, :product_unit, :product_desc, :product_status)";
        }
    } else {
        // Set flower type and ribbon color to empty strings or default values
        $flowerTypeStr = "";
        $ribbonColorStr = "";

        // Modify the SQL statement without flower type and ribbon color
        $sql = "INSERT INTO products (shop_owner, product_img, product_name, product_category, product_price, product_stocks, product_unit, product_desc, product_status) VALUES (:shop_id, :product_img, :product_name, :product_category, :product_price, :product_stocks, :product_unit, :product_desc, :product_status)";
    }



      // Prepare SQL to insert into products table
            $sql = "INSERT INTO products (shop_owner, product_img, product_name, product_category, product_price, product_desc, flower_type, ribbon_color, product_status";
            if (!empty($productStocks)) {
                $sql .= ", product_stocks";
            }
            if (!empty($productUnit)) {
                $sql .= ", product_unit";
            }
            $sql .= ") VALUES (:shop_id, :product_img, :product_name, :product_category, :product_price, :product_desc, :flower_type, :ribbon_color, :product_status";
            if (!empty($productStocks)) {
                $sql .= ", :product_stocks";
            }
            if (!empty($productUnit)) {
                $sql .= ", :product_unit";
            }
            $sql .= ")";

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
    $stmt->bindParam(':product_status', $productStatus); // Insert the selected product status

    // Bind product_stocks if not empty
    if (!empty($productStocks)) {
        $stmt->bindParam(':product_stocks', $productStocks);
    }

    // Bind product_unit if not empty
    if (!empty($productUnit)) {
        $stmt->bindParam(':product_unit', $productUnit);
    }

    $stmt->execute();

    echo "Product added successfully!";
    header("Location: arranger_product.php"); // Redirect as needed
    exit();
} catch (PDOException $e) {
    echo "Failed to add product to the database: " . $e->getMessage();
}
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
     height: 45px;
     width: 500px;
     background-color: #f0f0f0;
     border-radius: 10px;
     margin-left: 430px;
     margin-top: -10px;
}

 .nav-hr{
     width:60%;
     margin: auto;
     margin-top:-6px;
     color:rgba(0, 0, 0, .4);
}
 #search-results{
     display:none;
}
 .back{
     display: none;
}

.form-container {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
    padding: 20px;
    margin: 30px auto;
    width: 58%;
}
form {
    display: flex;
    flex-direction: column;
}
.add{
    display: flex;
}
h3{
    font-size: 14px;
    font-weight: 400;
    color:#555;
    margin-top: 0px;
    margin-left: 6px;
    
    flex-wrap: nowrap;
}

.image-upload-container {
	display: flex;
	
}




.preview-image {
	width:100px;
	height: 90px;
    margin-right: 5px;
    justify-content: center; 
	border-radius: 10px;
    margin-top:-5px;
    display: flex;
}

.image-preview {
    display: flex;
    flex-wrap: nowrap;
    margin-left: -1px;
    margin-top: 15px;
    padding: 0px;
}

.plus{
    text-align: center;
    font-size: 15px;
    margin-top: -2px;
    margin-right: 5px;
}
.image-upload {
    display: flex;
    height: 65px;
    text-align: center;
    margin-top: -43px;
    position: absolute;
    left:69%;
    transform: translateX(50%);
}
.image-upload input[type="file"] {
    display: none;
}
.image-upload label {
    border: none;
    color: #65A5A5;
    padding: 5px 5px;
    border-radius: 5px;
    cursor: pointer;
    
    
    font-size: 14px;
}
.delete-button {
	background-color: #6b68685e;
	color: #fff;
	border: none;
	border-radius: 50px;
	padding: 4px 8px;
	font-size: 9px;
	cursor: pointer;
	position: absolute;
	top: -10px;
	right: -1px;
}
.image-container {
	position: relative;
	margin-right: 10px;
	display: inline-block;
}

.modal {
	display: none;
	position: fixed;
	z-index: 100;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	overflow: auto;
	background-color: rgba(0, 0, 0, 0.4);
}
.modal-content {
	background-color: #f7b6b6f8;
	margin: 20% auto;
	padding: 20px;
	border: 1px solid rgb(240, 99, 99);
	width: 15%;
	text-align: center;
	color: white;
    font-size: 14px;
}
.photo-input-container {
    text-align: center;
    border: 2px dashed #d8d7d7;
;
    border-radius: 5px;
    padding: 20px;
    margin-top: 10px;
    cursor: pointer;
}

.photo-input-container:hover {
    border-color: #555;
}

input[type="file"] {
    display: none;
}

.photo-input-label {
    font-size: 16px;
    color: #555;
}
.submit-btn{
    justify-content: center;
    display: flex;
    margin:20px;
}
.addbtn {
    background-color: #65A5A5;
    color: white;
    font-size: 15px;
    padding: 8px 30px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
    
}
.addbtn:focus{
    outline:none;
    border: none;
}
.prodcaT, .prodprT{
    margin-top:35px;
}
.prodprT{
    margin-left: 10px;
}
.prodcaT{
    margin-left: px;
}

textarea:focus{
    outline: none;
    
}
select:focus{
    outline: none;
    
}
.proddeT{
    margin-bottom:20px;
}

/* Set a fixed width and height for text input fields */
input[type="text"]{
    font-size: 13px;
    padding: 10px;
    border: 1px solid #d8d7d7;
    border-radius: 5px;
    width: 100%; /* Set the width as desired */
    height:45px; /* Set the height as desired */
    margin-top: 15px;
    color: #666;
}

textarea {
    font-size: 13px;
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #d8d7d7;
   margin-bottom: 20px;
    border-radius: 5px;
    width: 100%; /* Set the width as desired */
    height: 130px; /* Set the height as desired */
    resize: none;
    color: #666;
}

/* Apply the same styles to input[type="number"] and select */
input[type="number"] {
    font-size: 13px;
    padding: 10px;
    border: 1px solid #d8d7d7;
;
    border-radius: 5px;
    width: 98%; /* Set the width as desired */
    height: 45px; /* Set the height as desired */
    color: #333; /* Set the text color */
    margin-bottom: 10px; /* Add margin to create space below the input */
    margin-left: 2%;
    margin-top: 15px;
}
input[type="text"]:focus, input[type="number"]:focus{
    outline:none;
}
/* Set the text color for select options */
select {
    font-size: 14px;
    padding: 10px;
    border: 1px solid #d8d7d7;
    border-radius: 5px;
    width: 98%; /* Set the width as desired */
    height: 45px; /* Set the height as desired */
    color: #555; /* Set the text color */
    margin-top: 15px;
    margin-left: 0px;
  
}   

label{
   display: flex;
    font-size: 13px;
    font-weight: 400;
    margin-top:14px;
    color:#555;
    
    
}
/* Style labels and input fields */
.form-row {
    display: flex;
    justify-content: space-between;
    margin-left:-5px;
}

.container {
    display: inline-block;
    margin-top:10px;
    width: 50%; 
    margin-left: -5px;
    font-size: 13px;
}
.container label{
    margin-left: 5px;
}
.custom{
    margin-left: 10px;
}

.input-box {
    display: inline-block;
    border: 1px solid #d8d7d7;
    padding: 10px;
    margin-right: 10px;
    margin-top: 10px;
    border-radius: 10px;
    font-size: 13px;
    text-align: center;
}

.display{
    margin-top: 2%;
    font-size: 13px;
    color:#666;
}




 
@media (min-width: 300px) and (max-width:500px) {
    .navbar{
        position: fixed;
        background-color: white;
        width:100%;
        z-index: 100;
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
        color: #555;
        margin-top: -20px;
   }
   a:hover{
       text-decoration: none;
       outline: none;
       border:none;
   }
    a .back{
        display: block;
        font-size: 20px;
        text-decoration: none;
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
   form {
        margin-top: 15px;
    }
    .form-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
        padding: 15px;
        margin: 60px auto;
        width: 93%;
    }
    .prodimgT{
        margin-left: 0px;
        
        
    }
    .form-row {
        display: flex;
        flex-wrap: wrap; 
        margin-top: -10px;
     
    }
    .form-row1 {
        display: flex;
        flex-wrap: wrap; 
        margin-top: -30px;
   
    }
    .form-row1 select {
        font-size: 12px; 
        padding: 2px 5px;
        height:37px;
        margin-top: 10px;
        margin-left: 0px;
        width: 100%;
        
    }
    /* Set a fixed width and height for text input fields */
input[type="text"]{
    font-size: 12px;
    padding: 10px;
    border: 1px solid #d8d7d7;
    border-radius: 5px;
    width: 100%; 
    height:37px; 
    color:#666;
  
}

textarea {
    font-size: 12px;
    padding: 10px;
    margin: 5px 0;
    margin-bottom: 25px;
    border: 1px solid #d8d7d7;
    border-radius: 5px;
    width: 100%; 
    height: 100px; 
    resize: none;
    color:#666;
}


input[type="number"] {
    font-size: 12px;
    padding: 10px;
    border: 1px solid #d8d7d7;
    border-radius: 5px;
    height: 37px; 
    color: #333; 
    margin-bottom: 10px; 
    margin-left: 2%;
    margin-top: 10px;
    color:#666;
}
input[type="text"]:focus, input[type="number"]:focus{
    outline:none;
}

.prodcaT, .prodprT{
    margin-left: 0px;
}

    
    .form-row select {
        font-size: 12px; 
        padding: 2px 5px;
        height:37px;
        margin-top: 10px;
        margin-left: 0px;
        width: 100%;
        
    }
    .add{
        display: flex;
    }
    h3{
        font-size: 13px;
        font-weight: 400;
        color:#555;
        margin-top: 0px;
        margin-left: 0px;
        
        flex-wrap: nowrap;
    }
    label{
        font-size: 12px;
        font-weight: 400;
        margin-top:14px;
        color:#555;   
        display: flex;
        
    }

    .image-upload-container {
        display: flex;
        margin-left: 0px
        
    }
    /* Image preview */
    
    
    
    .plus{
        text-align: center;
        font-size: 15px;
        margin-top: -2px;
        margin-right: 5px;
    }


    .modal {
        display: none;
        position: fixed;
        z-index: 100;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }
    .modal-content {
        background-color: #f7b6b6f8;
        margin: 20% auto;
        margin-top: 70%;
        padding: 20px;
        border: 1px solid rgb(240, 99, 99);
        width: 50%;
        height: 12%;
        text-align: center;
        color: white;
        font-size: 13px;
    }
    
    input[type="number"]{
        width:100%;
        padding: 10px;
    }





    /* Adjust styles for buttons on smaller screens */
    button {
        width: 100%; /* Full width for buttons */
        margin-top: 10px;
    }

    /* Adjust styles for the .photo-input-container on smaller screens */
    .photo-input-container {
        width: 100%; /* Full width on smaller screens */
    }

    
    .addbtn{
        font-size: 13px;
        width: 150px;
    }

    .prodpr{
        width: 100%; /* Full width for form elements */
        font-size: 10px;
        padding: 5px;
        flex: 1;
    }

    .prodca{
        width: 100%; /* Full width for form elements */
        font-size: 10px;
        padding: 5px;
        flex: 1;
    }

    .photo-input-container {
        text-align: center;
        border: 2px dashed #d8d7d7;
        border-radius: 5px;
        padding: 20px;
        margin-top: 10px;
        cursor: pointer;
        width: 100px;
        height: px;
        align-items: center;
    }

    .photo-input-label{
        font-size: 12px;
        text-align: center;
    }
    
    .search{
        width:385px;
        padding-right: 18px;
        padding-left: 10px;
    }
    .icon{
        position: absolute; 
        right: 26px;
        top: 8px;
      }
	/* Image preview */
	.image-preview {
		display: flex;
		flex-wrap: nowrap;
		margin-left: -1px;
		margin-top: 15px;
		padding: 0px;
	}

    .preview-image {
       width: 53px;
        height: 62px;
        margin-right: 5px;
        justify-content: center; /* Horizontally align the items */
        padding: 0px;
        border-radius: 5px;
        display: flex; /* Enable flexbox layout */
    }
    

	.image-upload {
		display: flex;
        height: 65px;
        text-align: center;
        
        position: absolute;
        left:58.5%;
        transform: translateX(50%);
	}
	.image-upload input[type="file"] {
		display: none;
	}
	.image-upload label {
		border: none;
		color: #65a5a5e1;
		padding: 5px 5px;
		border-radius: 5px;
		cursor: pointer;
		font-size: 11px;
	}

	.delete-button {
		background-color: #6b68685e;
		color: #fff;
		border: none;
		border-radius: 50px;
		padding: 4px 3px;
		font-size: 7px;
		cursor: pointer;
		position: absolute;
		top: -13px;
		right: -5px;
        width:20px;
	}
	.image-container {
		position: relative;
		margin-right: 10px;
		display: inline-block;
	}
    .container {

        margin-top:10px;
        
        font-size: 13px;
        margin-left: 0px;
    }
    .container input[type="text"]{
        margin-left: -10px;
        width: 130%; 
        margin-top: 10px;
    }
    .custom1{
        margin-left: 15px;
        margin-top: 10px;
    }
    .custom{
        margin-left: 0px;

    }
    .container label{
        margin-left: -10px;
    }

    .input-box {
        display: inline-block;
        border: 1px solid #d8d7d7;
        margin-left: -10px;
        border-radius: 5px;
        text-align: center;
        font-size: 13px;
        padding:3px 5px;
    }

    .display{
        margin-top: 2%;
        font-size: 13px;
        color:#666;
        

    }

    .addbtn:focus{
        outline:none;
        border: none;
    }
    .custom-btn{
        background-color: transparent;
        color: #bebebe;
        padding:0px 15px;
        font-size: 16px;
        border:none;
        border-radius: 8px;
        margin-left:5%;
        width:50px;
        margin-top: 10px;
    }
    .custom-btn:focus{
        outline:none;
        border:none;
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
                        <option value="Add Ons">Add Ons</option>
                        <option value="Other">Other</option>
                    </select>
                 </div>
              <div class="form-group col-md-6 prodpr">
                <h3 class="prodprT">Product Price:</h3>
                <input type="number" name="product_price" id="product_price" >   
              </div>
            </div>

            <div id="other-category" style="display: none;">
                <label for="other-category-input" class="prodott">Other Category:</label>
                <input type="text" name="other_category" id="other_category">
            </div>
            <div class="form-row1 ">
                <div class="form-group col-md-6 prodpr">
                    <h3 class="prodprT">Product Stocks</h3>
                    <input type="number" name="product_stocks" id="product_stocks" >     
                </div>
                <div class="form-group col-md-6 prodpr">
                    <h3 class="prodcaT">Product Unit </h3>
                    <select name="product_unit" id="product_unit">
                        <option value="" disabled selected>--Select a Unit--</option>
                        <option value="Per Piece">Per Piece</option>
                        <option value="Per Bundle">Per Bundle</option>
                        <option value="Per Dozen">Per Dozen</option>
                       
                    </select>
                </div>
            </div>
            <h3 class="proddeT">Product Description:</h3>
            <textarea name="product_desc" id="product_desc" rows="4" required></textarea>
            <div  style="display:flex">
                <input type="checkbox" name="customization_checkbox" id="customization_checkbox" onchange="toggleCustomization()">
                <h3 class="custom1">Customization</h3>
            </div>
            <div id="customizationSection" style="display: none;">
                <div class="container">
                    <label for="flowerTextBox">Flower Type(s)</label>
                    <div  style="display:flex">
                        <input type="text" id="flowerTextBox" style="width:600px">
                        <button class="custom-btn" type="button" onclick="addFlowerType('flowerTextBox', 'flowerDisplayContainer')"> + </button>
                    </div>
                    <div class="display" id="flowerDisplayContainer"></div>
                </div>
                <div class="container">
                    <label for="ribbonTextBox">Ribbon Color</label>
                    <div  style="display:flex"> 
                        <input type="text" id="ribbonTextBox" style="width:600px">
                        <button type="button" class="custom-btn" onclick="addRibbonColor('ribbonTextBox', 'ribbonDisplayContainer')"> + </button>
                    </div>
                    <div class="display" id="ribbonDisplayContainer"></div>
                </div>
                <input type="hidden" name="flowerTypes" id="flowerTypes" value="">
                <input type="hidden" name="ribbonColors" id="ribbonColors" value="">
            </div>

            <h3 class="custom mt-4">Availability</h3>
            <div class="form-row">
                <div class="container" style="display:flex">
                    <input type="radio" name="product_status" id="available" value="Available" checked>
                    <label for="available" style="margin-left:10px;margin-top:10px">Available</label>
                   
                </div>
                <div class="container"style="display:flex">
                    <input type="radio" name="product_status" id="notAvailable" value="Not Available"> 
                    <label for="notAvailable" style="margin-left:10px;margin-top:10px">Not Available</label>
                   
                </div>
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

    // Add this function to toggle customization section visibility
    function toggleCustomization() {
        const customizationSection = document.getElementById("customizationSection");
        const checkbox = document.getElementById("customization_checkbox");

        if (checkbox.checked) {
            customizationSection.style.display = "block";
        } else {
            customizationSection.style.display = "none";
        }
    }

    // Call the toggleCustomization function on page load to set initial visibility
    toggleCustomization();

    
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