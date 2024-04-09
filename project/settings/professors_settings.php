<?php 
session_start();

include("../professor_register/connection.php");
include("../professor_register/functions.php");

$professors_data = check_login($con);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podešavanja</title>
    <link rel="stylesheet" href="../user_style/user_settings_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- Include jQuery UI CSS for styling -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>

    <div class="overlay"></div>

    <div class="navbar">
        <div class="linkings">

            <a href="../professor_register/logout.php">Odjavi se</a>
            <a href="../professor_index.php">Vrati se na početnu stranu</a>
   
        </div>
    </div>
    <div class="container">
        <h1>Podešavanja</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label for="new_name">Novo ime:</label>
                <input type="text" name="new_name" value="<?php echo $professors_data['name']; ?>">
            </div>
            <div class="input-group">
                <label for="new_name">Nov email:</label>
                <input type="email" name="new_email" value="<?php echo $professors_data['email']; ?>">
            </div>
            <div class="input-group">
                <label for="password">Trenutna šifra:</label>
                <input type="password" name="password" required>
            </div>
            <div class="input-group">
                <label for="new_password">Nova šifra:</label>
                <input type="password" name="new_password">
            </div>
            <div class="button-group">
                <button type="submit">Sačuvaj promene</button>
            </div>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $current_password = $_POST['password']; // Current password input
                $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : null; // New password input
                $new_name = $_POST['new_name'];
                $new_interest = $_POST['new_interest'];

                if ($current_password === $professors_data['password']) {
                    // Update user information in the database
                    $query = "UPDATE professors SET name = '$new_name', interests = '$new_interest'";
                    
                    // Check if new password is provided and update it
                    if ($new_password !== null && $new_password !== '') {
                        $query .= ", password = '$new_password'";
                    }
                    
                    $query .= " WHERE user_id = {$professors_data['user_id']}";
                    
                    mysqli_query($con, $query);

                    // Redirect to the settings page after updating information
                    echo '<br><div class="success-message">Uspesno promenjene informacije</div>';
                    exit;
                } else {
                    echo "Incorrect password!";
                }
            }
            ?>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Define available categories for autocomplete
            var availableCategories = [
                "Programming",
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
            "Other"
            ];

            // Initialize autocomplete on the category input field
            $("#new_interest").autocomplete({
                source: availableCategories
            });
        });
    </script>
</body>
</html>