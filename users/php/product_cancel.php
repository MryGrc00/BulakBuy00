<?php
session_start();
include '../php/dbhelper.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $salesId = $_POST['salesId'];
    $customerId = $_POST['customerId'];

    // Update the status in the sales table for the specific product and customer
    $result = update_status($salesId, $customerId); 

    if ($result) {
        echo 'Status updated successfully.';
    } else {
        echo 'Failed to update status.';
    }
}

function update_status($salesId, $customerId) {
    $conn = dbconnect();
    $sql = "UPDATE sales SET status = 'Cancelled' WHERE sales_id = ? AND customer_id = ?";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$salesId, $customerId]);
        $conn = null;
        return true;
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}

?>