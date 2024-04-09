<?php
// Include database connection file
include "../user_register/connection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela korisnika</title>
    <style>
        /* CSS for professor table */
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

        /* CSS for search result */
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

        /* CSS for search form */
        .search-form {
            margin-top: 20px;
        }

        .search-form input[type="text"],
        .search-form input[type="submit"] {
            margin-left:5px;
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

        /* CSS for edit and delete links */
        .edit-link, .delete-link {
            text-decoration: none;
            color: blue;
            margin-right: 5px;
        }

        .edit-link:hover, .delete-link:hover {
            text-decoration: underline;
        }

        /* CSS for user table */
        .user-page {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .user-table-container {
            margin-top: 20px;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-table th, .user-table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        .user-table th {
            background-color: #f2f2f2;
        }

        .no-user-found {
            margin-top: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <a href="admin_index.php">Vrati se</a>

<?php
// Function to fetch users
function fetchUsers($con, $searchTerm = "") {
    $output = '';
    
    // Construct base query
    $query = "SELECT * FROM users";
    
    // Add WHERE clause if search term is provided
    if (!empty($searchTerm)) {
        $query .= " WHERE name LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%'";
    }
    
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $output .= "<div class='user-table-container'>";
        $output .= "<table class='user-table'>";
        $output .= "<tr><th>User_id</th><th>Password</th><th>Name</th><th>Last Name</th><th>Email</th><th>Interests</th><th>Skills</th><th>Education</th><th>Actions</th></tr>";


        while ($row = mysqli_fetch_assoc($result)) {
            $output .= "<tr>";
            $output .= "<td>" . $row['user_id'] . "</td>";
            $output .= "<td>" . $row['password'] . "</td>";
            $output .= "<td>" . $row['name'] . "</td>";
            $output .= "<td>" . $row['lastName'] . "</td>";
            $output .= "<td>" . $row['email'] . "</td>";
            $output .= "<td>" . $row['skill'] . "</td>";
            $output .= "<td>" . $row['interests'] . "</td>";
            $output .= "<td>" . $row['education'] . "</td>";
            $output .= "<td>";
            $output .= "<a href='CRUD/edit_user.php?id=" . $row['user_id'] . "'>Edit</a> | ";
            $output .= "<a href='CRUD/delete_user.php?id=" . $row['user_id'] . "'>Delete</a>";
            $output .= "</td>";
            $output .= "</tr>";
        }
        
        $output .= "</table>";
        $output .= "</div>";
    } else {
        $output .= "<div class='no-user-found'>No users found.</div>";
    }
    
    return $output;
}

// Display users
echo "<div class='user-page'>";
echo "<h2>Tabela korisnika</h2>";

// Check if search term is provided
$searchTerm = isset($_GET['search']) ? $_GET['search'] : "";

if (!empty($searchTerm)) {
    // Fetch and display detailed information of the searched user
    $query = "SELECT * FROM users WHERE name LIKE '%$searchTerm%'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<h3>Search Result</h3>";
        echo "<div class='search-result'>";
        echo "<table class='search-result-table'>";
        echo "<tr><th>User_id</th><th>Password</th><th>Name</th><th>Last Name</th><th>Email</th><th>Interests</th><th>Skills</th><th>Education</th><th>Actions</th></tr>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['user_id'] . "</td>";
            echo "<td>" . $row['password'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['lastName'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['skill'] . "</td>";
            echo "<td>" . $row['interests'] . "</td>";
            echo "<td>" . $row['education'] . "</td>";
            echo "<td>";
            echo "<a href='CRUD/edit_user.php?id=" . $row['user_id'] . "'>Edit</a> | ";
            echo "<a href='CRUD/delete_user.php?id=" . $row['user_id'] . "'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "</div>";
    } else {
        echo "<div class='no-user-found'>User not found.</div>";
    }
} else {
    // Display table of users
    echo "<form class='search-form' method='GET'>";
    echo "<input type='text' name='search' value='$searchTerm' placeholder='Search by name or email'>";
    echo "<input type='submit' value='Search'>";
    echo "</form>";
    echo fetchUsers($con, $searchTerm);
}
echo "</div>";
?>
</body>
</html>
