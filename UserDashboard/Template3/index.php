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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with Meyawo landing page.">
    <meta name="author" content="Devcrud">
    <title>Portfolio</title>
    <!-- font icons -->
    <link rel="stylesheet" href="assets/vendors/themify-icons/css/themify-icons.css">
    <!-- Bootstrap + Meyawo main styles -->
    <link rel="stylesheet" href="assets/css/meyawo.css">
</head>

<body data-spy="scroll" data-target=".navbar" data-offset="40" id="home">

    <!-- Page Navbar -->
    <nav class="custom-navbar" data-spy="affix" data-offset-top="20">
        <div class="container">
            <a class="logo" href="#">Portfolio</a>
            <ul class="nav">
                <li class="item">
                    <a class="link" href="#home">Home</a>
                </li>
                <li class="item">
                    <a class="link" href="#about">About</a>
                </li>
                <li class="item">
                    <a class="link" href="#portfolio">Education</a>
                </li>
                <li class="item">
                    <a class="link" href="#testmonial">Project</a>
                </li>
                <li class="item">
                    <a class="link" href="#blog">Experience</a>
                </li>
                <li class="item">
                    <a class="link" href="#contact">Contact</a>
                </li>
                <li class="item">
                <a href="#" class="link" id="shareBtn" onclick="sharePortfolio()">Share</a>
                </li>
            </ul>
            <a href="javascript:void(0)" id="nav-toggle" class="hamburger hamburger--elastic">
                <div class="hamburger-box">
                    <div class="hamburger-inner"></div>
                </div>
            </a>
        </div>
    </nav><!-- End of Page Navbar -->

    <div id="pdf-container">
    <!-- page header -->
    <header id="home" class="header">
        <div class="overlay"></div>
        <div class="header-content container">
            <h1 class="header-title">
                <span class="up">HI! I AM</span>
                <span class="down"><?php echo $name; ?></span>
            </h1>
            <p class="header-subtitle"><?php echo $profession; ?></p>

            <!-- <button class="btn btn-primary">Visit My Works</button> -->
        </div>
    </header><!-- end of page header -->

    <!-- about section -->
    <section class="section pt-0" id="about">
        <!-- container -->
        <div class="container text-center">
            <!-- about wrapper -->
            <div class="about">
                <div class="about-img-holder">
                    <img src="<?php echo $profile_pic; ?>" class="about-img"
                        alt="Download free bootstrap 4 landing page, free boootstrap 4 templates, Download free bootstrap 4.1 landing page, free boootstrap 4.1.1 templates, meyawo Landing page">
                </div>
                <div class="about-caption">
                    <p class="section-subtitle">Who Am I ?</p>
                    <h2 class="section-title mb-3">About Me</h2>
                    <p>
                    <?php echo $about_description; ?>
                    </p>
                    <!-- <button class="btn-rounded btn btn-outline-primary mt-4">Download CV</button> -->
                </div>
            </div><!-- end of about wrapper -->
        </div><!-- end of container -->
    </section> <!-- end of about section -->

