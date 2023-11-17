<?php
session_start();
include_once "dbhelper.php";
include_once "mail.php"; 

$conn = dbconnect();
$response = array();

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $checkStmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $checkStmt->bindParam(':email', $email);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        try {
            $newOTP = generateOTP(); 
            $updateStmt = $conn->prepare("UPDATE users SET otp = :otp WHERE email = :email");
            $updateStmt->bindParam(':otp', $newOTP);
            $updateStmt->bindParam(':email', $email);
            $updateStmt->execute();

            if (sendOTP($email, $newOTP)) {
                $_SESSION['email'] = $email;
                $response['success'] = "OTP sent successfully.";
            } else {
                $response['error'] = "Failed to send OTP.";
            }
        } catch (PDOException $e) {
            $response['error'] = "Error: " . $e->getMessage();
        }
    } else {
        $response['error'] = "Email does not exist. Please enter a valid email address.";
    }
} else {
    $response['error'] = "No email provided.";
}

echo json_encode($response);

function generateOTP() {
    return rand(100000, 999999);
}
?>
