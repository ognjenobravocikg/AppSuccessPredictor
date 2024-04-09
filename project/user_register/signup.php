<?php 
session_start();

include("connection.php");
include("functions.php");

$skill_options = ["Programming",
"Graphic Design",
"Cooking",
"Writing",
"Photography",
"Public Speaking",
"Playing a Musical Instrument",
"Video Editing",
"Data Analysis",
"Gardening",
"Dancing",
"Carpentry",
"Knitting",
"Foreign Language Proficiency",
"Painting",
"Martial Arts",
"Digital Marketing",
"Singing",
"Yoga",
"Woodworking",
"Acting",
"Sewing",
"Web Development",
"Pottery",
"Creative Writing",
"Hiking",
"Chess",
"Meditation",
"Interior Design",
"Event Planning",
"Animation",
"Calligraphy",
"Sculpting",
"Mountain Biking",
"Strategic Planning",
"Bird Watching",
"Home Brewing",
"Origami",
"Survival Skills",
"Baking",
"Horseback Riding",
"Astronomy",
"Drone Flying",
"Leatherworking",
"Stand-up Comedy",
"Archery",
"Urban Gardening",
"Glassblowing",
"App Development",
"Fishing",
"Makeup Artistry",
"Blogging",
"Electrical Repair",
"Wine Tasting",
"Storytelling",
"Robotics",
"Scuba Diving",
"Creative Problem Solving",
"DIY Projects",
"Brewing",
"Cosplay",
"Kayaking",
"Floral Arrangement",
"Journaling",
"Public Relations",
"Paragliding",
"Tarot Reading",
"Dog Training",
"Sustainable Living",
"Wood Carving",
"Video Game Design",
"Rock Climbing",
"Feng Shui",
"Crossword Puzzles",
"Food Styling",
"Geocaching",
"Volunteer Work",
"Wine Making",
"Quilting",
"Stand-up Paddleboarding",
"Podcasting",
"Antique Restoration",
"Home Decorating",
"Magic Tricks",
"Skiing",
"Journalism",
"Perfume Making",
"Aquascaping",
"Beekeeping",
"Virtual Reality Design",
"Mixology",
"Parkour",
"Political Activism",
"Other"];
$interests_options = ["Programming",
"Graphic Design",
"Cooking",
"Writing",
"Photography",
"Public Speaking",
"Playing a Musical Instrument",
"Video Editing",
"Data Analysis",
"Gardening",
"Dancing",
"Carpentry",
"Knitting",
"Foreign Language Proficiency",
"Painting",
"Martial Arts",
"Digital Marketing",
"Singing",
"Yoga",
"Woodworking",
"Acting",
"Sewing",
"Web Development",
"Pottery",
"Creative Writing",
"Hiking",
"Chess",
"Meditation",
"Interior Design",
"Event Planning",
"Animation",
"Calligraphy",
"Sculpting",
"Mountain Biking",
"Strategic Planning",
"Bird Watching",
"Home Brewing",
"Origami",
"Survival Skills",
"Baking",
"Horseback Riding",
"Astronomy",
"Drone Flying",
"Leatherworking",
"Stand-up Comedy",
"Archery",
"Urban Gardening",
"Glassblowing",
"App Development",
"Fishing",
"Makeup Artistry",
"Blogging",
"Electrical Repair",
"Wine Tasting",
"Storytelling",
"Robotics",
"Scuba Diving",
"Creative Problem Solving",
"DIY Projects",
"Brewing",
"Cosplay",
"Kayaking",
"Floral Arrangement",
"Journaling",
"Public Relations",
"Paragliding",
"Tarot Reading",
"Dog Training",
"Sustainable Living",
"Wood Carving",
"Video Game Design",
"Rock Climbing",
"Feng Shui",
"Crossword Puzzles",
"Food Styling",
"Geocaching",
"Volunteer Work",
"Wine Making",
"Quilting",
"Stand-up Paddleboarding",
"Podcasting",
"Antique Restoration",
"Home Decorating",
"Magic Tricks",
"Skiing",
"Journalism",
"Perfume Making",
"Aquascaping",
"Beekeeping",
"Virtual Reality Design",
"Mixology",
"Parkour",
"Political Activism",
"Other"];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $skill = $_POST['skill'];
    $interests = $_POST['interests'];
    $education = $_POST['education'];

    if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($password) && !empty($skill) && !empty($interests) && !empty($education)) {
        $user_id = random_num(20);
        $query = "INSERT INTO users (name, lastName, email, password, skill, interests, education, user_id, registration_date) VALUES ('$first_name', '$last_name', '$email', '$password', '$skill', '$interests', '$education', '$user_id', NOW())";
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
    <link rel="stylesheet" href="user_register_style/signup_style.css">
</head>
<body>
    <div id="box">
        <form method="post">
            <div class="wrapper">
                <h1>Registrujte se</h1>
                <div class="input-box">
                    <input type="text" name="first_name" placeholder="Ime" required><br><br>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="last_name" placeholder="Prezime" required><br><br>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="E-mail" required><br><br>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Šifra" required><br><br>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <label for="skill">Vestina:</label>
                    <i class='bx bxs-user'></i>
                </div>    
                <div class="input-box">
                    <select name="skill" id="skill" required>
                        <?php foreach ($skill_options as $option): ?>
                            <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                        <?php endforeach; ?>
                    </select><br><br>
                </div>
                <div class="input-box">
                    <label for="interests">Interesovanje:</label>
                    <i class='bx bxs-user'></i>
                </div>    
                <div class="input-box">
                    <select name="interests" id="interests" required>
                        <?php foreach ($interests_options as $option): ?>
                            <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                        <?php endforeach; ?>
                    </select><br><br>
                </div>
                <div class="input-box">
                    <input type="text" name="education" placeholder="Edukacija" required><br><br>
                    <i class='bx bxs-user'></i>
                </div>
            </div>
            <input id="button" type="submit" value="Registruj se">
            <div class="register-link">
                <a href="login.php">Već imaš nalog? Uloguj se.</a>
            </div>
        </form>
    </div>
</body>
</html>

