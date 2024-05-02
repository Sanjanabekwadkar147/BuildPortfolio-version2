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

    $profile_pic = "./images/profile.jpg";
    $about_title = "Software Developer";
    $about_description = "I am a beginner developer with a passion for coding and learning new technologies.";
}

?>

<!DOCTYPE html>
<html>
<head>
	<!--  *****   Link To Custom CSS Style sheet   *****  -->
	<link rel="stylesheet" type="text/css" href="style.css">

	<!--  *****   Link To Font Awsome Icons   *****  -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>

	<!--   ***** Link To Magnific Popup CSS *****   -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css"/>

	<!--   ***** Links To OwlCarousel CSS *****   -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"/>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Portfolio</title>
</head>
<body>
<!--   *** Website Container Starts ***   -->
<div class="Website-container">
	
<!--   *** Home Section Starts ***   -->
<section class="home" id="home">
	
	<!--   === Navbar Starts ===   -->
	<nav class="navbar">
		<div class="logo">
		<?php echo $name; ?>
		</div>

		<ul class="nav-links">
			<li><a href="#home">Home</a></li>
			<li><a href="#about">About</a></li>
			<li><a href="#services">Projects</a></li>
			<li><a href="#resume">Resume</a></li>
			<li><a href="#" id="shareBtn" onclick="sharePortfolio()">Share</a></li>
		</ul>

		<a href="#contactForm" class="button-wrapper">
			<button class="btn contact-btn">Contact</button>
		</a>

		<div class="menu-btn">
			<span class="bar"></span>
			<span class="bar"></span>
			<span class="bar"></span>
		</div>
	</nav>
	<!--   === Navbar Ends ===   -->

	<!--   === Hero Starts ===   -->
	<div class="hero">
		<div class="hero-text">
			<h3>Hey, There</h3>
			<h1>I am <?php echo $name; ?></h1>
			<h2><?php echo $profession; ?></h2>
			<p> <?php echo $about_description; ?></p>
			<!-- <button class="btn hire-btn">Hire Me</button> -->
		</div>

		<!-- <div class="hero-image">
			<img src="images/hero-img.png">
		</div> -->
	</div>
	<!--   === Hero Ends ===   -->


</section>
<!--   *** Home Section Ends ***   -->

<!--   *** About Section Starts ***   -->
<section class="about" id="about">
	
	<!--   === About Image Starts ===   -->
	<div class="about-image">
		<img src="<?php echo $profile_pic; ?>">
		<div class="social-media">
			<a href="<?php echo $facebook; ?>" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
			<a href="<?php echo $linkedin; ?>"  target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
			<a href="<?php echo $github; ?>"  target="_blank"><i class="fa-brands fa-github"></i></a>
			<a href="<?php echo $twitter; ?>"  target="_blank"><i class="fa-brands fa-twitter"></i></a>
		</div>
	</div>
	<!--   === About Image Ends ===   -->

	<!--   === About Description Starts ===   -->
	<div class="about-desc">
		<h3>About Me</h3>
		<h2><?php echo $profession; ?></h2><br>
		<p><?php echo $about_description; ?></p><br>
		<h3>Skills : </h3>
<div class="about-personal-info">

    <?php
    // Fetch skills from the skills table
    $skills_query = "SELECT skills FROM skills WHERE id = $user_id";
    $skills_result = mysqli_query($conn, $skills_query);

    if ($skills_result && mysqli_num_rows($skills_result) > 0) {
        echo '<div class="row gy-3">';
        while ($row = mysqli_fetch_assoc($skills_result)) {
            // Extract skills from the row
            $skills = explode(",", $row['skills']);

            // Display skills, ensuring 4 skills per row
            foreach ($skills as $skill) {
                echo "<div class='col-md-3' data-aos='fade-up'>";
                echo "<div class='service p-4 bg-base rounded-4 shadow-effect'>";
                echo "<span>$skill</span>"; // Corrected closing tag for span
                echo "</div>";
                echo "</div>";
            }
        }
        echo '</div>';
    } else {
        // Default skills, ensuring 4 skills per row by using col-md-3
        $default_skills = array(
            "Java",
            "HTML",
            "CSS",
            "JavaScript",
            "Problem Solving",
            "Soft Skills",
            "Networking"
        );

        echo '<div class="row gy-4">';
        // Display default skills
        foreach ($default_skills as $skill) {
            echo "<div class='col-md-3' data-aos='fade-up'>"; // Use col-md-3 for 4 items per row
            echo "<div class='service p-4 bg-base rounded-4 shadow-effect'>";
            echo "<span>$skill</span>";
            echo "</div>";
            echo "</div>";
        }
        echo '</div>';
    }
    ?>
