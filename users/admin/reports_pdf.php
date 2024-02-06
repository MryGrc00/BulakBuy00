<?php
include '../php/dbhelper.php'; // Include your actual dbhelper file
require_once "../../dompdf/autoload.inc.php";
use Dompdf\Dompdf;

$totalAmount = 0;
$amount = 249;

if (isset($_POST['submit'])) {
    $selectedMonth = $_POST["month"];
    $selectedYear = $_POST["year"];

    try {
        $pdo = dbconnect(); 
        // Modify the SQL query to select the required columns with product and customer names
        $sql = "SELECT s.shop_id, s.s_date, s.e_date, s.status, CONCAT(u.first_name, ' ', u.last_name) AS full_name, u.role, u.user_id 
        FROM subscription s 
        JOIN shops sh ON s.shop_id = sh.shop_id 
        JOIN users u ON sh.owner_id = u.user_id 
        WHERE MONTH(s.s_date) = :month AND YEAR(s.s_date) = :year";
        
        
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':month', $selectedMonth, PDO::PARAM_INT);
            $stmt->bindParam(':year', $selectedYear, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        

        if ($result) {
            if (count($result) > 0) {
                $pdf = new Dompdf();
        
                $html = '
                <table style="border-collapse: collapse; width: 100%;" border="1">
                <tr>
                    <th style="border: 1px solid #000;">Subscriber Name</th>
                    <th style="border: 1px solid #000;">Role</th>
                    <th style="border: 1px solid #000;">Start Date</th>
                    <th style="border: 1px solid #000;">End Date</th>
                    <th style="border: 1px solid #000;">Amount</th>
                </tr>';
        
                foreach ($result as $row) {
                    $html .= '
                        <tr>
                        <td style="border: 1px solid #000;">' . $row['full_name'] . '</td>
                        <td style="border: 1px solid #000;">' . $row['role'] . '</td>
                        <td style="border: 1px solid #000;">' . $row['s_date'] . '</td>
                        <td style="border: 1px solid #000;">' . $row['e_date'] . '</td>
                        <td style="border: 1px solid #000;">' . $amount . '</td>
                        </tr>';
        
                    // Accumulate the total amount
                    $totalAmount += $amount;
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
                $pdf->setPaper('A4', 'landscape');
        
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
