<?php
// Start the session to access the user_id
session_start();

// Check if the user is logged in (adjust the condition as needed)
if (isset($_SESSION['user_id'])) {
    // Get user_id from the session
    $complainant_id = $_SESSION['user_id'];

    // Get other data from the form
    $report_text = $_POST['report_text'];
    $complain_date = date('Y-m-d'); // Current date

    // Perform database operations to insert the report
    // Replace the placeholders with your actual database connection code
    $pdo = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

    // Assuming you have a table named 'reports'
    $sql = "INSERT INTO reports (complainant_id, defendant_id, reason, complain_date) 
            VALUES (:complainant_id, :defendant_id, :reason, :complain_date)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':complainant_id', $complainant_id);
    $stmt->bindParam(':defendant_id', $defendant_id); // You need to set the shop_id here
    $stmt->bindParam(':reason', $report_text);
    $stmt->bindParam(':complain_date', $complain_date);

    // Execute the statement
    $stmt->execute();

    // Redirect to a success page or do other actions as needed
    header('Location: success_page.php');
    exit();
} else {
    // Redirect to login page or handle the case where the user is not logged in
    header('Location: index.php');
    exit();
}
?>
