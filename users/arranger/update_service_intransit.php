<?php
session_start();
include '../php/dbhelper.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servicedetailsId= $_POST['servicedetailsId'];
    $customerId = $_POST['customerId'];

    // Update the status in the sales table for the specific product and customer
    $result = update_status($servicedetailsId, $customerId);

    if ($result) {
        echo 'Status updated successfully.';
    } else {
        echo 'Failed to update status.';
    }
}

function update_status($servicedetailsId, $customerId) {
    $conn = dbconnect();
    $sql = "UPDATE servicedetails SET status = 'Intransit' WHERE servicedetails_id = ? AND customer_id = ?";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$servicedetailsId, $customerId]);
        $conn = null;
        return true;
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}
?>