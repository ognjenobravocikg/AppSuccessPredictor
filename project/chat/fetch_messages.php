<?php
session_start();

// Include database connection
include("../user_register/connection.php");

// Check if there was a connection error
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: ../user_register/login.php');
    exit;
}

if (isset($_GET['professor_id'])) {
    $professor_id = $_GET['professor_id'];        
} else {
    die("Professor ID not provided.");
}

// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// Retrieve messages from database in reverse order
$sql = "SELECT sender_id, receiver_id, message FROM chat_messages WHERE (sender_id = '$user_id' AND receiver_id = '$professor_id') OR (receiver_id = '$user_id' AND sender_id='$professor_id') ORDER BY timestamp LIMIT 10"; // Change limit as needed
$result = mysqli_query($con, $sql);

if (!$result) {
    // Query execution failed, handle the error
    die("Query failed: " . mysqli_error($con));
}

$messages = '';

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        // Display the message only if the current user is either the sender or receiver
        if ($row['sender_id'] == $user_id) {
            $messages .= "<p>You: " . $row['message'] . "\n";
        } else {
            $messages .= "<p>Receiver: " . $row['message'] . "\n";
        }
    }
}

// Close database connection
$con->close();

// Output messages
echo $messages;
?>
