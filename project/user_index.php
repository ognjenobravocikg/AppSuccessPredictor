<?php
session_start();

include("user_register/connection.php");
include("user_register/functions.php");
include("user_functions.php");

$user_data = check_login($con);

// Initialize variables
$search_results = [];
$skill_search_results = [];
$interests = $user_data['interests']; // Get user's interests

// Perform search for professors
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $search_query = $_POST['search_query'];
    // Perform search for professors
    $search_results = search_professors($con, $search_query);
}

if (!empty($interests)) {
    $skill_search_results = search_skills($con, $interests);
}

// Function to get professor's name and last name by professor_id
function getProfessorNameById($con, $professor_id) {
    $query = "SELECT name, lastName FROM professors WHERE professor_id = '$professor_id'";
    $result = mysqli_query($con, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $professor = mysqli_fetch_assoc($result);
        return $professor['name'] . ' ' . $professor['lastName'];
    } else {
        return "N/A";
    }
}


function displayFollowerProfessor($con, $student_id) {
    // Query to fetch all follower professors for the student
    $query = "SELECT professor_id FROM professor_student WHERE student_id = '$student_id' AND request_id = 2";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        echo "<div class='all-followers'>";
        echo "<h2 class='followers-title'>Profesori čije časove pratiš:</h2>";

        while ($row = mysqli_fetch_assoc($result)) {
            $professor_id = $row['professor_id'];
            echo "<div class='follower-professor'>";
            echo "<p class='follower-professor-title'>Profesor sa ID: $professor_id.</p>";

            $professor_skills = fetchSkillsProfessorId($con, $professor_id);

            if (!empty($professor_skills)) {
                echo "<div class='professor-skills'>";
                echo "<h3 class='skills-title'>Veštine profesora:</h3>";
                echo "<ul class='skills-list'>";
                foreach ($professor_skills as $skill) {
                    echo "<li class='skill'>";
                    echo "<div class='skill-info'>";
                    echo "<div class='text-info'>";
                    echo "<strong class='skill-title'>Naziv veštine:</strong> " . $skill['title'] . "<br>";
                    echo "<span class='skill-description'><strong>Opis veštine:</strong> " . $skill['description'] . "</span>" . "<br>";
                    echo "<span class='skill-description'><strong>Vreme održavanja:</strong> " . $skill['time_frame'] . "</span>" . "<br>";
                    echo "</div>"; // Close text-info div
                    echo "<div class='image-container'>";
                    echo "<img src='uploads/" . $skill['image'] . "' alt='Skill Image' class='skill-image'>"; // Adjust the path to your images
                    echo "</div>"; // Close image-container div
                    echo "</div>"; // Close skill-info div
                    echo "<hr>";
                    echo "</li>";
                }
                echo "</ul>";
                echo "</div>"; // Close professor-skills div
            } else {
                echo "<p class='no-skills'>No skills found for this professor.</p>";
            }

            // Rating and commenting form
            echo "<div class='rating-comment-form'>";
            echo "<form action='handler/rate_comment.php' method='post'>";
            echo "<input type='hidden' name='professor_id' value='$professor_id'>";
            echo "<label for='rating'>Ocena profesora:</label>";
            echo "<input type='number' name='rating' id='rating' min='1' max='5' required><br>";
            echo "<label for='comment'>Komentar:</label>";
            echo "<textarea name='comment' id='comment' rows='4' cols='50'></textarea>";
            echo "<button type='submit'>Objavi</button>";
            echo "</form>";
            echo "</div>"; // Close rating-comment-form div
            echo '<button class="chat-button" onclick="openChatWindow(' . $professor_id . ')">Chat</button>';
            echo "</div>"; // Close follower-professor div
        }
        echo "</div>"; // Close all-followers div
    } else {
        echo "<p class='no-follower-professor'>Ne razmenjujete veštine niti sa jednim profesorom.</p>";
    }
}





?>

