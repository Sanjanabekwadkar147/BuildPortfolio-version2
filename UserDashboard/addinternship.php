<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page
    header("Location: login.php"); // Assuming your login page file is named login.php
    exit; // Make sure to exit after redirection to prevent further execution of the script
}
include 'config.php';
$user_id = $_SESSION['user_id'];
$count = isset($_POST['name']) ? count($_POST['name']) : 1;
$labels = array("Current Employment", "Past Employment", "Past Employment" , "Past Employment" , "Past Employment" , "Past Employment");
$error_messages = array();
$isFormVisible = false;

$existing_experience_query = "SELECT * FROM internships_experience WHERE id = '$user_id'";
$result = $conn->query($existing_experience_query);
if ($result->num_rows > 0) {
    $existing_experience = true;
    $names = array();
    $company_names = array();
    $start_dates = array();
    $end_dates = array();
    $descriptions = array();
    $i_ids = array();
    while ($row = $result->fetch_assoc()) {
        $i_id = $row['i_id'];
        $names[] = $row['name'];
        $company_names[] = $row['company_name'];
        $start_dates[] = $row['start_date'];
        $end_dates[] = $row['end_date'];
        $descriptions[] = $row['i_description'];
        $i_ids[] = $i_id;

    }
    $count = count($names);
} else {
    // User is new, set existing_experience to false
    $existing_experience = false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isFormVisible = true;
    if (isset($_POST['update'])) {
        // Update button clicked, enable fields
        $existing_experience = false; // Set to false to enable fields
    } elseif (isset($_POST['submit'])) {
        $all_inputs_valid = true;
        // Save button clicked, process form data
        $names = $_POST['name'];
        $company_names = $_POST['company_name'];
        $start_dates = $_POST['start_date'];
        $end_dates = $_POST['end_date'];
        $descriptions = $_POST['i_description'];
        $i_ids = $_POST['i_id']; // This might not be set for new entries
        $count = count($names);

        for ($i = 0; $i < $count; $i++) {
            $i_id = $i_ids[$i] ?? null; // Use null coalescing operator to handle non-existing i_id
            $name = $names[$i];
            $company_name = $company_names[$i];
            $start_date = $start_dates[$i];
            $end_date = $end_dates[$i];
            $i_description = $descriptions[$i];

            if (!preg_match("/^[a-zA-Z\s\d\W]+$/", $i_description)) {
                $error_messages[$i]['i_description'] = "Description should only contain letters, numbers, and whitespaces.";
                $all_inputs_valid = false;
            }
            if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
                $error_messages[$i]['name'] = "Role should only contain letters and whitespaces.";
                $all_inputs_valid = false;
            }

            if (empty($error_messages[$i])) {
                if (!empty($i_id)) {
                    // Update existing entry
                    $sql = "UPDATE internships_experience SET name=?, company_name=?, start_date=?, end_date=?, i_description=? WHERE i_id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssssi", $name, $company_name, $start_date, $end_date, $i_description, $i_id);
                } else {
                    // Insert new entry
                    $sql = "INSERT INTO internships_experience (id, name, company_name, start_date, end_date, i_description) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("isssss", $user_id, $name, $company_name, $start_date, $end_date, $i_description);
                }

                if ($stmt->execute()) {
                    // Query executed successfully
                } else {
                    // Query execution failed
                    $error_message['database'] = "Error: " . $stmt->error;
                    $all_inputs_valid = false; // Mark inputs as invalid
                }
                $stmt->close();
            }
        }

        // Display success message only if all inputs are valid and no database errors
        if ($all_inputs_valid) {
            // This success message will only be set if all inputs are valid and the query execution was successful
            if ($existing_experience) {
                $success_message = "Experinece Details updated successfully!";
            } else {
                $success_message = "Experinece Details added successfully!";
                header('location: about.php');
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
            display: none;
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
        .delete-experience-icon {
            margin-left: auto;
    margin-right: 10px; 
            cursor: pointer;
            display: inline-block;
        }

        /* Adjust the icon size if needed */
        .delete-experience-icon i {
            font-size: 20px;
            color: red;
            /* You can adjust the color */
        }
        .label-container {
    display: flex;
    flex-direction: column; /* Stack items vertically */
    align-items: center; /* Center items horizontally */
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
<?php
                        // Check if there are existing education details
                        $existing_experience_query = "SELECT * FROM internships_experience WHERE id = '$user_id'";
                        $result = $conn->query($existing_experience_query);
                        if ($result->num_rows === 0) {
                            ?>
                          <div class="checkbox-container text-center mt-5">
        <input type="checkbox" id="experienceCheckbox" name="experienceCheckbox" <?php echo $isFormVisible ? 'checked' : ''; ?>>
        <label for="experienceCheckbox">Have you completed any internships or do you have any work experience?</label>
    </div>
                            <?php
                        }
                        ?>

            <div class="form-container" style="<?php echo $isFormVisible ? 'display:flex;' : 'display:none;'; ?>">
                <form action="" method="post" enctype="multipart/form-data">
                    <h3>Internship/Experience</h3>
                    <div class="form-fields-container">
                        <?php
                        for ($i = 0; $i < $count; $i++) {
                            $company_value = isset($company_names[$i]) ? htmlspecialchars($company_names[$i]) : '';
                            $name_value = isset($names[$i]) ? htmlspecialchars($names[$i]) : '';
                            $start_date_value = isset($start_dates[$i]) ? htmlspecialchars($start_dates[$i]) : '';
                            $end_date_value = isset($end_dates[$i]) ? htmlspecialchars($end_dates[$i]) : '';
                            $description_value = isset($descriptions[$i]) ? htmlspecialchars($descriptions[$i]) : '';
                            ?>
                            <div class="label-container">
                                    <label style="text-align: center;">
                                        <?php echo $labels[$i]; ?>:
                                    </label>
                                    <!-- Add delete icon in front of project label -->
                                    <label for="delete_experience_<?php echo $i; ?>" class="delete-experience-icon"
                                        title="Delete Experience" data-experience-id="<?php echo $i_id; ?>">
                                        <i class="fas fa-trash"></i>
                                    </label>

                            </div>
                            <div class="form-group">
                                <label for="company_name" class="required-label">Enter Company name</label>
                                <input type="text" id="company_name" name="company_name[]" placeholder="Enter Company name" required
                                    value="<?php echo $company_value; ?>" <?php echo $existing_experience ? 'disabled' : ''; ?>>
                            </div>
                            <div class="form-group">
                                <label for="name" class="required-label">Enter Role</label>
                                <input type="text" id="name" name="name[]" placeholder="Enter Role" required
                                    value="<?php echo $name_value; ?>" <?php echo $existing_experience ? 'disabled' : ''; ?>>
                                    <?php if (!empty($error_messages[$i]['name'])): ?>
    <div class="text-danger">
        <?php echo $error_messages[$i]['name']; ?>
    </div>
<?php endif; ?>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label>Enter Start Date</label>
                                        <input type="date" name="start_date[]" placeholder="Start Date"
                                            value="<?php echo $start_date_value; ?>" <?php echo $existing_experience ? 'disabled' : ''; ?>>
                                    </div>
                                    <div class="col">
                                        <label>Enter End Date</label>
                                        <input type="date" name="end_date[]" placeholder="End Date"
                                            value="<?php echo $end_date_value; ?>" <?php echo $existing_experience ? 'disabled' : ''; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="required-label">Enter Description</label>
                                <textarea name="i_description[]"
                                    placeholder="Enter description" required <?php echo $existing_experience ? 'disabled' : ''; ?>><?php echo isset($descriptions[$i]) ? htmlspecialchars($descriptions[$i]) : ''; ?></textarea>
                                <?php if (!empty($error_messages[$i]['i_description'])): ?>
                                    <div class="text-danger">
                                        <?php echo $error_messages[$i]['i_description']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="i_id[]" value="<?php echo isset($i_ids[$i]) ? $i_ids[$i] : ''; ?>">

                       
                        <?php } ?>
                    </div>
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-btns-container">
                    
                            <div class="form-btn-container" style="text-align: right;">
                                <input type="button" id="showFormAgainButton" value="Add More" class="form-btn">
                            </div>
                           
                        <div class="form-btn-container">
                        <input type="submit" name="<?php echo $existing_experience ? 'update' : 'submit'; ?>"
                                value="<?php echo $existing_experience ? 'Update' : 'Save'; ?>" class="form-btn">

                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <div id="confirmationModal" class="modal fade" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this Experience?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
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

        document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("showFormAgainButton").addEventListener("click", function() {
        var newExperienceForm = `
            <div class="label-container">
                <label style="text-align: center;">New Experience:</label>
                <label class="delete-experience-icon" title="Delete Experience">
                    <i class="fas fa-trash"></i>
                </label>
            </div>
            <div class="form-group">
                <label for="company_name" class="required-label">Enter Company name</label>
                <input type="text" id="company_name" name="company_name[]" placeholder="Enter Company name" required>
            </div>
            <div class="form-group">
                <label for="name" class="required-label">Enter Role</label>
                <input type="text" id="name" name="name[]" placeholder="Enter Role" required>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <label>Enter Start Date</label>
                        <input type="date" name="start_date[]" placeholder="Start Date">
                    </div>
                    <div class="col">
                        <label>Enter End Date</label>
                        <input type="date" name="end_date[]" placeholder="End Date">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="required-label">Enter Description</label>
                <textarea name="i_description[]" placeholder="Enter description" required></textarea>
            </div>
            <input type="hidden" name="i_id[]" value="">
        `;
        var formFieldsContainer = document.querySelector(".form-fields-container");
        var newFormWrapper = document.createElement('div');
        newFormWrapper.classList.add('new-form-wrapper');
        newFormWrapper.innerHTML = newExperienceForm;
        formFieldsContainer.appendChild(newFormWrapper);

        // Add event listener to the newly added delete icon
        var deleteIcon = newFormWrapper.querySelector('.delete-experience-icon');
        deleteIcon.addEventListener('click', function() {
            newFormWrapper.remove(); // Remove the entire form field container
        });
    });
});


document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.delete-experience-icon').forEach(function (deleteIcon) {
            deleteIcon.addEventListener('click', function () {
                var experienceId = this.getAttribute('data-experience-id');
                var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
                confirmDeleteBtn.addEventListener('click', function() {
                    // Send AJAX request to delete the education
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'delete_experience.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            // Reload the page or update the UI as needed
                            location.reload();
                        }
                    };
                    xhr.send('experience_id=' + experienceId);
                });

                // Show the confirmation modal
                var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                confirmationModal.show();
            });
        });
    });


    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
    var checkbox = document.getElementById('experienceCheckbox');
    var formContainer = document.querySelector('.form-container');

    if (checkbox) {
        checkbox.addEventListener('change', function() {
            formContainer.style.display = this.checked ? 'flex' : 'none';
        });
    } else if (formContainer) {
        // If there is no checkbox and the form container exists, show it by default.
        formContainer.style.display = 'flex';
    }
});


$(document).ready(function () {
        // Validation for the form submission
        $("form").submit(function (e) {
            // Remove all existing error messages
            $(".text-danger").remove();

            // Validation logic
            var isValid = true;

            // Validation for project name
            $("input[name='name[]']").each(function () {
                var name = $(this).val();
                var fieldId = $(this).attr("id");
                if (!name.match(/^[a-zA-Z\s]+$/)) {
                    $(this).after('<div class="text-danger">Role should only contain letters and whitespaces.</div>');
                    isValid = false;
                }
            });
            

            // Validation for description
            $("textarea[name='i_description[]']").each(function () {
                var description = $(this).val();
                if (!description.match(/^[a-zA-Z0-9\s]+$/)) {
                    $(this).after('<div class="text-danger">Description should only contain letters, numbers, and whitespaces.</div>');
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