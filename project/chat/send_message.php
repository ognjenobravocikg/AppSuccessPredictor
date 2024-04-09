<?php
session_start();

include("../user_register/connection.php");
include("../user_register/functions.php");
include("../user_functions.php");

$user_data = check_login($con);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];
    $sender_id=$user_data['user_id'];

    // Insert message into database using prepared statement
    $query = "INSERT INTO chat_messages (sender_id, receiver_id, message, timestamp) VALUES (?, ?, ?, NOW())";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sss", $sender_id, $receiver_id, $message);
    
    if ($stmt->execute()) {
        header("Location: chat.php?receiver_id=$receiver_id");
        exit;
    } else {
        echo "Error sending message: " . $stmt->error;
    }
}
?>
