<?php
session_start();
include 'config.php';

// Fetch data from profile table
$user_id = $_SESSION['user_id'];

$profile_sql = "SELECT name AS profile_name, profession, email, facebook, linkedin, github, twitter, phone, address FROM profile WHERE id = $user_id";
$profile_result = mysqli_query($conn, $profile_sql);

if ($profile_result && mysqli_num_rows($profile_result) > 0) {
    $profile_row = mysqli_fetch_assoc($profile_result);
    $name = $profile_row['profile_name'];
    $profession = $profile_row['profession'];
    $email = $profile_row['email'];
    $facebook = $profile_row['facebook'];
    $linkedin = $profile_row['linkedin'];
    $github = $profile_row['github'];
    $twitter = $profile_row['twitter'];
    $phone = $profile_row['phone'];
    $address = $profile_row['address'];
} else {

    $name = "Your Name";
    $profession = "I am a beginner Developer from India";
    $email = "abc@gmail.com";
    $phone = "1234567890";
    $address = "ABC colony, Pune";
    $facebook = "#";
    $linkedin = "#";
    $github = "#";
    $twitter = "#";
}

$about_sql = "SELECT title AS about_title, profile_pic, description AS about_description FROM aboutme WHERE id = $user_id";
$about_result = mysqli_query($conn, $about_sql);

