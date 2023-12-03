<?php
include('../php/dbhelper.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['shop_id']) && isset($_POST['action']) && $_POST['action'] === 'delete_shop') {
    // Assuming you have established a database connection
    $pdo = dbconnect();

    $shopId = $_POST['shop_id'];

    // Fetch the owner_id of the shop
    $stmt = $pdo->prepare("SELECT owner_id FROM shops WHERE shop_id = ?");
    $stmt->execute([$shopId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $ownerId = $row['owner_id'];

        // Delete the shop from the shops table
        $stmt = $pdo->prepare("DELETE FROM shops WHERE shop_id = ?");
        $stmt->execute([$shopId]);

        // Delete the user from the users table
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->execute([$ownerId]);

        echo "Shop and associated user have been deleted successfully.";
    } else {
        echo "Shop not found.";
    }
} else {
    echo "Invalid request.";
}
?>
