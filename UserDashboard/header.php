<?php
include 'config.php';
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
        .disabled-link {
    color: var(--second-text-color) !important;
    pointer-events: none; /* Disable pointer events to prevent interaction */
    cursor: not-allowed; /* Change cursor to not-allowed */
}

    </style>

    <title>Portfolio</title>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
                <i class="fas fa-user-secret me-2"></i>Portfolio
            </div>
            <div class="list-group list-group-flush my-3">
                <a href="userdash.php" class="list-group-item list-group-item-action bg-transparent second-text active">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a href="profile.php"
                    class="list-group-item list-group-item-action bg-transparent primary-text fw-bold">
                    <i class="fas fa-user me-2"></i>Profile
                </a>

                <a href="skill.php"
    class="list-group-item list-group-item-action bg-transparent second-text fw-bold <?php echo !$profile_formsubmitted ? 'disabled-link' : 'enabled-link'; ?>">
    <i class="fas fa-tools me-2"></i>Skills
</a>
<div class="list-group-item list-group-item-action bg-transparent second-text fw-bold <?php echo !$skills_formsubmitted ? 'disabled' : ''; ?>">
    <div class="dropdown">
        <a class="dropdown-toggle <?php echo $skills_formsubmitted ? 'enabled-link' : ''; ?>" href="#"
            role="button" id="resumeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-file-alt me-2"></i>Resume
        </a>
        <ul class="dropdown-menu" aria-labelledby="resumeDropdown">
            <li><a class="dropdown-item <?php echo !$skills_formsubmitted ? 'disabled-link' : 'enabled-link'; ?>"
                    href="addeducation.php"><i class="fas fa-graduation-cap me-2"></i>Education</a></li>
            <li><a class="dropdown-item <?php echo !$resume_formsubmitted ? 'disabled-link' : 'enabled-link'; ?>"
                    href="addproject.php"><i class="fas fa-project-diagram me-2"></i>Projects</a></li>
            <li><a class="dropdown-item <?php echo !$project_formsubmitted ? 'disabled-link' : 'enabled-link'; ?>"
                    href="addinternship.php"><i class="fas fa-briefcase me-2"></i>Experience/Internship</a></li>
        </ul>
    </div>
</div>

<a href="about.php"
    class="list-group-item list-group-item-action bg-transparent second-text fw-bold <?php echo ($project_formsubmitted || $experience_formsubmitted) ? 'enabled-link' : 'disabled-link'; ?>">
    <i class="fas fa-info-circle me-2"></i>About
</a>
<a href="choose_template.php"
    class="list-group-item list-group-item-action bg-transparent second-text fw-bold <?php echo !$about_formsubmitted ? 'disabled-link' : 'enabled-link'; ?>">
    <i class="fas fa-file-alt"></i> &nbsp;Choose Template
</a>

                <a href="logout.php" class="list-group-item list-group-item-action bg-transparent second-text active">
                    <i class="fas fa-power-off me-2"></i>Logout
                </a>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script>
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");

        toggleButton.onclick = function () {
            el.classList.toggle("toggled");
        };
    </script> -->
</body>

</html>