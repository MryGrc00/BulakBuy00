<?php
session_start();
include_once "dbhelper.php";

// Establish database connection
$conn = dbconnect();

$input = $_POST['input'];
$password = $_POST['password'];

if (!empty($input) && !empty($password)) {
    // Sanitize and validate the email or username input
    $sanitizedInput = filter_var($input, FILTER_SANITIZE_STRING);

    // Check if the input is a valid email
    if (filter_var($sanitizedInput, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :input");
    } else {
        // The input is not a valid email, treat it as a username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :input");
    }

    $stmt->bindParam(':input', $sanitizedInput);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_pass = md5($password);
        $enc_pass = $row['password'];

        if ($user_pass === $enc_pass) {
            if ($row['e_status'] === 'Verified') {
                $status = "Active now";
                $updateStatus = $conn->prepare("UPDATE users SET status = :status WHERE user_id = :user_id");
                $updateStatus->bindParam(':status', $status);
                $updateStatus->bindParam(':user_id', $row['user_id']);
                $updateStatus->execute();

                if ($updateStatus) {
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['role'] = $row['role'];  // Store the user's role in the session

                    // Redirect based on the user role
                    if ($row['role'] === 'customer') {
                        echo "success|customer";
                    } else if ($row['role'] === 'arranger') {
                        echo "success|arranger";
                    } else if ($row['role'] === 'seller') {
                        echo "success|seller";
                    }
                } else {
                    echo "Something went wrong. Please try again!";
                }
            } else {
                echo "Email not verified!";
            }
        } else {
            echo "Invalid Credentials!";
        }
    } else {
        echo "Invalid Credentials!";
    }
} else {
    echo "All input fields are required!";
}

// Close the database connection
$conn = null;
?>
