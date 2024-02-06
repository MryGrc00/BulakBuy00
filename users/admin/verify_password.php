<?php
include('checksession.php'); 
include('../php/dbhelper.php');

if (isset($_SESSION["user_id"])) {
   $user_id = $_SESSION["user_id"];
   $users = get_record('users','user_id',$user_id);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'block_shop') {
   $shop_id = $_POST['shop_id'];
   block_shop($shop_id);
}

function block_shop($shop_id) {
   $conn = dbconnect();
   $sql = "UPDATE shops SET status = 'blocked' WHERE shop_id = :shop_id";

   try {
       $stmt = $conn->prepare($sql);
       $stmt->bindParam(':shop_id', $shop_id, PDO::PARAM_INT);
       $stmt->execute();
       $conn = null;
       
       echo "Shop blocked successfully";
   } catch (PDOException $e) {
       echo "Error: " . $e->getMessage();
       $conn = null;
   }
   exit;
}
function get_shop_details($shop_id) {
   $conn = dbconnect();
   // Assuming your shop table has columns like 'shop_id', 'shop_name', etc.
   $sql = "SELECT * FROM shops WHERE shop_id = :shop_id";

   try {
       $stmt = $conn->prepare($sql);
       // Bind the shop_id parameter to protect against SQL injection
       $stmt->bindParam(':shop_id', $shop_id, PDO::PARAM_INT);
       $stmt->execute();

       // Fetch the shop details
       $shop_details = $stmt->fetch(PDO::FETCH_ASSOC);
       $conn = null;

       return $shop_details;
   } catch (PDOException $e) {
       echo "Error: " . $e->getMessage();
       $conn = null;
       return null;
   }
}

function get_filtered_reports() {
    $conn = dbconnect();
    $sql = "SELECT r.*, s.shop_id, s.status FROM reports r
            LEFT JOIN shops s ON r.defendant_id = s.shop_id
            WHERE s.status = 'Reported'";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;
        return $reports;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        $conn = null;
        return [];
    }
}
function get_user_details($user_id) {
   $conn = dbconnect();
   // Assuming your users table has 'first_name' and 'last_name' columns
   $sql = "SELECT user_id, first_name, last_name, role, phone, address FROM users WHERE user_id = :user_id";

   try {
       $stmt = $conn->prepare($sql);
       $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
       $stmt->execute();

       $user_details = $stmt->fetch(PDO::FETCH_ASSOC);
       $conn = null;

       return $user_details;
   } catch (PDOException $e) {
       echo "Error: " . $e->getMessage();
       $conn = null;
       return null;
   }
}