if ($about_result && mysqli_num_rows($about_result) > 0) {
    $about_row = mysqli_fetch_assoc($about_result);
    $about_title = $about_row['about_title'];
    $profile_pic = "/BuildPortfolio/UserDashboard/" . $about_row['profile_pic'];

    $about_description = $about_row['about_description'];
} else {

    $profile_pic = "./assets/images/person.jpg";
    $about_title = "Software Developer";
    $about_description = "I am a beginner developer with a passion for coding and learning new technologies.";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/aos.css">
    <link rel="stylesheet" href="./assets/css/line-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body data-bs-spy="scroll" data-bs-target=".navbar">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container flex-lg-column">
            <a class="navbar-brand mx-lg-auto mb-lg-4" href="#">
                <span class="h3 fw-bold d-block d-lg-none"><?php echo $name; ?></span>
              
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto flex-lg-column text-lg-center">

                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Skills</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#blog">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item">
                    <a href="#" id="shareBtn" class="nav-link" onclick="sharePortfolio()">Share</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <!-- //NAVBAR -->

    <!-- CONTENT WRAPPER -->
    <div id="content-wrapper">

        <!-- HOME -->
        <section id="home" class="full-height px-lg-5">

<div class="container">
    <div class="row">
        <div class="col-lg-7">
            <h1 class="display-4 fw-bold" data-aos="fade-up">Hello I'm <span class="text-brand"><?php echo $name; ?><br>
                    </span> <br><?php echo $profession; ?></h1>
            <p class="lead mt-2 mb-4" data-aos="fade-up" data-aos-delay="300"><?php echo $about_description; ?></p><br>
            <div data-aos="fade-up" data-aos-delay="600">
                <!-- <a href="#work" class="btn btn-brand me-3">Explore My Work</a>
                <a href="#" class="link-custom">Call: (233) 3454 2342</a> -->
            </div>
        </div>
        <div class="col-lg-5">
            <img src="<?php echo $profile_pic; ?>" alt="Profile pic" class="img-fluid rounded" style="max-width:550px; height: 600px;">
        </div>
    </div>

</div>

</section>

        <!-- //HOME -->

        <!-- SERVICES -->
        <section id="services" class="full-height px-lg-5">
    <div class="container">

        <div class="row pb-4" data-aos="fade-up">
            <div class="col-lg-8">
                <h6 class="text-brand">SKILLS</h6>
                <h1>Skills That I Have</h1>
            </div>
        </div>

        <div class="row gy-4">
            <?php
            // Fetch skills from the skills table
            $skills_query = "SELECT skills FROM skills WHERE id = $user_id";
            $skills_result = mysqli_query($conn, $skills_query);

            if ($skills_result && mysqli_num_rows($skills_result) > 0) {
                while ($row = mysqli_fetch_assoc($skills_result)) {
                    // Extract skills from the row
                    $skills = explode(",", $row['skills']);

                    // Display skills
                    foreach ($skills as $skill) {
                        echo "<div class='col-md-3' data-aos='fade-up'>";
                        echo "<div class='service p-4 bg-base rounded-4 shadow-effect'>";
                        echo "<h5 class='mt-4 mb-2'>$skill</h5>";
                        echo "</div>";
                        echo "</div>";
                    }
                }
            } else {
                // Default skills
                $default_skills = array(
                    "Java",
                    "HTML",
                    "CSS",
                    "JavaScript",
                    "Problem Solving",
                    "Soft Skills",
                    "Networking"
                );

                // Display default skills
                foreach ($default_skills as $skill) {
                    echo "<div class='col-md-4' data-aos='fade-up'>";
                    echo "<div class='service p-4 bg-base rounded-4 shadow-effect'>";
                    echo "<h5 class='mt-4 mb-2'>$skill</h5>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            ?>
        </div>

    </div>
</section>


        <!-- SERVICES -->


        <!-- ABOUT -->
        <section id="about" class="full-height px-lg-5">
            <div class="container">

                <div class="row pb-4" data-aos="fade-up">
                    <div class="col-lg-8">
                        <h6 class="text-brand">ABOUT</h6>
                        <h1>My Education & Experience</h1>
                    </div>
                </div>

                <div class="row gy-5">
    <div class="col-lg-6">

        <h3 class="mb-4" data-aos="fade-up" data-aos-delay="300">Education</h3>
        <div class="row gy-4">
            <?php
            // Fetch education data from the database and populate the HTML
            $education_sql = "SELECT * FROM education WHERE id = $user_id";
            $education_result = mysqli_query($conn, $education_sql);

            if ($education_result && mysqli_num_rows($education_result) > 0) {
                while ($education_row = mysqli_fetch_assoc($education_result)) {
                    echo "<div class='col-12' data-aos='fade-up' data-aos-delay='600'>";
                    echo "<div class='bg-base p-4 rounded-4 shadow-effect'>";
                    echo "<h4>" . $education_row['qualification'] . "</h4>";
                    echo "<p class='text-brand mb-2'>" . $education_row['university'] . "</p>";
                    echo "<p class='mb-0'>Passing Year : " . $education_row['year'] . "</p>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                // If no education data found, display default education
                echo "<div class='col-12' data-aos='fade-up' data-aos-delay='600'>";
                echo "<div class='bg-base p-4 rounded-4 shadow-effect'>";
                echo "<h4>Bachelor's Degree</h4>";
                echo "<p class='text-brand mb-2'>University Name (2024)</p>";
                echo "<p class='mb-0'>Passing Year : 2021</p>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>

    </div>

    <div class="col-lg-6">

        <h3 class="mb-4" data-aos="fade-up" data-aos-delay="300">Experience</h3>
        <div class="row gy-4">
            <?php
            // Fetch experience data from the database and populate the HTML
            $experience_sql = "SELECT * FROM internships_experience WHERE id = $user_id";
            $experience_result = mysqli_query($conn, $experience_sql);

            if ($experience_result && mysqli_num_rows($experience_result) > 0) {
                while ($experience_row = mysqli_fetch_assoc($experience_result)) {
                    echo "<div class='col-12' data-aos='fade-up' data-aos-delay='600'>";
                    echo "<div class='bg-base p-4 rounded-4 shadow-effect'>";
                    echo "<h4>" . $experience_row['name'] . "</h4>";
                    echo "<p class='text-brand mb-2'>" . $experience_row['company_name'] ."</p>";
                    echo "<p class='mb-0'>" . $experience_row['i_description'] . "</p>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            ?>
        </div>

    </div>

</div>


            </div>
        </section>
        <!-- //ABOUT -->


        <!-- BLOG -->
        <section id="blog" class="full-height px-lg-5">
    <div class="container">

        <div class="row pb-4" data-aos="fade-up">
            <div class="col-lg-8">
                <h6 class="text-brand">PROJECT</h6>
                <h1>My Projects</h1>
            </div>
        </div>

        <div class="row gy-4 justify-content-center">

            <?php
            $project_sql = "SELECT * FROM projects WHERE id = $user_id";
            $project_result = mysqli_query($conn, $project_sql);

            if ($project_result && mysqli_num_rows($project_result) > 0) {
                while ($project_row = mysqli_fetch_assoc($project_result)) {
                    // Set the image path manually here
                    $image_path = "./assets/images/blog-post-3.jpg"; // Replace default.jpg with your actual image path
                    echo "<div class='col-md-4' data-aos='fade-up' data-aos-delay='300'>";
                    echo "<div class='card-custom rounded-4 bg-base shadow-effect'>";
                    echo "<div class='card-custom-image rounded-4'>";
                    echo "<img class='rounded-4' src='" . $image_path . "' alt='Project Image'>";
                    echo "</div>";
                    echo "<div class='card-custom-content p-4'>";
                    
                    echo "<h5 class='mb-4'>" . $project_row['p_name'] . "</h5>";
                    echo "<p class='text-brand mb-2'>" . $project_row['p_description'] . "</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                // Manually add projects with image paths
                $manual_projects = array(
                    array(
                        "p_name" => "Manual Project 1",
                        "date" => "20 Dec, 2022",
                        "image_path" => "./assets/images/blog-post-3.jpg"
                    ),
                    array(
                        "p_name" => "Manual Project 2",
                        "date" => "20 Dec, 2022",
                        "image_path" => "./assets/images/blog-post-1.jpg"
                    )
                );

                foreach ($manual_projects as $project) {
                    echo "<div class='col-md-4' data-aos='fade-up' data-aos-delay='300'>";
                    echo "<div class='card-custom rounded-4 bg-base shadow-effect'>";
                    echo "<div class='card-custom-image rounded-4'>";
                    echo "<img class='rounded-4' src='" . $project['image_path'] . "' alt='Project Image'>";
                    echo "</div>";
                    echo "<div class='card-custom-content p-4'>";
                    
                    echo "<h5 class='mb-4'>" . $project['p_name'] . "</h5>";
                    echo "<p class='text-brand mb-2'>" . $project['p_description'] . "</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            ?>

        </div>

    </div>
</section>


        <!-- //BLOG -->

        <!-- CONTACT -->
        <section id="contact" class="full-height px-lg-5">
            <div class="container">

                <div class="row justify-content-center text-center">
                    <div class="col-lg-8 pb-4" data-aos="fade-up">
                        <h6 class="text-brand">CONTACT</h6>
                        <h1>Interested in working together? Let's talk
                        </h1>
                    </div>

                    <div class="col-lg-8" data-aos="fade-up" data-aos-delay="300">
                        <form action="https://api.web3forms.com/submit" class="row g-lg-3 gy-3" method="post">
                            <div class="form-group col-md-6">
                            <input type="hidden" name="access_key" value="126fd1cd-ff7b-4a9d-a3b2-6d2b6a83f6b9">
                                <input type="text" class="form-control" name="name" placeholder="Enter your name">
                            </div>
                            <div class="form-group col-md-6">
                                <input type="email" class="form-control" name="email" placeholder="Enter your email">
                            </div>
                            <div class="form-group col-12">
                                <input type="text" class="form-control" name="subject" placeholder="Enter subject">
                            </div>
                            <div class="form-group col-12">
                                <textarea name="" rows="4" class="form-control" name="message" placeholder="Enter your message"></textarea>
                            </div>
                            <div class="form-group col-12 d-grid">
                                <button type="submit" class="btn btn-brand">Contact me</button>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
        </section>
        <!-- //CONTACT -->

        <!-- FOOTER -->
        <footer class="py-5 px-lg-5">
            <div class="container">
                <div class="row gy-4 justify-content-between">
                    <div class="col-auto">
                        <p class="mb-0">Designed by <a href="#" class="fw-bold"><?php echo $name; ?></a></p>
                    </div>
                    <div class="col-auto">
                        <div class="social-icons">
                            <a href="<?php echo $twitter; ?>" target="_blank"><i class="lab la-twitter"></i></a>
                            <a href="<?php echo $linkedin; ?>" target="_blank"><i class="lab la-linkedin"></i></a>
                            <a href="<?php echo $github; ?>" target="_blank"><i class="lab la-github"></i></a>
                            <a href="<?php echo $facebook; ?>" target="_blank"><i class="lab la-facebook"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- //FOOTER -->

    </div>
    <!-- //CONTENT WRAPPER -->



    <script src="./assets/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/aos.js"></script>
    <script src="./assets/js/main.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <script>
    function sharePortfolio() {
        // Check if the Web Share API is supported
        if (navigator.share) {
            navigator.share({
                title: 'My Portfolio',
                text: 'Check out my portfolio!',
                url: window.location.href
            }).then(() => console.log('Successful share'))
            .catch((error) => console.log('Error sharing:', error));
        } else {
            // Fallback for browsers that do not support Web Share API
            alert('Web Share API is not supported in this browser. You can manually copy the link from the address bar.');
        }
    }
</script>
</body>

</html>