<?php
// Include database connection file
include "../../user_register/connection.php";

// Check if user_id is provided
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    // Fetch user details
    $query = "SELECT * FROM users WHERE user_id = '$user_id'";
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
        $lastName = $row['lastName'];
        $email = $row['email'];
        $password = $row['password'];
        $skill = $row['skill'];
        $interests = $row['interests'];
        $education = $row['education'];
        // Add more fields as needed
    } else {
        echo "User not found.";
        exit;
    }
} else {
    echo "User ID not provided.";
    exit;
}

// Update user details
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $new_name = $_POST['name'];
    $new_lastName = $_POST['lastName'];
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];
    $new_skill = $_POST['skill'];
    $new_interests = $_POST['interests'];
    $new_education = $_POST['education'];
    // Add more fields as needed

    $update_query = "UPDATE users SET name = '$new_name', lastName = '$new_lastName', email = '$new_email', password = '$new_password', skill = '$new_skill', interests = '$new_interests', education = '$new_education' WHERE user_id = '$user_id'";
    
    if (mysqli_query($con, $update_query)) {
        header("Location: ../crud_user_table.php"); // Redirect to user table after updating
        exit;
    } else {
        echo "Error updating user: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ažuriraj Korisnika</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        
        .container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        
        form {
            display: grid;
            grid-template-columns: 1fr;
            grid-row-gap: 10px;
        }
        
        label {
            font-weight: bold;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Ažuriraj korisnika</h2>
        <form method="POST">
            <div>
                <label for="name">Ime:</label><br>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>"><br>
            </div>
            <div>
                <label for="lastName">Prezime:</label><br>
                <input type="text" id="lastName" name="lastName" value="<?php echo $lastName; ?>"><br>
            </div>
            <div>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>"><br>
            </div>
            <div>
                <label for="password">Šifra:</label><br>
                <input type="password" id="password" name="password" value="<?php echo $password; ?>"><br>
            </div>
            <div>
                <label for="skill">Veština:</label><br>
                <input type="text" id="skill" name="skill" value="<?php echo $skill; ?>"><br>
            </div>
            <div>
                <label for="interests">Interesovanja:</label><br>
                <input type="text" id="interests" name="interests" value="<?php echo $interests; ?>"><br>
            </div>
            <div>
                <label for="education">Edukacija:</label><br>
                <input type="text" id="education" name="education" value="<?php echo $education; ?>"><br>
            </div>
            <!-- Add more fields as needed -->
            <br>
            <input type="submit" value="Sačuvaj">
        </form>
    </div>
</body>
</html>
