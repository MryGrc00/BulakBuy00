<?php
session_start();
include '../php/dbhelper.php';

if (isset($_SESSION['user_id']) && isset($_POST['action']) && isset($_POST['product_id']) && isset($_POST['salesdetails_id']) && isset($_POST['quantity'])) {
    $user_id = $_SESSION['user_id'];
    $action = $_POST['action'];
    $product_id = $_POST['product_id'];
    $salesdetails_id = $_POST['salesdetails_id'];
    $new_quantity = $_POST['quantity'];

    // Validate the action (increase or decrease)
    if ($action === 'increase' || $action === 'decrease') {
        // Update the quantity in the database
        $success = update_product_quantity($user_id, $product_id, $salesdetails_id, $action, $new_quantity);

        if ($success) {
            // Return a success response
            echo 'Quantity updated successfully';
        } else {
            // Return an error response
            echo 'Failed to update quantity';
        }
    }
}

function update_product_quantity($user_id, $product_id, $salesdetails_id, $action, $new_quantity) {
    $conn = dbconnect();

    try {
        // Start a transaction to ensure data consistency
        $conn->beginTransaction();

        // Ensure that the current user owns the product in the cart
        if (user_owns_product($conn, $user_id, $product_id, $salesdetails_id)) {
            $current_quantity = get_current_quantity($conn, $user_id, $product_id, $salesdetails_id);

            if ($action === 'increase') {
                // Increase the quantity by 1
                $new_quantity = $current_quantity + 1;
            } elseif ($action === 'decrease') {
                // Decrease the quantity by 1, but ensure it's not less than 1
                $new_quantity = max($current_quantity - 1, 1);
            }

            // Update the quantity in the database
            $sql = "UPDATE salesdetails SET quantity = ? WHERE customer_id = ? AND product_id = ? AND salesdetails_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$new_quantity, $user_id, $product_id, $salesdetails_id]);

            // Commit the transaction if everything was successful
            $conn->commit();
            $conn = null;

            // Return true to indicate success
            return true;
        } else {
            // If the user doesn't own the product, return false
            return false;
        }
    } catch (PDOException $e) {
        // If an error occurs, roll back the transaction and return false
        $conn->rollBack();
        $conn = null;
        return false;
    }
}

function user_owns_product($conn, $user_id, $product_id, $salesdetails_id) {
    // Check if the user owns the product in the cart
    $sql = "SELECT COUNT(*) FROM salesdetails WHERE customer_id = ? AND product_id = ? AND salesdetails_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $product_id, $salesdetails_id]);
    $result = $stmt->fetchColumn();

    // If the count is greater than 0, the user owns the product
    return $result > 0;
}

function get_current_quantity($conn, $user_id, $product_id, $salesdetails_id) {
    // Query the current quantity from the database
    $sql = "SELECT quantity FROM salesdetails WHERE customer_id = ? AND product_id = ? AND salesdetails_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $product_id, $salesdetails_id]);
    $result = $stmt->fetchColumn();

    return $result;
}

?>
