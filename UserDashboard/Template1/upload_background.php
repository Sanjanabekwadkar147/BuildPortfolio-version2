<?php
session_start();
include 'config.php';

$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['background']) && $user_id) {
    $target_dir = "uploads/backgrounds/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["background"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["background"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo json_encode(['success' => false, 'message' => 'File is not an image.']);
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo json_encode(['success' => false, 'message' => 'Sorry, your file was not uploaded.']);
    } else {
        if (move_uploaded_file($_FILES["background"]["tmp_name"], $target_file)) {
            // Update the database with the new background image path
            $sql = "UPDATE profile SET background_image = '$target_file' WHERE id = $user_id";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(['success' => true, 'imageUrl' => $target_file]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Sorry, there was an error updating your profile.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Sorry, there was an error uploading your file.']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