</div>

		<!-- <button class="btn download-btn">Download CV</button> -->
	</div>
	<!--   === About Description Ends ===   -->

</section>
<!--   *** About Section Ends ***   -->

<!--   *** Services Section Starts ***   -->
<section class="services reusable" id="services">
	
	<!--   === Headings Text Starts ===   -->
	<header class="headings">
		<h3>Projects</h3>
		<h1>Explore My Projects</h1>
		</header>
	<!--   === Headings Text Ends ===   -->

	<!--   === Services Box Container Starts ===   -->
	<div class="services-container">
    <?php
    // Assuming $conn is your database connection variable
    $sql = "SELECT * FROM projects WHERE id = $user_id";
    $result = mysqli_query($conn, $sql);

    // Default projects array
    $default_projects = [
        [
            'service_icon' => 'fa-solid fa-palette',
            'service_name' => 'Default Web Design',
            'description' => 'This is a default description for web design services.',
        ],
        [
            'service_icon' => 'fa-solid fa-code',
            'service_name' => 'Default Web Development',
            'description' => 'This is a default description for web development services.',
        ],
        // Add more default projects as needed
    ];

    if ($result && mysqli_num_rows($result) > 0) {
        // Manually set the icon for fetched projects
        $database_project_icon = 'fa-solid fa-project-diagram'; // Example icon class
        // Display projects from database
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='service-box'>";
            echo "<div class='icon-wrapper'>";
            echo "<i class='".$database_project_icon."'></i>"; // Use the manually set icon here
            echo "</div>";
            echo "<h2>".$row['p_name']."</h2>";
            echo "<p>".$row['p_description']."</p>";
            echo "</div>";
        }
    } else {
        // Display default projects if no projects are found in the database
        foreach ($default_projects as $project) {
            echo "<div class='service-box'>";
            echo "<div class='icon-wrapper'>";
            echo "<i class='".$project['service_icon']."'></i>";
            echo "</div>";
            echo "<h2>".$project['service_name']."</h2>";
            echo "<p>".$project['description']."</p>";
            echo "</div>";
        }
    }
    ?>
</div>


	<!--   === Services Box Container Ends ===   -->

</section>
<!--   *** Services Section Ends ***   -->

<!--   *** Resume Section Starts ***   -->
<section class="resume reusable" id="resume">
	
	<!--   === Headings Text Starts ===   -->
	<header class="headings">
		<h3>Resume</h3>
		<h1>Education & Experience</h1>
		</header>
	<!--   === Headings Text Ends ===   -->

	<!--   === Resume Row Starts ===   -->
	<div class="resume-row">
		
		<!--   === Left Column Starts ===   -->
<div class="column column-left">
    <header class="sub-heading">
        <h2>EDUCATION</h2>
    </header>

    <main class="resume-contents">
        <?php
        // Fetch education data from the database and populate the HTML
        $education_sql = "SELECT * FROM education WHERE id = $user_id "; // Adjust as per your actual column names and conditions
        $education_result = mysqli_query($conn, $education_sql);

        if ($education_result && mysqli_num_rows($education_result) > 0) {
            while ($education_row = mysqli_fetch_assoc($education_result)) {
                echo "<div class='box'>";
                echo "<h3>" . htmlspecialchars($education_row['qualification']) . "</h3>";
                echo "<h4>" . htmlspecialchars($education_row['university']) . "</h4>";
				echo "<br>";
				echo "<p> Passing Year : " . htmlspecialchars($education_row['year']) . "</p>";
                
                echo "</div>";
            }
        } else {
            // Default education details
            echo "<div class='box'>";
			echo "<h4>Passing Year : 2019</h4>";
            echo "<h3>Computer Science (MSc)</h3>"; // Example default degree and field
            echo "<p>Shivaji University</p>"; // Example default description
            echo "</div>";

            echo "<div class='box'>";
            echo "<h4>Passing Year : 2019</h4>"; // Another example default start-end year
            echo "<h3>Computer Science (BSc)</h3>"; // Another example default degree and field
            echo "<p>Shivaji University</p>"; // Another example default description
            echo "</div>";
        }
        ?>
    </main>
