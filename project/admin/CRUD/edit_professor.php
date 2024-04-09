<?php
// Include database connection file
include "../../user_register/connection.php";

// Check if professor_id is provided
if (isset($_GET['id'])) {
    $professor_id = $_GET['id'];
    
    // Fetch professor details
    $query = "SELECT * FROM professors WHERE professor_id = '$professor_id'";
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
        $lastName = $row['lastName'];
        $email = $row['email'];
        $password = $row['password'];
        $freeTime = $row['freeTime'];
        // Add more fields as needed
    } else {
        echo "Professor not found.";
        exit;
    }
} else {
    echo "Professor ID not provided.";
    exit;
}

// Update professor details
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $new_name = $_POST['name'];
    $new_lastName = $_POST['lastName'];
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];
    $new_freeTime = $_POST['freeTime'];
    // Add more fields as needed

    $update_query = "UPDATE professors SET name = '$new_name', lastName = '$new_lastName', email = '$new_email', password = '$new_password', freeTime = '$new_freeTime' WHERE professor_id = '$professor_id'";
    
    if (mysqli_query($con, $update_query)) {
        header("Location: ../crud_professor_table.php"); // Redirect to professor table after updating
        exit;
    } else {
        echo "Error updating professor: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uredi Profesora</title>
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
        input[type="time"],
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
        <h2>Ažuriraj Profesora</h2>
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
                <label for="password">Lozinka:</label><br>
                <input type="password" id="password" name="password" value="<?php echo $password; ?>"><br>
            </div>
            <div>
                <label for="freeTime">Slobodno vreme:</label><br>
                <input type="time" id="freeTime" name="freeTime" value="<?php echo $freeTime; ?>"><br>
            </div>
            <!-- Add more fields as needed -->
            <br>
            <input type="submit" value="Sačuvaj">
        </form>
    </div>
</body>
</html>
