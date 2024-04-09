<?php
session_start();

include("../professor_register/connection.php");
include("../professor_register/functions.php");
include("../professor_functions.php");

$professor_data = check_login($con);

// Check if receiver_id is provided
if (isset($_GET['receiver_id'])) {
    $receiver_id = $_GET['receiver_id'];
} else {
    die("Receiver ID not provided.");
}
$sender_id = $professor_data['professor_id'];

// Check if there was a connection error
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if message is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST['message'];

    // Insert message into database (using prepared statement to prevent SQL injection)
    $sql = "INSERT INTO chat_messages (message) VALUES (?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $message);
    if ($stmt->execute() === FALSE) {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}

// Retrieve messages from database
$sql = "SELECT * FROM chat_messages";
$result = mysqli_query($con, $sql);
if (!$result) {
    // Query execution failed, handle the error
    die("Query failed: " . mysqli_error($con));
}

$con->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Chat Application</title>
	<link rel="stylesheet" href="chat_style.css">
</head>
<body>
    <div class='chat-container'>
        <div id="chat-box">
            <!-- Messages will be displayed here -->
        </div>

        <form method="post" action="send_professor_message.php">
            <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
            <textarea name="message" placeholder="Type your message"></textarea>
            <button type="submit">Send</button>
        </form>
    </div>

    <script>
    // Function to fetch and display messages
    function fetchProfessorMessages() {
        var student_id = "<?php echo $receiver_id; ?>"; // You need to define how to get professor_id here

        var xhr = new XMLHttpRequest();
        xhr.open("GET", "fetch_professor_messages.php?student_id=" + student_id, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState ==  4 && xhr.status ==  200) {
                var chatBox = document.getElementById("chat-box");
                chatBox.innerHTML = xhr.responseText; // Update chat box with all messages
                // Scroll to bottom of chat box
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        };
        xhr.send();
    }

    // Function to send a message
    function sendMessage(message) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "send_professor_message.php", true); // Changed endpoint to send_message.php
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState ==  4 && xhr.status ==  200) {
                // Fetch and display all messages after sending the message
                fetchProfessorMessages(); // This ensures messages are updated immediately after sending
            }
        };
        var params = "message=" + encodeURIComponent(message);
        xhr.send(params);
    }

    // Call fetchMessages function initially to display existing messages
    fetchProfessorMessages();

    // Add event listener for form submission
    document.getElementById("message-form").addEventListener("submit", function(event) {
        event.preventDefault();
        var messageInput = document.getElementById("message-input").value;
        sendMessage(messageInput);
        // Clear input field after message is sent
        document.getElementById("message-input").value = "";
    });

    </script>
</body>
</html>




