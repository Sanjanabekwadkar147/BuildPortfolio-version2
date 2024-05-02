<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page
    header("Location: login.php"); // Assuming your login page file is named login.php
    exit; // Make sure to exit after redirection to prevent further execution of the script
}
include 'config.php';
$user_id = $_SESSION['user_id'];
$count = isset($_POST['p_name']) ? count($_POST['p_name']) : 1;
$error_messages = array();
$labels = array("Project 1", "Project 2", "Project 3", "Project 4", "Project 5");
$existing_projects = false;
$existing_projects_query = "SELECT * FROM projects WHERE id = '$user_id'";
$result = $conn->query($existing_projects_query);
if ($result->num_rows > 0) {
    $existing_projects = true;
    $project_data = array(); // Array to hold project data including ID, name, description, and link
    while ($row = $result->fetch_assoc()) {
        $project_data[] = array(
            'p_id' => $row['p_id'],
            'p_name' => $row['p_name'],
            'p_description' => $row['p_description'],
            'project_link' => $row['project_link']
        );
    }
    $count = count($project_data);
} else {
    // User is new, set existing_projects to false
    $existing_projects = false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        // Update button clicked, enable fields
        $existing_projects = false; // Set to false to enable fields
    } elseif (isset($_POST['submit'])) {
        $all_inputs_valid = true;
        // Save button clicked, process form data
        $names = $_POST['p_name'];
        $descriptions = $_POST['p_description'];
        $project_links = $_POST['project_link']; // Retrieve project links from the form
        $project_ids = $_POST['project_id']; // Retrieve project IDs from the form

        // Loop through the submitted fields dynamically
        for ($i = 0; $i < count($names); $i++) {
            $p_name = $names[$i];
            $p_description = $descriptions[$i];
            $project_link = $project_links[$i]; // Retrieve the project link for the current iteration
            $project_id = $project_ids[$i]; // Retrieve the project ID for the current iteration

            // Validate project name
            if (!preg_match("/^[a-zA-Z0-9\s]+$/", $p_name)) {
                $error_messages[$i]['p_name'] = "Project name should only contain letters, numbers, and whitespaces.";
                $all_inputs_valid = false;
            }

            // Validate project description
            if (!preg_match("/^[a-zA-Z0-9\s]+$/", $p_description)) {
                $error_messages[$i]['p_description'] = "Description should only contain letters, numbers, and whitespaces.";
                $all_inputs_valid = false;
            }

            // Validate project link (optional)
            if (!empty($project_link) && !filter_var($project_link, FILTER_VALIDATE_URL)) {
                $error_messages[$i]['project_link'] = "Invalid project link format.";
                $all_inputs_valid = false;
            }

            // If no error, proceed with database operations
            if (empty($error_messages[$i])) {
                if ($existing_projects && !empty($project_id)) {
                    // Update existing project
                    $sql = "UPDATE projects SET p_name='$p_name', p_description='$p_description', project_link='$project_link' WHERE id='$user_id' AND p_id='$project_id'";


                } else {
                    // Insert new project
                    $sql = "INSERT INTO projects (id, p_name, p_description, project_link) VALUES ('$user_id', '$p_name', '$p_description', '$project_link')";
                }

                // Execute the SQL query
                if ($conn->query($sql) === TRUE) {
                    if ($all_inputs_valid) {
                        if ($existing_projects) {
                            $success_message = "Project Details updated successfully!";
                            header("Location: " . $_SERVER['PHP_SELF']);

                        } else {
                            $success_message = "Project Details added successfully!";
                            header('location: addinternship.php');

                        }
                    }

                    // If it's a new project, retrieve its ID from the database
                    if (!$existing_projects) {
                        $project_id = $conn->insert_id;
                    }

                    // Fetch project details from the database
                    $project_query = "SELECT * FROM projects WHERE id = '$user_id' AND p_id = '$project_id'";
                    $project_result = $conn->query($project_query);
                    if ($project_result->num_rows > 0) {
                        $project_data[$i] = $project_result->fetch_assoc();
                    }
                } else {
                    $error_message['database'] = "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }
    }

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
            max-width: 800px;
            /* Adjusted maximum width for responsiveness */
            width: 100%;
            /* Ensures the form takes full width on smaller screens */
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


        /* Button styles */
        .form-container form input[type="submit"],
        .form-container form input[type="button"],
        .form-container form .btn {

            background: #fbd0d9;
            /* Background color on hover */
            color: crimson;
            /* Text color on hover */
            text-transform: capitalize;
            font-size: 20px;
            max-width: 150px;
            cursor: pointer;
            border: none;
        }

        /* Hover styles */
        .form-container form input[type="submit"]:hover,
        .form-container form input[type="button"]:hover,
        .form-container form .btn:hover {
            background: crimson;
            /* Background color */
            color: #fff;
            /* Text color */

        }


        .required-label::after {
            content: '*';
            color: crimson;
            margin-left: 4px;
        }

        @media (max-width: 768px) {
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

        @media (max-width: 576px) {
            .form-container {
                padding: 10px;
            }

            .form-container form h3 {
                font-size: 20px;
            }

            .form-container form label {
                font-size: 16px;
            }

            .form-container form input,
            .form-container form textarea {
                font-size: 14px;
            }
        }

        .delete-project-icon {
            margin-left: auto;
            margin-right: 10px;
            cursor: pointer;
            display: inline-block;
        }

        /* Adjust the icon size if needed */
        .delete-project-icon i {
            font-size: 20px;
            color: red;
            /* You can adjust the color */
        }

        .label-container {
            display: flex;
            flex-direction: column;
            /* Stack items vertically */
            align-items: center;
            /* Center items horizontally */
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
            <div class="form-container">

                <form action="" method="post" enctype="multipart/form-data">
                    <h3>My Projects</h3>
                    <div class="form-fields-container">
                        <?php for ($i = 0; $i < $count; $i++):
                            $name_value = isset($project_data[$i]['p_name']) ? htmlspecialchars($project_data[$i]['p_name']) : '';
                            $description_value = isset($project_data[$i]['p_description']) ? htmlspecialchars($project_data[$i]['p_description']) : '';
                            $link_value = isset($project_data[$i]['project_link']) ? htmlspecialchars($project_data[$i]['project_link']) : ''; // Fetching project link from project_data
                            $project_id = isset($project_data[$i]['p_id']) ? $project_data[$i]['p_id'] : ''; // Fetching project ID from project_data
                            ?>
                            <!-- Inside the form loop -->
                            <div class="form-group">
                                <div class="label-container">
                                    <label style="text-align: center;">
                                        <?php echo $labels[$i]; ?>:
                                    </label>
                                    <!-- Add delete icon in front of project label -->
                                    <label for="delete_project_<?php echo $i; ?>" class="delete-project-icon"
                                        title="Delete Project" data-project-id="<?php echo $project_id; ?>">
                                        <i class="fas fa-trash"></i>
                                    </label>

                                </div>
                                <input type="hidden" name="project_id[]" value="<?php echo $project_id; ?>">
                            </div>
                            <div class="form-group">
                                <label for="p_name" class="required-label">Enter Project name</label>
                                <input type="text" id="p_name" name="p_name[]" placeholder="Enter Project name" required
                                    value="<?php echo $name_value; ?>" <?php echo $existing_projects ? 'disabled' : ''; ?>>
                                <?php if (!empty($error_messages[$i]['p_name'])): ?>
                                    <div class="text-danger">
                                        <?php echo $error_messages[$i]['p_name']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="p_description" class="required-label">Enter Project Description</label>
                                <textarea id="p_description" name="p_description[]" placeholder="Enter description" required
                                    <?php echo $existing_projects ? 'disabled' : ''; ?>><?php echo $description_value; ?></textarea>
                                <?php if (!empty($error_messages[$i]['p_description'])): ?>
                                    <div class="text-danger">
                                        <?php echo $error_messages[$i]['p_description']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="project_link_<?php echo $i; ?>">Project Link:</label>
                                <input type="text" id="project_link_<?php echo $i; ?>" name="project_link[]"
                                    placeholder="Enter Project Link (optional)" value="<?php echo $link_value; ?>" <?php echo $existing_projects ? 'disabled' : ''; ?>>
                                <?php if (!empty($error_messages[$i]['project_link'])): ?>
                                    <div class="text-danger">
                                        <?php echo $error_messages[$i]['project_link']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                        <?php endfor; ?>
                    </div>
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-btns-container">
                        <div class="form-btn-container" style="text-align: right;">
                            <button type="button" name="add_more" class="btn btn-success add-more">Add More
                        </div>
                        <div class="form-btn-container">
                            <input type="submit" name="<?php echo $existing_projects ? 'update' : 'submit'; ?>"
                                value="<?php echo $existing_projects ? 'Update' : 'Save'; ?>" class="form-btn">
                        </div>
                    </div>
                    <?php if (!empty($error_message['database'])): ?>
                        <div class="text-danger">
                            <?php echo $error_message['database']; ?>
                        </div>
                    <?php endif; ?>
                </form>

            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
<!-- Add this modal code inside the <body> tag -->
<!-- Modal -->
<div id="deleteConfirmationModal" class="modal fade" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this Project?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");

        toggleButton.onclick = function () {
            el.classList.toggle("toggled");
        };
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

        $(document).ready(function () {

            // Add More button click handler
            $(".add-more").click(function () {

                var newProjectForm = `
        <div class="form-group">
            <div class="label-container">
                <label style="text-align: center;">New Project:</label>
                <label class="delete-project-icon" title="Delete Project">
                    <i class="fas fa-trash"></i>
                </label>
            </div>
            <input type="hidden" name="project_id[]" value="">
            <div class="form-group">
                <label for="p_name" class="required-label">Enter Project name</label>
                <input type="text" id="p_name" name="p_name[]" placeholder="Enter Project name" required>
                
            </div>
            <div class="form-group">
                <label for="p_description" class="required-label">Enter Project Description</label>
                <textarea id="p_description" name="p_description[]" placeholder="Enter description" required></textarea>
            </div>
            <div class="form-group">
                <label for="project_link">Project Link:</label>
                <input type="text" id="project_link" name="project_link[]" placeholder="Enter Project Link (optional)">
            </div>
        </div>
    `;
                $(".form-fields-container").append(newProjectForm);
            });

            // Delete project click handler remains the same


          // Delete project click handler
$(document).on("click", ".delete-project-icon", function () {
    var projectId = $(this).data("project-id");
    // Check if the project ID is empty or null (indicating a dynamically added project)
    if (!projectId) {
        // If the project ID is empty or null, simply remove the project from the DOM
        $(this).closest('.form-group').remove();
    } else {
        // If the project ID is not empty or null, show the confirmation modal
        $('#deleteConfirmationModal').modal('show');

        // Store the project ID in a data attribute of the modal's delete button
        $('#confirmDelete').data('project-id', projectId);
    }
});

// Confirm delete button click handler
$('#confirmDelete').click(function () {
    var projectId = $(this).data('project-id');
    // Proceed with the deletion via AJAX
    $.ajax({
        url: 'delete_project.php',
        type: 'post',
        data: {
            project_id: projectId
        },
        dataType: 'json', // Expect JSON response
        success: function (response) {
            if (response.status === 'success') {
                // If deletion successful, reload the page
                location.reload();
            } else {
                // If deletion fails, display an error message
                alert('Failed to delete project: ' + response.message);
            }
        },
        error: function () {
            // Handle AJAX error
            alert('Failed to delete project. Please try again later.');
        }
    });
});

            $("form").submit(function (e) {
        // Remove all existing error messages
        $(".text-danger").remove();

        // Validation logic goes here
        var isValid = true;
        // Example validation for project name
        $("input[name='p_name[]']").each(function () {
            var projectName = $(this).val();
            var fieldId = $(this).attr("id");
            if (!projectName.match(/^[a-zA-Z0-9\s]+$/)) {
                // Append error message directly after the input field
                $(this).after('<div class="text-danger">Project name should only contain letters, numbers, and whitespaces.</div>');
                isValid = false;
            }
        });

          // Validation logic for project description
          $("textarea[name='p_description[]']").each(function () {
            var projectDescription = $(this).val();
            if (!projectDescription.match(/^[a-zA-Z0-9\s]+$/)) {
                $(this).after('<div class="text-danger">Description should only contain letters, numbers, and whitespaces.</div>');
                isValid = false;
            }
        });

        // Validation logic for project link
        $("input[name='project_link[]']").each(function () {
            var projectLink = $(this).val();
            if (projectLink && !projectLink.match(/^http(s)?:\/\/.*/)) {
                $(this).after('<div class="text-danger">Invalid project link format.</div>');
                isValid = false;
            }
        });

        // Prevent form submission if validation fails
        if (!isValid) {
            e.preventDefault();
        }
    });
        });
    </script>
</body>

</html>