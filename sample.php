<?php
session_start();
require_once 'users/php/dbhelper.php'; // Using require_once ensures the script stops if the file is missing.




$userId = $_SESSION["user_id"];


function getTotalIncome($userId) {
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
    return $result ? $result['total_income'] : 0; // Return the total income or 0 if none
}



function getMonthlySales($userId, $startYear, $endYear) {
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



$totalIncome = getTotalIncome($userId); // Fetch the total income

$monthlySales = getMonthlySales($userId, 2022,2023);
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
                <a class="navbar-brand d-flex align-items-center" href="customer_home.php">
                    <img src="../php/images/logo.png" alt="BulakBuy Logo" class="img-fluid logo">
                </a>
                <!-- Search Bar -->
                <div class="navbar-collapse justify-content-md-center">
                    <ul class="navbar-nav dib">
                        <li class="nav-item">
                            <form class="form-inline my-2 my-lg-0">
                                <a href=""><i class="fa fa-search"></i></a>
                                <input type="text"  class="form-control form-input" placeholder="Search" style="text-align:left;padding-left: 15px;font-size: 16px;">
                                <a href="vendor_home.php" id="back-link"><i class="back fa fa-angle-left" aria-hidden="true"></i></a>
                                <div id="search-results">Total Income</div>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
            <hr class="nav-hr">
        </header>
        </header>
        <main class="main">
            <div class="income-info">
                <div class="income-details">
                    <div class="income-name">
                        <span class="s-label">Total Income</span>
                    </div>
                    <span class="income">₱ <?php echo htmlspecialchars($totalIncome ? $totalIncome : '0'); ?></span>
                </div>
            </div>
            <section>
                <br>
                <?php foreach ($monthlySales as $monthlySale): ?>
                <div class="vertical-container">
                    <div class="subscription-details">
                        <i class="fa fa-money" aria-hidden="true"></i>
                        <div class="text-content">
                        <span class="subscription-status">
                        <?php 
                            $monthName = date("F", mktime(0, 0, 0, $monthlySale['month'], 10));
                            echo htmlspecialchars($monthName) . " " . htmlspecialchars($monthlySale['year']); 
                        ?>
                    </span>
                       <span class="income-monthly">
                             ₱ <?php echo htmlspecialchars($monthlySale['monthly_income']); ?>
                         </span>                        
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </section>
        </main>
        <form action="process.php" method="post">
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
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>