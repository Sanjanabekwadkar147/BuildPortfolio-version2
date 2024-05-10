<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    
    header("Location: login.php"); 
    exit; 
}
include 'config.php';

$user_id = $_SESSION['user_id'];
$error_message = array();
$skills = array();

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
      
        $skills_exists = false; 
    } elseif (isset($_POST['submit'])) {
       
        $skills = isset($_POST['skills']) ? $_POST['skills'] : array();

        // Server-side validation
        foreach ($skills as $index => $skill) {
            if (!preg_match('/^[a-zA-Z0-9\s+#-]+$/', $skill) || ctype_digit($skill)) {
                $error_message[] = "skill "."$skill"." is invalid";
            }
        }

        if (!empty($error_message)) {
         
        } else {
           
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
            var errorAlert = document.querySelector(".alert-danger");
            if (successAlert) {
                setTimeout(function () {
                    successAlert.style.display = 'none';
                }, 3000);
            }
            if (errorAlert) {
                setTimeout(function () {
                    errorAlert.style.display = 'none';
                }, 3000);
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
                this.parentElement.parentElement.remove();

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