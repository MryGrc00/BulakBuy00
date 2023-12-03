<?php
session_start(); 
include('../php/dbhelper.php'); 

$pdo = dbconnect();

$user_id = $_SESSION['user_id']; 


$stmt = $pdo->prepare("UPDATE users SET status = 'Offline Now' WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);

// Unset all session variables
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

header("Location: index.php");
exit;
?>
