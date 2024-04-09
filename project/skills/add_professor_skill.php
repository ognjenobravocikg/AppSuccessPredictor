<?php
session_start();
include("../user_register/connection.php");

// Check if the professor_id exists in the session
if (isset($_SESSION['professor_id'])) {
    $professor_id = $_SESSION['professor_id'];
} else {
    header("Location: http://localhost/testovi/project/professor_index.php");
    exit; // Terminate script execution
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve form data
    $title = $_POST['title'];
    $skill_description = $_POST['skill_description'];
    $category = $_POST['category'];

    // File upload handling
    $image = $_FILES['image'];
    $image_name = $image['name'];
    $image_tmp_name = $image['tmp_name'];
    $image_size = $image['size'];
    $image_error = $image['error'];

    // Check if file is uploaded without errors
    if ($image_error === 0) {
        // Generate a unique name for the image to avoid conflicts
        $image_new_name = uniqid('', true) . '.' . strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        // Define the directory where the image will be stored
        $image_destination = '../uploads/' . $image_new_name;

        // Move the uploaded file to the destination directory
        if (move_uploaded_file($image_tmp_name, $image_destination)) {
            // Insert skill into the database
            $insert_skill_query = "INSERT INTO skills (professor_id, title, image, description, category) 
                                   VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = $con->prepare($insert_skill_query);
            if ($insert_stmt) {
                $insert_stmt->bind_param("issss", $professor_id, $title, $image_new_name, $skill_description, $category);

                if ($insert_stmt->execute()) {
                    echo "Skill added successfully";
                } else {
                    echo "Error adding skill: " . $insert_stmt->error;
                }
            } else {
                echo "Error preparing SQL statement: " . $con->error;
            }

            $insert_stmt->close();
        } else {
            echo "Error uploading image";
        }
    } else {
        echo "Error uploading file: " . $image_error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Skill</title>
    <!-- Include jQuery and jQuery UI library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- Include jQuery UI CSS for styling -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="../professor_style/add_professor_skill_style.css"> <!-- Link to custom CSS file -->
    <script>
        /* Body styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0; /* Light background color */
            color: black; 
            margin: 10px;
            padding: 10px;
            position: relative; /* Make the body position relative for overlay */
        }

        .overlay {
            position: fixed; /* Change to fixed position */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-repeat: no-repeat;
            opacity: 0.2; /* Adjust the opacity as desired */
            z-index: -1; /* Set a high z-index to ensure it's above other content */
        }
    </script>
</head>
<body>
    <div class='overlay'></div>
    <div class="container">
        <a href="../professor_index.php" class="back-link">Vrati se</a>
        <h1>Dodaj veštine</h1>

        <form method="post" enctype="multipart/form-data" class="add-skill-form">
            <div class="form-group">
                <label for="title">Naziv:</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="image">Slika:</label>
                <input type="file" name="image" id="image" accept="image/*" class="form-control-file" required>
            </div>

            <div class="form-group">
                <label for="skill_description">Opis veštine:</label>
                <textarea name="skill_description" id="skill_description" rows="4" cols="50" class="form-control" required></textarea>
            </div>

            <div class="form-group">
                <label for="category">Kategorija:</label>
                <!-- Replace the text input with an autocomplete combobox -->
                <input type="text" id="category" name="category" class="form-control" required>
            </div>

            <button type="submit" name="submit" class="submit-btn">Objavi</button>
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
            $("#category").autocomplete({
                source: availableCategories
            });
        });
    </script>

</body>
</html>
