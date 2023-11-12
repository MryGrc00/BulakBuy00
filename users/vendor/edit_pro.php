<?php
include '../php/dbhelper.php';

session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $firstname = htmlspecialchars($_POST['first_name']);
    $lastname = htmlspecialchars($_POST['last_name']);
    $username = htmlspecialchars($_POST['uname']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $zipcode = htmlspecialchars($_POST['zipcode']);
    $address = htmlspecialchars($_POST['address']);

    // Check if the user has uploaded a profile picture
    $profileImage = null; // Default to null (no change)
    if (!empty($_FILES['profile_img']['name'][0])) {
        $uploadFolder = '../../images/';
        $file_name = generateUniqueFileName($_FILES['profile_img']['name'][0]);
        $file_tmp = $_FILES['profile_img']['tmp_name'][0];

        // Validate file type and size
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        if (!in_array(strtolower(pathinfo($file_name, PATHINFO_EXTENSION)), $allowedExtensions) || $_FILES['profile_img']['size'][0] > 2097152) {
            echo "Invalid file format or size. Allowed formats: JPG, JPEG, PNG (Max size: 2MB)";
            exit();
        }

        $newFilePath = $uploadFolder . $file_name;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file_tmp, $newFilePath)) {
            $profileImage = $newFilePath; // Set the new file path
        } else {
            echo "Failed to upload profile picture!";
            exit();
        }
    }

    // Update user profile in the database using the update_record function
    try {
        $pdo = dbconnect();
        $stmt = $pdo->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, username = :username, email = :email, phone = :phone, zipcode = :zipcode, address = :address, profile_img = :profile_img, e_status = CASE WHEN :email <> email THEN 'not_verified' ELSE e_status END WHERE user_id = :user_id");
        $stmt->bindParam(':first_name', $firstname);
        $stmt->bindParam(':last_name', $lastname);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':zipcode', $zipcode);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':profile_img', $profileImage, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();

        header("Location: vendor_product.php");
        exit();
    } catch (PDOException $e) {
        echo "Error updating user profile: " . $e->getMessage();
    }
}
?>
