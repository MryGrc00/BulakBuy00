<?php
// Include your database connection code here
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=bulakbuy", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
    echo 'Error in database connection: ' . $e->getMessage();
    exit;
}

// Check if a year is selected, default to 2023 otherwise
$selectedYear = isset($_POST['selectedYear']) ? filter_var($_POST['selectedYear'], FILTER_VALIDATE_INT) : 2023;

// Define all months
$allMonths = [
    'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
];

// Example SQL query, modify it based on your actual database structure
$sql = "SELECT MONTH(date) AS month, SUM(amount) AS total_sales
        FROM servicedetails
        WHERE YEAR(date) = :year
        GROUP BY MONTH(date)
        ORDER BY MONTH(date)";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':year', $selectedYear, PDO::PARAM_INT);

if (!$stmt->execute()) {
    echo json_encode(['error' => 'Error executing SQL query']);
    exit;
}

$labels = [];
$data = [];

// Initialize data array with zeros for all months
$dataArray = array_fill_keys(range(1, 12), 0);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $month = $row['month'];
    $dataArray[$month] = $row['total_sales'];
}

// Format data for response
foreach ($dataArray as $month => $totalSales) {
    $labels[] = $allMonths[$month - 1]; // Adjust index to match the array
    $data[] = $totalSales;
}

echo json_encode(['labels' => $labels, 'data' => $data]);
?>
