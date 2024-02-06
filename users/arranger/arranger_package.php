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

    // Retrieve service_id instead of shop_id
    $serviceId = null;
    try {
        $pdo = dbconnect();
        $serviceQuery = "SELECT service_id FROM services WHERE arranger_id = :user_id LIMIT 1";
        $serviceStmt = $pdo->prepare($serviceQuery);
        $serviceStmt->bindParam(':user_id', $userId);
        $serviceStmt->execute();

        if ($serviceStmt->rowCount() > 0) {
            $row = $serviceStmt->fetch(PDO::FETCH_ASSOC);
            $serviceId = $row['service_id'];
        } else {
            echo "No service found for this user.";
            exit();
        }
    } catch (PDOException $e) {
        echo "Error retrieving service ID: " . $e->getMessage();
        exit();
    }

    // Retrieve form data
    $packageImage = $_FILES['package_img'];
    $packageName = $_POST['package_name'];
    $packagePrice = $_POST['package_price'];
    $inclusions = $_POST['inclusions'];

    // Process and upload package image
    $uploadDir = "../php/images/";  // Update with your images directory
    $uploadFile = $uploadDir . basename($packageImage['name']);

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($packageImage['type'], $allowedTypes) && move_uploaded_file($packageImage['tmp_name'], $uploadFile)) {
        // Prepare SQL to insert into service_package table
        $sql = "INSERT INTO service_package (service_id, package_name, package_image, package_price, inclusions) VALUES (:service_id, :package_name, :package_image, :package_price, :inclusions)";

        // Insert package data into the database using PDO
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':service_id', $serviceId);
            $stmt->bindParam(':package_name', $packageName);
            $stmt->bindParam(':package_image', $uploadFile);
            $stmt->bindParam(':package_price', $packagePrice);
            $stmt->bindParam(':inclusions', $inclusions);

            $stmt->execute();

            echo "Product added successfully!";
            header("Location: package.php"); // Redirect as needed
            // Redirect or perform additional actions as needed
        } catch (PDOException $e) {
            echo "Failed to add package to the database: " . $e->getMessage();
        }
    } else {
        echo "Failed to upload package image.";
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
                          <div id="search-results">Add Package</div>
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
            <h3 class="prodimgT">Image   </h3> 
                            
            <div class="image-upload-container">
                <div class="image-upload">
                    <input type="file" name ="package_img" id="package_img" class="image-input" accept="image/*" required> 
                    <label for="package_img"><p class="plus"></p class="add-plus"> + Add Image</label>
                </div>
                <div class="image-preview" id="imagePreview">
                    <!-- Selected images will be displayed here -->
                </div>
            </div> 
            <h3 class="prodnaT" style="margin-top:20px">Package Name:</h3>
            <input type="text" name="package_name" id="package_name" required>
        
            <h3 class="prodprT">Package Price:</h3>
            <input type="number" name="package_price" id="package_price" >   
            <h3 class="proddeT mt-4">Package Description:</h3>
            <textarea name="inclusions" id="inclusions" rows="4" required></textarea>
            <div class="submit-btn">
                <button class="addbtn" type="submit" name="submit" id="submit">Add </button>
            </div>
        </div>
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js">
</script> 
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js">
</script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js">
</script> 

<script>document.addEventListener("DOMContentLoaded", function () {
    var imageInputs = document.querySelectorAll(".image-input");
    var imagePreview = document.getElementById("imagePreview");

    function displayImages() {
        imagePreview.innerHTML = "";
        imageInputs.forEach(function (input) {
            for (var i = 0; i < input.files.length; i++) {
                var file = input.files[i];
                if (file.type.startsWith("image/")) {
                    var imageUrl = URL.createObjectURL(file);
                    var imageContainer = document.createElement("div");
                    imageContainer.classList.add("image-container");

                    var imageElement = document.createElement("img");
                    imageElement.classList.add("preview-image");
                    imageElement.src = imageUrl;

                    imageContainer.appendChild(imageElement);
                    imagePreview.appendChild(imageContainer);
                }
            }
        });
    }

    // Add event listeners to all file inputs
    imageInputs.forEach(function (input) {
        input.addEventListener("change", function () {
            displayImages();
        });
    });
});</script>



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