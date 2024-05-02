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
                <div class="logo"><a href="#">Portfolio<span>lio.</span></a></div>
                <ul class="menu">
                    <li><a href="index.php" class="menu-btn">Home</a></li>
                    <li><a href="index.php" class="menu-btn">About</a></li>
                    <li><a href="index.php" class="menu-btn">Services</a></li>
                    <li><a href="index.php" class="menu-btn">Contact</a></li>
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
                <input type="text" name="name" required placeholder="Enter your name">
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
            <input type="submit" name="submit" value="Register Now" class="form-btn">
            <p>Already have an account? <a href="login.php">Login now</a></p>

            <!-- Toast Message -->
            <?php if (!empty($success_message)) : ?>
                <div id="toast"
                    class="toast align-items-center text-white bg-peach position-fixed top-30 start-50 translate-middle"
                    role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <?php echo $success_message; ?>
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" onclick="hideToast()"
                            aria-label="Close"></button>
                    </div>
                </div>

                <style>
                    .bg-peach {
                        background-color: #FFDAB9;
                    }
                </style>

                <script>
                    function hideToast() {
                        document.getElementById('toast').style.display = 'none';
                    }

                    document.addEventListener('DOMContentLoaded', function () {
                        document.getElementById('toast').style.display = 'block';
                        setTimeout(function () {
                            hideToast();
                        }, 5000);
                    });
                </script>
            <?php endif; ?>

        </form>
    </div>
    <footer>
        <span>Created By <a href="#">Sanjana Bekwadkar</a> | <span class="far fa-copyright"></span> 2024 All rights reserved.</span>
    </footer>
</body>
</html>
