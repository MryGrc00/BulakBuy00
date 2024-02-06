<?php
include '../php/dbhelper.php'; // Include your actual dbhelper file
require_once "../../dompdf/autoload.inc.php";
use Dompdf\Dompdf;

$totalAmount = 0;
$html = ''; // Initialize $html

if (isset($_POST['submit'])) {
    $selectedMonth = $_POST["month"];
    $selectedYear = $_POST["year"];
    $currentlyLoggedInUserId = $_POST['user_id'];

    try {
        $pdo = dbconnect(); 
        $sql = "SELECT 
                sd.*,  
                CONCAT(u_arranger.first_name, ' ', u_arranger.last_name) AS arranger_name,
                CONCAT(u_customer.first_name, ' ', u_customer.last_name) AS customer_name,
                sp.package_name
            FROM servicedetails sd
            JOIN services sr ON sd.service_id = sr.service_id
            JOIN users u_arranger ON sr.arranger_id = u_arranger.user_id
            JOIN users u_customer ON sd.customer_id = u_customer.user_id
            JOIN service_package sp ON sd.package_id = sp.package_id
            WHERE MONTH(sd.date) = :month AND YEAR(sd.date) = :year
            AND sr.arranger_id = :arranger_id"; 

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':month', $selectedMonth, PDO::PARAM_INT);
        $stmt->bindParam(':year', $selectedYear, PDO::PARAM_INT);
        $stmt->bindParam(':arranger_id', $currentlyLoggedInUserId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


        if ($result) {
            if (count($result) > 0) {
                $pdf = new Dompdf();

                $html .= '
                <table style="border-collapse: collapse; width: 100%;" border="1">
                        <tr>
                            <th style="border: 1px solid #000;">Customer Name</th>
                            <th style="border: 1px solid #000;">Package Name</th>
                            <th style="border: 1px solid #000;">Date</th>
                            <th style="border: 1px solid #000;">Time</th>
                            <th style="border: 1px solid #000;">Amount</th>
                        </tr>';

                foreach ($result as $row) {
                    $html .= '
                        <tr>
                            <td style="border: 1px solid #000;">' . $row['customer_name'] . '</td>
                            <td style="border: 1px solid #000;">' . $row['package_name'] . '</td>
                            <td style="border: 1px solid #000;">' . $row['date'] . '</td>
                            <td style="border: 1px solid #000;">' . $row['time'] . '</td>
                            <td style="border: 1px solid #000;">' . $row['amount'] . '</td>
                        </tr>';
                    
                    // Accumulate the total amount
                    $totalAmount += $row['amount'];
                }

                // Add a row for the total
                $html .= '
                    <tr>
                        <td colspan="4">Total</td>
                        <td colspan="1">' . $totalAmount . '</td>
                    </tr>';

                $html .= '</table>';

                $pdf->loadHtml($html);

                // Set paper size and orientation
                $pdf->setPaper('A4', 'portrait');

                // Render PDF (first pass to get total pages)
                $pdf->render();

                // Output PDF to the browser
                $pdf->stream("service_reports.pdf");
            } else {
                // If no data found, output an empty table
                $html .= '<table class="table" bordered="1"></table>';
            }
        } else {
            echo 'No Data Found';
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
