<?php
// Include your database connection code here
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=bulakbuy", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
    echo 'Error in database connection: ' . $e->getMessage();
    exit;
}

$selectedYear = isset($_POST['selectedYear']) ? filter_var($_POST['selectedYear'], FILTER_VALIDATE_INT) : date('Y');

// Get the role of the currently logged-in user (you may need to adjust this based on your authentication system)
$userRole = 'arranger'; // Replace with the actual role of the user

// Example SQL query, modify it based on your actual database structure
$sql = "SELECT MONTHNAME(s.sales_date) AS month, SUM(s.amount) AS total_sales
        FROM sales s
        JOIN shops sh ON s.shop_id = sh.shop_id
        JOIN users u ON sh.owner_id = u.user_id
        WHERE YEAR(s.sales_date) = :year
          AND u.role = :userRole
          AND EXISTS (
            SELECT 1
            FROM shops sh2
            WHERE sh2.shop_id = s.shop_id
              AND sh2.owner_id = u.user_id
          )
        GROUP BY MONTH(s.sales_date)
        ORDER BY MONTH(s.sales_date)";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':year', $selectedYear, PDO::PARAM_INT);
$stmt->bindParam(':userRole', $userRole, PDO::PARAM_STR);

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
