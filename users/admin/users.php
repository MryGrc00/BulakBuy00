<?php
include('../php/dbhelper.php'); // Include the dbhelper file to use its functions.
$users = all_record("users");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="UTF-8">
      <title>Users Account</title>
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
               <a href="dashboard.html">
               <i class="fa fa-home" aria-hidden="true"></i>
               <span class="links_name">Dashboard</span>
               </a>
            </li>
            <li>
               <a href="users.php"  class="active">
               <i class="fa fa-user-circle-o" aria-hidden="true"></i>
               <span class="links_name">Users</span>
               </a>
            </li>
            <li>
               <a href="reported_accounts.html">
               <i class="fa fa-user-times" aria-hidden="true"></i>
               <span class="links_name">Reported Accounts</span>
               </a>
            </li>
            <li>
               <a href="blocked_accounts.html">
               <i class="fa fa-ban" aria-hidden="true"></i>
               <span class="links_name">Blocked Accounts</span>
               </a>
            </li>
            <li>
               <a href="subscriptions.html">
               <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
               <span class="links_name">Subscriptions</span>
               </a>
            </li>
            <li>
               <a href="review_accounts.html">
               <i class="fa fa-users" aria-hidden="true"></i>
               <span class="links_name">Review Accounts</span>
               </a>
            </li>
            <li>
               <a href="transaction_history.html">
               <i class="fa fa-line-chart" aria-hidden="true"></i>
               <span class="links_name">Transaction History</span>
               </a>
            </li>
            <li>
            <a href="../php/logout.php?logout_id=<?php echo $user['user_id']; ?>">
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
            <div class="sales-boxes py-5  ">
               <div class="recent-sales box">
                  <div class="table-container" style="height:700px">
                     <table class="table" id="myTable">
                        <thead style="text-align: center;">
                           <tr class="title" style="text-align: center;">
                              <th scope="col" class="px-5" style="text-align: center;">IDNO</th>
                              <th scope="col" class="px-5" style="text-align: center;">Name</th>
                              <th scope="col" class="px-5" style="text-align: center;">Role</th>
                              <th scope="col" class="px-5" style="text-align: center;">Contact No.</th>
                              <th scope="col" class="px-5" style="text-align: center;">Address</th>
                              <th scope="col" class="px-5" style="text-align: center;">Status</th>
                           </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Fetch user data from the database
                        $users = all_record("users");

                        // Loop through the user data and display it in the table
                        foreach ($users as $user) {
                              echo '<tr class="name" style="text-align: center;">';
                              echo '<td class="px-4 py-3" style="min-width: 100px;">' . $user['user_id'] . '</td>';
                              echo '<td class="px-5 py-3" style="width: 400px; text-align: center;">' . $user['first_name'] . ' ' . $user['last_name'] . '</td>';
                              echo '<td class="px-5 py-3" style="min-width: 150px; text-align: center;">' . ucfirst($user['role']). '</td>';
                              echo '<td class="px-5 py-3" style="min-width: 200px; text-align: center;">' . $user['phone'] . '</td>';
                              echo '<td class="px-5 py-3 text-center" style="min-width: 300px;">' . $user['address'] . ', ' . $user['zipcode'] . '</td>';
                              echo '<td class="px-5 py-3" style="min-width: 150px; text-align: center;">' . $user['status'] . '</td>';
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