<?php
session_start();

include("../professor_register/connection.php");
include("../professor_register/functions.php");
include("../professor_functions.php");

$professor_data = check_login($con);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];
    if (isset($professor_data['professor_id'])) {
        $sender_id = $professor_data['professor_id'];
    } else {
        $sender_id = $professor_data['professor_id'];
    }

    // Insert message into database using prepared statement
    $query = "INSERT INTO chat_messages (sender_id, receiver_id, message, timestamp) VALUES (?, ?, ?, NOW())";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sss", $sender_id, $receiver_id, $message);
    
    if ($stmt->execute()) {
        header("Location: professor_chat.php?receiver_id=$receiver_id");
        exit;
    } else {
        echo "Error sending message: " . $stmt->error;
    }
}
?>