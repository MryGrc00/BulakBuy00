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

    // Retrieve service_id
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
    $imageFiles = $_FILES['files']; // Assuming 'files' is the name attribute of your file input

    // Process and upload images
    $uploadDir = "../php/images/"; // Update with your images directory
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    $statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';

    foreach ($imageFiles['name'] as $key => $val) {
        $fileName = basename($imageFiles['name'][$key]);
        $targetFilePath = $uploadDir . $fileName;

        // Check whether file type is valid
        $fileType = $imageFiles['type'][$key];

        if (in_array($fileType, $allowedTypes) && move_uploaded_file($imageFiles['tmp_name'][$key], $targetFilePath)) {
            // Insert image information into database
            $insertValuesSQL .= "(:service_id, '$targetFilePath'),";
        } else {
            $errorUpload .= $imageFiles['name'][$key] . ' | ';
        }
    }

    // Error message
    $errorUpload = !empty($errorUpload) ? 'Upload Error: ' . trim($errorUpload, ' | ') : '';

    if (!empty($insertValuesSQL)) {
        $insertValuesSQL = trim($insertValuesSQL, ',');

        // Insert image file path into the gallery table
        try {
            $pdo = dbconnect();
            $sql = "INSERT INTO gallery (service_id, image) VALUES $insertValuesSQL";
            $stmt = $pdo->prepare($sql);

            // Bind the service_id parameter
            $stmt->bindParam(':service_id', $serviceId, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();

            $statusMsg = "Files are uploaded successfully." . $errorUpload;
            header("Location: arranger_shop.php"); // Redirect as needed
            exit();
        } catch (PDOException $e) {
            echo "Failed to add images to the database: " . $e->getMessage();
        }
    } else {
        $statusMsg = "Upload failed! " . $errorUpload;
    }

    echo $statusMsg;
}
?>




<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Image</title>
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
/* Image preview */

.image-preview {
    display: flex;
    flex-wrap: wrap; 
    margin-left: 0;
    margin-top: 25px;
    margin-bottom: 20px;
    padding: 0;
    gap: 10px; 
}

.preview-image {
    width: 103px;
    height: 90px;
    margin-right: -5px;
    padding: 0;
    border-radius: 10px;
    margin-top: -5px;
}

.image-preview .image-container {
    max-width: calc(10% - 10px);
    margin: 0;
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


.submit-btn{
    justify-content: center;
    display: flex;
    margin:20px;
}
button {
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

label{
   display: flex;
    font-size: 13px;
    font-weight: 400;
    margin-top:14px;
    color:#555;
    
    
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
        margin-top: 70px;
        width: 93%;
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
        margin-left: 0px;
        
    }
    /* Image preview */
    
    
    
    .plus{
        text-align: center;
        font-size: 13px;
        margin-top: -2px;
        margin-left: -15px;
    }


  
    /* Adjust styles for buttons on smaller screens */
    button {
        width: 100%; /* Full width for buttons */
        margin-top: 10px;
    }

   
    
    .addbtn{
        font-size: 13px;
        width: 150px;
    }

  
	/* Image preview */
	.image-preview {
		display: flex;
		flex-wrap: wrap;
		margin-left: -1px;
		margin-top: 15px;
		padding: 0px;
	}
	.preview-image {
		max-width: 61px;
		height: 62px;
		margin-right: -7px;
		align-items: flex-start;
		padding: 0px;
		border-radius: 5px;
	}
    .image-preview .image-container {
        max-width: calc(50% - 10px); 
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
   
    .addbtn:focus{
        outline:none;
        border: none;
    
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
                          <div id="search-results">Add Image</div>
                        </a>
                        
                    </form>
                </li>     
            </ul>
        </div>
    </nav><hr class="nav-hr">
</header>

<div class="wrapper">
    <div class="form-container">
        <h3 class="prodimgT">Gallery</h3>

        <!-- Add a form element -->
        <form action="" method="post" enctype="multipart/form-data">
            <div class="image-upload-container">
                <div class="image-upload">
                    <!-- Update the name attribute to match the PHP code -->
                    <!-- Update the name attribute to match the PHP code -->
                    <input accept="image/*" id="imageInput" multiple type="file" name="files[]">
                    <label for="imageInput"><p class="plus">+ Add Images</p></label>
                   

                </div>
                <div class="image-preview" id="imagePreview">
                    <!-- Selected images will be displayed here -->
                </div>
            </div>

            <div class="submit-btn">
                <!-- Change the button type to "submit" -->
                <button class="addbtn" type="submit" name="submit">Save</button>
            </div>
        </form>
        <!-- Close the form element -->
    </div>
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

    //for flower types && ribbons
    function checkEnter(event, textBoxId, displayContainerId) {
    if (event.key === "Enter") {
        const textBox = document.getElementById(textBoxId);
        const displayContainer = document.getElementById(displayContainerId);

        if (textBox.value.trim() !== "") {
            const newTextBox = document.createElement("div");
            newTextBox.className = "input-box";
            newTextBox.textContent = textBox.value;
            displayContainer.appendChild(newTextBox);
            textBox.value = ""; // Clear the input field
        }

        event.preventDefault(); // Prevent Enter from creating a newline in the input field
    }
}
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js">
</script> 
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js">
</script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js">
</script> 
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get the file input, image preview container, and stored images from Local Storage
        var imageInput = document.getElementById("imageInput");
        var imagePreview = document.getElementById("imagePreview");
        var storedImages = JSON.parse(localStorage.getItem("selectedImages")) || [];

        function displayImages() {
            imagePreview.innerHTML = "";

            // Loop through the stored images and create img elements
            storedImages.forEach(function (imageUrl, index) {
                var imageContainer = document.createElement("div");
                imageContainer.classList.add("image-container");

                var imageElement = document.createElement("img");
                imageElement.classList.add("preview-image");
                imageElement.src = imageUrl;

                var deleteButton = document.createElement("button");
                deleteButton.classList.add("delete-button");
                deleteButton.innerHTML = "X";
                deleteButton.addEventListener("click", function () {
                    // Remove the image from the storedImages array
                    storedImages.splice(index, 1);

                    // Save the updated images to Local Storage
                    localStorage.setItem("selectedImages", JSON.stringify(storedImages));

                    // Display the updated images
                    displayImages();
                });

                imageContainer.appendChild(imageElement);
                imageContainer.appendChild(deleteButton);

                imagePreview.appendChild(imageContainer);
            });
        }

        // Display initially stored images
        displayImages();

        // Add an event listener to the file input
        imageInput.addEventListener("change", function () {
            var newImages = [];

            // Loop through the selected files
            for (var i = 0; i < this.files.length; i++) {
                var file = this.files[i];

                // Check if the file is an image
                if (file.type.startsWith("image/")) {
                    var imageUrl = URL.createObjectURL(file);

                    // Store the image URL in the array
                    newImages.push(imageUrl);
                }
            }

            // Combine the new and existing images
            storedImages = storedImages.concat(newImages);

            // Save the combined images to Local Storage
            localStorage.setItem("selectedImages", JSON.stringify(storedImages));

            // Display the updated images
            displayImages();
        });
    });

</script>

         <script>
            function goBack() {
                window.history.back();
            }
          </script>
   
</body>

</html>