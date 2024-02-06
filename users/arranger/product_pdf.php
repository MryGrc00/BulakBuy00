<?php
include '../php/dbhelper.php'; // Include your actual dbhelper file
require_once "../../dompdf/autoload.inc.php";
use Dompdf\Dompdf;

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
        

        if ($result) {
            if (count($result) > 0) {
                $pdf = new Dompdf();
        
                $html = '
                <table style="border-collapse: collapse; width: 100%;" border="1">
                <tr>
                    <th style="border: 1px solid #000;">Product Name</th>
                    <th style="border: 1px solid #000;">Customer Name</th>
                    <th style="border: 1px solid #000;">Quantity</th>
                    <th style="border: 1px solid #000;">Price Per Product</th>
                    <th style="border: 1px solid #000;">Amount</th>
                    <th style="border: 1px solid #000;">Sales Date</th>
                </tr>';
        
                foreach ($result as $row) {
                    $html .= '
                        <tr>
                        <td style="border: 1px solid #000;">' . $row['product_name'] . '</td>
                        <td style="border: 1px solid #000;">' . $row['customer_name'] . '</td>
                        <td style="border: 1px solid #000;">' . $row['quantity'] . '</td>
                        <td style="border: 1px solid #000;">' . $row['product_price'] . '</td>
                        <td style="border: 1px solid #000;">' . $row['amount'] . '</td>
                        <td style="border: 1px solid #000;">' . $row['sales_date'] . '</td>
                        </tr>';
        
                    // Accumulate the total amount
                    $totalAmount += $row['amount'];
                }
        
                // Add a row for the total
                $html .= '
                    <tr>
                        <td colspan="4">Total</td>
                        <td>' . $totalAmount . '</td>
                        <td></td>
                    </tr>';
        
                $html .= '</table>';
        
                // Load HTML content into Dompdf
                $pdf->loadHtml($html);

        
                // Set paper size and orientation
                $pdf->setPaper('A4', 'portrait');
        
                // Render PDF (first pass to get total pages)
                $pdf->render();
        
                // Output PDF to the browser
                $pdf->stream("reports.pdf");
            } else {
                // If no data found, output an empty table
                $output .= '<table class="table" bordered="1"></table>';
            }
        } else {
            echo 'No Data Found';
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
