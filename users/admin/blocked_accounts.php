<?php
include('checksession.php'); 
include('../php/dbhelper.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'unblock_shop') {
   $shop_id = $_POST['shop_id'];
   unblock_shop($shop_id);
}

function unblock_shop($shop_id) {
   $conn = dbconnect();
   $sql = "UPDATE shops SET status = '' WHERE shop_id = :shop_id";

   try {
       $stmt = $conn->prepare($sql);
       $stmt->bindParam(':shop_id', $shop_id, PDO::PARAM_INT);
       $stmt->execute();
       $conn = null;
       
       echo "Shop Unblocked successfully";
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

function getBlockedShopsAndOwners() {
   $conn = dbconnect();
   $sql = "SELECT s.*, u.* 
           FROM shops s
           LEFT JOIN users u ON s.owner_id = u.user_id
           WHERE s.status = 'Blocked'";

   try {
       $stmt = $conn->prepare($sql);
       $stmt->execute();
       $blockedShops = $stmt->fetchAll(PDO::FETCH_ASSOC);
       $conn = null;
       return $blockedShops;
   } catch (PDOException $e) {
       echo "Error: " . $e->getMessage();
       $conn = null;
       return [];
   }
}




function get_user_details($user_id) {
   $conn = dbconnect();
   // Assuming your users table has 'first_name' and 'last_name' columns
   $sql = "SELECT first_name, last_name FROM users WHERE user_id = :user_id";

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
$filtered_blocked = getBlockedShopsAndOwners();

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
               <img src='https://media.istockphoto.com/id/517528555/photo/trendy-young-man-smiling-on-white-background.jpg?s=1024x1024&w=is&k=20&c=FJijfaHuhjDH_byYfFku4oclIL5oepIO5ZCA4y_iav0=' alt="Admin Profile">
               <h6>Dan Mark</h6>
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
               <a href="reported_accounts.php">
                  <i class="fa fa-user-times" aria-hidden="true"></i>
                  <span class="links_name">Reported Accounts</span>
               </a>
            </li>
            <li>
               <a href="blocked_accounts.php"  class="active">
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
                         <div class="table-container" style="height:700px">
                          <table class="table" id="myTable">
                              <thead style="text-align: center;">
                                 <tr class="title" style="text-align: center;">
                                    <th scope="col" class="px-5" style="text-align: center;">Name</th>
                                    <th scope="col" class="px-5" style="text-align: center;">Shop Name</th>
                                    <th scope="col" class="px-5" style="text-align: center;">Role</th>
                                    <th scope="col" class="px-5" style="text-align: center;">Contact No.</th>
                                    <th scope="col" class="px-5" style="text-align: center;">Address</th>
                                    <th scope="col" class="px-5" style="text-align: center;">Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php
                              foreach ($filtered_blocked as $blocked) {            
                       
                         // Fetch shop details
                         $FullName = $blocked['first_name'] . ' ' . $blocked['last_name'];


                           echo '<tr class="name" style="text-align: center;">';
                           echo '<td class="px-5 py-2" style="width:300px;">' . $FullName . '</td>';
                           echo '<td class="px-5 py-2" style="width:300px;">' . $blocked['shop_name'] . '</td>';
                           echo '<td class="px-5 py-2" style="width:200px;">' . $blocked['role']. '</td>';
                           echo '<td class="px-5 py-2">' . $blocked['phone'] . '</td>';
                           echo '<td class="px-5 py-2">' . $blocked['address'] . '</td>';
                           echo '<td class="button py-2" style="min-width: 240px;">';
                           echo '<button class="btn dib" onclick="unblockShop(' . $blocked['shop_id'] . ')">Unblock</button>';
                           echo '</a>';
                           echo '</td>';
                           echo '</tr>';
                        }
                        ?>

                              </tbody>
                           </table>
                        </div>
                     </div>
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
         <script>
         function unblockShop(shopId) {
            if(confirm("Are you sure you want to unblock this shop?")) {
               $.ajax({
                     url: 'blocked_accounts.php', // Adjusted URL
                     type: 'POST',
                     data: { 'shop_id': shopId, 'action': 'unblock_shop' },
                     success: function(response) {
                        alert("Shop has been unblocked successfully.");
                        location.reload(); // Reloads the current page
                     },
                     error: function(xhr, status, error) {
                        console.error("Error blocking shop:", error);
                     }
               });
            }
         }
         </script>

     
     
   </body>
</html>