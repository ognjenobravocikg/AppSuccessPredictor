<?php
session_start();

include("../user_register/connection.php");
include("../user_register/functions.php");
include("../user_functions.php");

$user_data = check_login($con);


// Check if receiver_id is provided
if (isset($_GET['receiver_id'])) {
    $receiver_id = $_GET['receiver_id'];
} else {
    die("Receiver ID not provided.");
}
$sender_id = $user_data['user_id'];

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
    <div id="chat-box">
        <!-- Messages will be displayed here -->
    </div>

    <form method="post" action="send_message.php">
        <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
        <textarea name="message" placeholder="Unesi poruku"></textarea>
        <button type="submit">Pošalji</button>
    </form>



    <script>
    // Function to fetch and display messages
    function fetchMessages() {
        var professor_id = "<?php echo $receiver_id; ?>"; // You need to define how to get professor_id here

        var xhr = new XMLHttpRequest();
        xhr.open("GET", "fetch_messages.php?professor_id=" + professor_id, true);
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
        xhr.open("POST", "send_message.php", true); // Changed endpoint to send_message.php
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState ==  4 && xhr.status ==  200) {
                // Fetch and display all messages after sending the message
                fetchMessages(); // This ensures messages are updated immediately after sending
            }
        };
        var params = "message=" + encodeURIComponent(message);
        xhr.send(params);
    }

    // Call fetchMessages function initially to display existing messages
    fetchMessages();

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