<?php
session_start();

if (isset($_SESSION['unique_id'])) {
    include_once "config.php";

    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $output = "";

    $sql = "SELECT * FROM messages LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
            WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
            OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) ORDER BY msg_id";

    $query = mysqli_query($conn, $sql);

    if(mysqli_num_rows($query) > 0) {
        while($row = mysqli_fetch_assoc($query)) {
            $time = date('h:i A', strtotime($row['timestamp']));
            $message = $row['msg'];

            if (!empty($message) || isImage($message)) {
                if ($row['outgoing_msg_id'] === $outgoing_id) {
                    $output .= '<div class="chat outgoing">
                                <div class="details">
                                    '. getMessageContent($conn, $message) .'
                                    <span class="time">' . $time . '</span>
                                </div>
                            </div>';
                } else {
                    $output .= '<div class="chat incoming">
                                <img src="php/images/'.$row['img'].'" alt="User Image">
                                <div class="details">
                                    '. getMessageContent($conn, $message) .'
                                    <span class="time">' . $time . '</span>
                                </div>
                            </div>';
                }
            }
        }
    } else {
        $output .= '<div class="text">No messages are available. Once you send a message, they will appear here.</div>';
    }

    echo $output;
} else {
    echo "Invalid request!";
}

function isImage($message) {
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $extension = pathinfo($message, PATHINFO_EXTENSION);
    return in_array($extension, $imageExtensions);
}

function getMessageContent($conn, $message) {
    if (isImage($message)) {
        return '<img src="php/images/'.$message.'" alt="Image">';
    } else {
        return '<p>'. $message .'</p>';
    }
}
?>
