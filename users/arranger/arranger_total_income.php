<?php
session_start();
require_once '../php/dbhelper.php'; // Using require_once ensures the script stops if the file is missing.

// Redirect non-sellers or unauthenticated users to the login page
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "arranger") {
    header("Location: ../login.php");
    exit(); // Stop script execution after a header redirect
}

$userId = $_SESSION["user_id"];


function getProductTotalIncome($userId) {
    $pdo = dbconnect(); // Ensure this is the correct function to establish your database connection

    // SQL query to sum the amount from sales where the shop_id is owned by the user
    $sql = "SELECT SUM(s.amount) AS total_income 
            FROM sales s
            INNER JOIN shops sh ON s.shop_id = sh.shop_id
            WHERE sh.owner_id = :userId";

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['total_income'] : 0; 
}

function getServiceTotalIncome($userId) {
    $pdo = dbconnect(); 


    $sql = "SELECT SUM(sd.amount) AS total_income 
            FROM servicedetails sd
            INNER JOIN services s ON sd.service_id = s.service_id
            WHERE s.arranger_id = :userId";

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['total_income'] : 0; 
}



function getProductMonthlySales($userId, $startYear, $endYear) {
    $pdo = dbconnect();
    $sql = "SELECT 
                YEAR(sales_date) AS year, 
                MONTH(sales_date) AS month, 
                SUM(amount) AS monthly_income 
            FROM sales 
            WHERE YEAR(sales_date) BETWEEN :startYear AND :endYear
            AND shop_id IN (
                SELECT shop_id FROM shops WHERE owner_id = :userId
            ) 
            GROUP BY YEAR(sales_date), MONTH(sales_date)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':startYear', $startYear, PDO::PARAM_INT);
    $stmt->bindParam(':endYear', $endYear, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getServiceMonthlySales($userId, $startYear, $endYear) {
    $pdo = dbconnect();
    $sql = "SELECT 
                YEAR(sd.date) AS year, 
                MONTH(sd.date) AS month, 
                SUM(sd.amount) AS monthly_income 
            FROM servicedetails sd
            INNER JOIN services s ON sd.service_id = s.service_id
            WHERE YEAR(sd.date) BETWEEN :startYear AND :endYear
            AND s.arranger_id = :userId
            GROUP BY YEAR(sd.date), MONTH(sd.date)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':startYear', $startYear, PDO::PARAM_INT);
    $stmt->bindParam(':endYear', $endYear, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




$productTotalIncome = getProductTotalIncome($userId); 
$serviceTotalIncome = getServiceTotalIncome($userId);
$totalIncome = $productTotalIncome + $serviceTotalIncome;

$productMonthlySales = getProductMonthlySales($userId, 2022,2023);
$serviceMonthlySales = getServiceMonthlySales($userId, 2022,2023);

?>

<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Total Income</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/total_income.css">
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
                                <input type="text"  class="form-control form-input" placeholder="Search">
                                <a href="javascript:void(0);" onclick="goBack()">
                                    <i class="back fa fa-angle-left" aria-hidden="true"></i>
                                    <div id="search-results">Total Income</div>
                                  </a>
                                  
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <main class="main">
            <div class="income-info">
                <div class="income-details">
                    <div class="income-name">
                        <span class="s-label">Total Income</span>
                    </div>
                    <span class="income">₱ <?php echo $totalIncome;?></span>
                </div>
            </div>
            <section>
            <div class="container1">
                <div class="product-button card2">
                    <span class="label">Products</span>
                    <span class="sales">₱ <?php echo $productTotalIncome;?></span>
                </div>
                <div class="service-button card2">
                    <span class="label">Services</span>
                    <span class="sales">₱ <?php echo $serviceTotalIncome;?></span>
                </div>
            </div>
            <div class="product-details" style="display:block;">
            <?php foreach ($productMonthlySales as $monthlySale): ?>
                <div class="vertical-container">
                    <div class="subscription-details">
                        <i class="fa fa-money" aria-hidden="true"></i>
                        <div class="text-content">
                            <div class="subscript">
                            <?php 
                                $monthName = date("F", mktime(0, 0, 0, $monthlySale['month'], 10));
                                echo htmlspecialchars($monthName) . " " . htmlspecialchars($monthlySale['year']); 
                            ?>                           
                             </div>
                            <span class="income-monthly">
                             ₱ <?php echo htmlspecialchars($monthlySale['monthly_income']); ?>
                         </span>   
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <div class="service-details" style="display:none;">
            <?php foreach ($serviceMonthlySales as $monthlySale): ?>
                <div class="vertical-container">
                    <div class="subscription-details">
                        <i class="fa fa-money" aria-hidden="true"></i>
                        <div class="text-content">
                            <div class="subscript">
                            <?php 
                                $monthName = date("F", mktime(0, 0, 0, $monthlySale['month'], 10));
                                echo htmlspecialchars($monthName) . " " . htmlspecialchars($monthlySale['year']); 
                            ?>                           
                             </div>
                            <span class="income-monthly">
                             ₱ <?php echo htmlspecialchars($monthlySale['monthly_income']); ?>
                         </span>   
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            </section>
        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            function goBack() {
                window.history.back();
            }
          </script>
        <script>
            function showProductsDetails() {
                // Set products to be visible
                const productsDetails = document.querySelector('.product-details');
                const servicesDetails = document.querySelector('.service-details');
                if (productsDetails && servicesDetails) {
                    productsDetails.style.display = 'block';
                    servicesDetails.style.display = 'none';
                }
            }

            // Function to show the services details
            function showServicesDetails() {
                // Set services to be visible
                const productsDetails = document.querySelector('.product-details');
                const servicesDetails = document.querySelector('.service-details');
                if (productsDetails && servicesDetails) {
                    productsDetails.style.display = 'none';
                    servicesDetails.style.display = 'block';
                }
            }

            // Get the buttons
            const productButton = document.querySelector('.product-button');
            const serviceButton = document.querySelector('.service-button');

            // Attach click event listeners to buttons
            if (productButton && serviceButton) {
                productButton.addEventListener('click', showProductsDetails);
                serviceButton.addEventListener('click', showServicesDetails);
            }
        </script>


            
    </body>
</html>