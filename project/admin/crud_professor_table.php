<?php
// Include database connection file
include "../professor_register/connection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela profesora</title>
    <style>
        /* Style for professor table */
        .professor-page {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .professor-table-container {
            margin-top: 20px;
        }

        .professor-table {
            width: 100%;
            border-collapse: collapse;
        }

        .professor-table th, .professor-table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        .professor-table th {
            background-color: #f2f2f2;
        }

        .no-professor-found {
            margin-top: 20px;
            font-style: italic;
        }

        /* Style for search result */
        .search-result {
            margin-top: 20px;
        }

        .search-result-table {
            width: 100%;
            border-collapse: collapse;
        }

        .search-result-table th, .search-result-table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        .search-result-table th {
            background-color: #f2f2f2;
        }

        /* Style for search form */
        .search-form {
            margin-top: 20px;
        }

        .search-form input[type="text"],
        .search-form input[type="submit"] {
            margin-left: 5px;
            padding: 6px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        /* Style for edit and delete links */
        .edit-link, .delete-link {
            text-decoration: none;
            color: blue;
            margin-right: 5px;
        }

        .edit-link:hover, .delete-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <a href="admin_index.php">Vrati se</a>


<?php
// Function to fetch professors
function fetchProfessors($con, $searchTerm = "") {
    $output = '';
    
    // Construct base query
    $query = "SELECT * FROM professors";
    
    // Add WHERE clause if search term is provided
    if (!empty($searchTerm)) {
        $query .= " WHERE name LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%'";
    }
    
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $output .= "<div class='professor-table-container'>";
        $output .= "<table class='professor-table'>";
        $output .= "<tr><th>Professor id</th><th>Password</th><th>Name</th><th>Last Name</th><th>Email</th><th>Available on</th><th>Registration time</th><th>Actions</th></tr>";        
        
        while ($row = mysqli_fetch_assoc($result)) {
            $output .= "<tr>";
            $output .= "<td>" . $row['professor_id'] . "</td>";
            $output .= "<td>" . $row['password'] . "</td>";
            $output .= "<td>" . $row['name'] . "</td>";
            $output .= "<td>" . $row['lastName'] . "</td>";
            $output .= "<td>" . $row['email'] . "</td>";
            $output .= "<td>" . $row['freeTime'] . "</td>";
            $output .= "<td>" . $row['registration_date'] . "</td>";
            $output .= "<td>";
            $output .= "<a class='edit-link' href='CRUD/edit_professor.php?id=" . $row['professor_id'] . "'>Edit</a> | ";
            $output .= "<a class='delete-link' href='CRUD/delete_professor.php?id=" . $row['professor_id'] . "'>Delete</a>";
            $output .= "</td>";
            $output .= "</tr>";
        }
        
        $output .= "</table>";
        $output .= "</div>";
    } else {
        $output .= "<div class='no-professor-found'>No professors found.</div>";
    }
    
    return $output;
}

// Display professors
echo "<div class='professor-page'>";
echo "<h2>Tabela profesora</h2>";

// Check if search term is provided
$searchTerm = isset($_GET['search']) ? $_GET['search'] : "";

if (!empty($searchTerm)) {
    // Fetch and display detailed information of the searched professor
    $query = "SELECT * FROM professors WHERE name LIKE '%$searchTerm%'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<h3>Search Result</h3>";
        echo "<div class='search-result'>";
        echo "<table class='search-result-table'>";
        echo "<tr><th>Professor id</th><th>Password</th><th>Name</th><th>Last Name</th><th>Email</th><th>Interests</th><th>Available on</th><th>Registration date</th><th>Actions</th></tr>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['professor_id'] . "</td>";
            echo "<td>" . $row['password'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['lastName'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['freeTime'] . "</td>";
            echo "<td>" . $row['registration_date'] . "</td>";
            echo "<td>";
            echo "<a class='edit-link' href='CRUD/edit_professor.php?id=" . $row['professor_id'] . "'>Edit</a> | ";
            echo "<a class='delete-link' href='CRUD/delete_professor.php?id=" . $row['professor_id'] . "'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "</div>";
    } else {
        echo "<div class='no-professor-found'>Professor not found.</div>";
    }
} else {
    // Display table of professors
    echo "<form class='search-form' method='GET'>";
    echo "<input type='text' name='search' value='$searchTerm' placeholder='Search by name or email'>";
    echo "<input type='submit' value='Search'>";
    echo "</form>";
    echo fetchProfessors($con, $searchTerm);
}
echo "</div>";
?>

</body>
</html>
