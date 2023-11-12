<?php
session_start();
if (isset($_SESSION["user_id"]) && isset($_SESSION["role"])) {
    $user_id = $_SESSION["user_id"];
    $role = $_SESSION["role"];

    include '../php/dbhelper.php';


    $users = get_record_by_user($user_id) ;
    // The rest of your HTML code remains the same...
}else {
    // Handle cases where the user is not logged in or role is not set
    echo "User not logged in or role not set.";
    // Optional: Redirect to login page or show a login link
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
          <img src="../../images/logo.png" alt="BulakBuy Logo" class="img-fluid logo">
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
              <a href="">
                <li class="bell">
                  <div class="dropdown">
                    <button class="dropbtn" id="dropdownBtn">
                      <i class="bi bi-bell"></i>
                      <p class="num-cart">1</p>
                    </button>
                    <div class="dropdown-content">
                      <div class="notification-details">
                        <img src="https://assets.florista.ph/uploads/product-pics/1674804112_5022.png" alt="Product Image">
                        <div class="text-content">
                          <span class="order-status">Order out for delivery!</span>
                          <span class="order-description">Your order of a new hahahahhaha iPhone 14 Pro is on its way!</span>
                          <div class="o-date-time">
                            <span class="order-date">23 July 2023</span>
                            <span class="order-time">7:20 AM</span>
                          </div>
                        </div>
                      </div>
                      <hr class="notif-hr">
                      <div class="notification-details">
                        <img src="https://assets.florista.ph/uploads/product-pics/1674804112_5022.png" alt="Product Image">
                        <div class="text-content">
                          <span class="order-status">Order out for delivery!</span>
                          <span class="order-description">Your order of a new hahahahhaha iPhone 14 Pro is on its way!</span>
                          <div class="o-date-time">
                            <span class="order-date">23 July 2023</span>
                            <span class="order-time">7:20 AM</span>
                          </div>
                        </div>
                      </div>
                      <hr class="notif-hr">
                      <div class="notification-details">
                        <img src="https://assets.florista.ph/uploads/product-pics/1674804112_5022.png" alt="Product Image">
                        <div class="text-content">
                          <span class="order-status">Order out for delivery!</span>
                          <span class="order-description">Your order of a new hahahahhaha iPhone 14 Pro is on its way!</span>
                          <div class="o-date-time">
                            <span class="order-date">23 July 2023</span>
                            <span class="order-time">7:20 AM</span>
                          </div>
                        </div>
                      </div>
                      <hr class="notif-hr">
                      <div class="notification-details">
                        <img src="https://assets.florista.ph/uploads/product-pics/1674804112_5022.png" alt="Product Image">
                        <div class="text-content">
                          <span class="order-status">Order out for delivery!</span>
                          <span class="order-description">Your order of a new hahahahhaha iPhone 14 Pro is on its way!</span>
                          <div class="o-date-time">
                            <span class="order-date">23 July 2023</span>
                            <span class="order-time">7:20 AM</span>
                          </div>
                        </div>
                      </div>
                      <hr class="notif-hr">
                      <div class="notification-details">
                        <img src="https://assets.florista.ph/uploads/product-pics/1674804112_5022.png" alt="Product Image">
                        <div class="text-content">
                          <span class="order-status">Order out for delivery!</span>
                          <span class="order-description">Your order of a new hahahahhaha iPhone 14 Pro is on its way!</span>
                          <div class="o-date-time">
                            <span class="order-date">23 July 2023</span>
                            <span class="order-time">7:20 AM</span>
                          </div>
                        </div>
                      </div>
                      <hr class="notif-hr">
                      <div class="notification-details">
                        <img src="https://assets.florista.ph/uploads/product-pics/1674804112_5022.png" alt="Product Image">
                        <div class="text-content">
                          <span class="order-status">Order out for delivery!</span>
                          <span class="order-description">Your order of a new hahahahhaha iPhone 14 Pro is on its way!</span>
                          <div class="o-date-time">
                            <span class="order-date">23 July 2023</span>
                            <span class="order-time">7:20 AM</span>
                          </div>
                        </div>
                      </div>
                      <hr class="notif-hr">
                      <div class="notification-details">
                        <img src="https://assets.florista.ph/uploads/product-pics/1674804112_5022.png" alt="Product Image">
                        <div class="text-content">
                          <span class="order-status">Order out for delivery!</span>
                          <span class="order-description">Your order of a new hahahahhaha iPhone 14 Pro is on its way!</span>
                          <div class="o-date-time">
                            <span class="order-date">23 July 2023</span>
                            <span class="order-time">7:20 AM</span>
                          </div>
                        </div>
                      </div>
                      <hr class="notif-hr">
                      
                    </div>
                  </div>
                </li>
              </a>
              <li class="nav-item">
                <form class="form-inline my-2 my-lg-0">
                  <a href="search_results.html"><i class="fa fa-search"></i></a>
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
                <?php
                    $profileImage = !empty($user['profile_img']) ? $user['profile_img'] : '../php/images/default.jpg'; 
                    echo '<img src="' . $profileImage . '" alt="' . $user['last_name'] . '" class="user-image">';
                 ?>
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
            <a href="product_order.html">
              <div class="card">
                <p class="num-cart1">1 </p>
                <i class="bi bi-handbag"></i>
                  <span class="label">Orders</span>
              </div>
            </a>
            <a href="service_order.html">
              <div class="card">
                  <p class="num-cart1">1 </p>
                  <i class="bi bi-person-plus"></i>
                    <span class="label">Services</span>
              </div>
            </a>
            <a href="service_process.html">
              <div class="card">
                <p class="num-cart1">1 </p>
                <i class="bi bi-gear"></i>
                  <span class="label">Processing</span>
              </div>
            </a>
            <a href="service_intransit.html">
              <div class="card">
                <p class="num-cart1">1 </p>
                <i class="bi bi-truck"></i>
                  <span class="label">In Transit</span>
              </div>
            </a>
            <a href="service_completed.html">
              <div class="card  ">
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
        <a href="arranger_shop.html">
          <div class="card2">
            <i class="bi bi-shop"></i>
            <span class="label">Shop</span>
          </div>
        </a>
        <a href="arranger_transaction_history.html">
          <div class="card2">
            <i class="bi bi-file-earmark-text"></i>
            <span class="label">Transaction History</span>
          </div>
        </a>
        <a href="arranger_total_income.html">
          <div class="card2">
            <i class="bi bi-file-bar-graph"></i>
            <span class="label">Reports</span>
          </div>
        </a>
      </div>
      
      <div class="container1">
        <a href="arranger_subscription.html">
          <div class="card2">
            <i class="bi bi-credit-card"></i>
            <span class="label">Subscription</span>
          </div>
        </a>
        <a href="../common/feedbacks.html">
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
</body>

</html>
