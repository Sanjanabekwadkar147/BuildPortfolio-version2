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

    $profile_pic = "img/profile.jpg";
    $about_title = "Software Developer";
    $about_description = "I am a beginner developer with a passion for coding and learning new technologies.";
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Home.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="print.css" media="print">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.21.0/font/bootstrap-icons.css">
    <title>Portfolio Website</title>

</head>

<body>

    <section id="header">
        <div class="header container">
            <div class="nav-bar">
                <div class="brand">
                    <a href="#hero">
                        <h1><span>
                                <?php echo $name; ?>
                            </span></h1>
                    </a>
                </div>
                <div class="nav-list">
                    <div class="hamburger">
                        <div class="bar"></div>
                    </div>
                    <ul>
                        <li><a href="#hero" data-after="Home">Home</a></li>
                        <li><a href="#services" data-after="Service">Skills</a></li>
                        <li><a href="#resume" data-after="Resume">Resume</a></li>
                        <li><a href="#about" data-after="About">About</a></li>
                        <li><a href="#contact" data-after="Contact">Contact</a></li>
                        <li><a href="#" id="shareBtn" onclick="sharePortfolio()">Share</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- End Header -->

    <!-- Hero Section -->
    <section id="hero">
        <div class="hero container">
            <div>
                <h1>Hello, <span></span></h1>
                <h1>My Name is <span></span></h1>
                <h1>
                    <?php echo $name; ?><span></span>
                </h1>
                <p>
                    <?php echo $profession; ?>
                </p>
            </div>
        </div>
    </section>
    <!-- End Hero Section -->


    <!-- Service Section -->
    <section id="services">
        <div class="services container">
            <div class="service-top">
                <h1 class="section-title">Skills</h1>
            </div>
            <div class="service-bottom">
                <?php
                // Fetch skills from aboutme table
                $skills_query = "SELECT skills FROM skills WHERE id = $user_id";
                $skills_result = mysqli_query($conn, $skills_query);

                if ($skills_result && mysqli_num_rows($skills_result) > 0) {
                    while ($row = mysqli_fetch_assoc($skills_result)) {
                        // Extract skills from the row
                        $skills = explode(",", $row['skills']);

                        // Display skills
                        foreach ($skills as $skill) {
                            echo "<div class='service-item'>";
                            echo "<div class='icon'><img src='https://img.icons8.com/bubbles/100/000000/services.png' /></div>";
                            echo "<h2 class='" . (strlen($skill) > 10 ? 'long-skill' : '') . "'>$skill</h2>"; // Add a CSS class for long skills
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
                        echo "<div class='service-item'>";
                        echo "<div class='icon'><img src='https://img.icons8.com/bubbles/100/000000/services.png' /></div>";
                        echo "<h2 class='" . (strlen($skill) > 10 ? 'long-skill' : '') . "'>$skill</h2>"; // Add a CSS class for long skills
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>
    </section>
    <!-- End Service Section -->

    <!-- Resume Section -->
    <section id="resume">
        <div class="resume container">
            <div class="resume-top">
                <h1 class="section-title"><span>Resume</span></h1>
            </div><br>
            <div class="resume-bottom">
                <div class="resume-column">
                    <!-- Left Column -->
                    <div class="vertical-line"></div>
                    <h2>Summary</h2>
                    <p>
                        <?php echo $about_description; ?>
                    </p>
                    <br>
                    <h2>Education</h2>
                    <?php
                    $education_sql = "SELECT * FROM education WHERE id = $user_id";
                    $education_result = mysqli_query($conn, $education_sql);

                    if ($education_result && mysqli_num_rows($education_result) > 0) {
                        while ($education_row = mysqli_fetch_assoc($education_result)) {
                            echo "<div class='education-item'>";
                            echo "<h3>" . $education_row['qualification'] . "</h3>";
                            echo "<p>" . $education_row['university'] . " | Passing Year: " . $education_row['year'] . "</p>";
                            echo "</div>";
                        }
                    } else {
                        $default_education = array(
                            array("qualification" => "Bachelor's Degree", "university" => "University Name", "year" => "2024"),
                            array("qualification" => "High School Diploma", "university" => "High School Name", "year" => "2021"),
                            array("qualification" => "School", "university" => "School Name", "year" => "2018")
                        );

                        foreach ($default_education as $education) {
                            echo "<div class='education-item'>";
                            echo "<h3>" . $education['qualification'] . "</h3>";
                            echo "<p>" . $education['university'] . " | Passing Year: " . $education['year'] . "</p>";
                            echo "</div>";
                        }
                    }
                    ?>

                </div>

                <div class="resume-column">

                    <h2>Projects</h2>
                    <?php
                    $project_sql = "SELECT * FROM projects WHERE id = $user_id";
                    $project_result = mysqli_query($conn, $project_sql);

                    if ($project_result && mysqli_num_rows($project_result) > 0) {
                        while ($project_row = mysqli_fetch_assoc($project_result)) {
                            echo "<div class='project-item'>";
                            echo "<h3>" . $project_row['p_name'] . "</h3>";
                            echo "<p>" . $project_row['p_description'] . "</p>";
                            echo "</div>";
                        }
                    } else {
                        $default_projects = array(
                            array("p_name" => "Default Project 1", "p_description" => "This is project description."),
                            array("p_name" => "Default Project 2", "p_description" => "This is project description.")
                        );

                        foreach ($default_projects as $project) {
                            echo "<div class='project-item'>";
                            echo "<h3>" . $project['p_name'] . "</h3>";
                            echo "<p>" . $project['p_description'] . "</p>";
                            echo "</div>";
                        }
                    }
                    ?>
                    <h2>Internship</h2>
                    <?php
                    $internship_sql = "SELECT * FROM internships_experience WHERE id = $user_id";
                    $internship_result = mysqli_query($conn, $internship_sql);

                    if ($internship_result && mysqli_num_rows($internship_result) > 0) {
                        while ($internship_row = mysqli_fetch_assoc($internship_result)) {
                            echo "<div class='internship-item'>";
                            echo "<h3>" . $internship_row['name'] . "</h3>";
                            echo "<p>" . $internship_row['company_name'] . "</p>";
                            echo "<p>" . $internship_row['i_description'] . "</p>";
                            echo "</div>";
                        }
                    } else {
                        // Display default internship details if no records found
                        echo "<div class='internship-item'>";
                        echo "<h3> Internship</h3>";
                        echo "<p>Company Name</p>";
                        echo "<p>internship description.</p>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
    <!-- End Resume Section -->


    <!-- About Section -->
    <section id="about">
        <div class="about container">
            <div class="col-left">
                <div class="about-img">
                    <img src="<?php echo $profile_pic; ?>" alt="Profile Picture">
                </div>

            </div>
            <div class="col-right">
                <h1 class="section-title">About <span>me</span></h1>
                <h2>
                    <?php echo $about_title; ?>
                </h2>
                <p>
                    <?php echo $about_description; ?>
                </p>
                <!-- <a href="#" class="cta">Download Resume</a> -->
            </div>
        </div>
    </section>
    <!-- End About Section -->

    <!-- Contact Section -->
    <section id="contact">
        <div class="contact container">
            <div>
                <h1 class="section-title">Contact <span>info</span></h1>
            </div>
            <div class="contact-items">
                <div class="contact-item">
                    <div class="icon"><img src="https://img.icons8.com/bubbles/100/000000/phone.png" /></div>
                    <div class="contact-info">
                        <h1>Phone</h1>
                        <h2>+91
                            <?php echo $phone; ?>
                        </h2>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="icon"><img src="https://img.icons8.com/bubbles/100/000000/new-post.png" /></div>
                    <div class="contact-info">
                        <h1>Email</h1>
                        <h2>
                            <?php echo $email; ?>
                        </h2>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="icon"><img src="https://img.icons8.com/bubbles/100/000000/map-marker.png" /></div>
                    <div class="contact-info">
                        <h1>Address</h1>
                        <h2>
                            <?php echo $address; ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="form-container">

                <form action="https://api.web3forms.com/submit" method="post">
                    <div class="form-row">
                        <input type="hidden" name="access_key" value="126fd1cd-ff7b-4a9d-a3b2-6d2b6a83f6b9">
                        <input type="text" name="name" placeholder="Your Name">
                        <input type="email" name="email" placeholder="Your Email">
                    </div>
                    <input type="text" name="subject" placeholder="Subject">
                    <textarea name='message' placeholder="Message"></textarea>
                    <input type="submit" name="submit" value="SEND MESSAGE" class="form-btn">
                </form>

            </div>
        </div>
    </section>
    <!-- End Contact Section -->

    <!-- Footer -->
    <section id="footer">
        <div class="footer container">
            <div class="brand">
                <h1><span>
                        <?php echo $name; ?>
                </h1>
            </div>
            <h2>
                <?php echo $profession; ?>
            </h2>
            <div class="social-icon">
                <div class="social-item">
                    <a href="<?php echo $facebook; ?>"><img
                            src="https://img.icons8.com/bubbles/100/000000/facebook-new.png" /></a>
                </div>
                <div class="social-item">
                    <a href="<?php echo $github; ?>"><img
                            src="https://img.icons8.com/?size=80&id=118553&format=png" /></a>
                </div>
                <div class="social-item">
                    <a href="<?php echo $twitter; ?>"><img
                            src="https://img.icons8.com/?size=80&id=108650&format=png" /></a>
                </div>
                <div class="social-item">
                    <a href="<?php echo $linkedin; ?>"><img
                            src="https://img.icons8.com/?size=80&id=108812&format=png" /></a>
                </div>
            </div>
            <p>Copyright © 2020
                <?php echo $name; ?>. All rights reserved
            </p>
        </div>
    </section>
    <!-- End Footer -->


    <script src="./app.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
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

    </div>

</body>

</html>