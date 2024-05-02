<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['project_id'])) {
    $projectId = $_POST['project_id'];
    
    // Prepare and execute SQL query to delete the project
    $deleteQuery = "DELETE FROM projects WHERE p_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $projectId);
    
    if ($stmt->execute()) {
        // Deletion successful
        echo json_encode(array("status" => "success"));
    } else {
        // Deletion failed
        echo json_encode(array("status" => "error", "message" => "Error deleting project: " . $conn->error));
    }
    
    $stmt->close();
} else {
    // Invalid request
    echo json_encode(array("status" => "error", "message" => "Invalid request"));
}
?>
