<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['experience_id'])) {
    $experienceId = $_POST['experience_id'];
    
    // Prepare and execute SQL query to delete the experience
    $deleteQuery = "DELETE FROM internships_experience WHERE i_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $experienceId);
    
    if ($stmt->execute()) {
        // Deletion successful
        echo "Experience deleted successfully";
    } else {
        // Deletion failed
        echo "Error deleting experience";
    }
    
    $stmt->close();
} else {
    // Invalid request
    echo "Invalid request";
}
?>
