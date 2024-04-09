<?php
// Include database connection file
include "../../professor_register/connection.php";

// Check if user_id is provided
if (isset($_GET['id'])) {
    $professor = $_GET['id'];
    
    // Delete user
    $delete_query = "DELETE FROM professors WHERE professor_id = '$professor_id'";
    
    if (mysqli_query($con, $delete_query)) {
        header("Location: ../crud_professor_table.php"); // Redirect to user table after deleting
        exit;
    } else {
        echo "Error deleting professor: " . mysqli_error($con);
    }
} else {
    echo "Professor ID not provided.";
    exit;
}
?>