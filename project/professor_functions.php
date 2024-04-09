<?php

function fetch_ratings_comments($con, $follower_id) {
    // Sanitize the follower_id to prevent SQL injection
    $follower_id = mysqli_real_escape_string($con, $follower_id);

    // Query to fetch ratings and comments for the follower
    $query = "SELECT rating, comment FROM professor_stats WHERE student_id = '$follower_id'";

    // Execute the query
    $result = mysqli_query($con, $query);

    // Check if the query was successful
    if ($result) {
        // Initialize an array to store ratings and comments
        $ratings_comments = array();

        // Fetch the rows from the result set
        while ($row = mysqli_fetch_assoc($result)) {
            // Add each rating and comment to the array
            $ratings_comments[] = $row;
        }

        // Return the array of ratings and comments
        return $ratings_comments;
    } else {
        // If the query fails, return an empty array
        return array();
    }
}



function fetch_professor_student($con, $professor_id) {
    $requests = array();
    
    $query = "SELECT professor_student.request_id, users.user_id, users.name AS student_name
              FROM professor_student
              JOIN users ON professor_student.student_id = users.user_id
              WHERE professor_student.professor_id = ? AND professor_student.request_id = 1";
    
    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param("i", $professor_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $requests[] = $row;
            }
        } else {
            // Handle execution error
            error_log("Error executing query: " . $stmt->error);
        }
        $stmt->close();
    } else {
        // Handle preparation error
        error_log("Error preparing query: " . $con->error);
    }
    
    return $requests;
}


function fetch_professor_followers($con, $professor_id) {
    $followers = array();
    
    $query = "SELECT users.user_id, users.name AS student_name, users.skill
              FROM professor_student
              JOIN users ON professor_student.student_id = users.user_id
              WHERE professor_student.professor_id = ? AND professor_student.request_id = 2";
    
    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param("i", $professor_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $followers[] = $row;
            }
        } else {
            // Handle execution error
            error_log("Error executing query: " . $stmt->error);
        }
        $stmt->close();
    } else {
        // Handle preparation error
        error_log("Error preparing query: " . $con->error);
    }
    
    return $followers;
}


?>