$filtered_reports = get_filtered_reports();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="UTF-8">
      <title>Reported Accounts</title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
      <!-- Boxicons CDN Link -->
      <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="../../css/admin.css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
      
   </head>
   <style>
  
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");


        form .input-field {
        flex-direction: row;
        column-gap: 10px;
        }
        .input-field input {
        height: 45px;
        width: 42px;
        border-radius: 6px;
        outline: none;
        font-size: 1.125rem;
        text-align: center;
        border: 1px solid #ddd;
        }
        .input-field input:focus {
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
        }
        .input-field input::-webkit-inner-spin-button,
        .input-field input::-webkit-outer-spin-button {
        display: none;
        }
        form button {
        margin-top: 25px;
        width: 100%;
        color: #fff;
        font-size: 1rem;
        border: none;
        padding: 9px 0;
        cursor: pointer;
        border-radius: 6px;
        pointer-events: none;
        background: #6e93f7;
        transition: all 0.2s ease;
        }
        form button.active {
        background: #4070f4;
        pointer-events: auto;
        }
        form button:hover {
        background: #0e4bf1;
        }

        .error-message {
        color: red;
        font-weight: bold;
        margin-top: 10px;
        display: none;
        }

        .error-message.visible {
        display: block;
        }
        .verify{
            color:#666;
            font-size: 17px;
            text-align: center;
            letter-spacing: 0.1rem;
            margin-top: 15px;
            font-weight: 500;
        }
        .btn{
            background-color: #65A5A5;
            color:white;
            width:440px;
            padding:7px;
            border-radius:10px;
            margin-top: 10px;
            letter-spacing: 0.1rem;
            font-size: 16px;
        }
        .btn:hover{
            color:#fefefe;
        }
      </style>
   </head>
   <body>   
   </style>
   <body>
      <!---Sidebar-->
      <div class="sidebar">
         <div class="d-flex flex-column ">
            <div class="logo align-items-center  text-center mt-5">
               <img src='../php/images/logo.png' alt="BulakBuy Logo">
               <hr>
            </div>
            <div class="profile">
            <?php
                    $profileImage = !empty($users['profile_img']) ? $users['profile_img'] : '../php/images/default.jpg'; 
                    echo '<img src="' . $profileImage . '" alt="' . $users['last_name'] . '" class="user-image">';
                 ?>               
                 <br><?php echo $users['first_name'] . ' ' . $users['last_name']; ?> 
               <a href="edit_profile.php?user_id=<?php echo $users['user_id']; ?>"><i class="bi bi-pencil-square"></i></a>
            </div>
         </div>
         <ul class="nav-links align-items-center  text-center ">
            <li>
               <a href="dashboard.php">
               <i class="fa fa-home" aria-hidden="true"></i>
               <span class="links_name">Dashboard</span>
               </a>
            </li>
            <li>
               <a href="users.php">
               <i class="fa fa-user-circle-o" aria-hidden="true"></i>
               <span class="links_name">Users</span>
               </a>
            </li>
            <li>
               <a href="admins.php">
               <i class="bi bi-person-vcard"></i>
               <span class="links_name">Admins</span>
               </a>
            </li>
            <li>
               <a href="reported_accounts.php">
               <i class="fa fa-user-times" aria-hidden="true"></i>
               <span class="links_name">Reported Accounts</span>
               </a>
            </li>
            <li>
               <a href="blocked_accounts.php">
               <i class="fa fa-ban" aria-hidden="true"></i>
               <span class="links_name">Blocked Accounts</span>
               </a>
            </li>
            <li>
               <a href="subscriptions.php">
               <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
               <span class="links_name">Subscriptions</span>
               </a>
            </li>
            <li>
               <a href="reports.php">
               <i class="fa fa-users" aria-hidden="true"></i>
               <span class="links_name">Reports</span>
               </a>
            </li>
            <li>
               <a href="transaction_history.php">
               <i class="fa fa-line-chart" aria-hidden="true"></i>
               <span class="links_name">Transaction History</span>
               </a>
            </li>
            <li>
               <a href="../forgot_password.php">
               <i class="fa fa-key" aria-hidden="true"></i>
               <span class="links_name">Change Password</span>
               </a>
            </li>
            <li>
               <a href="logout.php">
               <i class="fa fa-sign-out" aria-hidden="true"></i> 
               <span class="links_name">Logout</span>
               </a>
            </li>
         </ul>
      </div>
      
      <div class="home-section">
         <nav class="navbar navbar-expand-lg ">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-lg-12">
                     <div class=" head ">
                        <div class="dropdown dib">
                           <div class="header-icon" data-toggle="dropdown">
                              <i class="fa fa-bell-o" aria-hidden="true"></i>
                              <div class="drop-down dropdown-menu dropdown-menu-right">
                                 <div class="dropdown-content-heading">
                                    <span class="text-left">Recent Notifications</span>
                                 </div>
                                 <div class="dropdown-content-body">
                                    <ul>
                                       <li>
                                          <a href="#">
                                             <img class="pull-left m-r-10 avatar-img" src="#" alt="" />
                                             <div class="notification-content">
                                                <small class="notification-timestamp pull-right">02:34 PM</small>
                                                <div class="notification-heading">Mr. John</div>
                                                <div class="notification-text">5 members joined today </div>
                                             </div>
                                          </a>
                                       </li>
                                       <li class="text-center">
                                          <a href="#" class="more-link">See All</a>
                                       </li>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="dib">
                           <div class="header-icon">
                              <i class="fa fa-commenting-o" aria-hidden="true"></i>
                           </div>
                        </div>
                        <div class="container dib">
                           <div class="row">
                              <div class="col-lg-5">
                                 <div class="form">
                                    <i class="fa fa-search"></i>
                                    <input type="text" style="height:50px;" class="form-control form-input" id="myInput" onkeyup="myFunction()" placeholder="Search . . .">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </nav>
      
         <div class="home-content">
            <div class="tab-content">
               <div id="home" class="tab-pane fade in active">
                  <div class="sales-boxes py-5  ">
                     <div class="recent-sales box text-center">
                     <form id="otp-form" action="../php/verify_otp1.php" method="post">
                     <header>
                        <i class="bx bxs-check-shield"></i>
                    </header>
                    <h4>Enter OTP Code</h4>
                        <div class="error-text"></div>
                        <div class="input-field">
                            <input type="number" maxlength="1"/>
                            <input type="number" maxlength="1" disabled />
                            <input type="number" maxlength="1" disabled />
                            <input type="number" maxlength="1" disabled />
                            <input type="number" maxlength="1" disabled />
                            <input type="number" maxlength="1" disabled />
                        </div>
                        <button type="submit" id="verify-button" class="btn verify" >Verify OTP</button>
                    </form>

                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div> 
      
      <script src="../js/verify_padmin.js"></script>


   </body>
</html> 