</div>

		<!--   === Left Column Ends ===   -->

		<!--   === Right Column Starts ===   -->
		<div class="column column-right">
    <header class="sub-heading">
        <h2>EXPERIENCE</h2>
    </header>

    <main class="resume-contents">
        <?php
        // Assuming you have a valid database connection in $conn and a user ID in $user_id
        $experience_sql = "SELECT * FROM internships_experience WHERE id = $user_id "; // Make sure to adjust your column names accordingly
        $experience_result = mysqli_query($conn, $experience_sql);

        if ($experience_result && mysqli_num_rows($experience_result) > 0) {
            while ($row = mysqli_fetch_assoc($experience_result)) {
                

                echo "<div class='box'>";
                // echo "<h4>{$start_year} - {$end_year}</h4>";
                echo "<h3>" . htmlspecialchars($row['name']) . "</h3>"; // Assuming the job title is stored in 'job_title'
				echo "<h4>" . htmlspecialchars($row['company_name']) . "</h4>";
                echo "<br>";
				echo "<p>" . htmlspecialchars($row['i_description']) . "</p>"; // And the job description in 'description'
				echo "</div>";
            }
        } else {
            echo "<div class='box'><p>No experience found.</p></div>";
        }
        ?>
    </main>
</div>

		<!--   === Right Column Ends ===   -->

	</div>
	<!--   === Resume Row Ends ===   -->

</section>
<!--   *** Resume Section Ends ***   -->

<!--   *** Contact Section Starts ***   -->
<section class="contact-form" id="contactForm">
	<div class="contact-row">
		
		<!--   === Left Column Starts ===   -->
		<div class="contact-col column-1">
			<div class="contactTitle">
				<h2>Get In Touch</h2>
				<p>Interested in working together? Let's talk</p>
			</div>

			<form class="form-1" action="https://api.web3forms.com/submit" method="post">
				<div class="inputGroup">
					<input type="hidden" name="access_key" value="126fd1cd-ff7b-4a9d-a3b2-6d2b6a83f6b9">
					<input type="text" name="name" required="required">
					<label>Your Name</label>
				</div>

				<div class="inputGroup">
					<input type="email" name="email" required="required">
					<label>Email</label>
				</div>
			</form>

			<div class="contactSocialMedia">
				<a href="<?php echo $facebook; ?>"><i class="fa-brands fa-facebook-f"></i></a>
				<a href="<?php echo $twitter; ?>"><i class="fa-brands fa-twitter"></i></a>
				<a href="<?php echo $github; ?>"><i class="fa-brands fa-github"></i></a>
				<a href="<?php echo $linkedin; ?>"><i class="fa-brands fa-linkedin-in"></i></a>
			</div>
		</div>
		<!--   === Left Column Ends ===   -->

		<!--   === Right Column Starts ===   -->
		<div class="contact-col column-2">
			<form class="form-2" action="https://api.web3forms.com/submit" method="post">
				<div class="inputGroup">
				<input type="hidden" name="access_key" value="126fd1cd-ff7b-4a9d-a3b2-6d2b6a83f6b9">
					<textarea required="required" name="message"></textarea>
					<label>Say Something</label>
				</div>
				<button type="submit" class="form-button">MESSAGE ME</button>
			</form>
		</div>
		<!--   === Right Column Ends ===   -->

	</div>
</section>
<!--   *** Contact Section Ends ***   -->

<!--   *** Footer Section Starts ***   -->
<section class="page-footer">
	
	<footer class="footer-contents">
		<a href="index.php">Portfolio</a>
		<p>Created by <span><?php echo $name; ?></span> | All rights reserved.</p>
	</footer>
	
</section>
<!--   *** Footer Section Ends ***   -->



</div>
<!--   *** Website Container Ends ***   -->




<!--   *** Link To JQuery ***   -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" ></script>

<!--   *** Link To Isotope ***   -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>

<!--   *** Link To Magnific Popup ***   -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

<!--   *** Link To OwlCarousel Js ***   -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<!--   *** Link To Custom Script File ***   -->
<script type="text/javascript" src="script.js"></script>
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