<?php
session_start(); 
$user_id = $_SESSION['user_id'];

$url = "https://api4wrd-v1.kpa.ph/paymongo/v1/create";

$redirect = [
        "success" => "http://172.20.10.3:80/Bulakbuy00/Payments/Subscription/success.php",
        "failed" => "http://172.20.10.3:80/Bulakbuy00/Payments/Subscription/failed.php"
];

$billing = [
    "email" => $_POST["email"],
    "name" =>  $_POST["first_name"] . " " .  $_POST["last_name"],
    "phone" =>  $_POST["mobile"]
];

$amount = intval($_POST["amount"]) * 100; // Convert amount to integer

$attributes = [
    "livemode" => false,
    "type" => "gcash",
    "amount" => $amount,
    "currency" => "PHP",
    "redirect" => $redirect,
    "billing" => $billing,
];

$source = [
    "app_key" => "524c2dddc616c69901a03f54551cc4a9864c6561",
    "secret_key" => "sk_test_SbKTAvuxNeV41rAbTwKQ3FVJ",
    "password" => "@Copernicus1543",
    "data" => [
        "attributes" => $attributes
    ]
];

$jsonData = json_encode($source);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// disable ssl
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($ch);
$resData = json_decode($result, true);

if ($resData["status"] == 200) {
    header("Location: " . $resData["url_redirect"]);
} else {
    // handle error or redirect to an error page
    echo $result;
}

die();
?>
