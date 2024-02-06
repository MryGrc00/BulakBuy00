<?php
session_start();
include '../php/dbhelper.php'; // Include your dbhelper.php file

// Function to fetch product details from the product table
function get_product_details_by_sales_id($sales_id) {
    $conn = dbconnect();
    $sql = "SELECT p.product_id, p.product_name, p.product_img, p.product_price, sd.quantity, sd.flower_type, sd.ribbon_color
            FROM sales s
            JOIN products p ON s.product_id = p.product_id
            JOIN salesdetails sd ON s.salesdetails_id = sd.salesdetails_id
            WHERE s.sales_id = ?";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$sales_id]);
        $product_details = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $product_details;
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}


// Function to add review and feedback to the database
// Function to add or update review and feedback in the database
// Function to add or update review and feedback in the database
function add_review_feedback($servicedetails_id, $customer_id, $feedback, $rating) {
    $table = 'servicedetails';
    $fields = ['customer_id', 'servicedetails_id', 'feedback', 'rating'];
    $data = [$customer_id, $servicedetails_id, $feedback, $rating];

    // Check if a record for the servicedetail already exists
    $existing_record = get_existing_servicedetail($servicedetails_id, $customer_id);

    if ($existing_record) {
        // Update the existing record
        $where = ['servicedetails_id', 'customer_id'];
        $where_data = [$servicedetails_id, $customer_id];
        return update_sales($table, $fields, $data, $where, $where_data);
    } else {
        // Record does not exist, insert a new record
        return insert_into_servicedetails($table, $fields, $data);
    }
}


// ...


// Function to get an existing record for a specific product and customer
function get_existing_servicedetail($servicedetails_id, $customer_id) {
    $conn = dbconnect(); // Make sure dbconnect() establishes a database connection
    $sql = "SELECT * FROM servicedetails WHERE servicedetails_id = ? AND customer_id = ?";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$servicedetails_id, $customer_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Close the database connection
        $conn = null;

        if ($result) {
            return $result; // Return the found record
        } else {
            return false; // No record found
        }
    } catch (PDOException $e) {
        // Handle the exception
        echo "Database error: " . $e->getMessage();
        $conn = null;
        return false; // Return false in case of an error
    }
}


// Function to update an existing record
function update_sales($table, $fields, $data, $where, $where_data) {
    $conn = dbconnect();
    $update_fields = array_map(function ($field) {
        return "$field = ?";
    }, $fields);
    $update_fields_str = implode(', ', $update_fields);
    $where_fields = array_map(function ($field) {
        return "$field = ?";
    }, $where);
    $where_fields_str = implode(' AND ', $where_fields);
    $sql = "UPDATE $table SET $update_fields_str WHERE $where_fields_str";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute(array_merge($data, $where_data));
        $conn = null;
        return true;
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}


// Function to get shop_id based on product_id
function get_shop_id_by_sales_id($sales_id) {
    $conn = dbconnect();
    // SQL to fetch shop_owner (shop_id) based on sales_id
    $sql = "SELECT p.shop_owner
            FROM products p
            JOIN sales s ON p.product_id = s.product_id
            WHERE s.sales_id = ?";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$sales_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $result ? $result['shop_owner'] : 0;
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return 0;
    }
}




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if feedback and rating are set in the POST request
    if (isset($_POST['feedback']) && isset($_POST['rating']) && isset($_POST['servicedetails_id'])) {
        $feedback = $_POST['feedback'];
        $rating = $_POST['rating'];
        $servicedetails_id = $_POST['servicedetails_id'];

        if (isset($_SESSION['user_id'])) {
            $customer_id = $_SESSION['user_id'];

            // Add the review and feedback to the database
            $result = add_review_feedback($servicedetails_id, $customer_id, $feedback, $rating);
            if ($result) {
                // Redirect to the thank-you page
                header('Location: customer_home.php');
                exit();
            } else {
                $message = "Failed to submit review. Please try again.";
            }
        }
    } else {
        $message = "Please provide all required fields.";
    }
}




// Rest of your HTML and form code...
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;   700&display=swap");
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
	height: 50px;
	width: 500px;
	background-color: #f0f0f0;
	border-radius: 10px;
	margin-left: 430px;
	margin-top: -10px;
}
.nav-hr {
	width: 60%;
	margin: auto;
	margin-top: -6px;
}
.back {
	display: none;
}
#search-results {
	display: none;
}
.container {
	display: flex;
	justify-content: space-between;
	gap: 20px;
}
.cart-container {
	width: 120%;
	margin: 20px auto;
	background-color: #fff;
	border-radius: 5px;
	box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}
