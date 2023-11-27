<?php
session_start();
include '../php/dbhelper.php';

$pdo = dbconnect(); 
$userID = isset($_GET['user_id']) ? filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT) : null;

if (isset($_GET['user_id'])) {
    $stmt = $pdo->prepare("SELECT users.*, services.service_id, services.service_rate, services.service_description FROM users LEFT JOIN services ON users.user_id = services.arranger_id WHERE users.user_id = :userID");
    $stmt->execute(['userID' => $userID]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found";
    } 
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_service'])) {
    $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $arrangerAddress = filter_input(INPUT_POST, 'arranger_address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $serviceRate = filter_input(INPUT_POST, 'service_rate', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $serviceDescription = filter_input(INPUT_POST, 'service_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


    // Process profile image upload
    $profileImage = $user['profile_img'];
  // Process profile image upload if a file is uploaded
  if (!empty($_FILES['profile_img']['name'])) {
    $targetDir = "../php/images/";
    $fileName = time() . basename($_FILES["profile_img"]["name"]);
    $targetFile = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["profile_img"]["tmp_name"]);

    if($check !== false && ($_FILES["profile_img"]["size"] < 5000000) && 
       in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        if (!file_exists($targetFile)) {
            if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $targetFile)) {
                $profileImage = $targetFile;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Sorry, file already exists.";
        }
    } else {
        echo "Invalid file format or file is too large.";
    }
}

// Ensure profileImage is set before updating the user
if(isset($profileImage)) {
    // Update User Details
    $updateUserQuery = "UPDATE users SET first_name = :firstName, last_name = :lastName, address = :address, profile_img = :profileImg WHERE user_id = :userID";
    $stmt = $pdo->prepare($updateUserQuery);
    $stmt->execute([
        'firstName' => $firstName,
        'lastName' => $lastName,
        'address' => $arrangerAddress,
        'profileImg' => $profileImage,
        'userID' => $userID
    ]);
}

    // Check if a service entry exists for this user
    $stmt = $pdo->prepare("SELECT * FROM services WHERE arranger_id = :userID");
    $stmt->execute(['userID' => $userID]);
    $serviceExists = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($serviceExists) {
        // Update existing service
        $updateServiceQuery = "UPDATE services SET service_rate = :serviceRate, service_description = :serviceDescription WHERE arranger_id = :arrangerID";
        $stmt = $pdo->prepare($updateServiceQuery);
        $stmt->execute([
            'serviceRate' => $serviceRate,
            'serviceDescription' => $serviceDescription,
            'arrangerID' => $userID
        ]);
    } else {
        // Insert new service
        $insertServiceQuery = "INSERT INTO services (arranger_id, service_rate, service_description, status) VALUES (:arrangerID, :serviceRate, :serviceDescription, 'enabled')";        $stmt = $pdo->prepare($insertServiceQuery);
        $stmt->execute([
            'arrangerID' => $userID,
            'serviceRate' => $serviceRate,
            'serviceDescription' => $serviceDescription
        ]);
    }

    header("Location: arranger_home.php");
    exit;
}

?>








<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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
                          <div id="search-results">Edit Profile</div>
                        </a>
                        
                    </form>
                </li>     
            </ul>
        </div>
    </nav><hr class="nav-hr">
</header>
<div class="wrapper">
    <form action="" enctype="multipart/form-data" method="post">
         <?php if ($user): ?>
            <div class="form-container">
            <h3 class="profimgT">Profile Image</h3>                      
            <div class="circle-container">
            <img class="circle-image" id="profile-image" src="<?php echo !empty($user['profile_img']) ? $user['profile_img'] : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png'; ?>" alt="Shop Image">                       <label class="upload-button">
                        <input accept="image/*" type="file" id="imageInput" name="profile_img">
                        <i class="bi bi-plus"></i>
                    </label>
                </div>
            <h3 class="arrangerfname">First Name</h3>
            <input type="text" name="first_name" id="first_name" value="<?php echo $user['first_name']; ?>" required>  
            <h3 class="arrangerlname">Last Name</h3>
            <input type="text" name="last_name" id="last_name" value="<?php echo $user['last_name']; ?>" required>
            <h3 class="arranger_rate">Service Rate</h3>
            <input id="arranger-rate" name="service_rate" required="" type="number" value="<?php echo $user['service_rate']; ?>">
            <h3 class="arranger_address">Address</h3>
            <input id="arranger-address" name="arranger_address" required="" value="<?php echo $user['address']; ?>" type="text">
            <h3 class="arranger_deT">Description:</h3>
            <textarea id="arranger-description" name="service_description" required="" rows="4"><?php echo $user['service_description']; ?></textarea>
            <div class="submit-btn">
                <button class="addbtn" type="submit" name="add_service">Save</button>
            </div>
        </div>
        <?php endif; ?>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js">
</script> 
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js">
</script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js">
</script> 
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