<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=bulakbuy", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
    echo json_encode(['error' => 'Problem in database connection! Contact administrator! ' . $e->getMessage()]);
    exit;
}

$selectedMonth = isset($_POST['selectedMonth']) ? filter_var($_POST['selectedMonth'], FILTER_VALIDATE_INT) : date('n');
$selectedYear = isset($_POST['selectedYear']) ? filter_var($_POST['selectedYear'], FILTER_VALIDATE_INT) : date('Y');

$sqlActive = "SELECT COUNT(*) FROM subscription WHERE status = 'active' AND MONTH(s_date) = :month AND YEAR(s_date) = :year";
$sqlExpired = "SELECT COUNT(*) FROM subscription WHERE status = 'expired' AND MONTH(s_date) = :month AND YEAR(s_date) = :year";
$sqlTotal = "SELECT COUNT(*) FROM subscription WHERE MONTH(s_date) = :month AND YEAR(s_date) = :year";

$stmtActive = $pdo->prepare($sqlActive);
$stmtExpired = $pdo->prepare($sqlExpired);
$stmtTotal = $pdo->prepare($sqlTotal);

$stmtActive->bindParam(':month', $selectedMonth, PDO::PARAM_INT);
$stmtExpired->bindParam(':month', $selectedMonth, PDO::PARAM_INT);
$stmtTotal->bindParam(':month', $selectedMonth, PDO::PARAM_INT);

$stmtActive->bindParam(':year', $selectedYear, PDO::PARAM_INT);
$stmtExpired->bindParam(':year', $selectedYear, PDO::PARAM_INT);
$stmtTotal->bindParam(':year', $selectedYear, PDO::PARAM_INT);

if (!$stmtActive->execute() || !$stmtExpired->execute() || !$stmtTotal->execute()) {
    echo json_encode(['error' => 'Error executing SQL queries']);
    exit;
}

$activeCount = $stmtActive->fetchColumn();
$expiredCount = $stmtExpired->fetchColumn();
$totalCount = $stmtTotal->fetchColumn();

// Prepare data for a graph
$data = [
    'activeCount' => $activeCount,
    'expiredCount' => $expiredCount,
    'totalCount' => $totalCount
];

// Output the data as JSON for the graph
echo json_encode($data);
?>
