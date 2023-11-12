<?php
session_start();
include_once "dbhelper.php";

if (isset($_SESSION['user_id'])) {
    $conn = dbconnect(); // Establish database connection using dbconnect() function from dbhelper.php

    $outgoing_id = $_SESSION['user_id'];
    $incoming_id = $_POST['incoming_id'];
    $output = "";

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT messages.*, users.profile_img 
            FROM messages 
            LEFT JOIN users ON users.user_id = messages.outgoing_msg_id
            WHERE (outgoing_msg_id = :outgoing_id AND incoming_msg_id = :incoming_id)
            OR (outgoing_msg_id = :incoming_id AND incoming_msg_id = :outgoing_id) 
            ORDER BY msg_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':outgoing_id', $outgoing_id);
    $stmt->bindParam(':incoming_id', $incoming_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['outgoing_msg_id'] == $outgoing_id) {
                $output .= '<div class="chat outgoing">
                            <div class="details">
                                <p>'. $row['msg'] .'</p>
                            </div>
                            </div>';
            } else {
                $output .= '<div class="chat incoming">
                             <img src="images/' . $row['profile_img'] . '" alt="Profile Image">
                            <div class="details">
                                <p>'. $row['msg'] .'</p>
                            </div>
                            </div>';
            }
        }
    } else {
        $output .= '<div class="text">No messages are available. Once you send a message, it will appear here.</div>';
    }

    echo $output;

    // Close the database connection
    $conn = null;
} else {
    header("location: ../login.php");
}
?>
