<?php
session_start(); // Start or resume the session to access session variables

include '../php/dbhelper.php';

if (isset($_GET['product_id'])) {
    // Get the product ID from the URL
    $productID = $_GET['product_id'];

    // Check if the product belongs to the currently logged-in seller
    $pdo = dbconnect();
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = :product_id AND seller_id = :seller_id");
    $stmt->bindParam(':product_id', $productID);
    $stmt->bindParam(':seller_id', $_SESSION['user_id']);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Attempt to delete the product with the provided product ID and seller ID
        $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = :product_id AND seller_id = :seller_id");
        $stmt->bindParam(':product_id', $productID);
        $stmt->bindParam(':seller_id', $_SESSION['user_id']);
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
        // Product does not belong to the currently logged-in seller, handle error
        echo "Unauthorized access to delete the product!";
    }
} else {
    // Product ID not provided in the URL, handle error (redirect, display error message, etc.)
    echo "Product ID not provided!";
}
?>
