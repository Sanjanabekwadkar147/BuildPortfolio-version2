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
                            header('location: addproject.php');
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

    <div id="confirmationModal" class="modal fade" tabindex="-1" aria-labelledby="confirmationModalLabel"
        aria-hidden="true">
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

        document.addEventListener("DOMContentLoaded", function () {
            var maxForms = 3; // Maximum number of additional forms allowed
            var existingFormsCount = <?php echo $count; ?>; // Get the count of existing forms
            var remainingForms = maxForms - existingFormsCount; // Calculate remaining forms allowed

            var addMoreButton = document.getElementById("showFormAgainButton");

            if (remainingForms <= 0) {
                addMoreButton.style.display = 'none'; // Hide the "Add More" button if the maximum limit is reached
            }

            addMoreButton.addEventListener("click", function () {
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
                    deleteIcon.addEventListener('click', function () {
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
                    confirmDeleteBtn.addEventListener('click', function () {
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