<?php
session_start();
include '../php/dbhelper.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the product data from the JSON string
    $productsData = json_decode($_POST["productsData"], true);

    // Add each product to the sales table
    foreach ($productsData as $productData) {
        $productID = $productData["product_id"];
        $customerID = $_SESSION["user_id"];
        $totalAmount = $productData["totalAmount"];
        $salesDate = date("Y-m-d H:i:s");
        $status = "Pending";

        // Add the sales record to the database
        $success = add_sales_record($productID, $customerID, $totalAmount, $salesDate, $status);

        // If one of the additions fails, set $success to false
        if (!$success) {
            $success = false;
        }
    }

    // Send a JSON response
    echo json_encode(["success" => $success]);
    exit();
}
?>
