<?php
// Include database connection file
include "../user_register/connection.php";

// Check if professor ID is set in POST request
if(isset($_POST['professorId'])) {
    // Sanitize professor ID
    $professorId = mysqli_real_escape_string($con, $_POST['professorId']);
    
    // SQL query to delete professor from database
    $query = "DELETE FROM professors WHERE professor_id = '$professorId'";
    
    // Execute the query
    if(mysqli_query($con, $query)) {
        echo "Professor removed successfully!";
    } else {
        echo "Error removing professor: " . mysqli_error($con);
    }
} else {
    echo "Professor ID not provided!";
}
?>
