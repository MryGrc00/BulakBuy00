<?php
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    
    $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = :user_id OR outgoing_msg_id = :user_id)
            AND (outgoing_msg_id = :outgoing_id OR incoming_msg_id = :outgoing_id)
            ORDER BY msg_id DESC LIMIT 1";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bindParam(':user_id', $row['user_id']);
    $stmt2->bindParam(':outgoing_id', $outgoing_id);
    $stmt2->execute();
    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

    if ($stmt2 && $stmt2->rowCount() > 0) {
        $result = $row2['msg'];
        $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;
        $you = ($row2['outgoing_msg_id'] == $outgoing_id) ? "You: " : "";

        $offline = ($row['status'] == "Offline now") ? "offline" : "";
        $hid_me = ($outgoing_id == $row['user_id']) ? "hide" : "";

        $output .= '<a href="chat.php?user_id=' . $row['user_id'] . '">
                    <div class="content">
                        <img src="images/' . $row['profile_img'] . '" alt="">
                        <div class="details">
                            <span>' . $row['first_name'] . " " . $row['last_name'] . '</span>
                            <p>' . $you . $msg . '</p>
                        </div>
                    </div>
                    <div class="status-dot ' . $offline . '"><i class="fas fa-circle"></i></div>
                </a>';
    }
}
?>
