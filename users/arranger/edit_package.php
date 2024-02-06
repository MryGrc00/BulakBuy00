<?php
session_start();
include '../php/dbhelper.php';

$package = null;

if (isset($_GET['package_id'])) {
    $packageID = $_GET['package_id'];
    $package = get_record('service_package', 'package_id', $packageID);

    // Retrieve service details based on the service_id field in service_package
    $service = get_record('services', 'service_id', $package['service_id']);

    // Verify if the current user is the owner of the service
    if ($service['arranger_id'] != $_SESSION['user_id']) {
        echo "Unauthorized access!";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['package_name'];
    $productPrice = $_POST['package_price'];
    $productDesc = $_POST['inclusions'];
    $imagePath = ''; // Initialize $imagePath variable

    $imagePath = '';
    $uploadFolder = '../php/images/';

    if (!empty($_FILES['package_image']['name'])) {
        $file_name = $_FILES['package_image']['name'];
        $file_tmp = $_FILES['package_image']['tmp_name'];

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
        $imagePath = $package['package_image']; // Use the existing image path if no new image is uploaded
    }

    $arrangerID = $_SESSION['user_id'];
    
    try {
        $pdo = dbconnect();
        // Update the SQL query to include only necessary fields
        $stmt = $pdo->prepare("UPDATE service_package SET package_name = :package_name, package_price = :package_price, inclusions = :inclusions,  package_image = :package_image WHERE package_id = :package_id");
        $stmt->bindParam(':package_name', $productName);
        $stmt->bindParam(':package_price', $productPrice);
        $stmt->bindParam(':inclusions', $productDesc);
        $stmt->bindParam(':package_image', $imagePath);
        $stmt->bindParam(':package_id', $packageID); // Bind the package ID for the WHERE clause

        $stmt->execute();

        header("Location: package.php");
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
        display: inline-block;
        margin-top:10px;
        
        font-size: 13px;
        margin-left: 0px;
    }
    
    .custom{
        margin-left: 0px;
    }
    .container label{
        margin-left: -10px;
    }
    .container input[type="text"]{
        margin-left: -10px;
        width: 114%; 
        margin-top: 10px;
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
<?php if ($package): ?>
    <div class="form-container">
            <h3 class="prodimgT">Product Images</h3>                      
            <div class="image-upload-container">
            <div class="image-upload">
                    <input type="file" name ="package_image" id="package_image" class="image-input" accept="image/*" > 
                    <label for="package_image"><p class="plus"></p class="add-plus"> + Edit Image</label>
                </div>
                <div class="image-preview" id="imagePreview">
                <?php
                        echo '<img src="' . $package['package_image'] . '" alt="' . $package['package_name'] . '" class="preview-image">';
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
            <input type="text" name="package_name" id="package_name" value="<?php echo $package['package_name']; ?>" required>
           
              <div class="form-group col-md-6 prodpr">
                <h3 class="prodprT">Product Price:</h3>
                <input type="number" name="package_price" id="package_price" value="<?php echo $package['package_price']; ?>" required>                 
             </div>
             <h3 class="proddeT">Product Description:</h3>
            <textarea name="inclusions" id="inclusions" rows="4" required><?php echo $package['inclusions']; ?></textarea>            
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