

<?php
include('checksession.php'); 
include('../php/dbhelper.php');
include_once('../php/mail.php');

if (isset($_SESSION["user_id"]) && ($_GET['email']) ){
    $user_id = $_SESSION["user_id"];
    $users = get_record('users','user_id',$user_id);
    $mail= $_GET['email'];
    $user = fetchAllExceptAdmin("users");
 
// Establish database connection
$conn = dbconnect();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $checkStmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $checkStmt->bindParam(':email', $email);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        try {
            // Generate a new OTP
            $newOTP = generateOTP(); 

            // Update the OTP in the database for the given email
            $updateStmt = $conn->prepare("UPDATE users SET otp = :otp WHERE email = :email");
            $updateStmt->bindParam(':otp', $newOTP);
            $updateStmt->bindParam(':email', $email);
            $updateStmt->execute();

            // Send the new OTP via email using the sendOTP function from mail.php
            if (sendOTP($email, $newOTP)) {
                $_SESSION['email'] = $email; 
                $_SESSION['user_id'] = $user_id; 
                header("Location: verify_password.php");
                exit();
            } else {
                // Failed to send OTP, display error message or handle it accordingly
                echo "Failed to send OTP.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Email does not exist in the database, display error message
        echo "Email does not exist. Please enter a valid email address.";
    }
}

 }
function generateOTP() {
    // Logic to generate a new OTP, for example:
    $otp = rand(100000, 999999);
    return $otp;
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="UTF-8">
      <title>Blocked Accounts</title>
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
      <style>
            .form-control {
            /* Add general styling for form controls here */
            padding: 20px;
            border:none;
            width: 440px;
            background-color: #F5F5F5;
            border-radius:10px;
             letter-spacing: 0.1rem;
            color:#888;
            margin-top: 10px;
            outline: none !important;
            display:inline-block;
        }
        .form-control::placeholder {
            font-size: 15px;
            color:#A0A0A0;
        }
        .form-control:focus {
            border:1px solid #fefefe;
            outline:none;
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
            <a href="change_pass.php?email=<?php echo urlencode($users["email"]); ?>" class="active">
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
                                             <img class="pull-left m-r-10 avatar-img" src="images/avatar/3.jpg" alt="" />
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
                        <div class="dropdown dib">
                           <div class="header-icon" data-toggle="dropdown">
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
            <!-- Your navigation and tabs code -->
         
            <!-- All -->
            <div class="tab-content">
               <div id="home" class="tab-pane fade in active">
                  <div class="sales-boxes py-5  ">
                     <div class="recent-sales box">
                         <div class="container-fluid mt-5 text-center">     
                                    <h2>Enter Your Email</h2>
                                    <div class="error-text"></div>
                                    <div class="success-text"></div>
                                    <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                                    <div class="form-group">
                                        <input type="text" class="form-control bg-light rounded" name="email" id="email" placeholder="Email" value="<?php echo $mail?>" required>
                                    </div>
                                        <div class="form-group">
                                            <br>
                                            <div class="button">
                                                <input type="submit" name="submit" class="btn verify" value="Verify"></button>
                                            </div>
                                            <br>
                                        </div>
                                    </form>                
                            </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>   
                    </body>
                    </html>