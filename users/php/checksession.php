<?php

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // User is logged in, redirect to the desired page

} else {
    // User is not logged in, redirect back to the login page
    header("Location: index.php"); // Replace 'index.php' with your login page URL
    exit();
}
?>
