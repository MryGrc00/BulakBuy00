<?php
// Include your database connection code or functions here
session_start();
include '../php/dbhelper.php';
// Check if product_id is set in the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch product details from the product table
    $product_details = get_product_details($product_id);

    // Fetch shop details using shop_id from the product table
    $shop_details = get_shop_details($product_details['shop_owner']);
}
function get_product_details($product_id) {
    // Add your database connection code or function here
    $conn = dbconnect();
    $sql = "SELECT * FROM products WHERE product_id = ?";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$product_id]);
        $product_details = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $product_details;
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}

// Function to fetch shop details from the shop table
function get_shop_details($shop_id) {
    // Add your database connection code or function here
    $conn = dbconnect();
    $sql = "SELECT * FROM shops WHERE shop_id = ?";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$shop_id]);
        $shop_details = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $shop_details;
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    // Check if user is logged in (you may want to add more robust user authentication)
    if (isset($_SESSION['user_id'])) {
        // Get user ID from the session
        $complainant_id = $_SESSION['user_id'];

        // Get shop ID from the product details
        $shop_id = $product_details['shop_owner'];

        // Get input from the form
        $reason = $_POST['reason'];

        // Get the current date and time
        $complain_date = date('Y-m-d H:i:s');

        // Insert into the reports table
        $result = insert_report($complainant_id, $shop_id, $reason, $complain_date);

        // Check if the insertion was successful
		if ($result) {
			// Use PHP to generate JavaScript code
			echo '<script>
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
						window.location.href = "customer_home.php";
					}, 3000); // 2 seconds
				}
			</script>';
		} else {
			echo "Error submitting report.";
		}
    } else {
        echo "User not logged in.";
    }
}

// Function to insert a report into the reports table
function insert_report($complainant_id, $defendant_id, $reason, $complain_date) {
    $conn = dbconnect();
    $sql = "INSERT INTO reports (complainant_id, defendant_id, reason, complain_date) VALUES (?, ?, ?, ?)";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$complainant_id, $defendant_id, $reason, $complain_date]);

        // After inserting the report, update the shop status if the defendant_id matches a shop_id
        update_shop_status_if_reported($defendant_id);

        $conn = null;
        return true;
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}
function update_shop_status_if_reported($shop_id) {
    $conn = dbconnect();
    $sql = "SELECT * FROM shops WHERE shop_id = ?";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$shop_id]);
        $shop = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($shop) {
            // Shop found, update its status
            $updateSql = "UPDATE shops SET status = 'Reported' WHERE shop_id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->execute([$shop_id]);
        }
    } catch (PDOException $e) {
        echo "Error updating shop status: " . $e->getMessage();
    }

    $conn = null;
}

?>
<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/report.css">

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
        <div class="custom-checkbox" style="margin-top:-30px">
            <img alt="<?php echo $product_details['product_name']; ?>" src="<?php echo $product_details['product_img']; ?>">
        </div>
        <div class="item-details">
            <h2><?php echo $shop_details['shop_name']; ?></h2>
			<form method="POST" action="">
                <div class="review-input">
                    <textarea name="reason" placeholder="Write your report here..." rows="4" required></textarea>
                </div>
                <button type="submit" class="submit-button" name="submit_review" onclick="submitReview()">Submit Review</button>
            </form>
    </form>
            <div class="modal1" id="thankYouModal">
                <div class="modal1-content">
                    <i class="bi bi-emoji-smile"></i>
                    <p>We already received your report!</p>
                </div>
            </div>
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
	function goBack() {
		window.history.back();
	}
  </script>
   
</body>

</html>
