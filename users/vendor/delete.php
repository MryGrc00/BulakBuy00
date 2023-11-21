<?php
session_start(); // Start or resume the session to access session variables

include '../php/dbhelper.php';

if (isset($_GET['product_id'])) {
    // Get the product ID from the URL
    $productID = $_GET['product_id'];

    // Establish database connection
    $pdo = dbconnect();

    // Check if the product belongs to a shop owned by the currently logged-in user
    $stmt = $pdo->prepare("SELECT p.*, s.owner_id FROM products p 
                           INNER JOIN shops s ON p.shop_owner = s.shop_id 
                           WHERE p.product_id = :product_id");
    $stmt->bindParam(':product_id', $productID);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product && $product['owner_id'] == $_SESSION['user_id']) {
        // Attempt to delete the product with the provided product ID
        $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $productID);
        $stmt->execute();

        // Check if the product deletion was successful
        if ($stmt->rowCount() > 0) {
            // Product deleted successfully, redirect to a success page
            header("Location: vendor_product.php");
            exit();
        } else {
            // Product deletion failed, handle error (redirect, display error message, etc.)
            echo "Failed to delete product!";
        }
    } else {
        // Product does not belong to the currently logged-in user's shop, handle error
        echo "Unauthorized access to delete the product!";
    }
} else {
    // Product ID not provided in the URL, handle error (redirect, display error message, etc.)
    echo "Product ID not provided!";
}
?>

