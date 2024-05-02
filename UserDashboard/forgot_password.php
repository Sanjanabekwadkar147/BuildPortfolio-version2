<?php
session_start();
include 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $emailquery = "select * from users where email = '$email'";
    $query = mysqli_query($conn, $emailquery);
    $email_count = mysqli_num_rows($query);

    if ($email_count) {
        $userdata = mysqli_fetch_array($query);
        $name = $userdata['name'];
        
        // Generate a new token
        $token = bin2hex(random_bytes(32));
        $token_created = date('Y-m-d H:i:s'); // Timestamp when the token is created

        // Update the user's token and token_created in the database
        $updatequery = "update users set token='$token', token_created='$token_created' where email='$email'";
        $update_result = mysqli_query($conn, $updatequery);

        if ($update_result) {
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'sanjanabekwadkar@gmail.com';
                $mail->Password = 'pacc ofrf ewsx sdiu'; // Use an app password if using Gmail
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('sanjanabekwadkar@gmail.com', 'Mailer');
                $mail->addAddress($email, $name);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset';
                $mail->Body    = "Hi, $name. Click here to reset your password: http://localhost:81/BuildPortfolio/UserDashboard/reset_password.php?token=$token";

                $mail->send();
                $success_message = "Check your email to reset your password $email";
                header('location:login.php');
            } catch (Exception $e) {
                echo "Email sending failed. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Failed to update token.";
        }
    } else {
        echo "No email found";
    }
    
}
?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Portfolio Website</title>
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.11/typed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
    
</head>

<body>
<div class="wrapper">
        <nav class="navbar">
            <div class="max-width">
                <div class="logo"><a href="#">Portfolio.</a></div>
                <ul class="menu">
                    <li><a href="/BuildPortfolio/index.php" class="menu-btn">Home</a></li>
                    <li><a href="/BuildPortfolio/index.php" class="menu-btn">About</a></li>
                    <li><a href="/BuildPortfolio/index.php" class="menu-btn">Services</a></li>
                    <li><a href="/BuildPortfolio/index.php" class="menu-btn">Contact</a></li>
                </ul>
                <div class="menu-btn">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </nav>     
    </div>

   <div class="form-container">

      <form action="" method="post">
         <h3>Reset Password</h3>
         <?php
         if (isset($error)) {
            foreach ($error as $error) {
               echo '<span class="error-msg">' . $error . '</span>';
            }
            ;
         }
         ;
         ?>
         <input type="email" name="email" required placeholder="enter your email">
         <input type="submit" name="submit" value="Send Mail" class="form-btn">

      </form>

   </div>
   <footer>
        <span>Created By <a href="#">Sanjana Bekwadkar</a> | <span class="far fa-copyright"></span> 2024 All rights reserved.</span>
    </footer>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
      crossorigin="anonymous"></script>
      <script src="script.js"></script>
</body>

</html>