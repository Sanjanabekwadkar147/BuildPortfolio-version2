<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page
    header("Location: login.php"); // Assuming your login page file is named login.php
    exit; // Make sure to exit after redirection to prevent further execution of the script
}
include 'config.php';

$user_id = $_SESSION['user_id'];
//Profile
$profile_query = "SELECT * FROM profile WHERE id = '$user_id'";
$profile_result = $conn->query($profile_query);
$profile_formsubmitted = $profile_result->num_rows > 0;


//Skills

$skills_query = "SELECT * FROM skills WHERE id = '$user_id'";
$skills_result = $conn->query($skills_query);
$skills_formsubmitted = $skills_result->num_rows > 0;

//Resume

$existing_education_query = "SELECT * FROM education WHERE id = '$user_id'";
$result = $conn->query($existing_education_query);
$resume_formsubmitted = $result->num_rows > 0;


$existing_projects_query = "SELECT * FROM projects WHERE id = '$user_id'";
$result = $conn->query($existing_projects_query);
$project_formsubmitted = $result->num_rows > 0;

$existing_experience_query = "SELECT * FROM internships_experience WHERE id = '$user_id'";
$result = $conn->query($existing_experience_query);
$experience_formsubmitted = $result->num_rows > 0;


//About
$about_query = "SELECT * FROM aboutme WHERE id = '$user_id'";
$about_result = $conn->query($about_query);
$about_formsubmitted = $about_result->num_rows > 0;

// Initializing session variables for each form status if not already set
if (!isset($_SESSION['profile_completed'])) {
    $_SESSION['profile_completed'] = false;
}
if (!isset($_SESSION['skills_completed'])) {
    $_SESSION['skills_completed'] = false;
}
if (!isset($_SESSION['resume_completed'])) {
    $_SESSION['resume_completed'] = false;
}

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
        :root {
            --main-bg-color: #dc143c;
            /* Crimson color code */
            --main-text-color: #dc143c;
            /* Crimson color code */
            --second-text-color: #bbbec5;
            --second-bg-color: #fbd0d9;
        }


        .primary-text {
            color: var(--main-text-color);
        }

        .second-text {
            color: var(--second-text-color);
        }

        .primary-bg {
            background-color: var(--main-bg-color);
        }

        .secondary-bg {
            background-color: var(--second-bg-color);
        }

        .rounded-full {
            border-radius: 100%;
        }

        #wrapper {
            overflow-x: hidden;
            background-image: linear-gradient(to right, #fbd0d9, #fbd0d9, #fbd0d9, #fbd0d9, #fbd0d9);
        }

        #sidebar-wrapper {
            min-height: 100vh;
            margin-left: -15rem;
            -webkit-transition: margin 0.25s ease-out;
            -moz-transition: margin 0.25s ease-out;
            -o-transition: margin 0.25s ease-out;
            transition: margin 0.25s ease-out;
        }

        #sidebar-wrapper .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
        }

        #sidebar-wrapper .list-group {
            width: 15rem;
        }

        #page-content-wrapper {
            min-width: 100vw;
            background-color: #fbd0d9;
        }

        #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
        }

        #menu-toggle {
            cursor: pointer;
        }

        .list-group-item {
            border: none;
            padding: 20px 30px;
        }

        .list-group-item.active {
            background-color: transparent;
            color: var(--main-text-color);
            font-weight: bold;
            border: none;
        }

        .list-group-item a {
            color: var(--second-text-color);
            text-decoration: none;
        }

        .list-group-item a:hover {
            color: var(--main-text-color);
        }

        @media (min-width: 200px) {
            #sidebar-wrapper {
                margin-left: 0;
            }

            #page-content-wrapper {
                min-width: 0;
                width: 100%;
            }

            #wrapper.toggled #sidebar-wrapper {
                margin-left: -15rem;
            }
        }

        /* CSS to change the text color to black on hover for the dropdown toggle */


        /* CSS to change the text color to black on hover for the dropdown items */
        .dropdown-menu a.dropdown-item:hover {
            color: black !important;
            /* Ensuring this takes precedence over other color styles */
        }

        .enabled-link {
            color: var(--main-text-color) !important;
            /* Override other color settings to ensure visibility */
        }

        .enabled-link.dropdown-toggle {
            color: var(--main-text-color) !important;
            /* Specific for dropdown toggles */
        }

        .dropdown-menu .dropdown-item.enabled-link:hover,
        .dropdown-menu .dropdown-item.enabled-link:focus {
            color: white;
            /* Adjust as needed */
            background-color: var(--main-bg-color);
        }


        /* Responsive text sizes using viewport width (vw) */
        .h3-responsive {
            font-size: 2vw;
            /* Adjust the size based on your preference */
        }

        /* Ensure .fs-1 scales with the viewport width for responsiveness */
        .fs-1-responsive {
            font-size: 5vw;
            /* Adjust based on preference */
        }

        /* Using Flexbox to ensure contents stay within their containers */
        .p-3 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            /* Allows items to wrap in smaller screens */
        }

        @media screen and (max-width: 768px) {

            /* For tablets and below */
            .h3-responsive {
                font-size: 3vw;
                /* Increase size for better readability */
            }

            .fs-1-responsive {
                font-size: 8vw;
                /* Adjust for smaller screens */
            }
        }

        @media screen and (max-width: 280px) {

            /* For mobile phones */
            .h3-responsive {
                font-size: 5vw;
                /* Further increase for small devices */
            }

            .fs-1-responsive {
                font-size: 12vw;
                /* Make icons bigger on small screens */
            }

            /* Adjust padding inside boxes for small screens */
            .p-3 {
                padding: 20px;
                /* Reduced padding */
            }
        }
    </style>
    <title>Portfolio</title>
