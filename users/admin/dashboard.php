<?php
include('checksession.php'); 
include('../php/dbhelper.php'); 
if (isset($_SESSION["user_id"])) {
   $user_id = $_SESSION["user_id"];
   $users = get_record('users','user_id',$user_id);
   $user = fetchAllExceptAdmin("users");

}
$pdo = dbconnect();
try {
    // Total Accounts
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role != 'admin'");
    $totalAccounts = $stmt->fetchColumn();    

    // Total Seller/Arranger Accounts
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'seller' OR role = 'arranger'");
    $totalSellers = $stmt->fetchColumn();

    // Total Customer Accounts
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'");
    $totalCustomers = $stmt->fetchColumn();

    // Total Subscribed Accounts
    $stmt = $pdo->query("SELECT COUNT(*) FROM subscription");
    $totalSubscribed = $stmt->fetchColumn();

    // Count the number of shops with status 'reported'
   $stmt = $pdo->prepare("SELECT COUNT(*) FROM shops WHERE status = :status");
   $stmt->execute(['status' => 'reported']);
   $countReportedShops = $stmt->fetchColumn();

   $stmt = $pdo->prepare("SELECT COUNT(*) FROM shops WHERE status = :status");
   $stmt->execute(['status' => 'blocked']);
   $countBlockedShops = $stmt->fetchColumn();


} catch (PDOException $e) {
    // Handle exception
    echo "Database error: " . $e->getMessage();
    die();
}
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="UTF-8">
      <title>Dashboard</title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
      <!-- Boxicons CDN Link -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="../../css/admin.css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
               <a href="dashboard.php" class="active">
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
            <a href="change_pass.php?email=<?php echo urlencode($users["email"]); ?>">
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
      <section class="home-section">
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
                                             <img class="pull-left m-r-10 avatar-img"
                                                src="images/avatar/3.jpg" alt="" />
                                             <div class="notification-content">
                                                <small class="notification-timestamp pull-right">02:34
                                                PM</small>
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
         <div class="content-wrap">
            <div class="main">
               <div class="container-fluid">
                  <div class="row pl-xl-5 pr-xl-5">
                     <div class="col-lg-4 pl-xl-5 pr-xl-5 ">
                        <div class="card">
                           <div class="stat-widget-one">
                              <div class="stat-icon dib">
                                 <i class="bi bi-people"></i>
                              </div>
                              <div class="stat-content dib">
                                 <div class="stat-text">Total Accounts</div>
                                 <div class="stat-digit"><?php echo $totalAccounts;?></div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-4 pl-xl-5 pr-xl-5">
                        <div class="card">
                           <div class="stat-widget-one">
                              <div class="stat-icon dib"><i class="bi bi-person-gear"></i>
                              </div>
                              <div class="stat-content dib">
                                 <div class="stat-text">Seller / Worker Accounts</div>
                                 <div class="stat-digit"><?php echo $totalSellers;?></div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-4 pl-xl-5 pr-xl-5">
                        <div class="card">
                           <div class="stat-widget-one">
                              <div class="stat-icon dib"><i class="bi bi-person-square"></i>
                              </div>
                              <div class="stat-content dib">
                                 <div class="stat-text">Customer Accounts</div>
                                 <div class="stat-digit"><?php echo $totalCustomers;?></div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-4 pl-xl-5 pr-xl-5 mt-5">
                        <div class="card">
                           <div class="stat-widget-one">
                              <div class="stat-icon dib"><i class="bi bi-person-vcard"></i>
                              </div>
                              <div class="stat-content dib">
                                 <a href="subscriptions.php">
                                 <div class="stat-text">Subscribed Accounts</div>
                                 <div class="stat-digit"><?php echo $totalSubscribed;?></div>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-4 pl-xl-5 pr-xl-5 mt-5">
                        <div class="card">
                           <div class="stat-widget-one">
                              <div class="stat-icon dib"><i class="bi bi-person-slash"></i>
                              </div>
                              <div class="stat-content dib">
                                 <a href="blocked_dash.php">
                                    <div class="stat-text">Blocked Accounts</div>
                                    <div class="stat-digit"><?php echo  $countBlockedShops;?></div>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-4 pl-xl-5 pr-xl-5 mt-5">
                        <div class="card">
                           <div class="stat-widget-one">
                              <div class="stat-icon dib"><i class="bi bi-person-x"></i>
                              </div>
                              <div class="stat-content dib">
                              <a href="reported_dash.php">
                                 <div class="stat-text">Reported Accounts</div>
                                 <div class="stat-digit"> <?php echo  $countReportedShops;?></div>
                              </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
      </section>
      
      </div>
      </div>
   </body>
</html>