<!DOCTYPE html>
<html>
<head>
    <title>Moj sajt</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="user_style/user_index_style.css">
    <style>
        .rating-comment-form {
        margin-top: 20px;
        }

        .rating-comment-form label {
            display: block;
            margin-bottom: 5px;
        }

        .rating-comment-form input[type="number"],
        .rating-comment-form textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .rating-comment-form button {
            background-color: #0073e6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .rating-comment-form button:hover {
            background-color: #005bbb;
        }

        .send-request-button{
            margin-top:5px;
            background-color: #0073e6; /* Blue button color */
            color: #fff; /* Light text color for button */
            border: none;
            border-radius: 4px;
            padding: 6px 13px;
            cursor: pointer;
            margin-right: 3px;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <link rel='stylesheet prefetch' 
    href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.2/css/font-awesome.min.css'>
    <link href="css/style.css" rel="stylesheet" id="bootstrap-css">
    <script src="js/chat.js"></script>
</head>
<body>
    <div class="overlay"></div>
    <!-- Navigation bar -->
    <div class="navbar">
        <div class="linkings">
            <a href="user_register/logout.php">Odjavi se</a>
            <a href="user_search_skill.php" class="search-professor-link">Pretraži profesore</a>
            <a href="settings/users_settings.php">Podešavanja</a>
        </div>
        <!-- Search form -->
        <div class="search-form">
            <form method="post">
                <input type="text" name="search_query" id="search_query" placeholder="Uneti ime ili prezime predavaca">
                <button type="submit">Pretraži</button>
            </form>
        </div>
    </div>

    <!-- Main content -->
    <div class="container">
        <h1>Dobrodošli</h1>
        <p>Ovaj sajt je projekat studenta Ognjena Obradovica, Fakulteta inzenjerskih nauka Univerziteta u Kragujevcu. Za pracenje koda onlajn, kao i pregled nasih ostalih radova mozete otici na moju github stranicu poslatu u mejlu.</p>
        <p>Sajt ima mogucnost pretrazivanja po raznim parametrima, dodavanje prijatelja (followera), pracenje vasih online casova i sl.</p>
        

        <!-- Hidden input field to store student_id -->
        <input type="hidden" id="student_id" value="<?php echo $user_data['user_id']; ?>">

        <!-- Search results for recommended skills based on interests -->
        <div class="search-results">
            <?php if (!empty($search_results)): ?>
                <h2>Rezultati pretrage:</h2>
                <ul>
                    <?php foreach ($search_results as $professor): ?>
                        <li>
                            <div class="professor-info">
                                <a href="profile.php?professor_id=<?php echo $professor['professor_id']; ?>">
                                    <?php echo $professor['name'] . ' ' . $professor['lastName']; ?>
                                </a>
                                <button class="chat-button" onclick="openChatWindow(<?php echo $professor['professor_id']; ?>)">Chat</button>
                                <button class="send-request-button" data-professor-id="<?php echo $professor['professor_id']; ?>">Send</button>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <!-- Display search results -->
        <?php if (!empty($skill_search_results)): ?>
            <div class="search-results">
                <h2>Preporucene vestine:</h2>
                <ul>
                <?php foreach ($skill_search_results as $skill): ?>
                    <li>
                        <div class="skill-info">
                            <div class="text-info">
                                <strong>Naziv veštine:</strong> <?php echo $skill['title']; ?><br>
                                <strong>Opis:</strong> <?php echo $skill['description']; ?><br>
                                <strong>Profesor:</strong> <?php echo getProfessorNameById($con, $skill['professor_id']); ?>
                            </div>
                            <img src="uploads/<?php echo $skill['image']; ?>" alt="Skill Image" class="skill-image"> <!-- Adjust the path to your images -->
                        </div>
                        <hr> <!-- Add horizontal line -->
                    </li>
                <?php endforeach; ?>

                </ul>
            </div>
        <?php endif; ?>

        <?php 
        // Call the function to display follower professor
        displayFollowerProfessor($con, $user_data['user_id']); 
        ?>

    </div>
            

    <!-- JavaScript for chat functionality -->
    <script>
        function openChatWindow(professorId) {
            // Redirect to chat window with professor's id as a parameter
            window.open('chat/chat.php?receiver_id=' + professorId, '_blank', 'width=600,height=400');
        }
        
        // JavaScript function to handle sending request action
        $(document).ready(function(){
            $('.send-request-button').click(function(){
                var professorId = $(this).data('professor-id');
                var studentId = $('#student_id').val(); // Retrieve student_id from hidden input field
                sendAction('sendReq', professorId, studentId);
            });
        });

        function sendAction(action, professorId, studentId){
            $.post('handler/action.php', { action: action, professor_id: professorId, user_id: studentId }, function(response){
                alert(response);
            });
        }
    </script>

</body>
</html>
