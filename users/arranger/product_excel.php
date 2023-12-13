<?php
include '../php/dbhelper.php'; // Include your actual dbhelper file

$output = "";
$totalAmount = 0;

if (isset($_POST['submit'])) {
    $selectedMonth = $_POST["month"];
    $selectedYear = $_POST["year"];
    $currentlyLoggedInUserId = $_POST['user_id'];

    try {
        $pdo = dbconnect(); 
        // Modify the SQL query to select the required columns with product and customer names
        $sql = "SELECT 
        s.product_id,
        p.product_name,
        s.customer_id,
        CONCAT(u.first_name, ' ', u.last_name) AS customer_name,
        sd.quantity,  
        p.product_price,  
        s.amount,
        s.sales_date
    FROM sales s
    JOIN shops sh ON s.shop_id = sh.shop_id
    JOIN users u ON sh.owner_id = u.user_id
    JOIN products p ON s.product_id = p.product_id
    JOIN salesdetails sd ON s.salesdetails_id = sd.salesdetails_id  -- Adjust the join condition based on your actual schema
    WHERE MONTH(s.sales_date) = :month AND YEAR(s.sales_date) = :year
    AND u.user_id = :owner_id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':month', $selectedMonth, PDO::PARAM_INT);
        $stmt->bindParam(':year', $selectedYear, PDO::PARAM_INT);
        $stmt->bindParam(':owner_id', $currentlyLoggedInUserId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        

        // Set headers for Excel download
        header('Content-type: application/xls');
        header('Content-Disposition: attachment; filename=reports.xls');

        if ($result) {
            if (count($result) > 0) {
                $output .= '
                    <table class="table" bordered="1">
                        <tr>
                            <th>Product Name</th>
                            <th>Customer Name</th>
                            <th>Quantity</th>
                            <th>Price Per Product</th>
                            <th>Amount</th>
                            <th>Sales Date</th>
                        </tr>';

                foreach ($result as $row) {
                    $output .= '
                        <tr>
                            <td>' . $row['product_name'] . '</td>
                            <td>' . $row['customer_name'] . '</td>
                            <td>' . $row['quantity'] . '</td>
                            <td>' . $row['product_price'] . '</td>
                            <td>' . $row['amount'] . '</td>
                            <td>' . $row['sales_date'] . '</td>
                        </tr>';
                    
                    // Accumulate the total amount
                    $totalAmount += $row['amount'];
                }

                // Add a row for the total
                $output .= '
                    <tr>
                        <td colspan="4">Total</td>
                        <td>' . $totalAmount . '</td>
                        <td></td>
                    </tr>';

                $output .= '</table>';
            } else {
                // If no data found, output an empty table
                $output .= '<table class="table" bordered="1"></table>';
            }

            // Output the table
            echo $output;
        } else {
            echo 'No Data Found';
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
