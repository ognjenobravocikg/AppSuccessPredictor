<?php
// Include database connection
include "../user_register/connection.php";

// Function to fetch all categories with their counts in ascending order
function getAllCategoriesWithCounts($con) {
    $output = '';
    $query_all_categories = "SELECT category, COUNT(*) AS count FROM skills GROUP BY category ORDER BY category DESC";
    $result_all_categories = mysqli_query($con, $query_all_categories);
    $output .= "<ul>";
    while ($row_category = mysqli_fetch_assoc($result_all_categories)) {
        $output .= "<li>" . $row_category['category'] . " (se pojavljuje: " . $row_category['count'] . ")</li>";
    }
    $output .= "</ul>";
    return $output;
}

// Call the function and echo the result
echo getAllCategoriesWithCounts($con);
?>
