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
			$query = "select * from users where email = '$email' limit 1";
			$result = mysqli_query($con, $query);

			if($result)
			{
				if($result && mysqli_num_rows($result) > 0)
				{

					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['password'] === $password)
					{

						$_SESSION['user_id'] = $user_data['user_id'];
						header("Location: http://localhost/testovi/project/user_index.php");
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,
    initial-scale=1.0" >
    <title>Prijava</title>
	<link rel="stylesheet" href="user_register_style/login_style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> 
</head>

<body>
    <div class="wrapper">
		<h1>Prijavite se</h1>
        <form method="post">
            <div class="input-box">
                <input type="email" name="email" placeholder="E-mail" required>
				<i class='bx bxs-envelope'></i> <!-- Icon for email -->
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Šifra" required>
                <i class='bx bxs-lock-alt' ></i>
            </div>
            
            <input class="btn" type="submit" value="Prijavi se"><br>
            <div class="register-link">
                <p><a href="http://localhost/testovi/project/professor_register/login.php">Prijavi se kao profesor!</a></p>
                <p><a href="signup.php">Nemaš nalog? Registruj se!</a></p>
            </div>
        </form>
    </div>
</body>

</html>