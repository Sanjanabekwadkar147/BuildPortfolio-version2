<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $profession = $_POST['profession'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $facebook = $_POST['facebook'];
    $linkedin = $_POST['linkedin'];
    $github = $_POST['github'];
    $twitter = $_POST['twitter'];
    $youtube = $_POST['youtube'];

    $namePattern = "/^[a-zA-Z\s]+$/";
    $emailPattern = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/";
    $phonePattern = "/^\d{10}$/";

    if (!preg_match($namePattern, $name)) {
        $_SESSION['error_message']['name'] = "Name should only contain letters and whitespaces.";
    }
    if (!preg_match($emailPattern, $email)) {
        $_SESSION['error_message']['email'] = "Invalid email format.";
    }
    if (!preg_match($phonePattern, $phone)) {
        $_SESSION['error_message']['phone'] = "Phone should be a 10-digit number.";
    }

    if (empty($_SESSION['error_message'])) {
        $sql = "UPDATE profile SET name='$name', profession='$profession', email='$email', phone='$phone', address='$address', facebook='$facebook', linkedin='$linkedin', github='$github', twitter='$twitter', youtube='$youtube' WHERE id='$user_id'";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['success_message'] = "Profile updated successfully!";
        } else {
            $_SESSION['error_message']['database'] = "Error updating profile: " . $conn->error;
        }
    }
}

$conn->close();

// Redirect back to the profile page
header("Location: profile.php");
exit();
?>
