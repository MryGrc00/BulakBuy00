<?php
// Include your database connection code here
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=bulakbuy", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
    echo 'Error in database connection: ' . $e->getMessage();
    exit;
}

$selectedYear = isset($_POST['selectedYear']) ? filter_var($_POST['selectedYear'], FILTER_VALIDATE_INT) : date('Y');

// Example SQL query, modify it based on your actual database structure
$sql = "SELECT MONTHNAME(date) AS month, SUM(amount) AS total_sales
        FROM servicedetails
        WHERE YEAR(date) = :year
        GROUP BY MONTH(date)
        ORDER BY MONTH(date)";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':year', $selectedYear, PDO::PARAM_INT);

if (!$stmt->execute()) {
    echo 'Error executing SQL query';
    exit;
}

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<tr class="name" style="text-align: center;color:#555">';
    echo '<td class="px-4 py-3">' . $row['month'] . '</td>';
    echo '<td class="px-5 py-3" style="width:300px;">' . $row['total_sales'] . '</td>';
    echo '</tr>';
}
?>
