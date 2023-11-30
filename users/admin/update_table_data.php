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
$sql = "SELECT MONTHNAME(s_date) AS month, COUNT(*) AS subscribed_count, 
               SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) AS active_count,
               SUM(CASE WHEN status = 'expired' THEN 1 ELSE 0 END) AS expired_count
        FROM subscription
        WHERE YEAR(s_date) = :year
        GROUP BY MONTH(s_date)
        ORDER BY MONTH(s_date)";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':year', $selectedYear, PDO::PARAM_INT);

if (!$stmt->execute()) {
    echo 'Error executing SQL query';
    exit;
}

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<tr class="name" style="text-align: center;color:#555;font-size:15px;color:#666">';
    echo '<td class="px-4 py-3">' . $row['month'] . '</td>';
    echo '<td class="px-5 py-3" style="width:300px;">' . $row['subscribed_count'] . '</td>';
    echo '<td class="px-5 py-3" style="width:200px;">' . $row['active_count'] . '</td>';
    echo '<td class="px-5 py-3" style="width:200px;">' . $row['expired_count'] . '</td>';
    echo '</tr>';
}
?>
