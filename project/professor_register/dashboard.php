<?php
    session_start();
    
    // Check if the user is logged in
    if (!isset($_SESSION['user_data'])) {
        // If not logged in, redirect to the login page
        header("Location: login.php");
        exit();
    }

    // Retrieve user data from session
    $user_data = $_SESSION['user_data'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to the Dashboard</h1>
    <p>Hello, <?php echo $user_data['username']; ?>!</p>
    <p>Your email: <?php echo $user_data['email']; ?></p>
    <p>Your role: <?php echo $user_data['role']; ?></p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>
