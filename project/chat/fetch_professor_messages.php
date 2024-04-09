<?php
session_start();

// Include database connection
include("../professor_register/connection.php");

// Check if there was a connection error
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['professor_id'])) {
    // Redirect to login page if not logged in
    header('Location: ../professor_register/login.php');
    exit;
}

// Retrieve user ID from session
$professor_id = $_SESSION['professor_id'];

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];        
} else {
    die("Student ID not provided.");
}

// Retrieve messages from database in reverse order
$sql = "SELECT sender_id, receiver_id, message FROM chat_messages WHERE (sender_id = '$professor_id' AND receiver_id='$student_id') OR (receiver_id = '$professor_id' AND sender_id='$student_id') ORDER BY timestamp LIMIT 10";
$result = mysqli_query($con, $sql);

if (!$result) {
    // Query execution failed, handle the error
    die("Query failed: " . mysqli_error($con));
}

$messages = '';

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        // Display the message only if the current user is either the sender or receiver
        if ($row['sender_id'] == $professor_id) {
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
