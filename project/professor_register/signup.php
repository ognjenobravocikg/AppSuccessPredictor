<?php 
session_start();

include("connection.php");
include("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $freeTime = $startTime . ' - ' . $endTime;

    if (!empty($name) && !empty($lastName) && !empty($email) && !empty($password) && !empty($freeTime)) {
        $professor_id = random_num(20);
        $query = "INSERT INTO professors (name, lastName, email, password, freeTime,  professor_id, registration_date) VALUES ('$name', '$lastName', '$email', '$password', '$freeTime', '$professor_id', NOW())";
        if (mysqli_query($con, $query)) {
            header("Location: login.php");
            exit;
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "Please enter valid information in all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="professor_style/signup_style.css">
</head>
<body>
    <div class="wrapper">
        <div id="box">
        <h2>Registracija</h2>
            <form method="post" class="signup-form">
                <div class="input-box">
                    <input type="text" name="first_name" placeholder="Ime" required>
                </div>
                <div class="input-box">
                    <input type="text" name="last_name" placeholder="Prezime" required>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="E-mail" required>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Šifra" required>
                </div>
                <div class="input-box">
                    <input type="time" name="startTime" required> - <input type="time" name="endTime" required>
                </div>
                <input id="button" type="submit" value="Registruj se">
                <div class="register-links">
                    <a href="login.php">Već imaš nalog? Uloguj se.</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

