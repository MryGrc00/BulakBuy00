<?php
include('checksession.php'); 
include('../php/dbhelper.php'); 
if (isset($_SESSION["user_id"])) {
   $user_id = $_SESSION["user_id"];
   $users = get_record('users','user_id',$user_id);
}

$pdo = dbconnect();
try {
    // Prepare and execute the query
    $stmt = $pdo->query("SELECT s.shop_id, s.s_date, s.e_date, s.status, CONCAT(u.first_name, ' ', u.last_name) AS full_name, u.role, u.user_id FROM subscription s JOIN shops sh ON s.shop_id = sh.shop_id JOIN users u ON sh.owner_id = u.user_id WHERE s.status = 'active'");
    $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate days left and add to each subscription
    foreach ($subscriptions as $key => $subscription) {
        $endDate = new DateTime($subscription['e_date']);
        $today = new DateTime();
        $interval = $today->diff($endDate);
        $subscriptions[$key]['days_left'] = $interval->days;
    }

    // Custom sort function
    usort($subscriptions, function($a, $b) {
        return $a['days_left'] <=> $b['days_left'];
    });

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
      <title>Subscriptions</title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
      <!-- Boxicons CDN Link -->
      <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <link rel="stylesheet" href="../../css/admin.css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
      
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
               <a href="subscriptions.php" class="active">
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
      
      <header class="home-section">
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
            <div id="home" class="tab-pane fade in active">
               <div class="sales-boxes py-5  ">
                  <div class="recent-sales box">
                        <div class="table-container" style="height:700px">
                        <table class="table" id="myTable">
                           <thead style="text-align: center;">
                              <tr class="title" style="text-align: center;">
                                 <th scope="col" class="px-5" style="text-align: center;">Name</th>
                                 <th scope="col" class="px-5" style="text-align: center;">Role</th>
                                 <th scope="col" class="px-5" style="text-align: center;">Start Date</th>
                                 <th scope="col" class="px-5" style="text-align: center;">End Date</th>
                                 <th scope="col" class="px-5" style="text-align: center;">Days Left</th>
                                 <th scope="col" class="px-5" style="text-align: center;">Status</th>
                                 <th scope="col" class="px-5" style="text-align: center;">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                           <tbody>
                              <?php foreach ($subscriptions as $subscription): ?>
                                 <tr class="name" style="text-align: center;">
                                       <td class="px-5 py-3" style="width:300px;"><?php echo htmlspecialchars($subscription['full_name']); ?></td>
                                       <td class="px-5 py-3" style="width:200px;"><?php echo htmlspecialchars($subscription['role']); ?></td>
                                       <td class="px-5 py-3" style="width:200px;"><?php echo htmlspecialchars($subscription['s_date']); ?></td>
                                       <td class="px-5 py-3" style="width:200px;"><?php echo htmlspecialchars($subscription['e_date']); ?></td>
                                       <td class="px-5 py-2" style="width:200px;"><?php echo $subscription['days_left']; ?></td>
                                       <td class="px-5 py-3"><?php echo htmlspecialchars($subscription['status']); ?></td>
                                       <td class="button py-2 " style="min-width: 240px;">
                                       <a href="../chat.php?user_id=<?php echo $subscription['user_id']; ?>">
                                             <button class="btn dib">Message</button>
                                          </a>
                                       </td>
                                 </tr>
                              <?php endforeach; ?>
                           </tbody>



                        </table>
                        <form action="reports_pdf.php" method="post">
                        <div class="form-group">
                              <label for="month">Select Month:</label>
                              <select class="form-control" id="month" name="month">
                                 <?php
                                 // Loop to generate options for months
                                 for ($i = 1; $i <= 12; $i++) {
                                    $monthName = date("F", mktime(0, 0, 0, $i, 10));
                                    echo "<option value='$i'>$monthName</option>";
                                 }
                                 ?>
                              </select>
                        </div>
                        <div class="form-group">
                              <label for="year">Select Year:</label>
                              <select class="form-control" id="year" name="year">
                                 <?php
                                 // Loop to generate options for years
                                 $currentYear = date('Y');
                                 for ($i = $currentYear; $i >= $currentYear - 10; $i--) {
                                    echo "<option value='$i'>$i</option>";
                                 }
                                 ?>
                              </select>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Download</button>
                     </form>
                     </div>
                  </div>
               </div>
            </div>
         </div> 
      <script>
         function myFunction() {
           var input, filter, table, tr, td, i, j, txtValue;
           input = document.getElementById("myInput");
           filter = input.value.toUpperCase();
           table = document.getElementById("myTable");
           tr = table.getElementsByTagName("tr");
           
           for (i = 0; i < tr.length; i++) {
             if (tr[i].classList.contains("title")) {
               continue; // Skip the header row
             }
         
             var rowVisible = false; // To keep track of row visibility
             
             // Loop through all <td> elements in the current row
             for (j = 0; j < tr[i].cells.length; j++) {
               td = tr[i].cells[j];
               if (td) {
                 txtValue = td.textContent || td.innerText;
                 if (txtValue.toUpperCase().indexOf(filter) > -1) {
                   rowVisible = true; // Set row as visible if any cell contains the filter text
                 }
               }
             }
             
             // Set row display property based on rowVisible
             if (rowVisible) {
               tr[i].style.display = "";
             } else {
               tr[i].style.display = "none";
             }
           }
         }
         </script>
     
     
   </body>
</html>