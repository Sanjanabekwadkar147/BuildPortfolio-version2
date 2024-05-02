<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page
    header("Location: login.php"); // Assuming your login page file is named login.php
    exit; // Make sure to exit after redirection to prevent further execution of the script
}
include 'config.php';
$user_id = $_SESSION['user_id'];
$count = isset($_POST['qualification']) ? count($_POST['qualification']) : 1;
$labels = array("Graduation Details", "12th/Diploma Details", "Schooling Details");
$error_messages = array();

$existing_education_query = "SELECT * FROM education WHERE id = '$user_id'";

$result = $conn->query($existing_education_query);
$existing_education = $result->num_rows > 0;

$resume_formsubmitted = $result->num_rows > 0;


if ($result->num_rows > 0) {
    $existing_education = true;
    $qualifications = array();
    $universities = array();
    $years = array();
    $percentages = array();
    $e_ids = array(); // Array to store education ids
    while ($row = $result->fetch_assoc()) {
        $e_id = $row['e_id'];
        $qualifications[] = $row['qualification'];
        $universities[] = $row['university'];
        $years[] = $row['year'];
        $percentages[] = $row['percentage'];
        $e_ids[] = $e_id;
    }
    $count = count($qualifications);
} else {
    // User is new, set existing_education to false
    $existing_education = false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        // Update button clicked, enable fields
        $existing_education = false; // Set to false to enable fields
    } elseif (isset($_POST['submit'])) {
        // Save button clicked, process form data
        $all_inputs_valid = true;
        $qualifications = $_POST['qualification'];
        $universities = $_POST['university'];
        $years = $_POST['year'];
        $percentages = $_POST['percentage'];
        $e_ids = $_POST['e_id']; // Retrieve education ids
        $count = count($qualifications);
        for ($i = 0; $i < $count; $i++) {
            $e_id = $e_ids[$i]; // Retrieve e_id for updating existing records
            $qualification = $qualifications[$i];
            $university = $universities[$i];
            $year = $years[$i];
            $percentage = $percentages[$i];

            if (!preg_match('/^[A-Za-z0-9\s]+$/', $qualification)) {
                $error_messages[$i]['qualification'] = "Qualification should only contain letters, numbers, and whitespaces.";
                $all_inputs_valid = false;
            }

            if (!preg_match('/^[A-Za-z\s]+$/', $university)) {
                $error_messages[$i]['university'] = "University should only contain letters and whitespaces.";
                $all_inputs_valid = false;
            }

            if (!preg_match('/^\d{4}$/', $year)) {
                $error_messages[$i]['year'] = "Please enter a valid four-digit year.";
                $all_inputs_valid = false;
            }

            if (!preg_match('/^\d+(\.\d+)?%?$/', $percentage)) { // Validation for percentage/CGPA
                $error_messages[$i]['percentage'] = "Please enter a percentage or CGPA.";
                $all_inputs_valid = false;
            }

            if (empty($error_messages[$i])) {
                if ($existing_education && !empty($e_id)) {
                    $sql = "UPDATE education SET qualification='$qualification', university='$university', year='$year', percentage='$percentage' WHERE e_id='$e_id'";
                } else {
                    $sql = "INSERT INTO education (id, qualification, university, year, percentage) 
                        VALUES ('$user_id', '$qualification', '$university', '$year', '$percentage')";
                }

                if ($conn->query($sql) === TRUE) {
                    if ($all_inputs_valid) {
                        // This success message will only be set if all inputs are valid and the query execution was successful
                        if ($existing_education) {
                            $success_message = "Education Details updated successfully!";
                        } else {
                            $success_message = "Education Details added successfully!";
                            header('location: addexperience.php');
                        }

                    }

                    if (!$existing_education) {
                        $e_id = $conn->insert_id;
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


        .form-container form input[type="submit"],
        .form-container form input[type="button"] {
            background: #fbd0d9;
            color: crimson;
            text-transform: capitalize;
            font-size: 20px;
            max-width: 150px;
            cursor: pointer;
            border: none;
        }

        .form-container form input[type="submit"]:hover,
        .form-container form input[type="button"]:hover {
            background: crimson;
            color: #fff;
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

        .delete-education-icon {
            margin-left: auto;
            margin-right: 10px;
            cursor: pointer;
            display: inline-block;
        }

        /* Adjust the icon size if needed */
        .delete-education-icon i {
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
                            <i class="fas fa-home me-2 fs-4"></i>  Adjust the font size here (e.g., fs-4) -->

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
                    <h3>My Education</h3>
                    <div class="form-fields-container">

                        <?php
                        for ($i = 0; $i < $count; $i++) {
                            $qualification_value = isset($qualifications[$i]) ? htmlspecialchars($qualifications[$i]) : '';
                            $university_value = isset($universities[$i]) ? htmlspecialchars($universities[$i]) : '';
                            $year_value = isset($years[$i]) ? htmlspecialchars($years[$i]) : '';
                            $percentage_value = isset($percentages[$i]) ? htmlspecialchars($percentages[$i]) : '';
                            ?>
                            <div class="label-container">
                                <label style="text-align: center;">
                                    <?php echo $labels[$i]; ?>:
                                </label>
                                <!-- Add delete icon in front of project label -->
                                <label for="delete_education_<?php echo $i; ?>" class="delete-education-icon"
                                    title="Delete Education" data-education-id="<?php echo $e_id; ?>">
                                    <i class="fas fa-trash"></i>
                                </label>

                            </div>

                            <div class="form-group">
                                <label class="required-label" for="qualification">Enter Qualification</label>
                                <input type="text" id="qualification" name="qualification[]"
                                    placeholder="Enter Qualification" required value="<?php echo $qualification_value; ?>"
                                    <?php echo $existing_education ? 'disabled' : ''; ?>>
                                <?php if (!empty($error_messages[$i]['qualification'])): ?>
                                    <div class="text-danger">
                                        <?php echo $error_messages[$i]['qualification']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="university" class="required-label">Enter University</label>
                                <input type="text" id="university" name="university[]" placeholder="Enter University"
                                    required value="<?php echo $university_value; ?>" <?php echo $existing_education ? 'disabled' : ''; ?>>
                                <?php if (!empty($error_messages[$i]['university'])): ?>
                                    <div class="text-danger">
                                        <?php echo $error_messages[$i]['university']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="year" class="required-label">Enter Passing Year</label>
                                <input type="text" id="year" name="year[]" placeholder="Enter Passing Year" required
                                    value="<?php echo $year_value; ?>" <?php echo $existing_education ? 'disabled' : ''; ?>>
                                <?php if (!empty($error_messages[$i]['year'])): ?>
                                    <div class="text-danger">
                                        <?php echo $error_messages[$i]['year']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label class="required-label" for="percentage">Enter Percentage/CGPA</label>
                                <input type="text" id="percentage" name="percentage[]" placeholder="Enter Percentage/CGPA"
                                    required value="<?php echo $percentage_value; ?>" <?php echo $existing_education ? 'disabled' : ''; ?>>
                                <?php if (!empty($error_messages[$i]['percentage'])): ?>
                                    <div class="text-danger">
                                        <?php echo $error_messages[$i]['percentage']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="e_id[]" value="<?php echo $e_ids[$i]; ?>">

                        <?php } ?>
                    </div>

                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>

                    <!-- HTML form -->
                    <div class="form-btns-container">

                        <div class="form-btn-container" style="text-align: right;">
                            <input type="button" id="showFormAgainButton" value="Add More" class="form-btn">
                        </div>
                        <div class="form-btn-container">
                            <input type="submit" name="<?php echo $existing_education ? 'update' : 'submit'; ?>"
                                value="<?php echo $existing_education ? 'Update' : 'Save'; ?>" class="form-btn">
                        </div>
                    </div>

                </form>
            </div>

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
                Are you sure you want to delete this Education?
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

        document.addEventListener("DOMContentLoaded", function() {
    var maxForms = 3; // Maximum number of additional forms allowed
    var existingFormsCount = <?php echo $count; ?>; // Get the count of existing forms
    var remainingForms = maxForms - existingFormsCount; // Calculate remaining forms allowed
    
    var addMoreButton = document.getElementById("showFormAgainButton");
    
    if (remainingForms <= 0) {
        addMoreButton.style.display = 'none'; // Hide the "Add More" button if the maximum limit is reached
    }
    
    addMoreButton.addEventListener("click", function() {
        if (remainingForms > 0) {
            var newEducationForm = `
                <div class="label-container">
                    <label style="text-align: center;">New Education:</label>
                    <label class="delete-education-icon" title="Delete Education">
                        <i class="fas fa-trash"></i>
                    </label>
                </div>
                <div class="form-group">
                    <label for="qualification" class="required-label">Enter Qualification</label>
                    <input type="text" id="qualification" name="qualification[]" placeholder="Enter Qualification" required>
                </div>
                <div class="form-group">
                    <label for="university" class="required-label">Enter University</label>
                    <input type="text" id="university" name="university[]" placeholder="Enter University" required>
                </div>
                <div class="form-group">
                    <label for="year" class="required-label">Enter Passing Year</label>
                    <input type="text" id="year" name="year[]" placeholder="Enter Passing Year" required>
                </div>
                <div class="form-group">
                    <label class="required-label">Enter Percentage/CGPA</label>
                    <input type="text" id="percentage" name="percentage[]" placeholder="Enter Percentage/CGPA" required>
                </div>
                <input type="hidden" name="e_id[]" value="">
            `;
            var formFieldsContainer = document.querySelector(".form-fields-container");
            var newFormWrapper = document.createElement('div');
            newFormWrapper.classList.add('new-form-wrapper');
            newFormWrapper.innerHTML = newEducationForm;
            formFieldsContainer.appendChild(newFormWrapper);

            // Decrease the remaining forms count
            remainingForms--;

            // Hide the "Add More" button if the maximum limit is reached
            if (remainingForms <= 0) {
                addMoreButton.style.display = 'none';
            }

            // Add event listener to the newly added delete icon
            var deleteIcon = newFormWrapper.querySelector('.delete-education-icon');
            deleteIcon.addEventListener('click', function() {
                newFormWrapper.remove(); // Remove the entire form field container

                // Increase the remaining forms count when a form is removed
                remainingForms++;

                // Show the "Add More" button when a form is removed and the maximum limit is not reached
                if (remainingForms > 0) {
                    addMoreButton.style.display = 'block';
                }
            });
        }
    });
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
        document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.delete-education-icon').forEach(function (deleteIcon) {
            deleteIcon.addEventListener('click', function () {
                var educationId = this.getAttribute('data-education-id');
                var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
                confirmDeleteBtn.addEventListener('click', function() {
                    // Send AJAX request to delete the education
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'delete_education.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            // Reload the page or update the UI as needed
                            location.reload();
                        }
                    };
                    xhr.send('education_id=' + educationId);
                });

                // Show the confirmation modal
                var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                confirmationModal.show();
            });
        });
    });
        $("form").submit(function (e) {
            // Remove all existing error messages
            $(".text-danger").remove();

            // Validation logic goes here
            var isValid = true;

            // Validation logic for qualification
            $("input[name='qualification[]']").each(function () {
                var qualification = $(this).val();
                if (!qualification.match(/^[A-Za-z0-9\s]+$/)) {
                    $(this).after('<div class="text-danger">Qualification should only contain letters, numbers, and whitespaces.</div>');
                    isValid = false;
                }
            });

            // Validation logic for university
            $("input[name='university[]']").each(function () {
                var university = $(this).val();
                if (!university.match(/^[A-Za-z\s]+$/)) {
                    $(this).after('<div class="text-danger">University should only contain letters and whitespaces.</div>');
                    isValid = false;
                }
            });

            // Validation logic for year
            $("input[name='year[]']").each(function () {
                var year = $(this).val();
                if (!year.match(/^\d{4}$/)) {
                    $(this).after('<div class="text-danger">Please enter a valid four-digit year.</div>');
                    isValid = false;
                }
            });

            // Validation logic for percentage
            $("input[name='percentage[]']").each(function () {
                var percentage = $(this).val();
                if (!percentage.match(/^\d+(\.\d+)?%?$/)) {
                    $(this).after('<div class="text-danger">Please enter a percentage or CGPA.</div>');
                    isValid = false;
                }
            });

            // Prevent form submission if validation fails
            if (!isValid) {
                e.preventDefault();
            }
        });


    </script>
</body>

</html>