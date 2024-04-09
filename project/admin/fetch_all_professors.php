<?php
// Include database connection
include "../user_register/connection.php";

// Function to fetch all professors in ascending order based on mentions
function getAllProfessors($con) {
    $output = '';
    $query_all_professors = "SELECT professor_id, COUNT(*) AS mentions FROM skills GROUP BY professor_id ORDER BY mentions ASC";
    $result_all_professors = mysqli_query($con, $query_all_professors);
    $output .= "<ul>";
    while ($row_professor = mysqli_fetch_assoc($result_all_professors)) {
        $output .= "<li>Professor ID: " . $row_professor['professor_id'] . ", se pojavljuje: " . $row_professor['mentions'] . "</li>";
    }
    $output .= "</ul>";
    return $output;
}

// Call the function and echo the result
echo getAllProfessors($con);
?>
