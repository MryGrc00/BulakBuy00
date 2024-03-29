<?php
session_start();
include '../php/dbhelper.php';

// Redirect if not logged in
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["role"])) {  
    header("Location: ../index.php");
    exit(); 
}

$pdo = dbconnect();
$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"];

$isShopEmpty = is_shop_empty($user_id);
$users = get_record_by_user($user_id);

if ($role == 'arranger' && $isShopEmpty) {
    echo "<script>$(document).ready(function() { $('#shopDetailsModal').modal('show'); });</script>";
}
?>


<!DOCTYPE html> 
<html lang="en">
<head>
<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Arranger Home</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/arranger_home.css">

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
            <a href="../users.php">
                <li class="messenger">
                    <p class="num-cart">1 </p>
                    <i class="bi bi-messenger"></i>
                </li>
              </a>
              <li class="nav-item">
                <form class="form-inline my-2 my-lg-0">
                  <a href="search_results.php"><i class="fa fa-search"></i></a>
                  <input type="text"  class="form-control form-input" placeholder="Search">
              </form>
            </li>
            </ul>
        </div>
    </nav>
</header>

  <main class="main">
  <?php foreach ($users as $user) : ?>
            <div class="user-info">
            <img src="<?php echo !empty($user['profile_img']) ? $user['profile_img'] : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png'; ?>" alt="Seller Image" class="user-image">
                <div class="user-details">
                    <div class="user-name">
                    <?php echo $user['first_name'] . ' ' . $user['last_name']; ?> <a href="edit_profile.php?user_id=<?php echo $user['user_id']; ?>"><i class="bi bi-pencil-square"></i></a>
                    </div>
                    <div class="username">
                        <p>@<?php echo $user['username']; ?></p>
                    </div>
                </div>
            </div>
    <?php endforeach; ?>

    <section class="main-section">
        <div class="container">
            <a href="product_order.php">
              <div class="card">
                <?php
              $pendingOrderCount = count_pending_seller_orders($user_id);
              ?>
               <?php if ($pendingOrderCount > 0): ?>
                  <p class="num-cart1"><?php echo $pendingOrderCount; ?></p>
              <?php endif; ?>  
              <i class="bi bi-handbag"></i>
                  <span class="label">Orders</span>
              </div>
            </a>
            <a href="service_order.php">
              <div class="card">
              <?php
              $pendingServiceOrderCount = get_count_of_pending_services('servicedetails', 'services', $user_id);
              ?>
               <?php if ($pendingServiceOrderCount > 0): ?>
                  <p class="num-cart1"><?php echo $pendingServiceOrderCount; ?></p>
              <?php endif; ?>                  
              <i class="bi bi-person-plus"></i>
                    <span class="label">Services</span>
              </div>
            </a>
            <a href="service_process.php">
              <div class="card">
              <?php
                  $processOrderCount = count_processing_seller_orders($user_id);
                  $processServiceOrderCount = get_count_of_processing_services('servicedetails', 'services', $user_id);
                  $totalProcessingCount = $processOrderCount + $processServiceOrderCount;
              ?>
                <?php if ($totalProcessingCount > 0): ?>
                  <p class="num-cart1"><?php echo $totalProcessingCount; ?></p>
                <?php endif; ?>               
               <i class="bi bi-gear"></i>
                  <span class="label">Processing</span>
              </div>
            </a>
            <a href="service_intransit.php">
              <div class="card">
              <?php
              $intransitOrderCount = count_intransit_seller_orders($user_id);
              $intransitServiceOrderCount = get_count_of_intransit_services('servicedetails', 'services', $user_id);
              $totalintransitCount = $intransitOrderCount + $intransitServiceOrderCount;
              ?>
               <?php if ($totalintransitCount > 0): ?>
                  <p class="num-cart1"><?php echo $totalintransitCount; ?></p>
              <?php endif; ?>
                <i class="bi bi-truck"></i>
                  <span class="label">To Deliver</span>
              </div>
            </a>
            <a href="service_completed.php">
              <div class="card  ">
              <?php
              $completedOrderCount = count_completed_seller_orders($user_id);
              $completedServiceOrderCount = get_count_of_completed_services('servicedetails', 'services', $user_id);
              $totalcompletedCount = $completedOrderCount + $completedServiceOrderCount;
              ?> 
               <?php if ($totalcompletedCount > 0): ?>
                  <p class="num-cart1"><?php echo $totalcompletedCount; ?></p>
              <?php endif; ?>
                <i class="bi bi-bag-check"></i>
                  <span class="label">Completed</span>
              </div>
            </a>
        </div>
      <div class="container1">
        <a href="arranger_product.php">
          <div class="card2">
            <i class="bi bi-box"></i>
            <span class="label">My Products</span>
          </div>
        </a>
        <a href="arranger_shop.php">
          <div class="card2">
            <i class="bi bi-shop"></i>
            <span class="label">Shop</span>
          </div>
        </a>
        <a href="add_service.php?user_id=<?php echo $user['user_id']; ?>">          
          <div class="card2">
            <i class="bi bi-person"></i>
            <span class="label">Services</span>
          </div>
        </a>
        <a href="package.php">
          <div class="card2">
            <i class="bi bi-flower1"></i>
            <span class="label">Add Packages</span>
          </div>
        </a>
        <a href="arranger_transaction_history.php">
          <div class="card2">
            <i class="bi bi-file-earmark-text"></i>
            <span class="label">Transaction History</span>
          </div>
        </a>
        <a href="arranger_total_income.php">
          <div class="card2">
            <i class="bi bi-file-bar-graph"></i>
            <span class="label">Reports</span>
          </div>
        </a>
      </div>
      
      <div class="container1">

        <a href="arranger_subscription.php">
          <div class="card2">
            <i class="bi bi-credit-card"></i>
            <span class="label">Subscription</span>
          </div>
        </a>
        <a href="arranger_feedbacks.php">
          <div class="card2">
            <i class="bi bi-chat-dots"></i>
            <span class="label">Feedback</span>
          </div>
        </a>
        <a href="arranger_settings.php">
          <div class="card2">
            <i class="bi bi-gear"></i>
            <span class="label">Settings</span>
          </div>
        </a>

        <a href="#">
            <div class="card2">
                <i class="bi bi-question-circle"></i>
                <span class="label">Help</span>
            </div>
        </a>
       
      </div>
      
    
    
    </section>

    <div class="modal fade" id="shopDetailsModal" tabindex="-1" aria-labelledby="shopDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shopDetailsModalLabel">Shop Details Needed</h5>
                </div>
                <div class="modal-body">
                    You need to fill up your shop details first.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="location.href='edit_shop.php'">Edit Shop Details</button>
                </div>
                </div>
            </div>
    </div>
    
   
  </main>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    // JavaScript to handle responsive behavior
    const dropdownContent = document.querySelector(".dropdown-content");
    const notificationIcon = document.querySelector(".dropbtn a");
    const dropdownBtn = document.getElementById("dropdownBtn");

    // Check the screen width
    function checkScreenWidth() {
        if (window.innerWidth <= 768) { 
            dropdownContent.style.display = "none"; // Hide dropdown on small screens
            dropdownBtn.addEventListener("click", redirectToNotification);
        } else {
            dropdownContent.style.display = "none"; // Hide dropdown on larger screens
            dropdownBtn.removeEventListener("click", redirectToNotification);
        }
    }

    // Redirect to the notification page
    function redirectToNotification(event) {
        event.preventDefault();
        window.location.href = "arranger_notification.html";
    }

    // Toggle dropdown when the notification icon is clicked
    dropdownBtn.addEventListener("click", function() {
        if (dropdownContent.style.display === "none" || dropdownContent.style.display === "") {
            dropdownContent.style.display = "block";
        } else {
            dropdownContent.style.display = "none";
        }
    });

    checkScreenWidth();

    // Listen for window resize
    window.addEventListener("resize", checkScreenWidth);
</script>
        <script>
                $(document).ready(function() {
                    <?php if ($isShopEmpty): ?>
                    $('#shopDetailsModal').modal({
                        backdrop: 'static', // This prevents closing by clicking outside of the modal
                        keyboard: false // This prevents closing by pressing the escape key
                    });
                    <?php endif; ?>
                });

        </script>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
  $("#completed-link").on("click", function (e) {
    e.preventDefault();

    // Send an AJAX request to update the completed order count
    $.ajax({
      url: "arranger_home.php", // Replace with the actual URL for updating the count
      method: "POST",
      success: function (data) {
        // Update the count with the response from the server
        $("#completed-count").html(data);
      },
    });
  });
});
</script> -->

</body>

</html>
