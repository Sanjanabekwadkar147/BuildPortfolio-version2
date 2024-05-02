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

    $profile_pic = "assets/profile.jpg";
    $about_title = "Software Developer";
    $about_description = "I am a beginner developer with a passion for coding and learning new technologies.";
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
    />
    <link rel="stylesheet" href="styles.css" />
    <title>Portfolio</title>
  </head>
  <body>
    <nav>
      <div class="nav__header">
        <div class="nav__logo">
          <a href="#">Port<span>folio.</span></a>
        </div>
        <div class="nav__menu__btn" id="menu-btn">
          <i class="ri-menu-line"></i>
        </div>
      </div>
      <ul class="nav__links" id="nav-links">
        <li class="link"><a href="#home">Home</a></li>
        <li class="link"><a href="#about">About</a></li>
        <li class="link"><a href="#resume">Resume</a></li>
        <li class="link"><a href="#blog">Projects</a></li>
        <li class="link"><a href="#contact">Contact</a></li>
        <li class="link"><a href="#" id="shareBtn" onclick="sharePortfolio()">Share</a></li>
      </ul>
    </nav>

    <header class="header" id="home">
      <div class="section__container header__container">
        <h4>Hello, I'm <?php echo $name; ?></h4>
        <h1><?php echo $profession; ?></h1>
        <p class="section__description">
          As 
          <?php echo $profession; ?>, my portfolio is a visual journey where
          innovation meets aesthetics. Welcome to a space where every pixel
          tells a story and each interaction is a work of art.
        </p>
        <!-- <div class="header__btns">
          <button class="btn btn__primary" id="download-cv">Download CV</button>
          <button class="btn btn__secondary">Read More</button>
        </div> -->
      </div>
    </header>

    <section class="section__container about__container" id="about">
      <div class="about__image">
        <img src="<?php echo $profile_pic; ?>" alt="about" />
      </div>
      <div class="about__content">
        <h4>Let's Introduce About Myself</h4>
        <p class="section__description">
          <span><?php echo $about_description; ?></span> 
        </p>
        <!-- <p class="section__description">
          Let's embark on a visual journey that
          <span>transcends boundaries</span> and redefines the digital
          landscape.
        </p> -->
        <h4>My Skills</h4>
        <div class="about__progress">
        
    <?php
    // Fetch skills and progress from database
    $skills_query = "SELECT * FROM skills WHERE id = $user_id";
    $skills_result = mysqli_query($conn, $skills_query);

    if ($skills_result && mysqli_num_rows($skills_result) > 0) {
        while ($row = mysqli_fetch_assoc($skills_result)) {
            // Loop through fetched skills and progress
            $skills = explode(",", $row['skills']);
            // Display skills and progress bars
            for ($i = 0; $i < count($skills); $i++) {
                echo "<h5>$skills[$i]</h5>";
            }
        }
    } else {
        // Default skills and progress
        $default_skills = array("UX Design", "Visual Storytelling", "Prototyping", "Adobe Mastery");
        // Display default skills and progress bars
        for ($i = 0; $i < count($default_skills); $i++) {
            echo "<h5>$default_skills[$i]</h5>";
            
        }
    }
    ?>
</div>

      </div>
    </section>

    <section class="section__container resume__container" id="resume">
      <h2 class="section__header">My Qualifications</h2>
      <p class="section__description">
      Driven by an unyielding desire for knowledge, my expertise is not limited to academic achievements.
       It also includes practical 
      experience through various projects and a dedication to keeping up with the latest developments in design innovation.
      </p>
      <div class="resume__tabs">
        <div class="resume__tablist">
          <button class="btn active" data-tab="1">Education</button>
          <button class="btn" data-tab="2">Experience</button>
        </div>
        <div class="resume__tabpanel">
        <div class="panel__grid active" data-panel="1">
    <?php
    $education_sql = "SELECT * FROM education WHERE id = $user_id";
    $education_result = mysqli_query($conn, $education_sql);

    if ($education_result && mysqli_num_rows($education_result) > 0) {
        while ($education_row = mysqli_fetch_assoc($education_result)) {
            echo "<div class='panel__card'>";
            echo "<span><i class='ri-graduation-cap-fill'></i></span>";
            echo "<h4>" . $education_row['qualification'] . "</h4>";
            echo "<p class='section__description'>" . $education_row['university'] . "</p>";
            echo "<p class='section__description'> Passing Year | " . $education_row['year'] . "</p>";
            echo "</div>";
        }
    } else {
        $default_education = array(
            array("qualification" => "Bachelor's Degree in Graphic Design", "university" => "University name", "year" => "Passing year: 2025"),
            array("qualification" => "Master's Degree in User Experience Design", "university" => "University name", "year" => "Passing year: 2022"),
            array("qualification" => "Professional Certification in Web Development", "university" => "University name", "year" => "Passing year: 2019")
        );

        foreach ($default_education as $education) {
            echo "<div class='panel__card'>";
            echo "<span><i class='ri-graduation-cap-fill'></i></span>";
            echo "<h4>" . $education['qualification'] . "</h4>";
            echo "<p class='section__description'>" . $education['university'] . "</p>";
            echo "<p class='section__description'>" . $education['year'] . "</p>";
            echo "</div>";
        }
    }
    ?>
        </div>

        <div class="panel__grid" data-panel="2">
    <?php
    $internship_sql = "SELECT * FROM internships_experience WHERE id = $user_id";
    $internship_result = mysqli_query($conn, $internship_sql);

    if ($internship_result && mysqli_num_rows($internship_result) > 0) {
        while ($internship_row = mysqli_fetch_assoc($internship_result)) {
            echo "<div class='panel__card'>";
            echo "<span><i class='ri-macbook-fill'></i></span>";
            echo "<h4>" . $internship_row['name'] . "</h4>";
            echo "<p class='section__description'>" . $internship_row['company_name'] . "</p>";
            echo "<p class='section__description'>" . $internship_row['i_description'] . "</p>";
            echo "</div>";
        }
    } else {
        // Display default internship details if no records found
        echo "<div class='panel__card'>";
        echo "<span><i class='ri-macbook-fill'></i></span>";
        echo "<h4>Role</h4>";
        echo "<p class='section__description'>Company Name</p>";
        echo "<p class='section__description'>Internship description.</p>";
        echo "</div>";
    }
    ?>
