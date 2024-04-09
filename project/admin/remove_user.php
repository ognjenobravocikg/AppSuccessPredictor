<?php
// Include database connection file
include "../user_register/connection.php";

// Check if user ID is set in POST request
if(isset($_POST['userId'])) {
    // Sanitize user ID
    $userId = mysqli_real_escape_string($con, $_POST['userId']);
    
    // SQL query to delete user from database
    $query = "DELETE FROM users WHERE user_id = '$userId'";
    
    // Execute the query
    if(mysqli_query($con, $query)) {
        echo "User removed successfully!";
    } else {
        echo "Error removing user: " . mysqli_error($con);
    }
} else {
    echo "User ID not provided!";
}
?>
