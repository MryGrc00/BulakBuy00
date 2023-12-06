<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... existing head content ... -->
</head>
<body>

<?php
session_start(); 
include '../../users/php/dbhelper.php'; // Adjust the path as needed
$selectedProducts = isset($_SESSION['selected_products']) ? $_SESSION['selected_products'] : [];
$selectedPayment = isset($_SESSION['selected_payment']) ? $_SESSION['selected_payment'] : '';
$_SESSION['from_success_page'] = true;

date_default_timezone_set('Asia/Manila');


        if (isset($_GET['paymongo_id'])) {
            $paymongo_id = $_GET['paymongo_id'];
        }

        // Display the success message and details
        echo "<div class='container center'>";
        echo "<div class='alert alert-success'>";
        echo "<strong>Reference Code: $paymongo_id</strong>";
        echo "</div>";
        echo "<a class='btn btn-primary btn-lg' href='http://172.20.10.3:80/Bulakbuy00/users/customer/place_order_after_payment.php?selected_products=" . urlencode(json_encode($selectedProducts)) . "&selected_payment=" . urlencode($selectedPayment) . "'>Back to main</a>";
        echo "</div>";
?>
<script>
    <?php
    $selectedProducts = json_decode('null'); // Initialize as null
    if (isset($_GET['selected_products'])) {
        $selectedProducts = json_decode(urldecode($_GET['selected_products']), true);
    }
    ?>
    var selectedProducts = <?php echo json_encode($selectedProducts); ?>;

    // Retrieve selected products from local storage
    var storedProducts = localStorage.getItem('selectedProducts');

    // Check if storedProducts is not null
    if (storedProducts) {
        storedProducts = JSON.parse(storedProducts);
        console.log('Selected Products from local storage:', storedProducts);
        // Use the storedProducts array as needed in your code
    } else {
        console.log('Selected Products not found in local storage');
    }
</script>
</body>
</html>