</div>

        </div>
      </div>
    </section>


    <section class="section__container blog__container" id="blog">
    <h2 class="section__header" style="text-align:center;">Explore My Projects</h2>
    <div class="blog__grid">
        <?php
        $project_sql = "SELECT * FROM projects WHERE id = $user_id";
        $project_result = mysqli_query($conn, $project_sql);

        if ($project_result && mysqli_num_rows($project_result) > 0) {
            while ($project_row = mysqli_fetch_assoc($project_result)) {
                // Manually specify image paths along with project details
                $image_path = "assets/blog-1.jpg"; // Replace with the actual image path
                echo "<div class='blog__card'>";
                echo "<img src='" . $image_path . "' alt='blog' />";
                echo "<div class='blog__content'>";
                echo "<h4>" . $project_row['p_name'] . "</h4>";
                echo "<p class='section__description'>" . $project_row['p_description'] . "</p>";
                echo "<div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            // Manually add default project details with images
            $default_projects = array(
                array(
                    "p_name" => "Default Project 1",
                    "p_description" => "This is project description.",
                    "image_path" => "assets/blog-1.jpg"
                ),
                array(
                    "p_name" => "Default Project 2",
                    "p_description" => "This is project description.",
                    "image_path" => "assets/blog-3.jpg"
                )
            );

            foreach ($default_projects as $project) {
                echo "<div class='blog__card'>";
                echo "<img src='" . $project['image_path'] . "' alt='blog' />";
                echo "<div class='blog__content'>";
                echo "<h4>" . $project['p_name'] . "</h4>";
                echo "<p class='section__description'>" . $project['p_description'] . "</p>";
                echo "<div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        }
        ?>
    </div>
</section>


    <footer class="footer" id="contact">
      <div class="section__container footer__container">
        <h2 class="section__header">Contact me</h2>
        <p class="section__description">
          Feel free to get in touch with me for any inquiries, collaborations, or just to say hello!
        </p>
        <div class="footer__grid">
          <div class="footer__content">
            <h4><?php echo $name; ?></h4>
            <p>Thank you for taking the time to explore my portfolio. If you’d like to stay updated on my latest projects or collaborate on future endeavors, 
              please follow me on social media or reach out directly via the contact form.
            </p>
            <p>Let's inspire and innovate together!</p>
            <p>Thank you for visiting!</p>
            <div class="footer__socials">
              <a href="<?php echo $facebook; ?>" target="_blank"><i class="ri-facebook-fill"></i></a>
              <a href="<?php echo $twitter; ?>" target="_blank"><i class="ri-twitter-fill"></i></a>
              <a href="<?php echo $linkedin; ?>" target="_blank"><i class="ri-linkedin-fill"></i></a>
              <a href="<?php echo $github; ?>" target="_blank"><i class="ri-github-fill"></i></a>

            </div>
          </div>
          <div class="footer__form">
            <form action="https://api.web3forms.com/submit" method="post">
              <div class="input__row">
              <input type="hidden" name="access_key" value="126fd1cd-ff7b-4a9d-a3b2-6d2b6a83f6b9">
                <input type="text" placeholder="Your Name" />
                <input type="text" placeholder="Your Email" />
              </div>
              <textarea placeholder="Your Message"></textarea>
              <button class="btn btn__primary">Send Message</button>
            </form>
          </div>
        </div>
      </div>
      <div class="footer__bar">
        Copyright © 2024 <?php echo $name; ?>. All rights reserved.
      </div>
    </footer>
    </div>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

    <script src="main.js"></script>
    <script>
  document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.resume__tablist button');
    const tabPanels = document.querySelectorAll('.resume__tabpanel .panel__grid');

    tabs.forEach(tab => {
      tab.addEventListener('click', function () {
        const targetPanel = document.querySelector('.resume__tabpanel .panel__grid[data-panel="' + this.dataset.tab + '"]');
        if (targetPanel) {
          tabPanels.forEach(panel => panel.classList.remove('active'));
          tabs.forEach(tab => tab.classList.remove('active'));
          targetPanel.classList.add('active');
          this.classList.add('active');
        }
      });
    });
  });

</script>
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
