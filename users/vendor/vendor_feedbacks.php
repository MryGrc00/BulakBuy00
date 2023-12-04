<?php 
// Start the session (if not started already)
session_start();
include '../php/dbhelper.php';

?>
<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Feedbacks</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/feedbacks.css">
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
                                <a href="javascript:void(0);" onclick="goBack()">
                                    <i class="back fa fa-angle-left" aria-hidden="true"></i>
                                    <div id="search-results">Feedbacks</div>
                                  </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
            <hr class="nav-hr">
        </header>
        <main class="main">
            <div class="container">
                <div class="column2">
                <?php


function get_shop_id_by_seller_id($owner_id)
{
    $conn = dbconnect();
    $sql = "SELECT shop_id FROM shops WHERE owner_id = ?";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$owner_id]);
        $shop_id = $stmt->fetchColumn();
        $conn = null;
        return $shop_id;
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}

if (isset($_SESSION['user_id'])) {
    $owner_id = $_SESSION['user_id'];

    // Fetch shop_id by matching seller_id
    $shop_id = get_shop_id_by_seller_id($owner_id);

    if ($shop_id) {
        // Fetch feedback and ratings for the specific shop
        $feedbackAndRatings = get_feedback_and_ratings($shop_id);

        if ($feedbackAndRatings) {
            foreach ($feedbackAndRatings as $feedback) {
                // Fetch customer details
                $customer = get_customer_details($feedback['customer_id']);
                $fullName = $customer['first_name'] . ' ' . $customer['last_name'];
                $reviewImagePath = '../php/images/' . $feedback['review_image'];

                // Display customer details if there is feedback and ratings
                if ($customer && $feedback['rating'] > 0) {
                    echo '<div class="p-review">
                            <div class="review-pic">
                                <img src="' . $customer['profile_img'] . '" alt="Customer Profile">
                            </div>
                            <div class="review-info">
                                <div class="review-text">
                                    <p class="c-name">' . $fullName . '</p>
                                    <p class="r-star">' . generate_star_rating($feedback['rating']) . '&nbsp;' . $feedback['rating'] . '&nbsp;stars</p>
                                </div>
                            </div>
                        </div>
                        <div class="r-details">
                            <p class="c-review">' . $feedback['feedback'] . '</p>';

                    // Display review image if available
                    if (!empty($feedback['review_image'])) {
                        echo '<div class="image-preview">
                                <img src="' . $reviewImagePath . '" alt="Review Image">
                            </div>';
                    }
                    echo '</div>';
                    echo '<hr>';
                } else {
                    // If there is no feedback and ratings or the rating is 0, don't display user details
                    echo "No feedback and ratings available.";
                }
            }
        } 
    
    }

}
// Function to fetch feedback and ratings from the sales table
function get_feedback_and_ratings($shop_id)
{
    $conn = dbconnect();
    $sql = "SELECT * FROM sales WHERE shop_id = ?";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$shop_id]);
        $feedbackAndRatings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;
        return $feedbackAndRatings;
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}

// Rest of your code...
// Function to fetch customer details
function get_customer_details($customer_id)
{
    $conn = dbconnect();
    $sql = "SELECT * FROM users WHERE user_id = ?";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$customer_id]);
        $customerDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $customerDetails;
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}

// Function to generate star rating HTML based on the rating value
function generate_star_rating($rating)
{
    $starRatingHTML = '<i class="fa fa-star" aria-hidden="true"></i>';
    $emptyStarHTML = '<i class="fa fa-star-o" aria-hidden="true"></i>';

    $fullStars = floor($rating);
    $emptyStars = 5 - $fullStars;

    $ratingHTML = str_repeat($starRatingHTML, $fullStars) . str_repeat($emptyStarHTML, $emptyStars);

    return $ratingHTML;
}

?>

                    
                <!-- Add this to your HTML -->
                <div id="imageModal" class="modal1">
                    <span class="close">&times;</span>
                    <img id="modalImage" class="modal1-content" alt="Modal Image">
                    <button id="prevButton" class="prev">&#8249;</button>
                    <button id="nextButton" class="next">&#8250;</button>
                </div>
            </div>
        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            // Get the modal and image elements
            var modal = document.getElementById('imageModal');
            var modalImage = document.getElementById('modalImage');
            var prevButton = document.getElementById('prevButton');
            var nextButton = document.getElementById('nextButton');
            var images = document.querySelectorAll('.image-preview img');
            var currentIndex = 0;
            
            // Function to open the modal and display the clicked image
            function openModal(index) {
            modal.style.display = 'block';
            modalImage.src = images[index].src;
            currentIndex = index;
            }
            
            // Function to close the modal
            function closeModal() {
            modal.style.display = 'none';
            }
            
            // Function to navigate to the previous image
            function prevImage() {
            if (currentIndex > 0) {
                currentIndex--;
                modalImage.src = images[currentIndex].src;
            }
            }
            
            // Function to navigate to the next image
            function nextImage() {
            if (currentIndex < images.length - 1) {
                currentIndex++;
                modalImage.src = images[currentIndex].src;
            }
            }
            
            // Add click event listeners to the images
            images.forEach(function (image, index) {
            image.addEventListener('click', function () {
                openModal(index);
            });
            });
            
            // Add click event listener to the close button
            document.querySelector('.close').addEventListener('click', closeModal);
            
            // Add click event listeners to the next and previous buttons
            prevButton.addEventListener('click', prevImage);
            nextButton.addEventListener('click', nextImage);
            
        </script>
         <script>
            function goBack() {
                window.history.back();
            }
          </script>
    </body>
</html>