<?php
session_start();
include 'config.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['skill']) && $user_id) {
    $skill = $_POST['skill'];
    
    // Delete the skill from the database
    $delete_sql = "DELETE FROM skills WHERE id = ? AND skills = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("is", $user_id, $skill);
    
    if ($delete_stmt->execute()) {
        echo "Skill deleted successfully.";
    } else {
        echo "Error deleting skill.";
    }
    
    $delete_stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
