<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>See All Reviews</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/see_all_reviews.css">
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
                                <a href="#" onclick="goBack()"><i class="back fa fa-angle-left" aria-hidden="true"></i></a>
                                <div id="search-results">Product Ratings</div>
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
                    include '../php/dbhelper.php';
                    $conn=dbconnect();
                    function get_service_details($service_id) {
                        $conn = dbconnect();
                        $sql = "SELECT * FROM services WHERE service_id = ?";
                        try {
                            $stmt = $conn->prepare($sql);
                            $stmt->execute([$service_id]);
                            $product_details = $stmt->fetch(PDO::FETCH_ASSOC);
                            $conn = null;
                            return $product_details;
                        } catch (PDOException $e) {
                            echo $sql . "<br>" . $e->getMessage();
                            $conn = null;
                            return false;
                        }
                    }
                        
                    if (isset($_GET['service_id'])) {
                        $service_id = $_GET['service_id'];
                    
                        // Fetch product details from the product table
                        $service_details = get_service_details($service_id);
                    
                        // Display the product details
                        if ($service_details) {
                            // ... (your existing code for displaying product details)
                    
                            // Display feedback and ratings
                            $feedbackAndRatings = get_feedback_and_ratings($service_id);
                    
                            if ($feedbackAndRatings) {
                                foreach ($feedbackAndRatings as $feedback) {
                                    // Fetch customer details
                                    $customer = get_customer_details($feedback['customer_id']);
                                    $fullName = $customer['first_name'] . ' ' . $customer['last_name'];
                                    
                                    // Display customer details if there are feedback and ratings
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
                                    }
                                }
                            } else {
                                echo '<div class="no_feedback">No feedback and ratings yet.</div>';
                            }
                        } else {
                            echo "Product details not found.";
                        }
                    } else {
                        echo "Product ID not provided.";
                    }
                            // Function to fetch feedback and ratings from the sales table
                            function get_feedback_and_ratings($service_id) {
                                $conn = dbconnect();
                                $sql = "SELECT * FROM servicedetails WHERE service_id = ?";
                                try {
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute([$service_id]);
                                    $feedbackAndRatings = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    $conn = null;
                                    return $feedbackAndRatings;
                                } catch (PDOException $e) {
                                    echo $sql . "<br>" . $e->getMessage();
                                    $conn = null;
                                    return false;
                                }
                            }

                            // Function to fetch customer details
                            function get_customer_details($customer_id) {
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
                            function generate_star_rating($rating) {
                                $starRatingHTML = '<i class="fa fa-star" aria-hidden="true"></i>';
                                $emptyStarHTML = '<i class="fa fa-star-o" aria-hidden="true"></i>';

                                $fullStars = floor($rating);
                                $emptyStars = 5 - $fullStars;

                                $ratingHTML = str_repeat($starRatingHTML, $fullStars) . str_repeat($emptyStarHTML, $emptyStars);

                                return $ratingHTML;
                            }
                            ?>
                <hr>
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