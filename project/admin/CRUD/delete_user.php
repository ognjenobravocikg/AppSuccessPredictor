<?php
// Include database connection file
include "../../user_register/connection.php";

// Check if user_id is provided
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    // Delete user
    $delete_query = "DELETE FROM users WHERE user_id = '$user_id'";
    
    if (mysqli_query($con, $delete_query)) {
        header("Location: ../crud_user_table.php"); // Redirect to user table after deleting
        exit;
    } else {
        echo "Error deleting user: " . mysqli_error($con);
    }
} else {
    echo "User ID not provided.";
    exit;
}
?>
