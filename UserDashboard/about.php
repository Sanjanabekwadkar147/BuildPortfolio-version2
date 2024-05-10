<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page
    header("Location: login.php"); // Assuming your login page file is named login.php
    exit; // Make sure to exit after redirection to prevent further execution of the script
}
include 'config.php';
$user_id = $_SESSION['user_id'];
$error_message = array();

$title = $description = '';
$profilePicName = '';

// Fetch existing data
$about_query = "SELECT * FROM aboutme WHERE id = '$user_id'";
$about_result = $conn->query($about_query);
$about_exists = $about_result->num_rows > 0;
$about_formsubmitted = $about_result->num_rows > 0;


if ($about_exists) {
    $about_data = $about_result->fetch_assoc();
    $title = $about_data['title'];
    $description = $about_data['description'];
    $profilePicPath = $about_data['profile_pic']; // Fetch profile picture path

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        // Update button clicked, enable fields
        $about_exists = false; // Set to false to enable fields
    }
    elseif (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];

    if (!empty($_FILES['profile_pic']['name'])) {
        // Handle profile picture upload
        $profilePicName = $_FILES['profile_pic']['name'];
        $profilePicTempName = $_FILES['profile_pic']['tmp_name'];
        $profilePicPath = "uploads/" . $profilePicName;
    
        // Upload profile picture
        move_uploaded_file($profilePicTempName, $profilePicPath);
    } else {
        // If no new file is uploaded, keep the existing profile picture path
        $profilePicPath = $about_data['profile_pic'];
    }

    // Validation
    if (!preg_match("/^[a-zA-Z\s]+$/", $title)) {
        $error_message['title'] = "Please enter only letters and spaces for the title field.";
    }
    if (!preg_match("/^[a-zA-Z\s\d\W]+$/", $description)) {
        $error_message['description'] = "Description should only contain letters, numbers, and whitespaces.";
    }

    if (empty($error_message)) {
       

        // Save or update data in the database
        if ($about_exists) {
            $sql = "UPDATE aboutme SET title = '$title', description = '$description', profile_pic = '$profilePicPath' WHERE id = '$user_id'";
            $message = "About details updated successfully!";
        } else {
            $sql = "INSERT INTO aboutme (id, title, description, profile_pic) VALUES ('$user_id', '$title', '$description', '$profilePicPath')";
            $message = "About details added successfully!";
            header('location: choose_template.php');
        }

        if ($conn->query($sql) === TRUE) {
            $success_message = $message;
            $title = $description ='';
            $about_query = "SELECT * FROM aboutme WHERE id = '$user_id'";
            $about_result = $conn->query($about_query);
            if ($about_result->num_rows > 0) {
                $about_data = $about_result->fetch_assoc();
                $title = $about_data['title'];
                $description = $about_data['description'];
                $profilePicPath = $about_data['profile_pic'];
            }
        } else {
            $error_message[] = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="global.css" />
    <title>Portfolio</title>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php
        include 'header.php';
        ?>
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle"></i>
                    <h2 class="fs-2 m-0">Dashboard</h2>
                </div>

                <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button> -->

            </nav>
            <div class="form-container">
                <form action="" method="post" enctype="multipart/form-data" >
                    <h3>About Me</h3>
                    <div class="form-group">
                        <label for="title" class="required-label">Enter Profession</label>
                        <input type="text" id="title" name="title" placeholder="Enter the title" required
                            value="<?php echo isset ($title) ? htmlspecialchars($title) : ''; ?>" <?php echo $about_exists ? 'disabled' : ''; ?>>
                        <?php if (isset ($error_message['title'])): ?>
                            <div class="text-danger">
                                <?php echo $error_message['title']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="description" class="required-label">Enter Description</label>
                        <textarea name="description" id="description" placeholder="Enter the description"
                            required <?php echo $about_exists ? 'disabled' : ''; ?>><?php echo isset ($description) ? htmlspecialchars($description) : ''; ?></textarea>
                        <?php if (isset ($error_message['description'])): ?>
                            <div class="text-danger">
                                <?php echo $error_message['description']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="profile-pic" class="required-label">Choose Profile Picture</label>
                        <?php if ($about_exists): ?>
                            <input type="text" id="profile-pic" name="profile_pic" placeholder="Enter profile picture path" value="<?php echo isset($profilePicPath) ? htmlspecialchars($profilePicPath) : ''; ?>" <?php echo $about_exists ? 'disabled' : ''; ?>>
                        <?php else: ?>
                            <input type="file" name="profile_pic" id="profile-pic" accept="image/*">
                        <?php endif; ?>
                    </div>  

                    <?php if (isset ($success_message)): ?>
                        <div class="alert alert-success">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>

                    <input type="submit" name="<?php echo $about_exists ? 'update' : 'submit'; ?>" value="<?php echo $about_exists ? 'Update' : 'Save'; ?>" class="form-btn" id="submit-btn">

                </form>
            </div>
        </div>

    </div>

    <!-- /#sidebar-wrapper -->
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");

        toggleButton.addEventListener("click", function () {
            el.classList.toggle("toggled");
        });
        function hideMessages() {
            var successAlert = document.querySelector(".alert-success");
            var errorMessages = document.querySelectorAll(".text-danger"); // Select all error messages

            if (successAlert) {
                setTimeout(function () {
                    successAlert.style.display = 'none';
                }, 5000); // 5000 milliseconds = 5 seconds
            }

            // Hide error messages after 5 seconds
            errorMessages.forEach(function (errorMessage) {
                setTimeout(function () {
                    errorMessage.style.display = 'none';
                }, 5000);
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            hideMessages();
        });

    </script>
</body>

</html>