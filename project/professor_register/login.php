<?php 

session_start();

include("connection.php");
include("functions.php");

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //something was posted
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(!empty($email) && !empty($password))
    {

        //read from database
        $query = "select * from professors where email = '$email' limit 1";
        $result = mysqli_query($con, $query);

        if($result)
        {
            if($result && mysqli_num_rows($result) > 0)
            {

                $user_data = mysqli_fetch_assoc($result);

                if($user_data['password'] === $password)
                {

                    $_SESSION['professor_id'] = $user_data['professor_id'];
                    header("Location: http://localhost/testovi/project/professor_index.php");
                    die;
                }
            }
        }
        
        echo "wrong username or password!";
    }else
    {
        echo "wrong username or password!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesor prijava</title>
    <link rel="stylesheet" href="professor_style/login_style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> 
</head>
<body>
    <div class="wrapper">
        <h1>Prijavljujete se kao profesor</h1>
        <div id="box">
            <form method="post" class="login-form">
                <div class="input-box">
                    <input type="email" name="email" placeholder="E-mail" required>
                    <i class='bx bxs-envelope'></i> <!-- Icon for email -->
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Šifra" required>
                    <i class='bx bxs-lock-alt'></i> <!-- Icon for password -->
                </div>
                
                <input id="button" type="submit" value="Uloguj se"><br>
                <div class="register-links">
                    <a href="http://localhost/testovi/project/user_register/login.php">Prijavi se kao učenik!</a><br><br>
                    <a href="signup.php">Nemate nalog? Registruj se!</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

