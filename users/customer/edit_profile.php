<?php
session_start();
include '../php/dbhelper.php';

$pdo = dbconnect();

if (isset($_GET['user_id'])) {
    $userID = $_GET['user_id'];
    $users = get_record('users', 'user_id', $userID);

    // Check if the user exists
    if ($users) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $firstname = htmlspecialchars($_POST['first_name']);
            $lastname = htmlspecialchars($_POST['last_name']);
            $username = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $phone = htmlspecialchars($_POST['phone']);
            $zipcode = htmlspecialchars($_POST['zipcode']);
            $address = htmlspecialchars($_POST['address']);

            $imagePath = ''; // Single image path
            $uploadFolder = '../php/images/';

            if (!empty($_FILES['profile_img']['name'])) {
                $file_name = $_FILES['profile_img']['name'];
                $file_tmp = $_FILES['profile_img']['tmp_name'];

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
                $imagePath = $users['profile_img']; // Use the existing image path if no new image is uploaded
            }

            try {
                // Assuming $pdo is initialized in dbhelper.php
                $stmt = $pdo->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, username = :username, email = :email, phone = :phone, zipcode = :zipcode, address = :address, profile_img = :profile_img WHERE user_id = :user_id");
                $stmt->bindParam(':first_name', $firstname);
                $stmt->bindParam(':last_name', $lastname);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':zipcode', $zipcode);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':profile_img', $imagePath);
                $stmt->bindParam(':user_id', $userID);
                $stmt->execute();

                header("Location: customer_profile.php");
                exit();
            } catch (PDOException $e) {
                echo "Error updating user record: " . $e->getMessage();
            }
        }
    } else {
        // Handle the case when the user is not found
        echo "User not found!";
    }
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
   
    <link rel="stylesheet" href="../../css/addproduct.css">

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
		width: 106%;
        margin-left: 10px;
	}
    .lab{
        margin-left: -12px;
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
    .prodNat{
        margin-top: 10px;
    }
}
   </style>

</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="customer_home.php">
                    <img src="../php/images/logo.png" alt="BulakBuy Logo" class="img-fluid logo">
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
        <?php if ($users): ?>
            <div class="form-container">
            <h3 class="profimgT">Profile Image</h3>                      
            <div class="image-upload-container">
            <div class="image-upload">
                    <input type="file" name ="profile_img" id="profile_img" class="image-input" accept="image/*" > 
                    <label for="profile_img"><p class="plus"></p class="add-plus"> + Edit Image</label>
                </div>
                <div class="image-preview" id="imagePreview">
                <?php
                    $profileImage = !empty($users['profile_img']) ? $users['profile_img'] : '../php/images/default.jpg'; 
                    echo '<img src="' . $profileImage . '" alt="' . $users['last_name'] . '" class="preview-image">';
                 ?>
                </div>
            </div> 
            
    
            <h3 class="prodnaT">First Name</h3>
            <input type="text" name="first_name" id="first_name" value="<?php echo $users['first_name']; ?>" required>
             
            <h3 class="prodnaT">Last Name</h3>
            <input type="text" name="last_name" id="last_name" value="<?php echo $users['last_name']; ?>" required>
             
            <h3 class="prodnaT">Username</h3>
            <input type="text" name="username" id="username" value="<?php echo $users['username']; ?>" required>
             
            <h3 class="prodnaT">Email</h3>
            <input type="text" name="email" id="email" value="<?php echo $users['email']; ?>" required>



            <div class="form-row">
                <div class="container">
                    <h3 class="lab">Number</h3>
                    <input id="" name="phone" required="" type="text" value="<?php echo $users['phone']; ?>">
                </div>
                <div class="container">
                    <h3 class="lab">Zipcode</h3>
                    <input id="" name="zipcode" required="" type="text" value="<?php echo $users['zipcode']; ?>">
                </div>
            </div>
            <h3 class="">Address</h3>
            <input id="" name="address" required="" type="text" value="<?php echo $users['address']; ?>">

            <div class="submit-btn">
                <button class="addbtn" type="submit" value="Save">Save</button>
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