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
    <link rel="stylesheet" href="../../css/add_image.css">

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
                    <label for="imageInput"><p class="plus">+</p></label>
                    <p class="add-plus"> Add Images</p>

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