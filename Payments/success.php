<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... existing head content ... -->
</head>
<body>

<?php
session_start(); 
include '../users/php/dbhelper.php'; // Adjust the path as needed

if (isset($_SESSION['user_id'])) {
    $pdo = dbconnect();
    $user_id = $_SESSION['user_id'];

    // Get the shop_id for the logged-in user
    $sql = "SELECT shop_id FROM shops WHERE owner_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($result && isset($result['shop_id'])) {
        $shop_id = $result['shop_id'];
    
        // Set start and end dates for the subscription
        $s_start = date('Y-m-d H:i:s'); 
        $e_start = date('Y-m-d H:i:s', strtotime('+1 month')); // 1 month from today
    
        // Check if a subscription already exists for the shop_id
        $checkSql = "SELECT * FROM subscription WHERE shop_id = :shop_id";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindParam(':shop_id', $shop_id, PDO::PARAM_INT);
        $checkStmt->execute();
        $existingSub = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
        if ($existingSub) {
            // Update the existing subscription record
            $updateSql = "UPDATE subscription SET s_start = :s_start, e_start = :e_start, status = 'active' WHERE shop_id = :shop_id";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->bindParam(':s_start', $s_start);
            $updateStmt->bindParam(':e_start', $e_start);
            $updateStmt->bindParam(':shop_id', $shop_id, PDO::PARAM_INT);
            $updateStmt->execute();
        } else {
            // Insert a new subscription record
            $insertSql = "INSERT INTO subscription (shop_id, s_start, e_start, status) VALUES (:shop_id, :s_start, :e_start, 'active')";
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->bindParam(':shop_id', $shop_id, PDO::PARAM_INT);
            $insertStmt->bindParam(':s_start', $s_start);
            $insertStmt->bindParam(':e_start', $e_start);
            $insertStmt->execute();
        }

        if (isset($_GET['paymongo_id'])) {
            $paymongo_id = $_GET['paymongo_id'];
        }

        // Display the success message and details
        echo "<div class='container center'>";
        echo "<div class='alert alert-success'>";
        echo "<strong>Subscription activated successfully for Shop ID: $shop_id</strong>";
        echo "<strong>Reference Code: $paymongo_id</strong>";
        echo "</div>";
        echo "<a class='btn btn-primary btn-lg' href='http://localhost:80/Bulakbuy00/Paymentsss/index.php'>Back to main</a>";
        echo "</div>";
    } else {
        // Handle case where shop_id is not found
        echo "<div class='container center'>";
        echo "<div class='alert alert-danger'>";
        echo "Shop ID not found for the user.";
        echo "</div>";
        echo "</div>";
    }
} else {
    // Handle the case where no user session is found
    echo "<div class='container center'>";
    echo "<div class='alert alert-danger'>";
    echo "No active session found. Please log in.";
    echo "</div>";
    echo "</div>";
}
?>

</body>
</html>
