<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page
    header("Location: login.php"); // Assuming your login page file is named login.php
    exit; // Make sure to exit after redirection to prevent further execution of the script
}
include 'config.php';
$user_id = $_SESSION['user_id'];
$phone_code = "+1";
$formSubmitted = false;  // Variable to track form submission


$name = $profession = $email = $phone = $address = $facebook = $linkedin = $github = $twitter = $youtube = '';
$error_message = array();

$profile_query = "SELECT * FROM profile WHERE id = '$user_id'";
$profile_result = $conn->query($profile_query);


// Check if profile data exists for the user
$profile_exists = $profile_result->num_rows > 0;
$profile_formsubmitted = $profile_result->num_rows > 0;



if ($profile_exists) {
    $profile_data = $profile_result->fetch_assoc();
    $name = $profile_data['name'];
    $profession = $profile_data['profession'];
    $email = $profile_data['email'];
    $phone = $profile_data['phone'];
    $address = $profile_data['address'];
    $facebook = $profile_data['facebook'];
    $linkedin = $profile_data['linkedin'];
    $github = $profile_data['github'];
    $twitter = $profile_data['twitter'];
    $youtube = $profile_data['youtube'];
    $phone_code = $profile_data['phone_code'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        // Update button clicked, enable fields
        $profile_exists = false; // Set to false to enable fields
    } elseif (isset($_POST['submit'])) {
        // Save button clicked, process form data
        $phone_code = $_POST['phone_code']; 
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
        $professionPattern = "/^[a-zA-Z\s]+$/";
        $emailPattern = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/";
        $phonePattern = "/^\d{10}$/";

        if (!preg_match($namePattern, $name)) {
            $error_message['name'] = "Name should only contain letters and whitespaces.";
        }
        if (!preg_match($professionPattern, $profession)) {
            $error_message['profession'] = "Profession should only contain letters and whitespaces.";
        }
        if (!preg_match($emailPattern, $email)) {
            $error_message['email'] = "Invalid email format.";
        } else {
            // Additional custom validation for email
            $parts = explode('@', $email);
            if (preg_match("/^\d|[#@]/", $parts[0])) {
                $error_message['email'] = 'Invalid email format';
            }
        }
        if (!preg_match($phonePattern, $phone)) {
            $error_message['phone'] = "Invalid phone number. Please enter a 10-digit number without any spaces, dashes, or decimal points.";
        }

        if (empty($error_message)) {
            $sql = "REPLACE INTO profile (id, name, profession, email, phone, phone_code, address, facebook, linkedin, github, twitter, youtube)
            VALUES ('$user_id', '$name', '$profession', '$email', '$phone', '$phone_code', '$address', '$facebook', '$linkedin', '$github', '$twitter', '$youtube')";
            
            if ($conn->query($sql) === TRUE) {
                if ($profile_exists) {
                    $success_message = "Profile updated successfully!";
                } else {
                    $success_message = "Profile created successfully!";
                    header('location: skill.php');
                }
            } else {
                $error_message['database'] = "Error: " . $sql . "<br>" . $conn->error;
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
    <style>
        .form-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        padding-bottom: 60px;
    }

    .form-container form {
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, .1);
        background: #fff;
        text-align: center;
        max-width: 920px; /* Adjusted maximum width for responsiveness */
        width: 100%; /* Ensures the form takes full width on smaller screens */
    }

    .form-container form h3 {
        font-size: 35px;
        text-transform: uppercase;
        margin-bottom: 10px;
        color: #333;
    }

    .form-container form label {
        display: block;
        text-align: left;
        font-size: 20px;
        margin-bottom: 8px;
        font-weight: bold;
        color: #333;
    }

    .form-container form input,
    .form-container form textarea {
        width: 100%;
        padding: 10px 15px;
        font-size: 17px;
        margin: 8px 0;
        border-radius: 5px;
    }

    .form-container form input[type="submit"] {
        background: #fbd0d9;
        color: crimson;
        text-transform: capitalize;
        font-size: 20px;
        max-width: 150px;
        cursor: pointer;
        border: none;
    }

    .form-container form input[type="submit"]:hover {
        background: crimson;
        color: #fff;
    }

    .required-label::after {
        content: '*';
        color: crimson;
        margin-left: 4px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .form-container form {
            padding: 20px;
        }
    }

    @media (max-width: 576px) {
        .form-container form {
            padding: 10px;
        }
        .form-container form h3 {
            font-size: 25px;
        }
        .form-container form label {
            font-size: 18px;
        }
        .form-container form input,
        .form-container form textarea {
            font-size: 16px;
        }
    }    
    .form-group .d-flex {
    display: flex;
    gap: 10px; /* Adds space between the select dropdown and the input */
}

.form-control {
    flex: 1; /* Allows the dropdown to take necessary space */
    max-width: 100px; /* Optionally set a max width */
}

#phone {
    flex: 3; /* Gives more space to the phone input field */
}
    </style>
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

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <!-- <a href="Home.php" class="nav-link second-text fw-bold">
                            <i class="fas fa-home me-2 fs-4"></i> Adjust the font size here (e.g., fs-4) -->
                        
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle second-text fw-bold" href="#" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-2"></i>John Doe
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="#">Settings</a></li>
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul>
                        </li> -->
                    </ul>
                </div>
            </nav>
            <div class="form-container">
                <form action="" method="post" enctype="multipart/form-data">
                    <h3>My Profile </h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="required-label">Name</label>
                                <input type="text" id="name" name="name" placeholder="Enter name" required
                                    value="<?php echo htmlspecialchars($name); ?>" <?php echo $profile_exists ? 'disabled' : ''; ?>>
                                <?php if (isset($error_message['name'])): ?>
                                    <div class="text-danger">
                                        <?php echo $error_message['name']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="profession" class="required-label">Profession</label>
                                <input type="text" id="profession" name="profession" placeholder="Enter profession"
                                    required value="<?php echo htmlspecialchars($profession); ?>" <?php echo $profile_exists ? 'disabled' : ''; ?>>
                                <?php if (isset($error_message['profession'])): ?>
                                    <div class="text-danger">
                                        <?php echo $error_message['profession']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="email" class="required-label">Email</label>
                                <input type="email" id="email" name="email" placeholder="Enter email" required
                                    value="<?php echo htmlspecialchars($email); ?>"  <?php echo $profile_exists ? 'disabled' : ''; ?>>
                                <?php if (isset($error_message['email'])): ?>
                                    <div class="text-danger">
                                        <?php echo $error_message['email']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
    <label for="phone" class="required-label">Phone</label>
    <div class="d-flex align-items-center">
        <select id="phone_code" name="phone_code" class="form-control" <?php echo $profile_exists ? 'disabled' : ''; ?>>
        <option value="+1" <?php echo $phone_code == '+1' ? 'selected' : ''; ?>>+1 United States</option>
            <option value="+44" <?php echo $phone_code == '+44' ? 'selected' : ''; ?>>+44 United Kingdom</option>
            <option value="+91" <?php echo $phone_code == '+91' ? 'selected' : ''; ?>>+91 India</option>
            <option value="+61" <?php echo $phone_code == '+61' ? 'selected' : ''; ?>>+61 Australia</option>
            <option value="+81" <?php echo $phone_code == '+81' ? 'selected' : ''; ?>>+81 Japan</option>
            <option value="+49" <?php echo $phone_code == '+49' ? 'selected' : ''; ?>>+49 Germany</option>
            <option value="+33" <?php echo $phone_code == '+33' ? 'selected' : ''; ?>>+33 France</option>
            <option value="+1" <?php echo $phone_code == '+1' ? 'selected' : ''; ?>>+1 Canada</option>
        <option value="+55" <?php echo $phone_code == '+55' ? 'selected' : ''; ?>>+55 Brazil</option>
        <option value="+52" <?php echo $phone_code == '+52' ? 'selected' : ''; ?>>+52 Mexico</option>
        <option value="+7" <?php echo $phone_code == '+7' ? 'selected' : ''; ?>>+7 Russia</option>
        <option value="+86" <?php echo $phone_code == '+86' ? 'selected' : ''; ?>>+86 China</option>
        <option value="+82" <?php echo $phone_code == '+82' ? 'selected' : ''; ?>>+82 South Korea</option>
        <option value="+39" <?php echo $phone_code == '+39' ? 'selected' : ''; ?>>+39 Italy</option>
        <option value="+34" <?php echo $phone_code == '+34' ? 'selected' : ''; ?>>+34 Spain</option>
        <option value="+27" <?php echo $phone_code == '+27' ? 'selected' : ''; ?>>+27 South Africa</option>
        <option value="+234" <?php echo $phone_code == '+234' ? 'selected' : ''; ?>>+234 Nigeria</option>
            <!-- Add more options as needed -->
        </select>
        <input type="text" id="phone" name="phone" placeholder="Enter phone number" required
            value="<?php echo htmlspecialchars($phone); ?>"  <?php echo $profile_exists ? 'disabled' : ''; ?>>
    </div>
    <?php if (isset($error_message['phone'])): ?>
        <div class="text-danger">
            <?php echo $error_message['phone']; ?>
        </div>
    <?php endif; ?>
</div>

                            <div class="form-group">
                                <label for="address" class="required-label">Address</label>
                                <textarea id="address" name="address" placeholder="Enter address"
                                    required  <?php echo $profile_exists ? 'disabled' : ''; ?>><?php echo htmlspecialchars($address); ?></textarea>
                                <?php if (isset($error_message['address'])): ?>
                                    <div class="text-danger">
                                        <?php echo $error_message['address']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="facebook">Facebook</label>
                                <input type="text" id="facebook" name="facebook" placeholder="Enter facebook link"
                                    value="<?php echo htmlspecialchars($facebook); ?>"  <?php echo $profile_exists ? 'disabled' : ''; ?>>
                            </div>
                            <div class="form-group">
                                <label for="linkedin">LinkedIn</label>
                                <input type="text" id="linkedin" name="linkedin" placeholder="Enter linkedin link"
                                    value="<?php echo htmlspecialchars($linkedin); ?>"  <?php echo $profile_exists ? 'disabled' : ''; ?>>
                            </div>
                            <div class="form-group">
                                <label for="github">Github</label>
                                <input type="text" id="github" name="github" placeholder="Enter github link"
                                    value="<?php echo htmlspecialchars($github); ?>"  <?php echo $profile_exists ? 'disabled' : ''; ?>>
                            </div>
                            <div class="form-group">
                                <label for="twitter">Twitter</label>
                                <input type="text" id="twitter" name="twitter" placeholder="Enter twitter link"
                                    value="<?php echo htmlspecialchars($twitter); ?>"  <?php echo $profile_exists ? 'disabled' : ''; ?>>
                            </div>
                            <div class="form-group">
                                <label for="youtube">YouTube</label>
                                <input type="text" id="youtube" name="youtube" placeholder="Enter youtube link"
                                    value="<?php echo htmlspecialchars($youtube); ?>"  <?php echo $profile_exists ? 'disabled' : ''; ?>>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>

                    <input type="submit" name="<?php echo $profile_exists ? 'update' : 'submit'; ?>" value="<?php echo $profile_exists ? 'Update' : 'Save'; ?>" class="form-btn" id="submit-btn">
            </div>

        </div>

    </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");

        toggleButton.onclick = function () {
            el.classList.toggle("toggled");
        };

        function hideMessages() {
            var successAlert = document.querySelector(".alert-success");
            var errorMessages = document.querySelectorAll(".text-danger"); 
            if (successAlert) {
                setTimeout(function () {
                    successAlert.style.display = 'none';
                }, 5000); 
            }

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