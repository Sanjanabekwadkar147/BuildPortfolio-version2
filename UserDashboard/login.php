<?php
session_start();
include 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {

   // Validate email
   if (empty($_POST['email'])) {
    $error['email'] = 'Email is required!';
} else {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = 'Invalid email format';
    } else {
        // Additional custom validation
        $parts = explode('@', $email);
        if (preg_match("/^\d|[#@]/", $parts[0])) {
            $error['email'] = 'Invalid email format';
        }
    }
}
   $password = $_POST["password"];
   //    $user_type = $_POST["user_type"];


   $select = " SELECT * FROM users WHERE email = '$email' && password = '$password' ";

   $result = mysqli_query($conn, $select);

   if (mysqli_num_rows($result) > 0) {

      $row = mysqli_fetch_array($result);

      if ($row) {
         $_SESSION['user_id'] = $row['id'];
         header('location: userdash.php?user_id=' . $row['id']);

      }
   } else {
      $error[] = 'Incorrect email or password!';
   }
}
;
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
         <h3>login now</h3>
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
         <input type="password" name="password" required placeholder="enter your password">
         <input type="submit" name="submit" value="login now" class="form-btn">
         <p>don't have an account? <a href="Register.php">register now</a></p>
         <p><a href="forgot_password.php">Forgot Password</a></p>

      </form>

   </div>
   <footer>
        <span>Created By <a href="#">Sanjana Bekwadkar</a> | <span class="far fa-copyright"></span> 2024 All rights reserved.</span>
    </footer>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
      crossorigin="anonymous"></script>
      <script src="script.js"></script>
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
        function hideErrorMessages() {
        var errorMessages = document.querySelectorAll(".error-msg");

        if (errorMessages.length > 0) {
            setTimeout(function () {
                errorMessages.forEach(function (errorMessage) {
                    errorMessage.style.display = 'none';
                });
            }, 3000); // 10000 milliseconds = 10 seconds
        }
    }

        document.addEventListener("DOMContentLoaded", function () {
            hideMessages();
            hideErrorMessages();
        });
                </script>

</body>

</html>