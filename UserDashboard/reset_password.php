<?php
include 'config.php';

$error = array();
$success_message = "";

$token = mysqli_real_escape_string($conn, $_GET['token']);

// Check token expiration and validity
$tokenquery = "SELECT * FROM users WHERE token = '$token'";
$query = mysqli_query($conn, $tokenquery);
$token_count = mysqli_num_rows($query);

if ($token_count) {
    $userdata = mysqli_fetch_array($query);
    $token_time = strtotime($userdata['token_created']);
    $current_time = time();
    $time_diff = $current_time - $token_time;

    // Check if token is expired (e.g., expires after 2 minutes)
    $token_expiry = 300; // 2 minutes in seconds
    if ($time_diff > $token_expiry) {
        echo "Token has expired. Please request a new password reset link.";
        exit;
    }
    $max_reset_attempts = 10;
    if ($userdata['password_reset_count'] >= $max_reset_attempts) {
        echo "You have reached the maximum limit for password resets.";
        exit;
    }
    
    $update_reset_count_query = "UPDATE users SET password_reset_count = password_reset_count + 1 WHERE token = '$token'";
    mysqli_query($conn, $update_reset_count_query);
    
} else {
    echo "Invalid token.";
    exit;
}

// Insert data into the database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {

    if(isset($_GET['token']))
    {
        $token=$_GET['token'];



    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
        if (strlen($password) < 6 || !preg_match("/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@#$%^&!])[A-Za-z\d@#$%^&!]+$/", $password)) {
            $error['password'] = 'Password must be at least 6 characters and include letters, numbers, and one special symbol.';
        }

        

        if($password==$cpassword)
        {


            $updatequery="update users set password='$password' where token='$token'";

            $iquery=mysqli_query($conn,$updatequery);
            if($iquery)
            {
                $success_message = "Your password has been updated successfully";
                header('location:login.php');
            }
            else
            {
                echo"password not updated";
                header('location:reset_password.php');
            }
        }
        else
        {
            echo 'password not matching';
        }
    }
}
?>

<!DOCTYPE html>
<!-- Created By CodingNepal -->
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
            
            <div class="form-group">
                <input type="password" name="password" required placeholder="Enter new password">
                <?php if (isset($error['password'])) echo '<span class="error-msg">' . $error['password'] . '</span>'; ?>
            </div>
            <div class="form-group">
                <input type="password" name="cpassword" required placeholder="Confirm password">
                <?php if (isset($error['password'])) echo '<span class="error-msg">' . $error['password'] . '</span>'; ?>
            </div>
           
            <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
            <input type="submit" name="submit" value="Reset Password" class="form-btn">

                <script>
                    function hideMessages() {
            var successAlert = document.querySelector(".alert-success");
            // var errorMessages = document.querySelectorAll(".text-danger"); // Select all error messages

            if (successAlert) {
                setTimeout(function () {
                    successAlert.style.display = 'none';
                }, 3000); // 5000 milliseconds = 5 seconds
            }


        }

        document.addEventListener("DOMContentLoaded", function () {
            hideMessages();
        });
                </script>

        </form>
    </div>
    <footer>
        <span>Created By <a href="#">Sanjana Bekwadkar</a> | <span class="far fa-copyright"></span> 2024 All rights reserved.</span>
    </footer>
    <script src="script.js"></script>
</body>
</html>

