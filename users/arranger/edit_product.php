<?php
session_start();
include '../php/dbhelper.php';

$products = null;

if (isset($_GET['product_id'])) {
    $productID = $_GET['product_id'];
    $products = get_record('products', 'product_id', $productID);

    // Retrieve shop details based on the shop_owner field in products
    $shop = get_record('shops', 'shop_id', $products['shop_owner']);

    // Verify if the current user is the owner of the shop
    if ($shop['owner_id'] != $_SESSION['user_id']) {
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

    // Processing flowerTypes and ribbonColors data
    $flowerTypes = isset($_POST['flowerTypes']) ? json_decode($_POST['flowerTypes'], true) : [];
    $ribbonColors = isset($_POST['ribbonColors']) ? json_decode($_POST['ribbonColors'], true) : [];

    if (!is_array($flowerTypes)) {
        $flowerTypes = explode(',', $products['flower_type']);
    }
    if (!is_array($ribbonColors)) {
        $ribbonColors = explode(',', $products['ribbon_color']);
    }

    $flowerTypeStr = implode(",", $flowerTypes);
    $ribbonColorStr = implode(",", $ribbonColors);

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

    $arrangerID = $_SESSION['user_id'];

    try {
        $pdo = dbconnect();
        $stmt = $pdo->prepare("UPDATE products SET product_name = :product_name, product_category = :product_category, other_category = :other_category, product_price = :product_price, product_desc = :product_desc, product_img = :product_img, flower_type = :flower_type, ribbon_color = :ribbon_color WHERE product_id = :product_id");
        $stmt->bindParam(':product_name', $productName);
        $stmt->bindParam(':product_category', $productCategory);
        $stmt->bindParam(':other_category', $otherCategory);
        $stmt->bindParam(':product_price', $productPrice);
        $stmt->bindParam(':product_desc', $productDesc);
        $stmt->bindParam(':product_img', $imagePath);
        $stmt->bindParam(':flower_type', $flowerTypeStr);
        $stmt->bindParam(':ribbon_color', $ribbonColorStr);
        $stmt->bindParam(':product_id', $productID); // Bind the product ID for the WHERE clause

        $stmt->execute();

        header("Location: arranger_product.php");
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
<form action="" method="post" enctype="multipart/form-data" onsubmit="clearLocalStorage()">
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
                        <option value="Flower Bouquets"<?php if ($products['product_category'] == 'Flower Bouquets') echo 'selected'; ?>>Flower Bouquets</option>
                        <option value="Candles"<?php if ($products['product_category'] == 'Candles') echo 'selected'; ?>>Candles</option>
                        <option value="Tropical Flowers"<?php if ($products['product_category'] == 'Tropical Flowers') echo 'selected'; ?>>Tropical Flowers</option>
                        <option value="Flower Bundles"<?php if ($products['product_category'] == 'Flower Bundles') echo 'selected'; ?>>Flower Bundles</option>
                        <option value="Arrangement Materials"<?php if ($products['product_category'] == 'Arrangement Materials') echo 'selected'; ?>>Arrangement Materials</option>
                        <option value="Flower Stands"<?php if ($products['product_category'] == 'Flower Stands') echo 'selected'; ?>>Flower Stands</option>
                        <option value="Leaves"<?php if ($products['product_category'] == 'Leaves') echo 'selected'; ?>>Leaves</option>
                        <option value="Other"<?php if ($products['product_category'] == 'Other') echo 'selected'; ?>>Other</option>
                    </select>
                 </div>
              <div class="form-group col-md-6 prodpr">
                <h3 class="prodprT">Product Price:</h3>
                <input type="number" name="product_price" id="product_price" value="<?php echo $products['product_price']; ?>" required>                 
             </div>
            </div>

            <div id="other-category" style="display: none;">
                <label for="other-category-input" class="prodott">Other Category:</label>
                <input type="text" name="other_category" id="other_category">
            </div>
    
            <h3 class="proddeT">Product Description:</h3>
            <textarea name="product_desc" id="product_desc" rows="4" required><?php echo $products['product_desc']; ?></textarea>            <h3 class="custom">Customization</h3>

            <div class="form-row">
                <div class="container">
                    <label for="flowerTextBox">Flower Type(s)</label>
                    <input type="text" id="flowerTextBox" value="<?php echo $products['flower_type']; ?>" onkeydown="checkEnter(event, 'flowerTextBox', 'flowerDisplayContainer')">
                    <div class="display" id="flowerDisplayContainer"></div>
                </div>
                <div class="container">
                    <label for="ribbonTextBox">Ribbon Color</label>
                    <input type="text" id="ribbonTextBox" value="<?php echo $products['ribbon_color']; ?>" onkeydown="checkEnter(event, 'ribbonTextBox', 'ribbonDisplayContainer')">
                    <div class="display" id="ribbonDisplayContainer"></div>
                </div>
                <input type="hidden" name="flowerTypes" id="flowerTypes" value="">
                <input type="hidden" name="ribbonColors" id="ribbonColors" value="">

            </div>


            <div class="submit-btn">
                <button class="addbtn" type="submit" name="submit" id="submit">Save</button>
            </div>
        </div>
        <?php else: ?>
        <p>No product found.</p>
        <?php endif; ?>
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

    function addFlowerType(textBoxId, displayContainerId) {
        const textBox = document.getElementById(textBoxId);
        const displayContainer = document.getElementById(displayContainerId);

        if (textBox.value.trim() !== "") {
            // Create a new div for the flower type
            const newDiv = document.createElement('div');
            newDiv.className = 'input-box';
            newDiv.textContent = textBox.value;
            displayContainer.appendChild(newDiv);

            textBox.value = ""; // Clear the input field
            updateHiddenField("flowerTypes", displayContainer); // Update the hidden field
        }
    }

function addRibbonColor(textBoxId, displayContainerId) {
    const textBox = document.getElementById(textBoxId);
    const displayContainer = document.getElementById(displayContainerId);

    if (textBox.value.trim() !== "") {
        // Create a new div for the ribbon color
        const newDiv = document.createElement('div');
        newDiv.className = 'input-box';
        newDiv.textContent = textBox.value;
        displayContainer.appendChild(newDiv);

        textBox.value = ""; // Clear the input field
        updateHiddenField("ribbonColors", displayContainer); // Update the hidden field
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
    function checkEnter(event, textBoxId, displayContainerId) {
        if (event.key === "Enter") {
            if (textBoxId === "flowerTextBox") {
                // Call the function to add flower type
                addFlowerType(textBoxId, displayContainerId);
            } else if (textBoxId === "ribbonTextBox") {
                // Call the function to add ribbon color
                addRibbonColor(textBoxId, displayContainerId);
            }

            // Prevent Enter from creating a newline in the input field
            event.preventDefault();
        }
    }

    function initializeFlowerAndRibbonData() {
        // Clear existing entries in the display containers
        document.getElementById('flowerDisplayContainer').innerHTML = '';
        document.getElementById('ribbonDisplayContainer').innerHTML = '';

        flowerTypes.forEach(flowerType => {
            if (flowerType.trim() !== "") {
                addFlowerTypeToDisplay(flowerType, 'flowerDisplayContainer');
            }
        });

        ribbonColors.forEach(ribbonColor => {
            if (ribbonColor.trim() !== "") {
                addRibbonColorToDisplay(ribbonColor, 'ribbonDisplayContainer');
            }
        });

        // Update the hidden fields with initial values
        updateHiddenField('flowerTypes', document.getElementById('flowerDisplayContainer'));
        updateHiddenField('ribbonColors', document.getElementById('ribbonDisplayContainer'));
    }

    // Functions to add flower type and ribbon color to display containers
    function addFlowerTypeToDisplay(flowerType, displayContainerId) {
        const displayContainer = document.getElementById(displayContainerId);
        const newDiv = document.createElement('div');
        newDiv.className = 'input-box';
        newDiv.textContent = flowerType;
        displayContainer.appendChild(newDiv);
    }

    function addRibbonColorToDisplay(ribbonColor, displayContainerId) {
        const displayContainer = document.getElementById(displayContainerId);
        const newDiv = document.createElement('div');
        newDiv.className = 'input-box';
        newDiv.textContent = ribbonColor;
        displayContainer.appendChild(newDiv);
    }

    // Call the initialization function on page load
    document.addEventListener('DOMContentLoaded', initializeFlowerAndRibbonData);

    
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