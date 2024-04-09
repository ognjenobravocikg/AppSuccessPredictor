<?php
session_start();

include("professor_register/connection.php");
include("professor_register/functions.php");
include("professor_functions.php");

$user_data = check_login($con);

// Fetch connection requests for the current professor
$professor_id = $user_data['professor_id'];
$connection_requests = fetch_professor_student($con, $professor_id);
$followers = fetch_professor_followers($con, $professor_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My website</title>
    <link rel="stylesheet" href="professor_style/professor_index_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="js/chat.js"></script>
    <style>
        .accept-req-btn, .deny-req-btn {
            padding: 10px 10px;
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 13.3px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }

        /* Style for accept button */
        .accept-req-btn {
            background-color: #4CAF50; /* Green */
        }

        /* Style for deny button */
        .deny-req-btn {
            background-color: #f44336; /* Red */
        }

        /* Hover effect for buttons */
        .accept-req-btn:hover, .deny-req-btn:hover {
            opacity: 0.8;
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
    </div>

    <!-- Main content -->
    <div class="container">
        <h1>Dobrodošli</h1>

        <!-- Buttons to show/hide followers and follow requests -->
        <button id="showFollowersBtn" class="toggle-btn">Prikaži pratitelje</button>
        <button id="showRequestsBtn" class="toggle-btn">Prikaži zahteve za povezivanje</button>

        <!-- Display connection requests -->
        <div id="connectionRequests" style="display: none;">
            <?php if (!empty($connection_requests)): ?>
                <h2>Zahtevi za prijateljstvo:</h2>
                <ul>
                    <?php foreach ($connection_requests as $request): ?>
                        <li>
                            Student: <?php echo $request['student_name']; ?>
                            <input type="hidden" class="student-id" value="<?php echo $request['user_id']; ?>"> <!-- Add hidden input for student_id -->
                            <button class="accept-req-btn">Prihvati</button>
                            <button class="deny-req-btn">Odbij</button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Nema.</p>
            <?php endif; ?>
        </div>

        <!-- Display followers -->
        <div id="followers" style="display: none;">
            <?php if (!empty($followers)): ?>
                <h2>Pratioci:</h2>
                <ul>
                    <?php foreach ($followers as $follower): ?>
                        <li>
                            Pratilac: <?php echo $follower['student_name'] ?>
                            <br>
                            Veština: <?php echo $follower['skill'] ?>
                            <?php
                                $follower_id = $follower['user_id'];
                                $ratings_comments = fetch_ratings_comments($con, $follower_id);
                                if (!empty($ratings_comments)) {
                                    echo "<h3>Ocene i komentari:</h3>";
                                    foreach ($ratings_comments as $rating_comment) {
                                        echo "Ocena: " . $rating_comment['rating'] . "<br>";
                                        echo "Komentar: " . $rating_comment['comment'] . "<br><br>";
                                    }
                                } else {
                                    echo "Nema ocena i komentara za ovog pratioca.";
                                }
                            ?>
                        </li>
                        <button class="chat-button" onclick="openChatWindow(<?php echo $follower['user_id']; ?>)">Chat</button>
                        <hr>            
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Trenutno Vas ne prati niko.</p>
            <?php endif; ?>
        </div>
    </div>


    <script>
        function openChatWindow(studentId) {
            // Redirect to chat window with professor's id as a parameter
            window.open('chat/professor_chat.php?receiver_id=' + studentId, '_blank', 'width=600,height=400');
        }

        $(document).ready(function(){
            $('#showFollowersBtn').click(function(){
                $('#followers').toggle(); // Toggle visibility of followers section
                $('#connectionRequests').hide(); // Hide connection requests section
            });

            $('#showRequestsBtn').click(function(){
                $('#connectionRequests').toggle(); // Toggle visibility of connection requests section
                $('#followers').hide(); // Hide followers section
            });
        });

        $(document).ready(function(){
            $('.accept-req-btn').click(function(){
                var studentId = $(this).siblings('.student-id').val(); // Retrieve student_id from hidden input field
                var professorId = <?php echo $professor_id; ?>; // Get professor_id from PHP variable
                sendAction('acceptReq', studentId, professorId); // Pass professor_id to the sendAction function
            });

            $('.deny-req-btn').click(function(){
                var studentId = $(this).siblings('.student-id').val(); // Retrieve student_id from hidden input field
                var professorId = <?php echo $professor_id; ?>; // Get professor_id from PHP variable
                sendAction('denyReq', studentId, professorId); // Pass professor_id to the sendAction function
            });
        });

        function sendAction(action, studentId, professorId){
            $.post('handler/action.php', { action: action, user_id: studentId, professor_id: professorId }, function(response){
                alert(response);
                // You may want to refresh the page or update the UI to reflect the changes
            });
        }

    </script>

</body>
</html>



