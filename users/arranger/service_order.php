<?php

session_start();
include '../php/dbhelper.php';
$pdo = dbconnect();
if (isset($_SESSION["user_id"]) && isset($_SESSION["role"])) {
    $user_id = $_SESSION["user_id"];
    $role = $_SESSION["role"];

    $users = get_record_by_user($user_id) ;

    $service_order = get_pending_service_details('servicedetails','services', 'users', $user_id);
}
else {
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
    <title>Services</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/arranger.css">

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
                            <div id="search-results">Services</div>
                            </a>
                            
                        </form>
                    </li>     
                </ul>
            </div>
        </nav><hr class="nav-hr">
    </header>

  <div class="wrapper">
    <?php foreach ($service_order as $order):?>
    <div class="products-card">
        <div class="single-card ">
            <div class="img-area">
                <img src="<?php echo $order["customer_profile"]?>" alt="">
            </div>
            <div class="info">
                <div class="text-left">                    
                
                <h3><?php echo $order["customer_first_name"]. " " . $order["customer_last_name"]; ?></h3>
                    <p class="ad"><?php echo $order["customer_address"]?></p>
                    <div class="o-date-time">
                        <span class="date"><?php echo $order["date"]?></span>
                        <span class="time"><?php echo $order["time"]?></span>
                    </div>
                    <p class="price"><?php echo $order["amount"]?></p>
                </div>
                
                <div class="text-right">
                    <div class="btn-container order">
                    <form method="post" action="update_service_status.php">
                        <input type="hidden" name="service_detail_id" value="<?php echo $order['servicedetails_id']; ?>">
                        <button type="submit" name="action" value="accept" class="service-accept">Accept</button>
                        <button class="service-cancel" type="submit" name="action" value="cancelled">Cancel</button>
                  </form>
                    </div>
                </div>
            </div>
        </div>
       
    
    
      
   
    </div>
    <?php endforeach;?>

  </div>
  <script>
    function goBack() {
        window.history.back();
    }
  </script>
    
</body>
</html>