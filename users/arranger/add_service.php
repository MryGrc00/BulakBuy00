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
    <title>Edit Service</title>
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
#arranger-num,
#arranger-zip,#arranger-state,
#arranger-city {
    width: 103.5%; /* Set the width as needed */
}
.arranger_uname{
    margin-top: 20px;
}
.nav-hr {
	width: 60%;
	margin: auto;
	margin-top: -6px;
	color: rgba(0, 0, 0, .4);
}
#search-results {
	display: none;
}
.back {
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
.prodimgT {
	margin-top: 0px;
}
h3 {
	font-size: 14px;
	font-weight: 400;
	color: #555;
	margin-top: 10px;
	flex-wrap: nowrap;
}
.circle-container {
	width: 100px;
	height: 100px;
	position: relative;
	display: flex;
	flex-direction: column;
	align-items: center;
	margin-top: 20px;
	margin-bottom: 30px;
	justify-content: center;
}
.circle-image {
	width: 95px;
	height: 95px;
	object-fit: cover;
	border-radius: 50%;
	overflow: hidden;
	background-image: url('https://www.shutterstock.com/image-vector/default-avatar-icon-vector-social-260nw-2180009415.jpg');
    background-size: cover;
}
.upload-button {
	position: absolute;
	text-align: center;
	cursor: pointer;
	bottom: 0;
	left: 70%;
	top: 70%;
	display: flex;
	justify-content: center;
	align-items: center;
	cursor: pointer;
}
.upload-button input[type="file"] {
	display: none;
}
.bi-plus {
	color: white;
	width: 25px;
	height: 25px;
	padding: 1px 4px;
	font-size: 15px;
	border-radius: 50px;
	background-color: #65A5A5;
}
.submit-btn {
	justify-content: center;
	display: flex;
	margin: 20px;
}
button {
	background-color: #65A5A5;
	color: white;
	font-size: 14px;
	padding: 8px 30px;
	border: none;
	border-radius: 5px;
	cursor: pointer;
	margin-top: 10px;
}
.addbtn:focus {
	outline: none;
	border: none;
}
textarea:focus {
	outline: none;
}
select:focus {
	outline: none;
}
.arranger_deT {
	margin-bottom: 20px;
	margin-top: 20px;
}
/* Set a fixed width and height for text input fields */

input[type="text"] {
	font-size: 13px;
	padding: 10px;
	border: 1px solid #d8d7d7;
	border-radius: 5px;
	width: 100%;
	/* Set the width as desired */
	height: 45px;
	/* Set the height as desired */
	margin-top: 10px;
	color: #666;
}
textarea {
	font-size: 13px;
	padding: 10px;
	margin: 5px 0;
	border: 1px solid #d8d7d7;
	margin-bottom: 20px;
	border-radius: 5px;
	width: 100%;
	/* Set the width as desired */
	height: 130px;
	/* Set the height as desired */
	resize: none;
	color: #666;
}
input[type="text"]:focus, input[type="number"]:focus {
	outline: none;
}
/* Set the text color for select options */

select {
	font-size: 14px;
	padding: 10px;
	border: 1px solid #d8d7d7;
	border-radius: 5px;
	width: 98%;
	/* Set the width as desired */
	height: 45px;
	/* Set the height as desired */
	color: #555;
	/* Set the text color */
	margin-top: 15px;
	margin-left: 0px;
}
/* Style labels and input fields */

.form-row {
	display: flex;
	justify-content: space-between;
}
.container {
	display: inline-block;
	margin-top: 10px;
	width: 50%;
	margin-left: -11px;
	font-size: 13px;
}
.input-box {
	display: inline-block;
	border: 1px solid #d8d7d7;
	padding: 10px;
	margin-top: 10px;
	border-radius: 10px;
	font-size: 13px;
	text-align: center;
}
@media (min-width: 300px) and (max-width:500px) {
	.navbar {
		position: fixed;
		background-color: white;
		width: 100%;
		z-index: 100;
	}
	.navbar img {
		display: none;
	}
	.form-input[type="text"] {
		display: none;
	}
	.nav-hr {
		width: 100%;
	}
	a #search-results {
		display: block;
		font-size: 15px;
		margin-left: 20px;
		color: #555;
		margin-top: -20px;
	}
	a:hover {
		text-decoration: none;
		outline: none;
		border: none;
	}
	a .back {
		display: block;
		font-size: 20px;
		text-decoration: none;
	}
	.form-inline .fa-search {
		display: none;
	}
	.form-inline .back {
		text-decoration: none;
		color: #666;
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
	.prodimgT {
		margin-left: 0px;
	}
	.circle-container {
		width: 100px;
		height: 100px;
		position: relative;
		display: flex;
		flex-direction: column;
		align-items: center;
		margin-top: -5px;
		margin-left: -10px;
		margin-bottom: 10px;
		justify-content: center;
	}
	.circle-image {
		width: 70px;
		height: 70px;
		object-fit: cover;
		border-radius: 50%;
		overflow: hidden;
	}
	.upload-button {
		position: absolute;
		text-align: center;
		cursor: pointer;
		bottom: 0;
		left: 63%;
		top: 47%;
		display: flex;
		justify-content: center;
		align-items: center;
		cursor: pointer;
	}
	.upload-button input[type="file"] {
		display: none;
	}
	.bi-plus {
		color: white;
		width: 20px;
		height: 20px;
		padding: 1px 4px;
		font-size: 13px;
		border-radius: 50px;
		background-color: #65A5A5;
	}
	.form-row {
		display: flex;
		flex-wrap: wrap;
		width: 100%;
	}
	/* Set a fixed width and height for text input fields */
	input[type="text"] {
		font-size: 12px;
		padding: 10px;
		border: 1px solid #d8d7d7;
		border-radius: 5px;
		width: 100%;
		height: 37px;
		color: #666;
	}
    input[type="number"] {
		font-size: 12px;
		padding: 10px;
		border: 1px solid #d8d7d7;
		border-radius: 5px;
		width: 100%;
		height: 37px;
		color: #666;
	}
    #arranger-num,
    #arranger-zip,#arranger-state,
    #arranger-city {
        width: 118%; 
    }
	textarea {
		font-size: 12px;
		padding: 10px;
		margin: 5px 0;
		border: 1px solid #d8d7d7;
		border-radius: 5px;
		width: 100%;
		height: 100px;
		resize: none;
		color: #666;
	}
	input[type="text"]:focus {
		outline: none;
	}
	h3 {
		font-size: 13px;
		font-weight: 400;
		color: #555;
		margin-top: 20px;
		flex-wrap: nowrap;
	}
	button {
		width: 100%;
		margin-top: 10px;
	}
	.addbtn {
		font-size: 13px;
		width: 150px;
	}
	.container {
		display: inline-block;
		margin-top: 10px;
		width: 51%;
		gap:10px;
		font-size: 13px;
	}
    .form-row input[type="text"] {
        width:100%;
    }
	.input-box {
		display: inline-block;
		border: 1px solid #d8d7d7;
		margin-left: -10px;
		border-radius: 5px;
		text-align: center;
		font-size: 13px;
		padding: 3px 5px;
	}
	.addbtn:focus {
		outline: none;
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
            <input id="arranger-rate" name="service_rate" required="" type="text" value="<?php echo $user['service_rate']; ?>" onkeypress="return isNumberKey(event)">
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
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        // Allow only numeric (0-9) input
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
</script>

<script>
    function goBack() {
        window.history.back();
    }
</script>
</body>
</html>