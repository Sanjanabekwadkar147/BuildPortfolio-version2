<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page
    header("Location: login.php"); // Assuming your login page file is named login.php
    exit; // Make sure to exit after redirection to prevent further execution of the script
}
include 'config.php';
$user_id = $_SESSION['user_id'];

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
    <title>Portfolio</title>
</head>
<style>
        .card {
            height: 100%;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .card:hover .card-img-top {
            transform: scale(1.1);
        }

        .card-body {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-body .btn-primary {
            background: #fbd0d9;
            color: crimson;
            text-transform: capitalize;
            font-size: 17px;
            max-width: 450px;
            width: 100%;
            cursor: pointer;
            border: none;
            transition: background 0.3s, color 0.3s;
        }

        .card-body .btn-primary:hover {
            background: crimson;
            color: #fff;
        }
    </style>

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
            <div class="container mt-5">
    <div class="row">
        
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="img/template2.png" class="card-img-top" alt="Template 2">
                <div class="card-body">
                    <a href="Template1/index.php" class="btn btn-primary" target="_blank">Explore Portfolio</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="img/template3.png" class="card-img-top" alt="Template 3">
                <div class="card-body">
                    <a href="Template3/index.php" class="btn btn-primary" target="_blank">Explore Portfolio</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="img/template4.png" class="card-img-top" alt="Template 2">
                <div class="card-body">
                    <a href="Template4/index.php" class="btn btn-primary" target="_blank">Explore Portfolio</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="img/template5.png" class="card-img-top" alt="Template 5">
                <div class="card-body">
                  <a href="Template5/index.php" class="btn btn-primary" target="_blank">Explore Portfolio</a>                </div>
            </div>
        </div>
         <div class="col-md-4 mb-4">
            <div class="card">
                <img src="img/template6.png" class="card-img-top" alt="Template 6">
                <div class="card-body">
                  <a href="Template6/index.php" class="btn btn-primary" target="_blank">Explore Portfolio</a>                </div>
            </div>
        </div>
    </div>
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
       
    </script>
</body>

</html>