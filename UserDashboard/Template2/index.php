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
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Portfolio</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="mediaqueries.css" />
</head>

<body>
  <nav id="desktop-nav">
    <div class="logo">
      <?php echo $name; ?>
    </div>
    <div>
      <ul class="nav-links">
        <li><a href="#about">About</a></li>
        <li><a href="#skills">Skills</a></li>
        <li><a href="#projects">Projects</a></li>
        <li><a href="#contact">Contact</a></li>
        <li><a href="#" id="shareBtn" onclick="sharePortfolio()">Share</a></li>
      </ul>
    </div>
  </nav>
  <nav id="hamburger-nav">
    <div class="logo">
      <?php echo $name; ?>
    </div>
    <div class="hamburger-menu">
      <div class="hamburger-icon" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
      </div>
      <div class="menu-links">
        <li><a href="#about" onclick="toggleMenu()">About</a></li>
        <li><a href="#skills" onclick="toggleMenu()">Skills</a></li>
        <li><a href="#projects" onclick="toggleMenu()">Projects</a></li>
        <li><a href="#contact" onclick="toggleMenu()">Contact</a></li>
        <li><a href="#" onclick="toggleMenu()">Generate PDF</a></li>
      </div>
    </div>
  </nav>
  <div id="pdf-container">
  <section id="profile">
    <!-- <div class="section__pic-container">
        <img src="<?php echo $profile_pic; ?>" alt="profile picture" />
      </div> -->

    <div class="section__text">
      <p class="section__text__p1">Hello, I'm</p>
      <h1 class="title">
        <?php echo $name; ?>
      </h1>
      <p class="section__text__p2">
        <?php echo $profession; ?>
      </p>
      <div class="btn-container">
        <button class="btn btn-color-2" onclick="window.open('./assets/resume-example.pdf')">
          Download CV
        </button>
        <button class="btn btn-color-1" onclick="location.href='./#contact'">
          Contact Info
        </button>
      </div>
      <div id="socials-container">
        <a href="<?php echo $linkedin; ?>" target="_blank">
          <img src="./assets/linkedin.png" alt="My LinkedIn profile" class="icon" />
        </a>
        <a href="<?php echo $github; ?>" target="_blank">
          <img src="./assets/github.png" alt="My Github profile" class="icon" />
        </a>
      </div>
    </div>
  </section>
  <section id="about">
    <p class="section__text__p1">Get To Know More</p>
    <h1 class="title">About Me</h1>
    <div class="section-container">
      <!-- <div class="section__pic-container" style="height: 500px;">
        <img src="<?php echo $profile_pic; ?>" alt="Profile Picture" class="about-pic" />
      </div> -->
      <div class="about-details-container">
        <div class="about-containers">
          <div class="details-container">
            <img src="./assets/experience.png" alt="Experience icon" class="icon" />
            <h3>Experience</h3><br>
            <?php
            $experience_sql = "SELECT * FROM internships_experience WHERE id = $user_id";
            $experience_result = mysqli_query($conn, $experience_sql);

            if ($experience_result && mysqli_num_rows($experience_result) > 0) {
              while ($experience_row = mysqli_fetch_assoc($experience_result)) {
                echo "<div class='education-item'>";
                echo "<h4 >" . $experience_row['name'] . "</h4>";
                echo "<p>" . $experience_row['company_name'] . "</p>";
                echo "<p>" . $experience_row['i_description'] . "</p>";
                echo "</div>";
                echo "<br>";
              }
            } else {
              echo "<div class='education-item'>";
              echo "<h4 >Role</h4>";
              echo "<p>Company Name</p>";
              echo "<p>Internship_Description</p>";
              echo "</div>";
              echo "<br>";
            }
            ?>
          </div>
          <div class="details-container">
            <img src="./assets/education.png" alt="Education icon" class="icon" />
            <h3>Education</h3><br>
            <?php
            $education_sql = "SELECT * FROM education WHERE id = $user_id";
            $education_result = mysqli_query($conn, $education_sql);

            if ($education_result && mysqli_num_rows($education_result) > 0) {
              while ($education_row = mysqli_fetch_assoc($education_result)) {
                echo "<div class='education-item'>";
                echo "<h4 >" . $education_row['qualification'] . "</h4>";
                echo "<p>" . $education_row['university'] . " | Passing Year " . $education_row['year'] . "</p>";
                echo "</div>";
                echo "<br>";
              }
            } else {
              // Display default education details if no records found
              echo "<div class='education-item'>";
              echo "<h4>Bachelor's Degree</h4>";
              echo "<p>University Name | Graduated in 2020</p>";
              echo "</div>";
              echo "<br>";
            }
            ?>
          </div>
        </div>
        <div class="text-container">
          <p>
            <?php echo $about_description; ?>
          </p>
        </div>
      </div>
    </div>
    <img src="./assets/arrow.png" alt="Arrow icon" class="icon arrow" onclick="location.href='./#experience'" />
  </section>

  <section id="projects">
    <p class="section__text__p1">Browse My Recent</p>
    <h1 class="title">Projects</h1>
    <div class="experience-details-container">
      <div class="about-containers">
        <?php
        // Array of image paths for each project (manually set)
        $image_paths = array(
          "./assets/project-1.png",
          "./assets/project-2.png",
          // Add more image paths for each project as needed
        );

        // Query to fetch projects
        $project_sql = "SELECT * FROM projects WHERE id = $user_id";
        $project_result = mysqli_query($conn, $project_sql);

        if ($project_result && mysqli_num_rows($project_result) > 0) {
          $index = 0; // Index to access image_paths array
          while ($project_row = mysqli_fetch_assoc($project_result)) {
            echo "<div class='details-container color-container'>";
            echo "<div class='project-item'>";
            echo "<img src='" . $image_paths[$index] . "' alt='Project Image' class='project-img' />";
            echo "<h2 class='experience-sub-title project-title'>" . $project_row['p_name'] . "</h2>";
            echo "<p>" . $project_row['p_description'] . "</p>";
            echo "<div class='btn-container'>";
            // Additional buttons or actions for each project can be added here
            echo "</div>";
            echo "</div>";
            echo "</div>";

            // Increment index for next project's image
            $index++;
          }
        } else {
          // Display default message if no projects found
          echo "<div class='details-container color-container'>";
          echo "<div class='project-item'>";
          echo "<img src='" . $image_paths[$index] . "' alt='Project Image' class='project-img' />";
          echo "<h2 class='experience-sub-title project-title'>Project Name</h2>";
          echo "<p >Project Description</p>";
          echo "<div class='btn-container'>";
          // Additional buttons or actions for each project can be added here
          echo "</div>";
          echo "</div>";
          echo "</div>";

        }
        ?>
      </div>
    </div>
    <img src="./assets/arrow.png" alt="Arrow icon" class="icon arrow" onclick="location.href='./#contact'" />
  </section>

