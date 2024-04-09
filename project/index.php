<?php 

include("user_register/connection.php");
include("user_register/functions.php");
include("user_functions.php");

// Perform search for professors
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $search_query = $_POST['search_query'];
    // Perform search for professors
    $search_results = search_professors($con, $search_query);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Skills</title>
    <link rel="stylesheet" href="user_style/user_index_style.css">
    <script>
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
    </script>
</head>
<body>
<div class="overlay"></div>
    <!-- Navigation bar -->
    <div class="navbar">
        <div class="linkings">
            <a href="user_register/login.php">Prijavi se</a>
            <a href="index_search_skill.php" class="search-professor-link">Pretraži profesore</a>
        </div>
        <!-- Search form -->
        <div class="search-form">
            <form method="post">
                <label for="search_query">Pretraži:</label>
                <input type="text" name="search_query" id="search_query" placeholder="Uneti ime ili prezime predavaca">
                <button type="submit">Pretraži</button>
            </form>
        </div>
    </div>

    <!-- Main content -->
    <div class="container">
        <h1>Dobrodošli</h1>
        <p>Ovaj sajt je projekat studenata Ognjena Obradovica, studenta Fakulteta inzenjerskih nauka Univerziteta u Kragujevcu. Za pracenje koda onlajn, kao i pregled nasih ostalih radova mozete ociti na nasu github stranicu.</p>
        <p>Sajt ima mogucnost pretrazivanja po raznim parametrima, dodavanje prijatelja (followera), pracenje vasih online casova i sl.</p>
        
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
                                <a href="user_register/login.php"> Prijavi se kako bi interagovao sa profesorom!</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>


    </div>
</body>
</html>