<?php
include 'users/php/dbhelper.php'; // Include your actual dbhelper file

$output = "";

if (isset($_POST['submit'])) {
    $selectedMonth = $_POST["month"];
    $selectedYear = $_POST["year"];
    $currentlyLoggedInUserId = $_SESSION['user_id'];

    try {
        $pdo = dbconnect(); 
        // Modify the SQL query to select only the required columns
        $sql = "SELECT s.product_id, s.customer_id, s.amount, s.sales_date
        FROM sales s
        JOIN shops sh ON s.shop_id = sh.shop_id
        JOIN users u ON sh.owner_id = u.user_id
        WHERE MONTH(s.sales_date) = :month AND YEAR(s.sales_date) = :year
        AND u.user_id = :owner_id";  // Assuming user_id is the identifier for the currently logged-in user

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':month', $selectedMonth, PDO::PARAM_INT);
$stmt->bindParam(':year', $selectedYear, PDO::PARAM_INT);
$stmt->bindParam(':owner_id', $currentlyLoggedInUserId, PDO::PARAM_INT); // Set the currently logged-in user's ID here
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);


        if ($result) {
            if (count($result) > 0) {
                $output .= '
                    <table class="table" bordered="1">
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Address</th>
                        </tr>';

                foreach ($result as $row) {
                    $output .= '
                        <tr>
                            <td>' . $row['first_name'] . '</td>
                            <td>' . $row['last_name'] . '</td>
                            <td>' . $row['email'] . '</td>
                            <td>' . $row['address'] . '</td>
                        </tr>';
                }

                $output .= '</table>';

                // Set headers for Excel download
                header('Content-type: application/xls');
                header('Content-Disposition: attachment; filename=reports.xls');

                // Output the table
                echo $output;
            } else {
                echo 'No data found';
            }
        } else {
            echo 'Error executing the query';
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
