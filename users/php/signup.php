<?php
session_start();
date_default_timezone_set('Asia/Manila');
include_once "dbhelper.php";
include_once "mail.php"; // Include the file with the sendOTP function

// Establish database connection
$conn = dbconnect();

// Get all the form data including email
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$username = $_POST['username'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$email = $_POST['email']; 
$role = isset($_POST['role']) ? $_POST['role'] : '';
$phone = $_POST['phone'];
$address = $_POST['address'];
$zipcode = $_POST['zipcode'];
$registrationDate = date('Y-m-d H:i:s'); // Current date and time in MySQL datetime format

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(array("success" => false, "message" => "Invalid email address!"));
    exit;
}

if (!empty($fname) && !empty($lname) && !empty($username) && !empty($password) && !empty($cpassword) && !empty($email) && !empty($role) && !empty($phone) && !empty($address) && !empty($zipcode)) {
    if ($password === $cpassword) {
        // Generate random 6-digit OTP
        $otp = sprintf("%06d", mt_rand(1, 999999));

        try {
            // Check if username already exists
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo json_encode(array("success" => false, "message" => "$username - This username already exists!"));
            } else {
                $status = "Active now";
                $email_stat = "Not Verified"; //
                $encrypt_pass = md5($password); // Note: md5 is not the most secure way to hash passwords, consider using password_hash() instead

                // Insert user data into the database with the email field
                $insert_query = $conn->prepare("INSERT INTO users (first_name, last_name, username, password, email, role, phone, address, zipcode, status, otp, e_status, date)
                VALUES (:fname, :lname, :username, :password, :email, :role, :phone, :address, :zipcode, :status, :otp, :email_stat, :registrationDate)");

                $insert_query->bindParam(':fname', $fname);
                $insert_query->bindParam(':lname', $lname);
                $insert_query->bindParam(':username', $username);
                $insert_query->bindParam(':password', $encrypt_pass);
                $insert_query->bindParam(':email', $email); // Bind email parameter
                $insert_query->bindParam(':role', $role);
                $insert_query->bindParam(':phone', $phone);
                $insert_query->bindParam(':address', $address);
                $insert_query->bindParam(':zipcode', $zipcode);
                $insert_query->bindParam(':status', $status);
                $insert_query->bindParam(':otp', $otp);
                $insert_query->bindParam(':email_stat', $email_stat);
                $insert_query->bindParam(':registrationDate', $registrationDate);

                if ($insert_query->execute()) {
                    // Send OTP via email using the sendOTP function
                    if (sendOTP($email, $otp)) {
                        // Send success response with OTP to the client-side JavaScript
                        echo json_encode(array("success" => true, "message" => "OTP sent successfully."));
                        $_SESSION['email'] = $email;
                        $_SESSION['user_id'] = $conn->lastInsertId(); // Assuming you're using auto-increment for user_id
                    } else {
                        echo json_encode(array("success" => false, "message" => "Failed to send OTP."));
                    }
                } else {
                    echo json_encode(array("success" => false, "message" => "Failed to register. Please try again!"));
                }
            }
        } catch (PDOException $e) {
            echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Passwords do not match!"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "All input fields are required!"));
}

// Close the database connection
$conn = null;
?>
