<?php
include('checksession.php'); 
include('../php/dbhelper.php');

if (isset($_SESSION["user_id"])) {
   $user_id = $_SESSION["user_id"];
   $users = get_record('users','user_id',$user_id);
}

// Establish database connection using dbconnect() function
$conn = dbconnect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the new password and confirm password from the form submission
    $newPassword = $_POST["password"];
    $confirmPassword = $_POST["c_password"];

    // Check if the new password matches the confirm password
    if ($newPassword === $confirmPassword) {
        $hashedPassword = md5($newPassword);

        // Retrieve the email from the session variable
        $email = $_SESSION["email"];

        // Update the user's password using PDO prepared statement
        $stmt = $conn->prepare("UPDATE users SET password = :password WHERE email = :email");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            // Password updated successfully

            // Retrieve the user's role from the session variable
            $userRole = $_SESSION["role"];

            // Redirect based on the user's role
            if ($userRole == "admin") {
                header("Location: admin/index.php");
            } else {
                header("Location: login.php");
            }
            exit();
        } else {
            // Error updating password, handle it accordingly
            echo "Error updating password.";
        }
    } else {
        // Passwords do not match, show an error message or handle it accordingly
        echo "Passwords do not match.";
    }
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
        .password-input-container {
            position: relative;
         }

         .toggle-password {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
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
                                    <input type="text" style="height:50px;" class="form-control form-input" id="myInput"  placeholder="Search . . .">
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
                     <div class="container-fluid mt-5">
                        <div class="row fw-semibold">
                            <h4>Enter New Password</h4>
                            <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                            <div class="error-text"></div>
                                <div class="form-group">
                                    <div class="password-input-container mt-4">
                                    <input type="password" class="form-control" name="password" id="password" placeholder="New Password" required>
                                    <span class="toggle-password" id="togglePassword">
                                        <i class="bi bi-eye-slash"></i>
                                    </span>
                                </div>
                                    <br>
                                    <div class="password-input-container mt-4">
                                        <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Confirm New Password" required>
                                        <span class="toggle-password" id="togglePassword">
                                            <i class="bi bi-eye-slash"></i>
                                        </span>
                                    </div>
                                    <br><br>
                                    <div class="button">
                                        <input type="submit" class="btn btn-primary w-100" name="submit" value="Save">
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
      </div>   

      <script src="../js/show-hide-pass.js"></script>
                    </body>
                    </html>