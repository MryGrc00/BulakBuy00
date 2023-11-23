<?php
session_start();
include '../php/dbhelper.php';
include '../php/checksession.php';

$pdo = dbconnect();
$userDetails = null;
$serviceDetails = null;
$arrangerDetails = null;

if (isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :userID");
    $stmt->execute(['userID' => $userID]);
    $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userDetails) {
        $userDetails['full_name'] = $userDetails['first_name'] . " " . $userDetails['last_name'];
    } else {
        echo "User details not found.";
    }
}

if (isset($_GET['service_id'])) {
    $serviceID = $_GET['service_id'];
    $stmt = $pdo->prepare("SELECT * FROM services WHERE service_id = :serviceID");
    $stmt->execute(['serviceID' => $serviceID]);
    $serviceDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($serviceDetails && isset($serviceDetails['arranger_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :arrangerID");
        $stmt->execute(['arrangerID' => $serviceDetails['arranger_id']]);
        $arrangerDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        $arrangerDetails['full_name'] = $arrangerDetails['first_name'] . " " . $arrangerDetails['last_name'];

    } else {
        echo "Service details not found or arranger not specified.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $serviceID = $_POST['service_id']; // Get service ID
   $customerID = $_SESSION['user_id']; // Get customer ID from session
   $date = $_POST['date'];
   $time = $_POST['time'];
   $totalAmount = $_POST['total_amount'];
   $status = "pending";

   $stmt = $pdo->prepare("INSERT INTO servicedetails (service_id, customer_id, amount, date, time, status) VALUES (:serviceID, :customerID, :totalAmount, :date, :time, :status)");
   $stmt->execute(['serviceID' => $serviceID, 'customerID' => $customerID, 'totalAmount' => $totalAmount, 'date' => $date, 'time' => $time, 'status' => $status]);
}
?>

<!DOCTYPE html> 
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Service Request</title>
      <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
      <link rel="stylesheet" href="../../css/service_request.css">
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
                        <input type="text"  class="form-control form-input" placeholder="Search" style="text-align:left;padding-left: 15px;font-size: 16px;">
                        <a href="javascript:void(0);" onclick="goBack()">
                           <i class="back fa fa-angle-left" aria-hidden="true"></i>
                           <div id="search-results">Service Request</div>
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
            <div class="column1">
            <div class="location">
               <i class="bi bi-geo-alt"></i>
               <div class="location-info">
                     <p class="name"><?php echo $userDetails['full_name']; ?></p>
                     <p class="number"><?php echo $userDetails['phone']; ?></p>
                     <p class="street"><?php echo $userDetails['address']; ?></p>
                  </div>
            </div>

          
               <div class="cart-container">
                  <div class="border"></div>
               <form id="service-request-form" action="" method="post">
               <input type="hidden" name="service_id" value="<?php echo $serviceID; ?>"> 
                  <div class="datetime-container">
                     <label class="date-label" for="date-input">Schedule</label>
                     <input type="date" id="date-input" name="date" class="datetime-input" />
                   </div>
                   <div class="datetime-container">
                     <label class="date-label"  for="time-input">Time</label>
                     <input type="time" id="time-input" name="time" class="datetime-input" />
                   </div>  
                   <div class="datetime-container">
                     <label class="date-label"  for="time-input">Hours</label>
                     <input type="number" id="hours-input" class="datetime-input" />
                   </div>                 
                   <div class="border"></div>
                     <div class="cart-item">
                        <div class="custom-checkbox" style="margin-top:-25px">
                           <img src="<?php echo $arrangerDetails['profile_img']; ?>" alt="Product 1">
                        </div>
                        <div class="item-details">
                           <h2><?php echo $arrangerDetails['full_name']; ?></h2>
                           <p class="service-location"><?php echo $arrangerDetails['address']; ?></p>
                           <p class="shop-num"><?php echo $arrangerDetails['phone']; ?></p>
                           <p class="price"><?php echo $serviceDetails['service_rate']; ?> / hr</p>
                        </div>
                     <div class="border"></div>
                  </div>
                  
               </div>
            </div>
            <div class="column2">
               <div class="summary-container">
                  <div class="order-summary">
                     <h6 class="order-label">Service Request Summary</h6>
                  </div>
                  <div class="summary-items">
                     <div class="sub-total">
                        <div class="product-price">
                           <p class="product">Service Price</p>
                           <p class="order-price">₱ <?php echo $serviceDetails['service_rate']; ?></p>
                           <br>
                        </div>
                        <div class="service-hours">
                           <p class="hours-label">Hours</p>
                           <p class="hours" id="display-hours"></p>
                           <br>
                        </div>
                        <div class="total-payment">
                           <p class="total">Total</p>
                           <input type="text" id="total_amount" name="total_amount" readonly style="display: none;" />
                           <p class="total-price" id="display-total-price"></p>
                        </div>
                      </div>
                  </div>
                  <div class="button-container">
                     <div class="button-container">
                     <div class="total-info">
                        <p class="total-item">Total</p>
                        <p class="total-price1" id="display-total-price-near-button"></p>
                     </div>
                        <button class="checkout" id="placeOrderBtn">Place Request</button>
                        <!-- Confirmation Modal -->
                        <div id="confirmationModal" class="modal">
                           <div class="modal-content">
                              <p class="confirm-order">Do you want to confirm your request?</p>
                              <p class="confirm-note">Once confirmed, it cannot be canceled.</p>
                              <div class="confirm-btn">
                                <button class="cancel" id="cancelOrderBtn">Cancel</button>
                                <button class="confirm" id="confirmOrderBtn">Confirm</button>
                              </div>
                           </div>
                        </div>
                        </form>
                        <!-- Thank You Modal (Initially hidden) -->
                        <div id="thankYouModal" class="modal">
                           <div class="modal-content">
                              <span class="close" id="closeThankYouModal">&times;</span>
                              <i class="bi bi-check-circle"></i>
                              <h2 class="confirmed">Service Request Confirmed!</h2>
                              <p class="successful">Your request has been placed successfully. Check the status of your service request here. <a href="#">Service Status</a></p>
                              <!-- Continue Shopping Button -->
                              <a href="customer_home.php"><button class="c-shopping" id="continueShoppingBtn">Continue Shopping</button></a>
                           </div>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
         </div>
      </main>
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <script>
         document.addEventListener("DOMContentLoaded", function() {
         const confirmationModal = document.getElementById("confirmationModal");
         const thankYouModal = document.getElementById("thankYouModal");
         
         // Get the buttons to trigger modals
         const placeOrderBtn = document.getElementById("placeOrderBtn");
         const confirmOrderBtn = document.getElementById("confirmOrderBtn");
         const cancelOrderBtn = document.getElementById("cancelOrderBtn");
         const continueShoppingBtn = document.getElementById("continueShoppingBtn"); // Continue Shopping button

         // Show the confirmation modal when the "Place Request" button is clicked
         placeOrderBtn.addEventListener("click", (event) => {
            event.preventDefault(); // Prevent form submission
            confirmationModal.style.display = "block";
         });

         confirmOrderBtn.addEventListener("click", function(event) {
         event.preventDefault();

         // Show the thank you modal
         confirmationModal.style.display = "none";
         thankYouModal.style.display = "block";

         // Perform AJAX form submission
         var formData = new FormData(document.getElementById('service-request-form'));
         fetch('service_request.php', {
               method: 'POST',
               body: formData
         })
         .then(response => response.text())
         .then(data => {
               console.log(data); // You can process the response here

               // Redirect after 10 seconds
               setTimeout(() => {
                  thankYouModal.style.display = "none";
                  window.location.href = 'customer_home.php';
               }, 10000);
         })
         .catch(error => console.error('Error:', error));
      });


         // Close the confirmation modal when the "Cancel" button is clicked
         cancelOrderBtn.addEventListener("click", () => {
            confirmationModal.style.display = "none";
         });

         // Close the thank you modal when the "Continue Shopping" button is clicked
         continueShoppingBtn.addEventListener("click", () => {
            thankYouModal.style.display = "none";
         });

         // Update hours and total amount
         var serviceRate = <?php echo json_encode($serviceDetails['service_rate'] ?? 0); ?>;
         document.getElementById('hours-input').addEventListener('input', function() {
            var hours = parseFloat(this.value);
            var total = hours * serviceRate;

            // Update displayed hours and total amount
            document.getElementById('display-hours').textContent = hours;
            document.getElementById('total_amount').value = total.toFixed(2);
            document.getElementById('display-total-price').textContent = '₱ ' + total.toFixed(2);
            document.getElementById('display-total-price-near-button').textContent = '₱ ' + total.toFixed(2);
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