.cart-item {
	display: flex;
	align-items: flex-start;
	padding: 20px;
}
.cart-item .custom-checkbox img {
	width: 300px;
	max-height: 280px;
	margin-left: 20px;
	margin-top: 50px;
	position: sticky;
}
.item-details {
	padding: 20px;
	margin-left: 20px;
}
.item-details h2 {
	font-size: 17px;
	color: #555;
}
.item-details p {
	margin: 5px 0;
}
.dropdown {
	display: none;
}
.flower-type {
	display: flex;
	gap: 10px;
	margin-top: -5px;
}
.flower {
	font-size: 15px;
	color: #777;
}
.type {
	font-size: 15px;
	color: #666;
}
.ribbon-color {
	display: flex;
	gap: 10px;
	margin-top: -5px;
}
.ribbon {
	font-size: 15px;
	color: #777;
}
.color {
	font-size: 15px;
	color: #666;
}
.price {
	color: #ff7e95;
	font-weight: 500;
	font-size: 18px;
}
.stars .bi {
	fill: none;
	font-size: 1.5em;
	margin-left: 10px;
	color: #888;
}
.stars {
	display: flex;
	margin-top: 5px;
	margin-left: -10px;
}
/* Review input */

.review-input textarea {
	width: 700px;
	padding: 10px;
	border: 1px solid #ccc;
	border-radius: 5px;
	margin-top: 10px;
}
.review-input textarea:focus {
	outline: none;
}
.image-upload-container {
	display: flex;
	flex-direction: column;
}
/* Image preview */

.image-preview {
	display: flex;
	flex-wrap: nowrap;
	margin-left: 0px;
	margin-top: 20px;
	padding: 0px;
}
.preview-image {
	width: 110px;
	height: 90px;
	margin-right: -5px;
	align-items: flex-start;
	padding: 0px;
	border-radius: 10px;
}
.image-upload {
	display: flex;
	font-size: 15px;
}
.image-upload input[type="file"] {
	display: none;
}
.image-upload label {
	border: 1px solid #65A5A5;
	color: #65A5A5;
	padding: 8px 12px;
	border-radius: 5px;
	cursor: pointer;
	margin-top: 10px;
	
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
	top: 5px;
	right: 0px;
}
.image-container {
	position: relative;
	margin-right: 10px;
	display: inline-block;
}
/* Modal Styles */

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
}
/* Modal Styles */

