<?php
session_start();
include 'config.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize the data received from the form
    $user_id = $_SESSION['user_id']; // Assuming you have a user ID stored in the session
    $draft_data = $_POST; // Draft data received from the form

    // You may want to perform additional validation and sanitization here

    // Check if there's an existing draft for the user
    $existing_draft_query = "SELECT * FROM projects WHERE id = '$user_id' AND is_draft = 1";
    $existing_draft_result = $conn->query($existing_draft_query);

    if ($existing_draft_result->num_rows > 0) {
        // Update existing draft data
        $update_stmt = $conn->prepare("UPDATE projects SET p_name = ?, p_description = ?, project_link = ? WHERE id = ? AND is_draft = 1");
        $update_stmt->bind_param("sssi", $p_name, $p_description, $project_link, $user_id);

        // Extract relevant data from draft data
        $p_name = isset($draft_data['p_name'][0]) ? $draft_data['p_name'][0] : '';
        $p_description = isset($draft_data['p_description'][0]) ? $draft_data['p_description'][0] : '';
        $project_link = isset($draft_data['project_link'][0]) ? $draft_data['project_link'][0] : '';

        $update_stmt->execute();

        if ($update_stmt->affected_rows > 0) {
            // Draft data updated successfully
            echo json_encode(array("status" => "success", "message" => "Draft updated successfully."));
        } else {
            // Failed to update draft data
            echo json_encode(array("status" => "error", "message" => "Failed to update draft data."));
        }

        $update_stmt->close();
    } else {
        // Insert new draft data
        $insert_stmt = $conn->prepare("INSERT INTO projects (id, p_name, p_description, project_link, is_draft) VALUES (?, ?, ?, ?, 1)");
        $insert_stmt->bind_param("isss", $user_id, $p_name, $p_description, $project_link);

        // Extract relevant data from draft data
        $p_name = isset($draft_data['p_name'][0]) ? $draft_data['p_name'][0] : '';
        $p_description = isset($draft_data['p_description'][0]) ? $draft_data['p_description'][0] : '';
        $project_link = isset($draft_data['project_link'][0]) ? $draft_data['project_link'][0] : '';

        $insert_stmt->execute();

        if ($insert_stmt->affected_rows > 0) {
            // Draft data saved successfully
            echo json_encode(array("status" => "success", "message" => "Draft saved successfully."));
        } else {
            // Failed to save draft data
            echo json_encode(array("status" => "error", "message" => "Failed to save draft data."));
        }

        $insert_stmt->close();
    }

} else {
    // Invalid request method
    echo json_encode(array("status" => "error", "message" => "Invalid request method."));
}

$conn->close(); // Close the database connection
?>