<section id="skills">
  <p class="section__text__p1">Explore My Skills</p>
  <h1 class="title">Skills</h1>
  <div class="skills-container">
    <?php
    $skills_sql = "SELECT skills FROM skills WHERE id = $user_id";
    $skills_result = mysqli_query($conn, $skills_sql);

    if ($skills_result && mysqli_num_rows($skills_result) > 0) {
      while ($skills_row = mysqli_fetch_assoc($skills_result)) {
        // Explode skills string to get individual skills
        $skills = explode(",", $skills_row['skills']);
        foreach ($skills as $skill) {
          echo "<div class='skill-item'>" . $skill . "</div>";
        }
      }
    } else {
      echo "<p>No skills found.</p>";
    }
    ?>
  </div>
</section>


  <section id="contact">
    <p class="section__text__p1">Get in Touch</p>
    <h1 class="title">Contact Me</h1>
    <div class="contact-info-upper-container">
      <div class="contact-info-container">
        <img src="./assets/email.png" alt="Email icon" class="icon contact-icon email-icon" />
        <p><a href="mailto:examplemail@gmail.com">
            <?php echo $email; ?>
          </a></p>
      </div>
    </div>
    <div class="contact-info-upper-container">
      <div class="contact-info-container">
        <img src="./assets/email.png" alt="Email icon" class="icon contact-icon email-icon" />
        <p><a href="mailto:examplemail@gmail.com">
            <?php echo $address; ?>
          </a></p>
      </div>
    </div>

  </section>
  <footer>
    <nav>
      <div class="nav-links-container">
        <ul class="nav-links">
          <li><a href="#about">About</a></li>
          <li><a href="#skills">Skills</a></li>
          <li><a href="#projects">Projects</a></li>
          <li><a href="#contact">Contact</a></li>
          <li><a href="#" id="generate-pdf-btn">Generate PDF</a></li>
        </ul>
      </div>
    </nav>
    <p>Copyright &#169; 2023
      <?php echo $name; ?>. All Rights Reserved.
    </p>
  </footer>
  </div>
  <script src="script.js"></script>
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