<?php
session_start();
include '../php/dbhelper.php';

$products = null;

if (isset($_GET['product_id'])) {
    $productID = $_GET['product_id'];
    $products = get_record('products', 'product_id', $productID);

    if ($products['seller_id'] != $_SESSION['user_id']) {
        echo "Unauthorized access!";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['product_name'];
    $productCategory = $_POST['product_category'];
    $productPrice = $_POST['product_price'];
    $productDesc = $_POST['product_desc'];
    $otherCategory = ($productCategory == 'Other') ? $_POST['other_category'] : '';

    $imagePath = '';
    $uploadFolder = '../php/images/';

    if (!empty($_FILES['product_img']['name'])) {
        $file_name = $_FILES['product_img']['name'];
        $file_tmp = $_FILES['product_img']['tmp_name'];

        if (!empty($file_name)) {
            $newFilePath = $uploadFolder . basename($file_name);
            if (move_uploaded_file($file_tmp, $newFilePath)) {
                $imagePath = $newFilePath;
            } else {
                echo "Error uploading file.";
                exit();
            }
        }
    } else {
        $imagePath = $products['product_img']; // Use the existing image path if no new image is uploaded
    }

    $sellerID = $_SESSION['user_id'];

    try {
        $pdo = dbconnect();
        $stmt = $pdo->prepare("UPDATE products SET product_name = :product_name, product_category = :product_category, other_category = :other_category, product_price = :product_price, product_desc = :product_desc, product_img = :product_img WHERE product_id = :product_id");
        $stmt->bindParam(':product_name', $productName);
        $stmt->bindParam(':product_category', $productCategory);
        $stmt->bindParam(':other_category', $otherCategory);
        $stmt->bindParam(':product_price', $productPrice);
        $stmt->bindParam(':product_desc', $productDesc);
        $stmt->bindParam(':product_img', $imagePath); // Store the image path directly, not as an array
        $stmt->bindParam(':product_id', $productID);
        $stmt->execute();

        header("Location: vendor_product.php");
        exit();
    } catch (PDOException $e) {
        echo "Error updating product record: " . $e->getMessage();
    }
}
?>






<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Products</title>
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
                          <div id="search-results">Edit Products</div>
                        </a>
                        
                    </form>
                </li>     
            </ul>
        </div>
    </nav><hr class="nav-hr">
</header>

<div class="wrapper">
    <form action="" method="post" enctype="multipart/form-data">
    <?php if ($products): ?>
        <div class="form-container">
            <h3 class="prodimgT">Product Images</h3>                      
            <div class="image-upload-container">
            <div class="image-upload">
                    <input type="file" name ="product_img" id="product_img" class="image-input" accept="image/*" > 
                    <label for="product_img"><p class="plus"></p class="add-plus"> + Edit Image</label>
                </div>
                <div class="image-preview" id="imagePreview">
                <?php
                        echo '<img src="' . $products['product_img'] . '" alt="' . $products['product_name'] . '" class="preview-image">';
                    ?>
                </div>
            </div> 
            <div class="modal" id="imageLimitModal">
                <div class="modal-content">
                    <p>Maximum of 5 images only.</p>
                </div>
            </div>
            <br>
            <h3 class="prodnaT">Product Name:</h3>
            <input type="text" name="product_name" id="product_name" value="<?php echo $products['product_name']; ?>" required>
            <div class="form-row ">
                <div class="form-group col-md-6 prodca">
                    <h3 class="prodcaT">Product Category</h3>
                    <select name="product_category" id="product_category" required onchange="toggleOtherCategoryInput(this)">
                        <option value="Birthday"<?php if ($products['product_category'] == 'Birthday') echo 'selected'; ?>>Birthday</option>
                        <option value="Anniversary"<?php if ($products['product_category'] == 'Anniversary') echo 'selected'; ?>>Anniversary</option>
                        <option value="Graduation"<?php if ($products['product_category'] == 'Graduation') echo 'selected'; ?>>Graduation</option>
                        <option value="Wedding"<?php if ($products['product_category'] == 'Wedding') echo 'selected'; ?>>Wedding</option>
                        <option value="Flower Bundles"<?php if ($products['product_category'] == 'Flower Bundles') echo 'selected'; ?>>Flower Bundles</option>
                        <option value="Leaves"<?php if ($products['product_category'] == 'Leaves') echo 'selected'; ?>>Leaves</option>
                        <option value="Baskets"<?php if ($products['product_category'] == 'Baskets') echo 'selected'; ?>>Baskets</option>
                        <option value="Tropical Flowers"<?php if ($products['product_category'] == 'Tropical Flowers') echo 'selected'; ?>>Tropical Flowers</option>
                        <option value="Native Plants<?php if ($products['product_category'] == 'Native Plants') echo 'selected'; ?>">Native Plants</option>
                        <option value="Other"<?php if ($products['product_category'] == 'Other') echo 'selected'; ?>>Other</option>
                    </select>
                 </div>
              <div class="form-group col-md-6 prodpr">
                <h3 class="prodprT">Product Price:</h3>
                <input type="number" name="product_price" id="product_price" value="<?php echo $products['product_price']; ?>" required>                 
             </div>
            </div>

            <div id="other_category"  <?php if ($products['product_category'] != 'Other') echo 'style="display: none;"'; ?>>
                <label for="other-category-input" class="prodott">Other Category:</label>
                <input type="text" name="other_category" id="other_category" value="<?php echo htmlspecialchars($products['other_category']); ?>">
            </div>
            <br>
            <h3 class="proddeT">Product Description:</h3>

            <textarea name="product_desc" id="product_desc" rows="4" required><?php echo $products['product_desc']; ?></textarea>

            <div class="submit-btn">
                <button class ="addbtn" type="submit" >Save</button>
           </div>
        </div>
        <?php else: ?>
        <p>No product found.</p>
        <?php endif; ?>
    </form>

</div>

<script>
    // Function to toggle visibility of other-category-input based on product category selection
    function toggleOtherCategoryInput() {
        const productCategory = document.getElementById("product_category");
        const otherCategoryInput = document.getElementById("other_category");

        if (productCategory.value === "Other") {
            otherCategoryInput.style.display = "block";
        } else {
            otherCategoryInput.style.display = "none";
        }
    }

    // Add event listener to the product category dropdown
    document.getElementById("product_category").addEventListener("change", toggleOtherCategoryInput);

    // Call the function initially to set the initial state based on the selected value
    toggleOtherCategoryInput();
</script>

<script>
    document.getElementById('imageInput').addEventListener('change', handleFileSelect, false);

    function handleFileSelect(evt) {
        var files = evt.target.files;

        var imagePreview = document.getElementById('imagePreview');
        imagePreview.innerHTML = ''; // Clear previous previews

        for (var i = 0, f; f = files[i]; i++) {
            if (!f.type.match('image.*')) {
                continue;
            }

            var reader = new FileReader();

            reader.onload = (function(theFile) {
                return function(e) {
                    var imageElement = document.createElement('img');
                    imageElement.className = 'preview-image';
                    imageElement.src = e.target.result;
                    imagePreview.appendChild(imageElement);
                };
            })(f);

            reader.readAsDataURL(f);
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
    });


       </script>
         <script>
            function goBack() {
                window.history.back();
            }
          </script>
   
</body>

</html>
