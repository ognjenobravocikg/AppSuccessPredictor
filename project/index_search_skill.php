<?php 
session_start();

include("user_register/connection.php");
include("user_register/functions.php");
include("user_functions.php");

$user_data = check_login($con);

$skill_search_results = [];
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $category = $_POST['category']; // Assuming category is provided via POST
    $skill_search_results = search_skills($con, $category);
}

// Function to get professor's name and last name by professor_id
function getProfessorNameById($con, $professor_id) {
    $query = "SELECT name, lastName FROM professors WHERE professor_id = '$professor_id'";
    $result = mysqli_query($con, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $professor = mysqli_fetch_assoc($result);
        return $professor['name'] . ' ' . $professor['lastName'];
    } else {
        return "0";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Skills</title>
    <link rel="stylesheet" href="user_style/user_search_skill_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <link rel='stylesheet prefetch' 
    href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.2/css/font-awesome.min.css'>
    <link href="css/style.css" rel="stylesheet" id="bootstrap-css">
    <script src="js/chat.js"></script>
</head>
<body>
    <div class="navbar">
        <div class="linkings">
            <a href="user_register/logout.php">Odjavi se</a>
            <a href="index.php">Vrati se</a>
        </div>
    </div>

    <input type="hidden" id="student_id" value="<?php echo $user_data['user_id']; ?>">


    <div class="container">
        <h1>Pretraga Veština</h1>

        <div class="overlay"></div>

        <!-- Search form for skills -->
        <form method="post" class="search-form">
            <label for="category">Kategorija:</label>
            <input type="text" name="category" id="category" placeholder="Uneti kategoriju">
        </form>

        <!-- Display search results -->
        <?php if (!empty($skill_search_results)): ?>
            <div class="search-results">
                <h2>Rezultati pretrage:</h2>
                <ul>
                <?php foreach ($skill_search_results as $skill): ?>
                    <?php if (getProfessorNameById($con, $skill['professor_id'])!='0'): ?>
                    <li>
                        <div class="skill-info">
                            <div class="text-info">
                                <strong>Title:</strong> <?php echo $skill['title']; ?><br>
                                <strong>Description:</strong> <?php echo $skill['description']; ?><br>
                                <strong>Professor:</strong> <?php echo getProfessorNameById($con, $skill['professor_id']); ?>
                            </div>
                            <img src="uploads/<?php echo $skill['image']; ?>" alt="Skill Image" class="skill-image"> <!-- Adjust the path to your images -->
                        </div>
                        <button class="chat-button" onclick="openChatWindow(<?php echo $skill['professor_id']; ?>)">Chat</button>
                        <button class="send-request-button" data-professor-id="<?php echo $skill['professor_id']; ?>">Send</button>
                        <hr> <!-- Add horizontal line -->
                    </li>
                    <?php endif ?>
                <?php endforeach; ?>

                </ul>
            </div>
        <?php endif; ?>

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


