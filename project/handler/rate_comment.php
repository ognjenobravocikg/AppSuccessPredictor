<?php
session_start();
include("../user_register/connection.php");
include("../user_register/functions.php");

$user_data = check_login($con);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST["professor_id"], $_POST["rating"], $_POST["comment"])) {
        // Sanitize inputs
        $professor_id = mysqli_real_escape_string($con, $_POST["professor_id"]);
        $student_id = $user_data['user_id'];
        $rating = mysqli_real_escape_string($con, $_POST["rating"]);
        $comment = mysqli_real_escape_string($con, $_POST["comment"]);

        // Echo the student_id
        echo "Student ID: " . $student_id . "<br>";

        // Insert the rating and comment into the database
        $insert_query = "INSERT INTO professor_stats (professor_id, student_id, rating, comment) VALUES ('$professor_id', '$student_id', '$rating', '$comment')";

        if (mysqli_query($con, $insert_query)) {
            echo "Rating and comment submitted successfully.";
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "All fields are required.";
    }
} else {
    echo "Invalid request method.";
}
    
?>