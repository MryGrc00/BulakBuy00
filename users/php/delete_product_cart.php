<?php
session_start();
include '../php/dbhelper.php';

header('Content-Type: application/json'); // Set the content type to JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the JSON data sent from the frontend
    $requestData = json_decode(file_get_contents('php://input'), true);

    if (isset($requestData['selectedProducts'])) {
        try {
            // Iterate through the selected products and delete them from the database
            foreach ($requestData['selectedProducts'] as $product) {
                $productId = $product['productId'];
                $shopId = $product['shopId'];

                // Perform the deletion query based on your database structure
                // Make sure to properly escape input data and handle potential SQL injection
                // Below is a basic example using MySQLi
                $sql = "DELETE FROM salesdetails WHERE product_id = ? AND shop_id = ?";

                $stmt = $mysqli->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param("ii", $productId, $shopId);
                    $stmt->execute();
                    $stmt->close();
                }
            }

            // Send a success response
            $response = array('status' => 'success', 'message' => 'Products deleted successfully');
            echo json_encode($response);
        } catch (Exception $e) {
            // Send an error response
            $response = array('status' => 'error', 'message' => 'Error deleting products: ' . $e->getMessage());
            echo json_encode($response);
        }
    } else {
        // Send an error response for invalid request data
        $response = array('status' => 'error', 'message' => 'Invalid request data');
        echo json_encode($response);
    }
} else {
    // Send an error response for invalid request method
    $response = array('status' => 'error', 'message' => 'Invalid request method');
    echo json_encode($response);
}
?>
