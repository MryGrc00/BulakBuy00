<?php
session_start();
include 'dbhelper.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = $_POST['productId'];
    $customerId = $_POST['customerId'];
    $salesId = $_POST['salesId'];

    // Update the status in the sales table for the specific product and customer
    $result = update_status($salesId,$productId, $customerId);

    if ($result) {
        echo 'Status updated successfully.';
    } else {
        echo 'Failed to update status.';
    }
}

function update_status($salesId, $productId, $customerId) {
    $conn = dbconnect();
    $sql = "UPDATE sales SET status = 'Processing' WHERE sales_id = ? AND product_id = ? AND customer_id = ?";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$productId, $customerId]);
        $conn = null;
        return true;
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}
?>
