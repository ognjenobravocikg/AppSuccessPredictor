<?php
// Start the session to access session variables
session_start();

include "../user_register/connection.php";
include "admin_function.php";

// Check if the admin is logged in, if not, redirect to the login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to login page
    exit;
}

$user_data = check_login($con);

// Set username session variable
$_SESSION['username'] = $user_data['username'];

// Function to fetch users and professors along with registration dates
function getUsersAndProfessors($con) {
    $output = '';
    
    // Fetch users
    $query_users = "SELECT * FROM users";
    $result_users = mysqli_query($con, $query_users);
    
    $output .= "<h2>Users</h2>";
    $output .= "<table border='1'>";
    $output .= "<tr><th>Username</th><th>Email</th><th>Registration Date</th></tr>";
    
    while ($row_users = mysqli_fetch_assoc($result_users)) {
        $output .= "<tr>";
        $output .= "<td>" . $row_users['username'] . "</td>";
        $output .= "<td>" . $row_users['email'] . "</td>";
        $output .= "<td>" . $row_users['registration_date'] . "</td>";
        $output .= "</tr>";
    }
    
    $output .= "</table>";
    
    // Fetch professors
    $query_professors = "SELECT * FROM professors";
    $result_professors = mysqli_query($con, $query_professors);
    
    $output .= "<h2>Professors</h2>";
    $output .= "<table border='1'>";
    $output .= "<tr><th>Name</th><th>Email</th><th>Registration Date</th></tr>";
    
    while ($row_professors = mysqli_fetch_assoc($result_professors)) {
        $output .= "<tr>";
        $output .= "<td>" . $row_professors['name'] . "</td>";
        $output .= "<td>" . $row_professors['email'] . "</td>";
        $output .= "<td>" . $row_professors['registration_date'] . "</td>";
        $output .= "</tr>";
    }
    
    $output .= "</table>";
    
    return $output;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
    body {
        font-family: 'Roboto', sans-serif; /* Change the font to Roboto */
        margin: 0;
        margin-left: 20px; /* Keep margin-left */
        padding: 0;
        background-color: #f4f4f4; /* Light background color */
        background-image: url(jean-philippe-delberghe-75xPHEQBmvA-unsplash.jpg);
    }

    h1 {
        color: #333; /* Dark text color */
    }

    p {
        color: #666; /* Gray text color */
    }

    button {
        padding: 10px 20px;
        background-color: #4CAF50; /* Green button color */
        color: #fff; /* White text color */
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    
    .button:hover{
        background-color: #45a049; /* Darker shade of green on hover */
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px; /* Keep margin-top */
    }

    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd; /* Light border */
    }

    th {
        background-color: #4CAF50; /* Blue background color */
        color: #fff; /* White text color */
    }

    tr:hover {
        background-color: #45a049; /* Light gray background color on hover */
    }

    a {
        color: #45a049; /* Blue link color */
        text-decoration: none;
        margin-top: 20px; /* Keep margin-top */
        display: inline-block;
    }
    </style></head>
<body>
    <h1>Zdravo administratore!</h1>
    <p>Ovo je glavna administratorska stranica, koristi se za gledanje CRUD tabela korisnika, kao i pretrazivanje analitike sajta. Hvala na saradnji !</p>

    <!-- Button to show users and professors -->
    <button onclick="toggleUsersAndProfessors()">Prikaži korisnike i profesore</button>
    <div id="usersAndProfessorsContainer" style="display: none;"></div>
    
    <!-- Button to show professors popularity -->
    <button onclick="showProfessorsPopularity()">Prikaži popularnost profesora</button>
    <div id="professorPopularityContainer" style="display: none;"></div>

    <!-- Button to show categories popularity -->
    <button onclick="showCategoriesPopularity()">Prikaži popularnost kategorija</button>
    <div id="categoriesPopularityContainer" style="display: none;"></div>
    

    <br>
    <a href="crud_user_table.php">CRUD tabela korisnika</a>
    <br>
    <a href="crud_professor_table.php">CRUD tabela profesora</a>


    <script>
        var tableVisible = false;

        function toggleUsersAndProfessors() {
            var container = document.getElementById("usersAndProfessorsContainer");
            tableVisible = !tableVisible;
            if (tableVisible) {
                // Ajax request to fetch users and professors
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        container.innerHTML = this.responseText;
                        container.style.display = "block";
                    }
                };
                xhttp.open("GET", "fetch_users_and_professors.php", true);
                xhttp.send();
            } else {
                container.style.display = "none";
            }
        }

        function closeTable(tableId) {
            var table = document.getElementById(tableId);
            table.style.display = "none";
        }

        // Move removeUser and removeProfessor outside showUsersAndProfessors function
        function removeUser(userId) {
            if (confirm("Are you sure you want to remove this user?")) {
                // Send AJAX request to remove user
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Reload the page to reflect changes
                        location.reload();
                    }
                };
                xhttp.open("POST", "remove_user.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("userId=" + userId);
            }
        }

        function removeProfessor(professorId) {
            if (confirm("Are you sure you want to remove this professor?")) {
                // Send AJAX request to remove professor
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Reload the page to reflect changes
                        location.reload();
                    }
                };
                xhttp.open("POST", "remove_professor.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("professorId=" + professorId);
            }
        }

        function showProfessorsPopularity() {
            var container = document.getElementById("professorPopularityContainer");
            // Toggle visibility
            if (container.style.display === "none") {
                // Fetch professors popularity using AJAX
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        container.innerHTML = "<h2>Professors Popularity</h2>" + this.responseText;
                        container.style.display = "block";
                    }
                };
                xhttp.open("GET", "fetch_all_professors.php", true);
                xhttp.send();
            } else {
                container.style.display = "none";
            }
        }

        function showCategoriesPopularity() {
            var container = document.getElementById("categoriesPopularityContainer");
            // Toggle visibility
            if (container.style.display === "none") {
                // Fetch categories popularity using AJAX
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        container.innerHTML = "<h2>Categories Popularity</h2>" + this.responseText;
                        container.style.display = "block";
                    }
                };
                xhttp.open("GET", "fetch_all_categories.php", true);
                xhttp.send();
            } else {
                container.style.display = "none";
            }
        }

    </script>
    <br><br>
    
    <a href="admin_logout.php">Odjavi se</a> <!-- Link to logout page -->
</body>
</html>