.modal1 {
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
.bi-emoji-smile {
	font-size: 40px;
	color: #666;
	margin-bottom: 20px;
}
.modal1-content {
	background-color: white;
	margin: 20% auto;
	padding: 30px 30px;
	width: 20%;
	text-align: center;
	color: #666;
	border-radius: 5px;
}
.submit-button {
	background-color: #65A5A5;
	color: #fff;
	border: none;
	padding: 10px 20px;
	border-radius: 5px;
	cursor: pointer;
	margin-top: 30px;
	float: right;
}
.submit-button:hover {
	background-color: #65A5A5;
}
.submit-button:focus {
	border: none;
	outline: none;
}
/*Responsiveness*/

@media (max-width: 768px) {
	.navbar {
		position: fixed;
		background-color: white;
		width: 100%;
		z-index: 10;
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
	.back {
		display: block;
		font-size: 20px;
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
	.container {
		margin-top: 50px;
		display: flex;
		justify-content: space-between;
	}
	.cart-container {
		width: 100%;
		margin: 20px auto;
		background-color: #fff;
		border-radius: 5px;
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
	}
	.cart-item {
		align-items: flex-start;
		margin-bottom: 50px;
		padding: 20px;
	}
	.cart-item .custom-checkbox img {
		width: 80px;
		max-height: 85px;
		margin-left: -5px;
		margin-top: 25px;
		position: sticky;
	}
	.item-details {
		margin-top: -15px;
		margin-left: -5px;
	}
	.item-details h2 {
		font-size: 13px;
		color: #555;
		margin-left: 0px;
		margin-top: -10px;
	}
	.item-details p {
		margin: 5px 0;
	}
	.flower-type {
		display: flex;
		justify-content: flex-start;
		width: 190px;
		margin-top: -1px;
	}
	.flower {
		color: #777;
		font-size: 11px;
	}
	.type {
		color: #777;
		font-size: 11px;
	}
	.ribbon-color {
		display: flex;
		gap: 10px;
		margin-top: 1px;
	}
	.ribbon {
		font-size: 11px;
		color: #777;
	}
	.color {
		font-size: 11px;
		color: #666;
	}
	.price {
		color: #ff7e95;
		font-weight: 500;
		font-size: 13px;
		display: flex;
		position: absolute;
		left: 40%;
		transform: translateX(-50%);
	}
	.stars .bi {
		fill: none;
		font-size: 1.2em;
		margin-left: 0px;
		color: #888;
	}
	.star-rating {
		display: flex;
		margin: auto;
		margin-top: -85px;
		margin-left: 110px;
		gap: 15px;
        position: absolute;
       
	}
	/* Review input */
	.review-input textarea {
		width: 210px;
		padding: 10px;
		border: 1px solid #ccc;
		border-radius: 5px;
		margin-top: -50px;
		margin-left: 110px;
		position: relative;
	}
	.review-input textarea:focus {
		outline: none;
	}
	.review-input textarea::placeholder {
		font-size: 12px;
	}
	.image-upload-container {
		display: flex;
		flex-direction: column;
	}
	/* Image preview */
	.image-preview {
		display: flex;
		flex-wrap: nowrap;
		margin-left: -98px;
		margin-top: 10px;
		padding: 0px;
	}
	.preview-image {
		width: 60px;
		height: 50px;
		margin-right: -5px;
		align-items: flex-start;
		padding: 0px;
		border-radius: 5px;
	}
	.image-upload {
		display: flex;
	}
	.image-upload input[type="file"] {
		display: none;
	}
	.image-upload label {
		border: 1px solid #65A5A5;
		color: #65A5A5;
		padding: 8px 15px;
		border-radius: 5px;
		cursor: pointer;
		margin-left: 13px;
		margin-top: 10px;
		font-size: 10px;
	}
	.delete-button {
		background-color: #6b68685e;
		color: #fff;
		border: none;
		border-radius: 50px;
		padding: 4px 8px;
		font-size: 7px;
		cursor: pointer;
		position: absolute;
		top: 2px;
		right: -4px;
	}
	.image-container {
		position: relative;
		margin-right: 10px;
		display: inline-block;
	}
	/* Modal Styles */
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
		margin: 80% auto;
		padding: 10px;
		border: 1px solid rgb(240, 99, 99);
		width: 50%;
		text-align: center;
		color: white;
		font-size: 12px;
		border-radius: 5px;
	}
	/* Modal Styles */
	.modal1 {
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
	.bi-emoji-smile {
		font-size: 20px;
		color: #666;
		margin-bottom: 20px;
	}
	.modal1-content {
		background-color: white;
		margin: 80% auto;
		padding: 30px 30px;
		width: 50%;
		text-align: center;
		color: #666;
		border-radius: 5px;
		font-size: 12px;
	}
	.submit-button {
		background-color: #65A5A5;
		color: #fff;
		border: none;
		padding: 8px 14px;
		border-radius: 5px;
		cursor: pointer;
		margin: auto;
		font-size: 10px;
		position: absolute;
		left: 50%;
		margin-top: 20px;
		transform: translateX(-50%);
	}
	.submit-button:hover {
		background-color: #65A5A5;
	}
	.submit-button:focus {
		border: none;
		outline: none;
	}
}
    </style>

	<style>
        .star-rating i.bi-star-fill {
            color: pink;
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
                          <div id="search-results">Review</div>
                        </a>
                        
                    </form>
                </li>     
            </ul>
        </div>
    </nav><hr class="nav-hr">
</header>

<main class="main">
	<div class="container">
		<div class="cart-container">
			<div class="cart-items">
				<div class="cart-item">
					<?php
                         

                            // Check if product_id is set in the URL
                            if (isset($_SESSION['user_id']) &&  isset($_GET['servicedetails_id'])){
                                $user_id = $_SESSION['user_id'];
                                $servicedetails_id = $_GET['servicedetails_id'];

                                // Fetch product details from the product table
                                $details= getServiceDetails("servicedetails", "services", "users", $servicedetails_id, $user_id);
                                if (isset($details)) {
                                    // Custom checkbox style with image
                                    echo '<div class="custom-checkbox" style="margin-top:-30px">';
                                    echo '<img src="' . $details["arranger_profile"] . '" alt="Service Image">';
                                    echo '</div>';
                                
                                    // Item details with similar style as product details
                                    echo '<div class="item-details">';
                                    echo '<h2>' . $details["arranger_first_name"] . " " . $details["arranger_last_name"] . '</h2>';
                            
                                    echo '<div class="flower-type">'; // Reusing the flower-type class for location
                                    echo '<p class="flower">Location:</p>'; // Reusing the flower class for the label
                                    echo '<p class="type">' . $details['arranger_address'] . '</p>'; // Reusing the type class for value
                                    echo '</div>';
                                
                                    echo '<div class="ribbon-color">'; //
                                    echo '<p class="ribbon">Phone:</p>'; 
                                    echo '<p class="color">' . $details['arranger_phone'] . '</p>'; // Reusing the color class for value
                                    echo '</div>';
                                
                                    // Price with similar styling
                                    echo '</div>';
                                    echo '</div>';
                                } else {
                                    echo "Service details not found.";
                                }
                                
                            // Function to fetch product details from the product table
                    }
                            ?>
						
						<div class="rating-input">
							<form method="post" class="review-form" id="reviewForm" enctype="multipart/form-data">
								<div class="star-rating" data-rating="">
									<i class="bi bi-star" data-rating="1"></i>
									<i class="bi bi-star" data-rating="2"></i>
									<i class="bi bi-star" data-rating="3"></i>
									<i class="bi bi-star" data-rating="4"></i>
									<i class="bi bi-star" data-rating="5"></i>
								</div>
								<input type="hidden" name="rating" id="star-rating" required>
								<div class="review-input">
									<textarea name="feedback" placeholder="Write your review here..." rows="4"></textarea>
								</div>
								<input type="hidden" name="servicedetails_id" value="<?php echo isset($_GET['servicedetails_id']) ? $_GET['servicedetails_id'] : ''; ?>">
								<button type="submit" class="submit-button">Submit Review</button>
							</form>
						</div>
 
						<div class="modal" id="imageLimitModal">
							<div class="modal-content">
								<p>Maximum of 1 image only.</p>
							</div>
						</div>
						<div class="modal1" id="thankYouModal">
							<div class="modal1-content">
								<i class="bi bi-emoji-smile"></i>
								<p>Thank you for your feedback !</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js">
</script> 
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js">
</script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js">
</script> 
<script>
	 document.addEventListener("DOMContentLoaded", function () {
        var starRating = document.querySelector(".star-rating");
        var starInput = document.getElementById("star-rating");
        var reviewForm = document.getElementById("reviewForm");

        starRating.addEventListener("click", function (e) {
            var selectedStar = e.target;
            if (selectedStar.tagName === "I") {
                var rating = selectedStar.getAttribute("data-rating");
                updateStarRating(rating);
            }
        });

        function updateStarRating(rating) {
            var stars = document.querySelectorAll(".star-rating i");

            for (var i = 0; i < stars.length; i++) {
                var star = stars[i];
                if (i < rating) {
                    star.classList.remove("bi-star");
                    star.classList.add("bi-star-fill");
                } else {
                    star.classList.remove("bi-star-fill");
                    star.classList.add("bi-star");
                }
            }

            starInput.value = rating;
        }

        // Add an event listener to the form for submission
        reviewForm.addEventListener("submit", function (e) {
            e.preventDefault(); // Prevent the default form submission

            // You can add additional validation here if needed

            // Manually submit the form using JavaScript
            submitForm();
        });

        function submitForm() {
            // Show the "Thank You" modal
            document.getElementById("thankYouModal").style.display = "block";

            // Automatically close the "Thank You" modal after 2 seconds
            setTimeout(function () {
                document.getElementById("thankYouModal").style.display = "none";

                // Clear the stored images from local storage
                localStorage.removeItem("selectedImages");

                // Submit the form
                reviewForm.submit();
            }, 1500); // 1.5 seconds
        }
    });	
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
			// Check the current image count
			var currentImageCount = storedImages.length;
			var newImages = [];
	
			// Loop through the selected files
			for (var i = 0; i < this.files.length; i++) {
				var file = this.files[i];
	
				// Check if the file is an image
				if (file.type.startsWith("image/")) {
					var imageUrl = URL.createObjectURL(file);
	
					// Check if the maximum limit of 5 images is reached
					if (currentImageCount >= 1) {
						// Show the modal indicating the limit is reached
						document.getElementById("imageLimitModal").style.display = "block";
						
						// Automatically close the modal after 2 seconds
						setTimeout(function () {
							document.getElementById("imageLimitModal").style.display = "none";
						}, 1500); // 1.5 seconds
	
						return;
					}
	
					// Store the image URL in the array
					newImages.push(imageUrl);
					currentImageCount++; // Increment the image count
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
	
	// Function to submit the review
	function submitReview() {
		// Show the "Thank You" modal
		document.getElementById("thankYouModal").style.display = "block";

		// Automatically close the "Thank You" modal after 2 seconds
		setTimeout(function () {
			document.getElementById("thankYouModal").style.display = "none";

			// Clear the stored images from local storage
			localStorage.removeItem("selectedImages");

			// Redirect to order_delivered.html
			window.location.href = "thank-you-page.html";
		}, 1500); // 1.5 seconds
}

</script>
<script>
	function goBack() {
		window.history.back();
	}
  </script>
   

</body>
</html>