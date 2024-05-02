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
$skills = array(); // Initialize an empty array for skills

// Fetch existing skills
$skills_query = "SELECT * FROM skills WHERE id = '$user_id'";
$skills_result = $conn->query($skills_query);
$skills_exists = $skills_result->num_rows > 0;

$skills_formsubmitted = $skills_result->num_rows > 0;


if ($skills_exists) {
    while ($row = $skills_result->fetch_assoc()) {
        $skills[] = $row['skills'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        // Update button clicked, enable fields
        $skills_exists = false; // Set to false to enable fields
    } elseif (isset($_POST['submit'])) {
        // Clean and store skills
        $skills = isset($_POST['skills']) ? $_POST['skills'] : array();

        // Check for duplicate skills
        $duplicate_skills = array();
        foreach ($skills as $index => $skill) {
            if (in_array($skill, $skills, true) && array_search($skill, $skills) !== $index) {
                $duplicate_skills[] = $skill;
            }
        }

        // If duplicate skills found, set error message
        if (!empty($duplicate_skills)) {
            $error_message[] = "The following skills already exist: " . implode(", ", $duplicate_skills);
        } else {
            // No duplicate skills found, proceed with insertion
            // Delete existing skills
            $delete_sql = "DELETE FROM skills WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $user_id);
            $delete_stmt->execute();
            $delete_stmt->close();

            // Insert new skills
            foreach ($skills as $skill) {
                $insert_sql = "INSERT INTO skills (skills, id) VALUES (?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("si", $skill, $user_id);

                if ($insert_stmt->execute()) {
                    if ($skills_exists) {
                        $success_message = "Skills updated successfully!";
                    } else {
                        $success_message = "Skills added successfully!";
                        header('location: addeducation.php');
                    }

                } else {
                    echo "Error: " . $conn->error;
                }

                $insert_stmt->close();
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
        .container {
            max-width: 100%;
            background: #fff;
            border-radius: 20px;
            width: 500px;
            padding: 20px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, .1);
        }

        .wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-bottom: 40px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e4e1e1;
        }

        .container .wrap h3 {
            font-size: 35px;
            margin-bottom: 10px;
            text-transform: uppercase;
            color: #333;
            text-align: center;
        }

        .add {
            text-decoration: none;
            display: inline-block;
            width: 30px;
            height: 30px;
            background: crimson;
            font-size: 2rem;
            font-weight: bold;
            color: #fff;
            display: flex;
           align-content: center;
           justify-content: center;
           align-items: center;
        }

        .inp-group {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            /* Create three columns */
            gap: 10px;
            /* Space between items */
            margin-bottom: 20px;
        }

        .flex {
            display: flex;
            align-items: center;
            /* Align items vertically */
            gap: 10px;
            /* Space between text field and delete button */
        }

        input[type="text"] {
            flex-grow: 1;
            /* Make input field take up available space */
        }

        .input-wrapper {
            position: relative;
            display: flex;
        }

        .input-wrapper input[type="text"] {
            padding-right: 40px;
            /* Make room for the delete button */
        }

        .delete {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            /* Make background transparent */
            color: crimson;
            font-size: 1.2rem;
            cursor: pointer;
            border: none;
        }


        input {
            width: 100%;
            padding: 10px 15px;
            font-size: 17px;
            margin: 8px 0;
            border-radius: 5px;
        }

        input:focus {
            outline: 1px solid #efefef;
        }

        .form-btn {
            background: #fbd0d9;
            color: crimson;
            text-transform: capitalize;
            font-size: 20px;
            max-width: 150px;
            cursor: pointer;
            border: none;
            /* Remove default button border */
            padding: 10px 20px;
            /* Add padding for better appearance */
            border-radius: 5px;
            /* Add border radius */
        }

        .form-btn:hover {
            background: crimson;
            color: #fff;
        }

        .btn-container {
            text-align: center;
            /* Center align the button */
            margin-top: 20px;
            /* Add margin for spacing */
        }

        @media (max-width: 992px) {
            .inp-group {
                grid-template-columns: repeat(2, 1fr);
                /* Adjust to 2 columns for tablets */
            }
        }

        @media (max-width: 768px) {
            .inp-group {
                grid-template-columns: repeat(1, 1fr);
                /* Adjust to 1 column for mobile */
            }

            .container {
                padding: 10px;
                width: 100%;
                /* Use the full width on smaller screens */
                max-width: 100%;
                /* Ensure the container does not exceed the screen width */
            }
        }

        @media (max-width: 576px) {
            .container .wrap h3 {
                font-size: 20px;
                /* Reduce the font size on smaller screens */
            }
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

            <div class="container" style="width: 900px;">
                <div class="wrap">
                    <h3>My Skills</h3>
                    <a href="#" class="add">&plus;</a>
                </div>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="inp-group">
                        <?php foreach ($skills as $index => $skill): ?>
                            <div class="flex">
                                <div class="input-wrapper">
                                    <input type="text" name="skills[]" value="<?php echo htmlspecialchars($skill); ?>" <?php echo $skills_exists ? 'disabled' : ''; ?>>
                                    <a href="#" class="delete" data-index="<?php echo $index; ?>">&times;</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>


                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($error_message)): ?>
    <div class="alert alert-danger">
        <?php foreach ($error_message as $message): ?>
            <p><?php echo $message; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
                    <!-- Form button -->
                    <div class="btn-container">
                        <input type="submit" name="<?php echo $skills_exists ? 'update' : 'submit'; ?>"
                            value="<?php echo $skills_exists ? 'Update' : 'Save'; ?>" class="form-btn" id="submit-btn">
                    </div>
                </form>
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
            // var errorMessages = document.querySelectorAll(".text-danger"); // Select all error messages

            if (successAlert) {
                setTimeout(function () {
                    successAlert.style.display = 'none';
                }, 3000); // 5000 milliseconds = 5 seconds
            }


        }

        document.addEventListener("DOMContentLoaded", function () {
            hideMessages();
        });


        const addbtn = document.querySelector('.add');
        const input = document.querySelector('.inp-group');

        function removeInput() {
            this.parentElement.remove();
        }
        function addInput() {
            const div = document.createElement("div");
            div.className = "flex";

            const inputWrapper = document.createElement("div");
            inputWrapper.className = "input-wrapper";

            const name = document.createElement("input");
            name.type = "text";
            name.name = "skills[]";
            name.placeholder = "Enter your skill";

            const btn = document.createElement("a");
            btn.className = "delete";
            btn.innerHTML = "&times;";
            btn.addEventListener("click", removeInput);

            inputWrapper.appendChild(name);
            inputWrapper.appendChild(btn);
            div.appendChild(inputWrapper);

            const inputGroup = document.querySelector('.inp-group');
            inputGroup.appendChild(div);
        }


        addbtn.addEventListener("click", addInput);
        const deleteButtons = document.querySelectorAll('.delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const index = parseInt(this.getAttribute('data-index'));
                const deletedSkill = this.parentElement.querySelector('input[type="text"]').value;

                // Remove the skill input field
                this.parentElement.remove();

                // Send AJAX request to delete the skill from the database
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_skill.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        const successMessage = document.createElement('div');
                        successMessage.className = 'alert alert-success';
                        successMessage.textContent = `Skill "${deletedSkill}" deleted successfully.`;

                        const btnContainer = document.querySelector('.btn-container');
                        btnContainer.parentNode.insertBefore(successMessage, btnContainer);


                        setTimeout(() => {
                            successMessage.remove();
                        }, 3000);
                    } else {

                        console.error('Error deleting skill:', xhr.responseText);
                    }
                };
                xhr.send(`skill=${encodeURIComponent(deletedSkill)}`);
            });
        });



    </script>
</body>

</html>