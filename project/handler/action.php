<?php
session_start();
include("../user_register/connection.php");

if(isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'sendReq') {
        // Inserting a new request
        if(isset($_POST['professor_id'], $_POST['user_id'])) {
            $professor_id = $_POST['professor_id'];
            $student_id = $_POST['user_id'];
    
            $stmt = $con->prepare("INSERT INTO professor_student (professor_id, student_id, request_id) VALUES (?, ?, 1)");
            if ($stmt === false) {
                die("Error preparing statement: " . $con->error);
            }
    
            $stmt->bind_param("ii", $professor_id, $student_id);
            
            if($stmt->execute()) {
                echo "Request sent successfully";
            } else {
                echo "Error sending request: " . $stmt->error;
            }
            
            $stmt->close();
        } else {
            echo "Professor ID or Student ID not provided";
        }
    } elseif ($action === 'acceptReq') {
        // Accepting the request
        if(isset($_POST['user_id'], $_POST['professor_id'])) {
            $student_id = $_POST['user_id'];
            $professor_id = $_POST['professor_id'];

            // Update the request_id to 2
            $stmt = $con->prepare("UPDATE professor_student SET request_id = 2 WHERE professor_id = ? AND student_id = ?");
            if ($stmt === false) {
                die("Error preparing statement: " . $con->error);
            }

            $stmt->bind_param("ii", $professor_id, $student_id);
            
            if($stmt->execute()) {
                echo "Request accepted successfully";
            } else {
                echo "Error processing request: " . $stmt->error;
            }
            
            $stmt->close();
        } else {
            echo "Student ID or Professor ID not provided";
        }
    } elseif ($action === 'denyReq') {
        // Deleting the request
        if(isset($_POST['user_id'], $_POST['professor_id'])) {
            $student_id = $_POST['user_id'];
            $professor_id = $_POST['professor_id'];

            // Delete the connection between the professor and the student
            $stmt = $con->prepare("DELETE FROM professor_student WHERE professor_id = ? AND student_id = ?");
            if ($stmt === false) {
                die("Error preparing statement: " . $con->error);
            }

            $stmt->bind_param("ii", $professor_id, $student_id);
            
            if($stmt->execute()) {
                echo "Connection deleted successfully";
            } else {
                echo "Error deleting connection: " . $stmt->error;
            }
            
            $stmt->close();
        } else {
            echo "Student ID or Professor ID not provided";
        }
    } else {
        echo "Invalid action";
    }
} else {
    echo "Action not provided";
}
?>





