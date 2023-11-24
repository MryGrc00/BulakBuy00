<?php
session_start();
include '../php/dbhelper.php';
$pdo = dbconnect();

if (isset($_POST['service_detail_id']) && isset($_POST['action'])) {
    $serviceDetailId = $_POST['service_detail_id'];
    $action = $_POST['action'];

    switch ($action) {
        case 'accept':
            $newStatus = 'processing';
            $redirectPage = 'service_process.php';
            break;
        case 'intransit':
            $newStatus = 'intransit';
            $redirectPage = 'service_intransit.php';
            break;
        case 'completed':
            $newStatus = 'completed';
            $redirectPage = 'service_completed.php';
            break;
        case 'cancelled':
                $newStatus = 'cancelled';
                $redirectPage = 'service_order.php';
                break;
        default:
            // Handle invalid action
            echo "Invalid action.";
            exit();
    }

    // Update the status in the database
    $stmt = $pdo->prepare("UPDATE servicedetails SET status = :newStatus WHERE servicedetails_id = :serviceDetailId");
    $stmt->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
    $stmt->bindParam(':serviceDetailId', $serviceDetailId, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect to the appropriate page
    header("Location: $redirectPage");
    exit();
}
?>
