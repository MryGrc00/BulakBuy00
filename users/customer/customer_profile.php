<?php

session_start();
include '../php/dbhelper.php';
if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $users = get_record('users','user_id',$user_id);
}


?>

<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Profile</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/user_profile.css">
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
                        <a href="cart.html">
                            <li class="cart">
                                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                <p class="num-cart">1 </p>
                            </li>
                        </a>
                        <li class="nav-item">
                            <form class="form-inline my-2 my-lg-0">
                                <a href=""><i class="fa fa-search"></i></a>
                                <input type="text"  class="form-control form-input" placeholder="Search">
                                <a href="customer_home.php" onclick="goBack()">
                                    <i class="back fa fa-angle-left" aria-hidden="true"></i>
                                    <div id="search-results">User Profile</div>
                                  </a>
                                  
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <main class="main">
        <div class="user-info">
                <?php
                    $profileImage = !empty($users['profile_img']) ? $users['profile_img'] : '../php/images/default.jpg'; 
                    echo '<img src="' . $profileImage . '" alt="' . $users['last_name'] . '" class="user-image">';
                 ?>
                <div class="user-details">
                    <div class="user-name">
                    <?php echo $users['first_name'] . ' ' . $users['last_name']; ?> <a href="edit_profile.php?user_id=<?php echo $users['user_id']; ?>"><i class="bi bi-pencil-square"></i></a>
                    </div>
                    <div class="username">
                        <p>@<?php echo $users['username']; ?></p>
                    </div>
                </div>
            </div>
            <section>
                <div class="container">
                    <a href="customer_order.php">
                        <div class="card">
                            <p class="num-cart1">1 </p>
                            <i class="bi bi-handbag"></i>
                            <span class="label">Orders</span>
                        </div>
                    </a>
                    <a href="customer_request.php">
                        <div class="card">
                        <?php
                        $OrderCount = get_pending_service_details_count_arranger('servicedetails', 'services','users', $user_id);
                        ?>
                        <?php if ($OrderCount > 0): ?>
                            <p class="num-cart1"><?php echo $OrderCount; ?></p>
                        <?php endif; ?>                              
                        <i class="bi bi-person-plus"></i>
                            <span class="label">Services</span>
                        </div>
                    </a>
                    <a href="customer_process.php">
                        <div class="card">
                        <?php
                        $processCount = get_process_service_details_count_arranger('servicedetails', 'services','users', $user_id);
                        ?>
                        <?php if ($processCount > 0): ?>
                            <p class="num-cart1"><?php echo $processCount; ?></p>
                        <?php endif; ?> 
                            <i class="bi bi-gear"></i>
                            <span class="label">Processing</span>
                        </div>
                    </a>
                    <a href="customer_intransit.php">
                        <div class="card">
                        <?php
                        $intransitCount = get_intransit_service_details_count_arranger('servicedetails', 'services','users', $user_id);
                        ?>
                        <?php if ($intransitCount > 0): ?>
                            <p class="num-cart1"><?php echo $intransitCount; ?></p>
                        <?php endif; ?> 
                            <i class="bi bi-truck"></i>
                            <span class="label">In Transit</span>
                        </div>
                    </a>
                    <a href="customer_completed.php">
                        <div class="card  ">
                        <?php
                        $completedCount = get_completed_service_details_count_arranger('servicedetails', 'services','users', $user_id);
                        ?>
                        <?php if ($completedCount > 0): ?>
                            <p class="num-cart1"><?php echo $completedCount; ?></p>
                        <?php endif; ?> 
                            <i class="bi bi-bag-check"></i>
                            <span class="label">Completed</span>
                        </div>
                    </a>
                </div>
                <div class="vertical-container">
                <a href="../forgot_password.php">
                        <div class="link-content">
                            <i class="bi bi-key"></i>
                            <span class="label1">Change Password</span>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="customer_transaction_history.html">
                        <div class="link-content">
                            <i class="bi bi-clock-history"></i>
                            <span class="label1">Transaction History</span>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="#">
                        <div class="link-content">
                            <i class="bi bi-question-circle"></i>
                            <span class="label1">Help</span>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="../php/logout.php?logout_id=<?php echo $users['user_id']; ?>">
                        <div class="link-content">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="label1">Logout</span>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </section>
        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            function goBack() {
                window.history.back();
            }
          </script>
    </body>
</html>