<!-- service section -->
<section class="section" id="service">
    <div class="container text-center">
        <!-- <p class="section-subtitle">What I Do ?</p> -->
        <h6 class="section-title mb-6">Skills</h6>
        <!-- row -->
        <div class="row">
            <?php
            // Fetch skills from the database
            $skills_sql = "SELECT skills FROM skills WHERE id = $user_id";
            $skills_result = mysqli_query($conn, $skills_sql);

            // Check if skills were fetched successfully
            if ($skills_result && mysqli_num_rows($skills_result) > 0) {
                // Loop through each skill
                while ($row = mysqli_fetch_assoc($skills_result)) {
                    $skills = $row['skills'];
                    ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="service-card">
                            <div class="body">
                                <img src="assets/imgs/responsive.svg" alt="Skill Icon" class="icon">
                                <h6 class="title"><?php echo $skills; ?></h6>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // If no skills found, display a default message
                echo '<div class="col-md-6 col-lg-3">
                <div class="service-card">
                    <div class="body">
                        <img src="assets/imgs/responsive.svg" alt="Skill Icon" class="icon">
                        <h6 class="title">HTML</h6>
                    </div>
                </div>
            </div>';
            }
            ?>
        </div><!-- end of row -->
    </div>
</section><!-- end of service section -->


 
<!-- pricing section -->
<section class="section" id="portfolio">
    <div class="container text-center">
        <!-- <p class="section-subtitle">How Much I Charge ?</p> -->
        <h6 class="section-title mb-6">My Education</h6>
        <!-- row -->
        <div class="pricing-wrapper">
            <!-- Fetching education details -->
            <?php
            $education_sql = "SELECT * FROM education WHERE id = $user_id";
            $education_result = mysqli_query($conn, $education_sql);

            if ($education_result && mysqli_num_rows($education_result) > 0) {
                while ($education_row = mysqli_fetch_assoc($education_result)) {
                    echo "<div class='pricing-card'>";
                    echo "<div class='pricing-card-header'>";
                    echo "<img class='pricing-card-icon' src='assets/imgs/education.svg' alt='Download free bootstrap 4 landing page, free boootstrap 4 templates, Download free bootstrap 4.1 landing page, free boootstrap 4.1.1 templates, meyawo Landing page'>";
                    echo "</div>";
                    echo "<div class='pricing-card-body'>";
                    echo "<h6 class='pricing-card-title'>" . $education_row['qualification'] . "</h6>";
                    echo "<div class='pricing-card-list'>";
                    echo "<p>" . $education_row['university'] ."</p>";
                    echo "<p> Passing Year: ". $education_row['year'] ."</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='pricing-card-footer'>";                
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                // Default education details
                $default_education = array(
                    array("qualification" => "Bachelor's Degree", "university" => "University Name", "year" => "2024"),
                    array("qualification" => "High School Diploma", "university" => "High School Name", "year" => "2021"),
                    array("qualification" => "School", "university" => "School Name", "year" => "2018")
                );

                foreach ($default_education as $education) {
                    echo "<div class='pricing-card'>";
                    echo "<div class='pricing-card-header'>";
                    echo "<img class='pricing-card-icon' src='assets/imgs/scooter.svg' alt='Download free bootstrap 4 landing page, free boootstrap 4 templates, Download free bootstrap 4.1 landing page, free boootstrap 4.1.1 templates, meyawo Landing page'>";
                    echo "</div>";
                    echo "<div class='pricing-card-body'>";
                    echo "<h6 class='pricing-card-title'>" . $education['qualification'] . "</h6>";
                    echo "<div class='pricing-card-list'>";
                    echo "<p>" . $education['university'] ."</p>";
                    echo "<p> Passing Year: " . $education['year'] ."</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='pricing-card-footer'>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            ?>
        </div><!-- end of pricing wrapper -->
    </div> <!-- end of container -->
</section><!-- end of pricing section -->


    <!-- section -->
    <section class="section-sm bg-primary">
        <!-- container -->
        <div class="container text-center text-sm-left">
            <!-- row -->
            <div class="row align-items-center">
                <div class="col-sm offset-md-1 mb-4 mb-md-0">
                    <h6 class="title text-light">Want to work with me?</h6>
                    <p class="m-0 text-light">Always feel Free to Contact & Hire me</p>
                </div>
                <div class="col-sm offset-sm-2 offset-md-3">
                    <button class="btn btn-lg my-font btn-light rounded">Hire Me</button>
                </div>
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </section> <!-- end of section -->

    <section class="section" id="testmonial">
    <div class="container text-center">
        <!-- <p class="section-subtitle">What Think Client About Me ?</p> -->
        <h6 class="section-title mb-6">My Projects</h6>

        <!-- row -->
        <div class="row">
            <?php
            // Fetch project details from the database
            $project_sql = "SELECT * FROM projects WHERE id = $user_id";
            $project_result = mysqli_query($conn, $project_sql);

            // Check if there are projects available
            if ($project_result && mysqli_num_rows($project_result) > 0) {
                while ($project_row = mysqli_fetch_assoc($project_result)) {
            ?>
                    <div class="col-md-6">
                        <div class="testimonial-card">
                            <!-- <div class="testimonial-card-img-holder">
                                <img src="<?php echo $profile_pic; ?>" class="testimonial-card-img" alt="Client Image">
                            </div> -->
                            <div class="testimonial-card-body">
                                <h6 class="testimonial-card-title"><?php echo $project_row['p_name']; ?></h6><br>
                                <p class="testimonial-card-subtitle"><?php echo $project_row['p_description']; ?></p>
                               
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                // Display default project details if no records found
                $default_projects = array(
                    array("p_name" => "Default Project 1", "p_description" => "Default project description 1"),
                    array("p_name" => "Default Project 2", "p_description" => "Default project description 2")
                );

                foreach ($default_projects as $project) {
            ?>
                    <div class="col-md-6">
                        <div class="testimonial-card">
                            <div class="testimonial-card-img-holder">
                                <img src="assets/imgs/avatar2.jpg" class="testimonial-card-img" alt="Client Image">
                            </div>
                            <div class="testimonial-card-body">
                                <p class="testimonial-card-subtitle"><?php echo $project['p_description']; ?></p>
                                <h6 class="testimonial-card-title"><?php echo $project['p_name']; ?></h6>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div> <!-- end of container -->
</section> <!-- end of testimonial section -->


    <!-- blog section -->
   <section class="section" id="blog">
    <div class="container text-center">
        <!-- <p class="section-subtitle">Recent Posts?</p> -->
        <h6 class="section-title mb-6">Experience?</h6>
        <!-- blog-wrapper -->
        <?php
        // Fetch experience details from the database
        $experience_sql = "SELECT * FROM internships_experience WHERE id = $user_id";
        $experience_result = mysqli_query($conn, $experience_sql);

        // Check if there are experiences available
        if ($experience_result && mysqli_num_rows($experience_result) > 0) {
            while ($experience_row = mysqli_fetch_assoc($experience_result)) {
        ?>
                <div class="blog-card">
                    <div class="blog-card-header">
                        <img src="assets/imgs/img-3.jpg" class="blog-card-img" alt="<?= $experience_row['name']; ?>">
                    </div>
                    <div class="blog-card-body">
                        <h5 class="blog-card-title"><?= $experience_row['name']; ?></h5>

                        <p class="blog-card-caption">
                            <?= $experience_row['company_name']; ?></a>
                        </p>

                        <p><?= $experience_row['i_description']; ?></p>

                    </div>
                </div><!-- end of blog wrapper -->
        <?php
                // Add an <hr> tag after each experience card except for the last one
                if ($experience_row !== end($experience_row)) {
                    echo '<hr>';
                }
            }
        } else {
            // Display default experience details if no records found
            $default_experience = array(
                array("name" => "Default Experience", "image" => "assets/imgs/default-img.jpg", "author" => "Admin", "likes" => "0", "comments" => "0", "description" => "Default experience description.")
            );

            foreach ($default_experience as $experience) {
        ?>
                <div class="blog-card">
                    <div class="blog-card-header">
                        <img src="<?= $experience['image']; ?>" class="blog-card-img" alt="<?= $experience['name']; ?>">
                    </div>
                    <div class="blog-card-body">
                        <h5 class="blog-card-title"><?= $experience['name']; ?></h5>

                        <p class="blog-card-caption">
                            <a href="#">By: <?= $experience['author']; ?></a>
                            <a href="#"><i class="ti-heart text-danger"></i> <?= $experience['likes']; ?></a>
                            <a href="#"><i class="ti-comment"></i> <?= $experience['comments']; ?></a>
                        </p>

                        <p><?= $experience['description']; ?></p>

                        <a href="#" class="blog-card-link">Read more <i class="ti-angle-double-right"></i></a>
                    </div>
                </div><!-- end of blog wrapper -->
        <?php
            }
        }
        ?>
    </div><!-- end of container -->
    </section><!-- end of blog section -->


    <!-- contact section -->
    <section class="section" id="contact">
        <div class="container text-center">
            <p class="section-subtitle">How can you communicate?</p>
            <h6 class="section-title mb-5">Contact Me</h6>
            <!-- contact form -->
            <form action="https://api.web3forms.com/submit" class="contact-form col-md-10 col-lg-8 m-auto" method="post">
                <div class="form-row">
                    <div class="form-group col-sm-6">
                    <input type="hidden" name="access_key" value="126fd1cd-ff7b-4a9d-a3b2-6d2b6a83f6b9">
                        <input type="text" size="50" class="form-control" placeholder="Your Name" required>
                    </div>
                    <div class="form-group col-sm-6">
                        <input type="email" class="form-control" placeholder="Enter Email" requried>
                    </div>
                    <div class="form-group col-sm-12">
                        <textarea name="comment" id="comment" rows="6" class="form-control"
                            placeholder="Write Something"></textarea>
                    </div>
                    <div class="form-group col-sm-12 mt-3">
                        <input type="submit" value="Send Message" class="btn btn-outline-primary rounded">
                    </div>
                </div>
            </form><!-- end of contact form -->
        </div><!-- end of container -->
    </section><!-- end of contact section -->

    <!-- footer -->
    <div class="container">
        <footer class="footer">
            <p class="mb-0">Copyright
                <script>document.write(new Date().getFullYear())</script> &copy; <a
                    href="#"><a
                    href="#"><?php echo $name; ?></a>
            </p>
            <div class="social-links text-right m-auto ml-sm-auto">
                <a href="<?php echo $facebook; ?>" class="link" target="_blank"><i class="ti-facebook"></i></a>
                <a href="<?php echo $twitter; ?>" class="link" target="_blank"><i class="ti-twitter-alt"></i></a>
                <a href="<?php echo $github; ?>" class="link" target="_blank"><i class="ti-github"></i></a>
                <a href="<?php echo $linkedin; ?>" class="link" target="_blank"><i class="ti-linkedin"></i></a>

            </div>
        </footer>
    </div> <!-- end of page footer -->
    </div>
    <!-- core  -->
    <script src="assets/vendors/jquery/jquery-3.4.1.js"></script>
    <script src="assets/vendors/bootstrap/bootstrap.bundle.js"></script>

    <!-- bootstrap 3 affix -->
    <script src="assets/vendors/bootstrap/bootstrap.affix.js"></script>

    <!-- Meyawo js -->
    <script src="assets/js/meyawo.js"></script>
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

</body>

</html>