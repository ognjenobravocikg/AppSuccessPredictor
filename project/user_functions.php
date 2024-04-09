<?php

function search_professors($con, $search_query) {
    // Sanitize search query
    $search_query = mysqli_real_escape_string($con, $search_query);

    // Perform search query
    $query = "SELECT * FROM professors WHERE name LIKE '%$search_query%' OR lastName LIKE '%$search_query%'";
    $result = mysqli_query($con, $query);

    $professors = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $professors[] = $row;
        }
    }

    return $professors;
}

// Function to fetch followers of the student
function fetch_student_followers($con, $student_id) {
    $followers = array();
    
    $query = "SELECT professor.professor_id, professor.name
              FROM professor_student
              JOIN professor ON professor_student.professor_id = professor.professor_id
              WHERE professor_student.professor_id = ? AND professor_student.request_id = 1";
    
    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param("i", $student_id);
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

// Function to fetch skills for a given professor_id
function fetchSkillsProfessorId($con, $professor_id) {
    // Sanitize professor_id
    $professor_id = mysqli_real_escape_string($con, $professor_id);

    // Perform search query
    $query = "SELECT title, description, image, time_frame FROM skills WHERE professor_id = '$professor_id'";
    $result = mysqli_query($con, $query);

    if (!$result) {
        echo "Error: " . mysqli_error($con);
        return [];
    }

    $skills = [];
    if ($result && mysqli_num_rows($result) >  0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $skills[] = $row;
        }
    }

    return $skills;
}

function search_skills($con, $category) {
    // Sanitize category
    $category = mysqli_real_escape_string($con, $category);

    // Perform search query
    $query = "SELECT title, description, professor_id, image FROM skills WHERE category = '$category'";
    $result = mysqli_query($con, $query);

    if (!$result) {
        echo "Error: " . mysqli_error($con);
        return [];
    }

    $skills = [];
    if ($result && mysqli_num_rows($result) >  0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $skills[] = $row;
        }
    }

    return $skills;
}
?>



