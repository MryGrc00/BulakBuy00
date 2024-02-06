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
        $sql = "SELECT 
                sd.*,  
                CONCAT(u_arranger.first_name, ' ', u_arranger.last_name) AS arranger_name,
                CONCAT(u_customer.first_name, ' ', u_customer.last_name) AS customer_name,
                sr.service_rate
            FROM servicedetails sd
            JOIN services sr ON sd.service_id = sr.service_id
            JOIN users u_arranger ON sr.arranger_id = u_arranger.user_id
            JOIN users u_customer ON sd.customer_id = u_customer.user_id
            WHERE MONTH(sd.date) = :month AND YEAR(sd.date) = :year
            AND sr.arranger_id = :arranger_id"; 

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':month', $selectedMonth, PDO::PARAM_INT);
        $stmt->bindParam(':year', $selectedYear, PDO::PARAM_INT);
        $stmt->bindParam(':arranger_id', $currentlyLoggedInUserId, PDO::PARAM_INT);
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
                            <th>Customer Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>No. of Hours</th>
                            <th>Service Rate</th>
                            <th>Amount</th>
                        </tr>';

                foreach ($result as $row) {
                    $output .= '
                        <tr>
                            <td>' . $row['customer_name'] . '</td>
                            <td>' . $row['date'] . '</td>
                            <td>' . $row['time'] . '</td>
                            <td>' . $row['hours'] . '</td>
                            <td>' . $row['service_rate'] . '</td>
                            <td>' . $row['amount'] . '</td>
                        </tr>';
                    
                    // Accumulate the total amount
                    $totalAmount += $row['amount'];
                }

                // Add a row for the total
                $output .= '
                    <tr>
                        <td colspan="5">Total</td>
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
