<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'bulakbuy@gmail.com'; // Your Gmail username
    $mail->Password = 'naxefwvrqbgwnioc'; // Your Gmail password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->setFrom('bulakbuy@gmail.com', 'BulakBuy');
    $mail->addAddress($email); // Use the email from the form for recipient
    $mail->isHTML(true);
    $mail->Subject = 'OTP Code';
    $mail->Body = 'Your OTP Code: ' . $otp;

    try {
        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}
?>
