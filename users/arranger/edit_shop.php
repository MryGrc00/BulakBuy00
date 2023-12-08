<?php
session_start();
include '../php/dbhelper.php'; // Ensure this path is correct

// Check user login and role
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "arranger") {
    echo "User not logged in or not a arranger.";
    exit();
}

$userId = $_SESSION["user_id"];

// Initialize variables
$shopImg = '';
$shopName = '';
$shopPhone = '';
$shopAddress = '';

// Fetch existing shop details (if any)
try {
    $pdo = dbconnect(); // Ensure dbconnect() returns a PDO object
    $stmt = $pdo->prepare("SELECT shop_img, shop_name, shop_phone, shop_address FROM shops WHERE owner_id = :user_id");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $shopImg = $row['shop_img'];
        $shopName = $row['shop_name'];
        $shopPhone = $row['shop_phone'];
        $shopAddress = $row['shop_address'];
    }
} catch (PDOException $e) {
    echo "Error fetching shop details: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Retrieve form data
    $shopName = $_POST['shop_name'];
    $shopPhone = $_POST['shop_phone'];
    $shopAddress = $_POST['shop_address'];

    // Process and upload product image
    $uploadDir = "../php/images/";  // Update with your images directory
    if (isset($_FILES['shop_img']) && $_FILES['shop_img']['error'] == 0) {
        $shopImage = $_FILES['shop_img'];
        $uploadFile = $uploadDir . basename($shopImage['name']);

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($shopImage['type'], $allowedTypes) && move_uploaded_file($shopImage['tmp_name'], $uploadFile)) {
            $shopImg = $uploadFile; // Update $shopImg with the new path
        } else {
            echo "Invalid file type or upload failed.";
        }
    }

    // Check if it's an update or a new insert
    if ($stmt->rowCount() > 0) {
        // Update existing shop details
        $sql = "UPDATE shops SET shop_img = :shop_img, shop_name = :shop_name, shop_phone = :shop_phone, shop_address = :shop_address WHERE owner_id = :user_id";
    } else {
        // Insert new shop details
        $sql = "INSERT INTO shops (owner_id, shop_img, shop_name, shop_phone, shop_address) VALUES (:user_id, :shop_img, :shop_name, :shop_phone, :shop_address)";
    }

    // Insert or update shop data in the database using PDO
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':shop_img', $shopImg);
        $stmt->bindParam(':shop_name', $shopName);
        $stmt->bindParam(':shop_phone', $shopPhone);
        $stmt->bindParam(':shop_address', $shopAddress);
        $stmt->execute();

        echo "Shop details updated successfully!";
        header("Location: arranger_shop.php"); // Redirect as needed
        exit();
    } catch (PDOException $e) {
        echo "Failed to update shop details: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Shop Profile</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/edit_profile.css">

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
                        <a href="arranger_home.php" onclick="goBack()">
                          <i class="back fa fa-angle-left" aria-hidden="true"></i>
                          <div id="search-results">Edit Shop Profile</div>
                        </a>
                    </form>
                </li>     
            </ul>
        </div>
    </nav><hr class="nav-hr">
</header>
<div class="wrapper">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" method="post">
            <div class="form-container">
                <h3 class="arranger_imgT">Shop's Profile Picture</h3>
                <div class="circle-container">
                    <img class="circle-image" id="profile-image" name ="shop_img" src="<?php echo !empty($shopImg) ? $shopImg : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png'; ?>" alt="Shop Image">   
                    <label class="upload-button">
                        <input accept="image/*" type="file" id="imageInput" name="shop_img">
                        <i class="bi bi-plus"></i>
                    </label>
                </div>

                <h3 class="seller_name">Shop Name</h3>
                <input id="seller-name" name="shop_name" required="" type="text" value="<?php echo htmlspecialchars($shopName); ?>">

                <h3 class="arranger_name">Phone</h3>
                <input id="arranger-name" name="shop_phone" type="text" placeholder="09...." required maxlength="11" minlength="11" pattern="^09\d{9}$" value="<?php echo htmlspecialchars($shopPhone); ?>">

                <h3 class="arranger_uname">Address</h3>
                <input id="arranger-uname" name="shop_address" required="" type="text" value="<?php echo htmlspecialchars($shopAddress); ?>">

                <div class="submit-btn">
                    <button class="addbtn" type="submit" name="submit">Save</button>
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
<script>
    const circleImage = document.getElementById('profile-image');
    const uploadInput = document.getElementById('imageInput');

    uploadInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const imageURL = URL.createObjectURL(file);
            circleImage.src = imageURL; // Temporarily change the image source for preview
        }
    });
</script>


<script>
    function goBack() {
        window.history.back();
    }
</script>
</body>
</html>