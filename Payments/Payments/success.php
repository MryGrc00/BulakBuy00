<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... existing head content ... -->
</head>
<body>

<?php
session_start(); 
include '../../users/php/dbhelper.php'; // Adjust the path as needed

date_default_timezone_set('Asia/Manila');


        if (isset($_GET['paymongo_id'])) {
            $paymongo_id = $_GET['paymongo_id'];
        }

        // Display the success message and details
        echo "<div class='container center'>";
        echo "<div class='alert alert-success'>";
        echo "<strong>Reference Code: $paymongo_id</strong>";
        echo "</div>";
        echo "<a class='btn btn-primary btn-lg' href='http://localhost:80/Bulakbuy00/users/customer/customer_home.php'>Back to main</a>";
        echo "</div>";
?>

</body>
</html>