</head>

<body>
    <div class="d-flex" id="wrapper">
    <div class="bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
                <i class="fas fa-user-secret me-2"></i>Portfolio
            </div>
            <div class="list-group list-group-flush my-3">
            <a href="userdash.php" class="list-group-item list-group-item-action bg-transparent second-text active">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a href="profile.php" class="list-group-item list-group-item-action bg-transparent primary-text fw-bold">
    <i class="fas fa-user me-2"></i>Profile
</a>

                <a href="skill.php"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold <?php echo !$profile_formsubmitted ? 'disabled' : 'enabled-link'; ?>">
                    <i class="fas fa-tools me-2"></i>Skills
                </a>
                <div
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold <?php echo !$skills_formsubmitted ? 'disabled' : ''; ?>">
                    <div class="dropdown">
                        <a class="dropdown-toggle <?php echo $skills_formsubmitted ? 'enabled-link' : ''; ?>" href="#"
                            role="button" id="resumeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-alt me-2"></i>Resume
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="resumeDropdown">
                            <li><a class="dropdown-item <?php echo $skills_formsubmitted ? 'enabled-link' : ''; ?>"
                                    href="addeducation.php"><i class="fas fa-graduation-cap me-2"></i>Education</a></li>
                            <li><a class="dropdown-item <?php echo $resume_formsubmitted ? 'enabled-link' : ''; ?>"
                                    href="addproject.php"><i class="fas fa-project-diagram me-2"></i>Projects</a></li>
                            <li><a class="dropdown-item <?php echo $project_formsubmitted ? 'enabled-link' : ''; ?>"
                                    href="addinternship.php"><i
                                        class="fas fa-briefcase me-2"></i>Experience/Internship</a></li>
                        </ul>
                    </div>
                </div>

                <a href="about.php"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold <?php echo !$experience_formsubmitted  ? 'disabled' : 'enabled-link'; ?>">
                    <i class="fas fa-info-circle me-2"></i>About
                </a>
                <a href="choose_template.php"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold <?php echo !$about_formsubmitted ? 'disabled' : 'enabled-link'; ?>">
                    <i class="fas fa-file-alt"></i>&nbsp; Choose Template
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action bg-transparent second-text active">
                    <i class="fas fa-power-off me-2"></i>Logout
                </a>
            </div>

        </div>

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
                            <i class="fas fa-home me-2 fs-4"></i> <!-- Adjust the font size here (e.g., fs-4) -->

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

            <div class="container-fluid px-4">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 my-2">
                    <div class="col">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="h3-responsive">Resume</h3>
                            </div>
                            <i
                                class="fas fa-file-alt fs-1-responsive primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="h3-responsive">Skills</h3>
                            </div>
                            <i
                                class="fas fa-tools fs-1-responsive primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="h3-responsive">Projects</h3>
                            </div>
                            <i
                                class="fas fa-project-diagram fs-1-responsive primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="h3-responsive">Experience</h3>
                            </div>
                            <i
                                class="fas fa-briefcase fs-1-responsive primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    </div>
    <!-- /#page-content-wrapper -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");

        toggleButton.onclick = function () {
            el.classList.toggle("toggled");
        };
    </script>

</body>

</html>