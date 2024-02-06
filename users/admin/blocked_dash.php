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
            WHERE s.status = 'blocked'";

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
                     <div class="recent-sales box">
                         <div class="table-container" style="height:700px">
                          <table class="table" id="myTable">
                              <thead style="text-align: center;">
                                 <tr class="title " style="text-align: center;">
                                    <th scope="col" class="px-5" style="text-align: center;">Name</th>
                                    <th scope="col" class="px-5" style="text-align: center;">Role</th>
                                    <th scope="col" class="px-5" style="text-align: center;">Shop Name</th>
                                    <th scope="col" class="px-5" style="text-align: center;">Phone</th>
                                    <th scope="col" class="px-5" style="text-align: center;">Address</th>
                                    <th scope="col" class="px-5" style="text-align: center;">Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                              <?php
                                    foreach ($filtered_reports as $report) {
                                        // Assuming you have user details fetch function like get_user_details()
                                        $complainant = get_user_details($report['complainant_id']);
                                        $complainantFullName = $complainant['first_name'] . ' ' . $complainant['last_name'];

                                        $defendant = get_shop_details($report['defendant_id']); 
                                        $shopName = isset($defendant['shop_name']) ? $defendant['shop_name'] : 'N/A';
                                                                           
                                        
                                        
                                        
                                        if (isset($defendant['owner_id'])) {
                                            // You can access user information using $defendant['owner_id']
                                            $ownerId = $defendant['owner_id'];
                                            
                                            // Assuming you have a function to fetch user details by user_id
                                            $ownerDetails = get_user_details($ownerId);
                                            $ownerFullName = isset($ownerDetails['first_name']) && isset($ownerDetails['last_name']) ? $ownerDetails['first_name'] . ' ' . $ownerDetails['last_name'] : 'N/A';

                                        
                                        }
                                        
                                        echo '<tr class="name" style="text-align: center;">';
                                        echo '<td class="px-5 py-2" style="width: 300px;">' . htmlspecialchars($ownerFullName) . '</td>';
                                        echo '<td class="px-5 py-2" style="width: 300px;">' . htmlspecialchars($ownerDetails['role']) . '</td>';
                                        echo '<td class="px-5 py-2" style="width: 300px;">' . htmlspecialchars($shopName) . '</td>';
                                        echo '<td class="px-5 py-2" style="width: 300px;">' . htmlspecialchars($ownerDetails['phone']) . '</td>';
                                        echo '<td class="px-5 py-2" style="width: 300px;">' . htmlspecialchars($ownerDetails['address']) . '</td>';
                                        echo '<td class="button py-2" style="min-width: 240px;">';
                                        echo '<button class="btn dib"  onclick="deleteShop(' . $report['defendant_id'] . ')">Delete</button>';
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
function deleteShop(shopId) {
    if(confirm("Are you sure you want to delete this shop and its owner?")) {
        $.ajax({
            url: 'delete.php',
            type: 'POST',
            data: { 'shop_id': shopId, 'action': 'delete' },
            success: function(response) {
                alert(response);
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error("Error deleting shop:", error);
            }
        });
    }
}
</script>


   </body>
</html> ]