<?php
include 'config.php';

$error = array();
// $success_message = "";

// Insert data into the database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {

   // Validate name
if (empty($_POST['name'])) {
    $error['name'] = 'Name is required!';
} else {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    if (!preg_match("/^(?=.*[A-Za-z])[A-Za-z\d@#$%^&+=!]*$/", $name)) {
        $error['name'] = 'Invalid username';
    }
}

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


    if (empty($_POST['password'])) {
        $error['password'] = 'Password is required!';
    } else {
        $password = $_POST['password'];
        if (strlen($password) < 6 || !preg_match("/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@#$%^&!])[A-Za-z\d@#$%^&!]+$/", $password)) {
            $error['password'] = 'Password must be at least 6 characters and include letters, numbers, and one special symbol.';
        }
    }

    $select = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $success_message = "user already exists";
    } else {
        if (empty($error)) {
            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

            if ($conn->query($sql) === TRUE) {
                $success_message = "User created successfully";
                header('location: login.php');
            } else {
                echo "Error: " . $conn->error;
            }
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
            <h3>Register Now</h3>
            <div class="form-group">
                <input type="text" name="name" placeholder="Enter user name">
                <?php if (isset($error['name'])) echo '<span class="error-msg">' . $error['name'] . '</span>'; ?>
            </div>
            <div class="form-group">
                <input type="email" name="email" required placeholder="Enter your email">
                <?php if (isset($error['email'])) echo '<span class="error-msg">' . $error['email'] . '</span>'; ?>
            </div>
            <div class="form-group">
                <input type="password" name="password" required placeholder="Enter your password">
                <?php if (isset($error['password'])) echo '<span class="error-msg">' . $error['password'] . '</span>'; ?>
            </div>
            <!-- <select name="user_type">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select> -->
            <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
            <input type="submit" name="submit" value="Register Now" class="form-btn">
            <p>Already have an account? <a href="login.php">Login now</a></p>


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

        </form>
    </div>
    <footer>
        <span>Created By <a href="#">Sanjana Bekwadkar</a> | <span class="far fa-copyright"></span> 2024 All rights reserved.</span>
    </footer>
    <script src="script.js"></script>
</body>
</html>

