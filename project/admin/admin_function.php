<?php

function check_login($con){
    if (isset($_SESSION['admin_id'])){
        $id=$_SESSION['admin_id'];
        $query="SELECT * FROM admins WHERE admin_id='$id' LIMIT 1";

        $result=mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result)>0){
            $user_data=mysqli_fetch_assoc($result);
            return $user_data;
        }
    }

    header("Location: admin_login.php"); // Redirect to admin login page
    exit; // Terminate script execution
}

?>
