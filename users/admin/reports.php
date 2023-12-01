<?php

include('checksession.php'); 
include('../php/dbhelper.php'); // Include the dbhelper file to use its functions.

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
      <style>
        .dropdown2 {
        position: absolute;
        display: inline-block;
        margin-left: 27%; 
        margin-top:-36px; 
        
        }
        .dropdown1 {
        position: absolute;
        display: inline-block;
        margin-left: 47%; 
        margin-top:-36px; 
        
        }
    
    .dropbtn2 {
      padding: 5px;
      border-radius: 10px;
      border: none;
      background: #fff;
      color: #666;
      margin: 0 2px;
      width: auto;
      font-size: 15px;
      cursor: pointer;
      color: #868e96;
    }
    
    .dropbtn:focus{
        border:none;
        outline:none;
    }
    .custom-button {
      background-color: transparent;
      color: gray;
      padding: 5px;
      font-size: 14px;
      border: none;
      cursor: pointer;
      margin: 5px;
      border-radius: 0pc;
    }

    
    .dropdown-content2 {
      display: none;
      position: absolute;
      background-color: rgb(252, 251, 251);
      border-radius: 10px;
      min-width: 152px;
      z-index: 100;
    }
    
    
    .dropdown-content2 .btn {
      padding: 10px;
      text-decoration: none;
      display: block;
      text-align: left;
    }
    
    
    .dropdown2:hover .dropdown-content2 {
      display: block;
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
               <a href="blocked_accounts.php">
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
               <a href="reports.php"  class="active">
               <i class="fa fa-users" aria-hidden="true"></i>
               <span class="links_name">Reports</span>
               </a>
            </li>
            <li>
               <a href="transaction_history.html">
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
            
               <div style="display:flex;margin-top:40px;">
                    <div style="width:580px;height:500px;background-color:white;padding:30px;margin-left:60px;margin-top:40px;border-radius:20px">
                        <p style="color: #868e96;font-size:15px">Subscribers</p>
                        <form id="filterForm1">
                            <label for="monthSelect" onchange="updateChartData()" style="color:#666;font-weight:400">Month:</label>
                            <select id="monthSelect"onchange="updateChartData()" class="btn custom-button month-button">
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>

                            <label for="yearSelect" style="color:#666;font-weight:400;margin-left:10%">Year:</label>
                             <select id="yearSelect1" class="btn custom-button">
                                <?php
                                    for ($year = 2023; $year <= 2030; $year++) {
                                        echo '<option value="' . $year . '">' . $year . '</option>';
                                    }
                                ?>
                            </select>

                            <input type="button" style="background-color:#65A5A5;border:none;color:white;padding:5px 15px;border-radius:10px" value="Apply" onclick="updateChartData()">
                        </form>

                        
                        <p style="width:520px;height:7000px;margin-top:40px"><canvas  id="chartjs_bar"></canvas></p>
                    </div>
                    <div style="width:900px;height:670px;background-color:white;padding:30px;margin-left:60px;margin-top:40px;border-radius:20px">
                        <p style="color: #868e96;font-size:15px">Subscribers</p>
                        <form id="filterForm2">
                            <label for="yearSelect" style="color:#666;font-weight:400">Year:</label>
                            <select id="yearSelect2" class="btn custom-button">
                                    <?php
                                    for ($year = 2023; $year <= 2030; $year++) {
                                        echo '<option value="' . $year . '">' . $year . '</option>';
                                    }
                                    ?>
                                </select>

                                <input type="button" class="year" style="background-color:#65A5A5;border:none;color:white;padding:5px 15px;border-radius:10px" value="Apply" onclick="updateTableData()">
                            </form>

                            <div id="home" class="tab-pane fade in active">
                                <div class="sales-boxes py-5">
                                    <div class="recent-sales box">
                                        <div class="table-container" style="height:700px">
                                            <table class="table" id="myTable">
                                                <!-- Table header -->
                                                <thead style="text-align: center;">
                                                    <tr class="title" style="text-align: center;color:#666;">
                                                        <th scope="col" class="px-5" style="text-align: center;font-weight:500">Date</th>
                                                        <th scope="col" class="px-5" style="text-align: center;font-weight:500">Subscribed</th>
                                                        <th scope="col" class="px-5" style="text-align: center;font-weight:500">Active</th>
                                                        <th scope="col" class="px-5" style="text-align: center;font-weight:500">Expired</th>
                                                    </tr>
                                                </thead>
                                                <!-- Table body -->
                                                <tbody id="tableBody">
                                                    <?php include("update_table_data.php")?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    </div>
               
               </div>
               <div style="display:flex;">
                    <div style="width:580px;height:630px;background-color:white;padding:30px;margin-left:60px;border-radius:20px">
                        <p style="color: #868e96;font-size:15px">Seller Sales</p>
                        <form id="filterForm3">
                            <label for="yearSelect" style="color:#666;font-weight:400">Year:</label>
                            <select id="yearSelect3" class="btn custom-button">
                                    <?php
                                    for ($year = 2023; $year <= 2030; $year++) {
                                        echo '<option value="' . $year . '">' . $year . '</option>';
                                    }
                                    ?>
                                </select>

                                <input type="button" class="year" style="background-color:#65A5A5;border:none;color:white;padding:5px 15px;border-radius:10px" value="Apply" onclick="updateSellerTableData()">
                            </form>

                        
                            <div id="home" class="tab-pane fade in active">
                                <div class="sales-boxes py-5  ">
                                    <div class="recent-sales box">
                                            <div class="table-container" style="height:700px">
                                            <table class="table" id="myTable">
                                            <thead style="text-align: center;">
                                                <tr class="title" style="text-align: center;color:#666;">
                                                    <th scope="col" class="px-5" style="text-align: center;font-weight:500">Date</th>
                                                    <th scope="col" class="px-5" style="text-align: center;font-weight:500">Sales</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            <tbody id="sellerBody">
                                                    <?php include("update_seller_table.php")?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                     
                    </div>
                    <div style="width:900px;height:550px;background-color:white;padding:30px;margin-left:60px;margin-top:100px;border-radius:20px">
                        <p style="color: #868e96;font-size:15px">Seller Sales</p>
                        <form id="filterForm4">
                           <label for="yearSelect4" style="color:#666;font-weight:400;">Year:</label>
                           <select id="yearSelect4" class="btn custom-button">
                                 <?php
                                 for ($year = 2023; $year <= 2030; $year++) {
                                    echo '<option value="' . $year . '">' . $year . '</option>';
                                 }
                                 ?>
                           </select>
                           <input type="button" style="background-color:#65A5A5;border:none;color:white;padding:5px 15px;border-radius:10px" value="Apply" onclick="updateSellerGraphData()">
                        </form>

                        <p style="max-width:800px;max-height:70px;margin-top:50px"><canvas id="seller"></canvas></p>
                     </div>

               
               </div>
               <div style="display:flex;">
                    <div style="width:580px;height:630px;background-color:white;padding:30px;margin-left:60px;margin-top:100px;border-radius:20px">
                        <p style="color: #868e96;font-size:15px">Flower Arranger Sales</p>
                        <form id="filterForm5">
                            <label for="yearSelect" style="color:#666;font-weight:400">Year:</label>
                            <select id="yearSelect5" class="btn custom-button">
                                    <?php
                                    for ($year = 2023; $year <= 2030; $year++) {
                                        echo '<option value="' . $year . '">' . $year . '</option>';
                                    }
                                    ?>
                                </select>

                                <input type="button" class="year" style="background-color:#65A5A5;border:none;color:white;padding:5px 15px;border-radius:10px" value="Apply" onclick="updateArrangerTableData()">
                            </form>

                        
                            <div id="home" class="tab-pane fade in active">
                                <div class="sales-boxes py-5  ">
                                    <div class="recent-sales box">
                                            <div class="table-container" style="height:700px">
                                            <table class="table" id="myTable">
                                            <thead style="text-align: center;">
                                                <tr class="title" style="text-align: center;color:#666;">
                                                    <th scope="col" class="px-5" style="text-align: center;font-weight:500">Date</th>
                                                    <th scope="col" class="px-5" style="text-align: center;font-weight:500">Sales</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            <tbody id="arrangerBody">
                                                    <?php include("update_seller_table.php")?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                     
                    </div>
                    <div style="width:900px;height:550px;background-color:white;padding:30px;margin-left:60px;margin-top:100px;border-radius:20px">
                        <p style="color: #868e96;font-size:15px">Flower Arranger Sales</p>
                        <form id="filterForm6">
                           <label for="yearSelect6" style="color:#666;font-weight:400;margin-left:10%">Year:</label>
                           <select id="yearSelect6" class="btn custom-button">
                                 <?php
                                 for ($year = 2023; $year <= 2030; $year++) {
                                    echo '<option value="' . $year . '">' . $year . '</option>';
                                 }
                                 ?>
                           </select>
                           <input type="button" style="background-color:#65A5A5;border:none;color:white;padding:5px 15px;border-radius:10px" value="Apply" onclick="updateArrangerGraphData()">
                        </form>

                        <p style="max-width:800px;max-height:70px;margin-top:50px"><canvas id="arranger"></canvas></p>
                     </div>

               
               </div>
               <div style="display:flex;">
                    <div style="width:580px;height:630px;background-color:white;padding:30px;margin-left:60px;margin-top:200px;border-radius:20px">
                        <p style="color: #868e96;font-size:15px">Service Customers</p>
                        <form id="filterForm7">
                            <label for="yearSelect" style="color:#666;font-weight:400">Year:</label>
                            <select id="yearSelect7" class="btn custom-button">
                                    <?php
                                    for ($year = 2023; $year <= 2030; $year++) {
                                        echo '<option value="' . $year . '">' . $year . '</option>';
                                    }
                                    ?>
                                </select>

                                <input type="button" class="year" style="background-color:#65A5A5;border:none;color:white;padding:5px 15px;border-radius:10px" value="Apply" onclick="updateServiceTableData()">
                            </form>

                        
                            <div id="home" class="tab-pane fade in active">
                                <div class="sales-boxes py-5  ">
                                    <div class="recent-sales box">
                                            <div class="table-container" style="height:700px">
                                            <table class="table" id="myTable">
                                            <thead style="text-align: center;">
                                                <tr class="title" style="text-align: center;color:#666;">
                                                    <th scope="col" class="px-5" style="text-align: center;font-weight:500">Date</th>
                                                    <th scope="col" class="px-5" style="text-align: center;font-weight:500">Sales</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            <tbody id="serviceBody">
                                                    <?php include("update_service_table.php")?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                     
                    </div>
                    <div style="width:900px;height:550px;background-color:white;padding:30px;margin-left:60px;margin-top:100px;border-radius:20px">
                        <p style="color: #868e96;font-size:15px">Service Customers</p>
                        <form id="filterForm8">
                           <label for="yearSelect8" style="color:#666;font-weight:400;">Year:</label>
                           <select id="yearSelect8" class="btn custom-button">
                                 <?php
                                 for ($year = 2023; $year <= 2030; $year++) {
                                    echo '<option value="' . $year . '">' . $year . '</option>';
                                 }
                                 ?>
                           </select>
                           <input type="button" style="background-color:#65A5A5;border:none;color:white;padding:5px 15px;border-radius:10px" value="Apply" onclick="updateServiceGraphData()">
                        </form>

                        <p style="max-width:800px;max-height:70px;margin-top:50px"><canvas id="service"></canvas></p>
                     </div>

               
               </div>
               
                  
      </section>
      </div>
      </div>
      <script src="../js/jquery.js"></script>
  <script src="../js/Chart.min.js"></script>
  <script type="text/javascript">
    var myChartSales;
    // Use AJAX to fetch the data from your PHP script
    fetch('update_data.php')
        .then(response => response.json())
        .then(data => {
            var activeCount = data.activeCount;
            var expiredCount = data.expiredCount;
            var totalCount = data.totalCount;

            var ctxSales = document.getElementById("chartjs_bar").getContext('2d');
            myChartSales = new Chart(ctxSales, {
                type: 'bar',
                data: {
                    labels: ['Active', 'Expired', 'Total Subscribed'],
                    datasets: [{
                        label: 'Subscription Status',
                        backgroundColor: ["#95C3C3", "#B7D7D7", "#f0f0f0"],
                        data: [activeCount, expiredCount, totalCount],
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            fontColor: '#868e96',
                            fontFamily: 'Poppins',
                            fontSize: 14,
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                fontColor: '#868e96',
                                fontFamily: 'Poppins',
                                fontSize: 14,
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontColor: '#868e96',
                                fontFamily: 'Poppins',
                                fontSize: 14,
                            }
                        }]
                    },
                    tooltips: {
                        enabled: true,
                        mode: 'index',
                        intersect: false,
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
        function updateChartData() {
        var selectedMonth = document.getElementById('monthSelect').value;
        var selectedYear = document.getElementById('yearSelect1').value;


        // Send selected month and year to the server using AJAX
        fetch('update_data.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'selectedMonth=' + encodeURIComponent(selectedMonth) + '&selectedYear=' + encodeURIComponent(selectedYear),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Assuming the response is JSON. Adjust if necessary.
        })
        .then(data => {
            // Update chart data based on the response
            myChartSales.data.datasets[0].data[0] = data.activeCount; // Update with new data
            myChartSales.data.datasets[0].data[1] = data.expiredCount;
            myChartSales.data.datasets[0].data[2] = data.totalCount;
            myChartSales.update();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    </script>

<script type="text/javascript">
   // Second Graph
   var ctxSalesDetails = document.getElementById("seller").getContext('2d');
var myChartSalesDetails = new Chart(ctxSalesDetails, {
    type: 'bar',
    data: {
        labels: [], // Initialize with an empty array
        datasets: [{
            backgroundColor: [
                "#95C3C3",
                "#B7D7D7",
                // Add more colors as needed
            ],
            data: [], // Initialize with an empty array
        }]
    },
    options: {
        legend: {
            display: true,
            position: 'bottom',
            labels: {
                fontColor: '#868e96',
                fontFamily: 'Poppins',
                fontSize: 14,
            }
        },
        scales: {
            yAxes: [{
                ticks: {
                    fontColor: '#868e96',
                    fontFamily: 'Poppins',
                    fontSize: 14,
                }
            }],
            xAxes: [{
                ticks: {
                    fontColor: '#868e96',
                    fontFamily: 'Poppins',
                    fontSize: 14,
                }
            }]
        }
    }
});

</script>
<script type="text/javascript">
   // Third Graph
   var ctxSalesArranger = document.getElementById("arranger").getContext('2d');
var myChartSalesArranger= new Chart(ctxSalesArranger, {
    type: 'bar',
    data: {
        labels: [], // Initialize with an empty array
        datasets: [{
            backgroundColor: [
                "#95C3C3",
                "#B7D7D7",
                // Add more colors as needed
            ],
            data: [], // Initialize with an empty array
        }]
    },
    options: {
        legend: {
            display: true,
            position: 'bottom',
            labels: {
                fontColor: '#868e96',
                fontFamily: 'Poppins',
                fontSize: 14,
            }
        },
        scales: {
            yAxes: [{
                ticks: {
                    fontColor: '#868e96',
                    fontFamily: 'Poppins',
                    fontSize: 14,
                }
            }],
            xAxes: [{
                ticks: {
                    fontColor: '#868e96',
                    fontFamily: 'Poppins',
                    fontSize: 14,
                }
            }]
        }
    }
});

</script>
<script type="text/javascript">
   // Third Graph
   var ctxService = document.getElementById("service").getContext('2d');
var myChartService= new Chart(ctxService, {
    type: 'bar',
    data: {
        labels: [], // Initialize with an empty array
        datasets: [{
            backgroundColor: [
                "#95C3C3",
                "#B7D7D7",
                // Add more colors as needed
            ],
            data: [], // Initialize with an empty array
        }]
    },
    options: {
        legend: {
            display: true,
            position: 'bottom',
            labels: {
                fontColor: '#868e96',
                fontFamily: 'Poppins',
                fontSize: 14,
            }
        },
        scales: {
            yAxes: [{
                ticks: {
                    fontColor: '#868e96',
                    fontFamily: 'Poppins',
                    fontSize: 14,
                }
            }],
            xAxes: [{
                ticks: {
                    fontColor: '#868e96',
                    fontFamily: 'Poppins',
                    fontSize: 14,
                }
            }]
        }
    }
});


// Function to update chart data based on selected month and year



</script>
<script>
    function updateTableData() {
    var selectedYear = document.getElementById('yearSelect2').value;

    // Send selected year to the server using AJAX
    fetch('update_table_data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'selectedYear=' + encodeURIComponent(selectedYear),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        // Update table data based on the response
        document.getElementById('tableBody').innerHTML = data;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

</script>



<!-- Add this script section to your HTML -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Default year when the page loads
        var defaultYear = 2023;

        // Check if a year is already selected
        var selectedYearElement = document.getElementById('yearSelect4');
        if (selectedYearElement.value === '') {
            // If no year is selected, set the default year
            selectedYearElement.value = defaultYear;
        }

        // Display default data when the page loads
        updateSellerGraphData();
    });

    function updateSellerGraphData() {
        var selectedYear = document.getElementById('yearSelect4').value;

        // Send selected year to the server using AJAX
        fetch('update_seller_graph.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'selectedYear=' + encodeURIComponent(selectedYear),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Assuming the server responds with JSON data
        })
        .then(data => {
            // Update graph data based on the response
            myChartSalesDetails.data.labels = data.labels;
            myChartSalesDetails.data.datasets[0].data = data.data;
            myChartSalesDetails.update(); // Update the chart
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>

<script>
   function updateSellerTableData() {
    var selectedYear = document.getElementById('yearSelect3').value;
    
    // Send selected year to the server using AJAX
    fetch('update_seller_table.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'selectedYear=' + encodeURIComponent(selectedYear),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        // Update table data based on the response
        document.getElementById('sellerBody').innerHTML = data;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

</script>
<!-- Add this script section to your HTML -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Default year when the page loads
        var defaultYear = 2023;

        // Check if a year is already selected
        var selectedYearElement = document.getElementById('yearSelect6');
        if (selectedYearElement.value === '') {
            // If no year is selected, set the default year
            selectedYearElement.value = defaultYear;
        }

        // Display default data when the page loads
        updateArrangerGraphData();
    });

    function updateArrangerGraphData() {
        var selectedYear = document.getElementById('yearSelect6').value;

        // Send selected year to the server using AJAX
        fetch('update_arranger_graph.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'selectedYear=' + encodeURIComponent(selectedYear),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Assuming the server responds with JSON data
        })
        .then(data => {
            // Update graph data based on the response
            myChartSalesArranger.data.labels = data.labels;
            myChartSalesArranger.data.datasets[0].data = data.data;
            myChartSalesArranger.update(); // Update the chart
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>

<script>
   document.addEventListener('DOMContentLoaded', function () {
    // Default year when the page loads
    var defaultYear = 2023;

    // Check if a year is already selected
    var selectedYearElement = document.getElementById('yearSelect5');
    if (selectedYearElement.value === '') {
        // If no year is selected, set the default year
        selectedYearElement.value = defaultYear;
    }

    // Display default data when the page loads
    updateArrangerTableData(); // Update the table data

});

function updateArrangerTableData() {
    var selectedYear = document.getElementById('yearSelect5').value;

    // Send selected year to the server using AJAX
    fetch('update_arranger_table.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'selectedYear=' + encodeURIComponent(selectedYear),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            // Update table data based on the response
            document.getElementById('arrangerBody').innerHTML = data;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}


</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Default year when the page loads
        var defaultYear = 2023;

        // Check if a year is already selected
        var selectedYearElement = document.getElementById('yearSelect8');
        if (selectedYearElement.value === '') {
            // If no year is selected, set the default year
            selectedYearElement.value = defaultYear;
        }

        // Display default data when the page loads
        updateServiceGraphData();
    });

    function updateServiceGraphData() {
        var selectedYear = document.getElementById('yearSelect8').value;

        // Send selected year to the server using AJAX
        fetch('update_service_graph.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'selectedYear=' + encodeURIComponent(selectedYear),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Assuming the server responds with JSON data
        })
        .then(data => {
            // Update graph data based on the response
            myChartService.data.labels = data.labels;
            myChartService.data.datasets[0].data = data.data;
            myChartService.update(); // Update the chart
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>

<script>
   function updateServiceTableData() {
    var selectedYear = document.getElementById('yearSelect7').value;
    
    // Send selected year to the server using AJAX
    fetch('update_service_table.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'selectedYear=' + encodeURIComponent(selectedYear),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        // Update table data based on the response
        document.getElementById('serviceBody').innerHTML = data;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

</script>
   </body>
</html>