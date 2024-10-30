<?php
session_start();

if (isset($_SESSION['unique_id'])) {
    include_once "config.php";

    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);

    // Check if a file was uploaded
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg, timestamp, img)
                                    VALUES ({$incoming_id}, {$outgoing_id}, '', NOW(), '{$target_file}')") or die(mysqli_error($conn));

            // Check if the query executed successfully
            if ($sql) {
                echo "Image sent successfully";
            } else {
                echo "Error sending image";
            }
        } else {
            echo "Error uploading image";
        }
    } else {
        // No file uploaded, handle text message
        $message = mysqli_real_escape_string($conn, $_POST['message']);

        if (!empty($message)) {
            $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg, timestamp)
                                        VALUES ({$incoming_id}, {$outgoing_id}, '{$message}', NOW())") or die(mysqli_error($conn));

            // Check if the query executed successfully
            if ($sql) {
                echo "Message sent successfully";
            } else {
                echo "Error sending message";
            }
        }
    }
} else {
    header("location: ../login.php");
}
?>
