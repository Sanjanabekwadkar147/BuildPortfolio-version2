<!DOCTYPE html>
<!-- Created By CodingNepal -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Portfolio Website</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.11/typed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>

</head>
<body>
    <div class="scroll-up-btn">
        <i class="fas fa-angle-up"></i>
    </div>
    <nav class="navbar">
        <div class="max-width">
            <div class="logo"><a href="#">Portfo<span>lio.</span></a></div>
            <ul class="menu">
                <li><a href="#home" class="menu-btn">Home</a></li>
                <li><a href="#about" class="menu-btn">About</a></li>
                <li><a href="#services" class="menu-btn">Services</a></li>
                <li><a href="#contact" class="menu-btn">Contact</a></li>
                
            </ul>
            <div class="menu-btn">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <!-- home section start -->
    <section class="home" id="home">
        <div class="max-width">
            <div class="home-content">
                <div class="text-2">Portfolio Palette</div><br>
                <div class="text-3">Build Your <span class="typing"></span> with us</div><br>
                <a href="UserDashboard/Register.php">Get Started</a>
            </div>
        </div>
    </section>

    <!-- about section start -->
    <section class="about" id="about">
        <div class="max-width">
            <h2 class="title">About us</h2>
            <div class="about-content">
                <!-- <div class="column left">
                    <img src="images/profile-1.jpeg" alt="">
                </div> -->
                
                    <!-- <div class="text">I'm Prakash and I'm a <span class="typing-2"></span></div> -->
                    <p>Embark on a journey of self-expression and professional growth with Portfolio Palette.
                         We understand the power of a strong portfolio in showcasing your unique talents and accomplishments. 
                         Whether you're an artist, designer, developer, or professional in any field, our platform empowers you to effortlessly build, manage,
                         and present your portfolio in a way that reflects your true essence. Join us  
                         and unlock a world where creativity meets opportunity. Your portfolio is not just a showcase;
                          it's a testament to your story, your evolution, and your potential. Let's build your narrative 
                          together and watch your professional journey unfold in the digital realm.</p>              
            </div>
        </div>
    </section>

    <!-- services section start -->
    <section class="services" id="services">
        <div class="max-width">
            <h2 class="title">Our services</h2>
            <div class="serv-content">
                <div class="card">
                    <div class="box">
                        <i class="fas fa-paint-brush"></i>
                        <div class="text">Portfolio Website Design</div>
                        <p>Unleash the full potential of your personal or professional journey with our portfolio website design services.</p>
                        </div>
                </div>
                <div class="card">
                    <div class="box">
                        <i class="fas fa-chart-line"></i>
                        <div class="text">Portfolio Maintenance</div>
                        <p>Ensure your portfolio remains fresh, relevant, and reflective 
                            of your ongoing achievements with our portfolio maintenance and update services.</p>
                        </div>
                </div>
                <div class="card">
                    <div class="box">
                        <i class="fas fa-code"></i>
                        <div class="text">Responsive Portfolio </div>
                        <p>Step into the future with our responsive portfolio apps design services.</p>
                    </div>
                </div>
               </div>
            </div>
        </div>
    </section>

    <!-- contact section start -->
    <section class="contact" id="contact">
        <div class="max-width">
            <h2 class="title">Contact us</h2>
            <div class="contact-content">
                <div class="column left">
                    <div class="text">Get in Touch</div>
                    <p>By partnering with us, you can craft a dynamic portfolio that not only showcases 
                        your skills but also paints a vivid picture of your unique talents and professional journey.
                    </p>
                    <div class="icons">
                        <div class="row">
                            <i class="fas fa-user"></i>
                            <div class="info">
                                <div class="head">Name</div>
                                <div class="sub-title">Portfolio Palette</div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="info">
                                <div class="head">Address</div>
                                <div class="sub-title">Surkhet, Nepal</div>
                            </div>
                        </div> -->
                        <div class="row">
                            <i class="fas fa-envelope"></i>
                            <div class="info">
                                <div class="head">Email</div>
                                <div class="sub-title">sanjanabekwadkar@gmail.com</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column right">
                    <div class="text">Message me</div>
                    <form action="https://api.web3forms.com/submit" method="post">
    <div class="fields">
        <div class="field name">
            <input type="hidden" name="access_key" value="126fd1cd-ff7b-4a9d-a3b2-6d2b6a83f6b9">
            <input type="text" placeholder="Name" name="name" required>
        </div>
        <div class="field email">
            <input type="email" placeholder="Email" name="email" required>
        </div>
    </div>
    <div class="field">
        <input type="text" placeholder="Subject" name="subject" required>
    </div>
    <div class="field textarea">
        <textarea cols="30" rows="10" placeholder="Message.." name="message" required></textarea>
    </div>
    <div class="button-area">
        <button type="submit">Send message</button>
    </div>
</form>

                </div>
            </div>
        </div>
    </section>
     
        <!-- <section class="contact" id="contact">
        <div class="max-width">
            <h2 class="title">Contact us</h2>
            <div class="contact-content">
            <div class="column right">
                    <div class="text">Register</div>
                    <form action="#">
                        <div class="fields">
                            <div class="field name">
                                <input type="text" placeholder="Name" required>
                            </div>
                        </div>
                        <div class="field email">
                                <input type="email" placeholder="Email" required>
                        </div>
                        <div class="field">
                            <input type="text" placeholder="Password" required>
                        </div>
                        <div class="button-area">
                            <button type="submit">Register</button>
                        </div>
                    </form>
                </div>
                <div class="column right">
                    <div class="text">Login</div>
                    <form action="#">

                        <div class="field email">
                                <input type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="field">
                            <input type="text" placeholder="Password" required>
                        </div>
                        <div class="button-area">
                            <button type="submit">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section> -->
    

    <!-- footer section start -->
    <footer>
        <span>Created By <a href="#">Sanjana Bekwadkar</a> | <span class="far fa-copyright"></span> 2024 All rights reserved.</span>
    </footer>

    <script src="script.js"></script>
</body>
</html>