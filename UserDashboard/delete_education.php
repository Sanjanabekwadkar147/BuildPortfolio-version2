<?php
// Include database connection or any necessary configuration file
include 'config.php';

// Check if education_id is set and not empty
if (isset($_POST['education_id']) && !empty($_POST['education_id'])) {
    // Sanitize the input to prevent SQL injection
    $education_id = mysqli_real_escape_string($conn, $_POST['education_id']);

    // Construct the SQL query to delete the education record
    $delete_query = "DELETE FROM education WHERE e_id = '$education_id'";

    // Perform the delete operation
    if (mysqli_query($conn, $delete_query)) {
        // If deletion is successful, return success response
        $response = array(
            'status' => 'success',
            'message' => 'Education record deleted successfully'
        );
        echo json_encode($response);
    } else {
        // If deletion fails, return error response
        $response = array(
            'status' => 'error',
            'message' => 'Failed to delete education record: ' . mysqli_error($conn)
        );
        echo json_encode($response);
    }
} else {
    // If education_id is not set or empty, return error response
    $response = array(
        'status' => 'error',
        'message' => 'Invalid request: education_id is missing or empty'
    );
    echo json_encode($response);
}
?>
