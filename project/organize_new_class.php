<!DOCTYPE html>
<html>
<head>
    <title>My website</title>
    <link rel="stylesheet" href="professor_style\organize_new_class_style.css">
    <style>
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1); /* Semi-transparent white background */
            border-radius: 10px;
        }
        /* Skill styles */
        .skill {
            margin-bottom: 20px;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.1); /* Semi-transparent white background */
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .skill h3 {
            margin-top: 0;
            color: black;
        }

        .skill p {
            margin-left: 15px;
            color: gray;
        }

        .skill img {
            margin-top: 10px;
        }
        input[type="text"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 8px;
            max-width: 97%;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class='overlay'></div>

    <!-- Navigation bar -->
    <div class="navbar">
        <a href="professor_register/logout.php">Odjavi se</a>
        <a href="skills/add_professor_skill.php">Dodaj novu veštinu</a>
        <a href="settings/professors_settings.php">Podešavanja</a>
        <a href="organize_new_class.php">Organizuj novi cas</a>
        <a href="professor_index.php">Vrati se</a>
    </div>

    <?php
    session_start();

    include("professor_register/connection.php");
    include("professor_register/functions.php");
    include("professor_functions.php");

    $user_data = check_login($con);

    // Fetch connection requests for the current professor
    $professor_id = $user_data['professor_id'];

    // Check if the form for updating time frame is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_time_frame'])) {
        $skill_id = $_POST['skill_id'];
        $new_time_frame = $_POST['time_frame'];

        // Update the time frame in the database
        $update_query = "UPDATE skills SET time_frame = '$new_time_frame' WHERE skill_id = '$skill_id'";
        $update_result = mysqli_query($con, $update_query);

        if ($update_result) {
            echo "Vreme promenjeno uspesno!";
        } else {
            echo "Vreme nije uspešno promenjeno: " . mysqli_error($con);
        }
    }

    // Fetch skills associated with the current professor
    $query = "SELECT skill_id, title, description,category, image, time_frame FROM skills WHERE professor_id = '$professor_id'";
    $result = mysqli_query($con, $query);

    // Check if query executed successfully
    if ($result) {
        // Fetch and display skills data
        echo "<div class='container'>";
        echo "<h2>Vaše veštine:</h2>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='skill'>";
            echo "<h3>Naziv: ". $row['title'] . "</h3>";
            echo "<p>Naslov: " . $row['description'] . "</p>";
            echo "<p>Kategorija: " . $row['category'] . "</p>";
            echo "<p>Vreme časa: " . $row['time_frame'] . "</p>";
            echo "<img src='uploads/" . $row['image'] . "' alt='Skill Image' width='100' height='100'>"; // Adjust width and height as needed

            // Form to update time frame
            echo "<form method='POST'>";
            echo "<input type='hidden' name='skill_id' value='" . $row['skill_id'] . "'>";
            echo "<label for='new_time_frame'>Novo vreme za čas:</label>";
            echo "<input type='text' name='time_frame' id='new_time_frame'>";
            echo "<button type='submit' name='update_time_frame'>Ažuriraj</button>";
            echo "</form>";

            echo "</div>";
        }
        echo "</div>"; // Close container div
    } else {
        echo "Error fetching skills: " . mysqli_error($skills_con);
    }
    ?>    
</body>
</html>


