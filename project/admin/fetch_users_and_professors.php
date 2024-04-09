<?php
// Include database connection file
include "../user_register/connection.php";

// Function to fetch users and professors
function getUsersAndProfessors($con) {
    $output = '';
    
    // Fetch users
    $query_users = "SELECT * FROM users ORDER BY registration_date DESC"; // Sort users by registration date in descending order
    $result_users = mysqli_query($con, $query_users);
    
    $output .= "<h2>Users</h2>";
    $output .= "<table border='1'>";
    $output .= "<tr><th>Name</th><th>Email</th><th>Registration Date</th><th>Remove</th></tr>";
    
    while ($row_users = mysqli_fetch_assoc($result_users)) {
        $output .= "<tr>";
        $output .= "<td>" . $row_users['name'] . "</td>";
        $output .= "<td>" . $row_users['email'] . "</td>";
        $output .= "<td>" . $row_users['registration_date'] . "</td>";
        $output .= "<td><button onclick='removeUser(" . $row_users['user_id'] . ")'>Remove</button></td>"; // Add remove button for each user
        $output .= "</tr>";
    }
    
    $output .= "</table>";
    
    // Fetch professors
    $query_professors = "SELECT * FROM professors ORDER BY registration_date DESC"; // Sort professors by registration date in descending order
    $result_professors = mysqli_query($con, $query_professors);
    
    $output .= "<h2>Professors</h2>";
    $output .= "<table border='1'>";
    $output .= "<tr><th>Name</th><th>Email</th><th>Registration Date</th><th>Remove</th></tr>";
    
    while ($row_professors = mysqli_fetch_assoc($result_professors)) {
        $output .= "<tr>";
        $output .= "<td>" . $row_professors['name'] . "</td>";
        $output .= "<td>" . $row_professors['email'] . "</td>";
        $output .= "<td>" . $row_professors['registration_date'] . "</td>";
        $output .= "<td><button onclick='removeProfessor(" . $row_professors['professor_id'] . ")'>Remove</button></td>"; // Add remove button for each professor
        $output .= "</tr>";
    }
    
    $output .= "</table>";
    
    return $output;
}

// Call the function to fetch users and professors
echo getUsersAndProfessors($con);
?>




