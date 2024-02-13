<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./assets/logo/logo.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="font.css">
    <title>Mettu University E-Learning</title>
    <style>
        /* Services Section */
        .services {
            background-color: #f8f9fa;
            padding: 50px 0;
        }
        .service-item {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
    
        /* FAQs Section */
        .faqs {
            padding: 50px 0;
        }
        .card {
            border: none;
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #f8f9fa;
            border: none;
        }
        .card-body {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
    
        /* Contact Section */
        .contact {
            background-color: #f8f9fa;
            padding: 50px 0;
        }
        .contact h3 {
            margin-bottom: 20px;
        }
        .contact p {
            margin-bottom: 10px;
        }
        .contact form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .footer {
        background-color: #343a40;
        color: #fff;
        padding: 50px 0;
    }
    .footer h3 {
        margin-bottom: 20px;
    }
    .footer p {
        margin-bottom: 10px;
    }
    .social-icons {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .social-icons li {
        display: inline-block;
        margin-right: 10px;
    }
    .social-icons li:last-child {
        margin-right: 0;
    }
    .social-icons a {
        color: #fff;
        font-size: 24px;
    }
    </style>
    
</head>
<body>

    <header>
        <div class="container">
            <div class="logo">
                <img src="./assets/logo/logo.png" alt="Mettu University Logo">
            </div>
            <div class="navig">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Courses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact Us</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav" id="userNav" style="display: none;">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                User
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">Enrolled Courses</a>
                                <a class="dropdown-item" href="#">Edit Profile</a>
                                <a class="dropdown-item" href="#">Settings</a>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="btns">
                <div class="user-account">
                    <a href="./App/index.php" class="account-link" target="_blank">Sign In</a>
                </div>
                <div class="cta">
                    <a href="./App/signup.php" class="button" target="_blank">Register</a>
                </div>
            </div>
        </div>
    </header>
    

    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <!-- Left content -->
                    <h1>Build Skills With Experts Any Time, Anywhere</h1>
                    <p>Education is both the act of teaching knowledge to others and the act of receiving knowledge from someone else.</p>
                    <a href="./Mentor/pages/request.php" target="_blank" class="button">Become Mentor</a>
                    <a href="./App/index.php" class="button" style="background-color: rgb(1, 95, 56);">Start Learning</a>
                </div>
                <div class="col-lg-6">
                    <!-- Right content -->
                    <img src="./assets/1.jpg" class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </section>
<section class="icons">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="time text-center">
                    <img src="./assets/time.svg" alt="time">
                    <p>Lifetime Support</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="teachers text-center">
                    <img src="./assets/teachers.svg" alt="teachers">
                    <p>Expert Teachers</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="certificate text-center">
                    <img src="./assets/ceritificate.svg" alt="certificate">
                    <p>Get Completion Certificate</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="payment text-center">
                    <img src="./assets/lock.svg" alt="payment">
                    <p>Secure Payment</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!--
    <section class="about">
        <h1>About US</h1>
        <div class="aboutUS">
            
            <div class="pic">
                <img src="./assets/meu.jpg" alt="">
            </div>
            <div class="text">
                <p>Mattu University (MaU) is third generation public universities in Ethiopia, which was established in 2011 GC in the ever-green vicinity endowed with untapped natural resources based on the Regulation of House of Ministers No. 238/2011 to meet the demand of the country’s fast-growing needs for high level of professionals. If one browses a Google Map, he/she can spot that MaU is located in southwestern part of Ethiopia, Oromia Regional State. It is situated in Mettu town which is 600kms away from the capital, Addis Ababa. MaU which is a boarding university in its very nature and its main campus lands over 350 hectare of land by fulfilling optimal facilities and infrastructure required to enhance its multifaceted undertakings. Since the establishment, the university made remarkable and multifarious progress in training, research and community service provision and increasing the number of qualified manpower by training at undergraduate and post graduate level.</p>
            </div>
           
        </div>
    </section> -->
    <section class="about">
        <div class="container">
            <div class="row align-items-center">
            
                    <div class="pic">
                        <img src="./assets/meu.jpg" class="img-fluid" alt="">
                    </div>
                
                <div class="col-md-6">
                    <div class="text">
                        <h2>About Us</h2>
                        <p>Mettu University (MaU) is a third-generation public university in Ethiopia, established in 2011. Located in the southwestern part of Ethiopia, in the Oromia Regional State, Mettu University occupies over 350 hectares of land, providing optimal facilities and infrastructure to enhance its multifaceted undertakings.</p>
                        <p>Since its establishment, the university has made remarkable progress in training, research, and community service provision. It offers undergraduate and postgraduate programs and is committed to meeting the country's growing demand for high-level professionals.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
<!--
    <section class="why-elearning">
        <h2>Why E-Learning for Mettu University?</h2>
        <div class="containerE">
          <div class="etext">
            <p>
                Embrace the future of education with Mettu University's E-Learning platform. Discover the key reasons why e-learning is an essential part of our educational vision.
            </p>
            <ul>
                <li><span>Flexibility:</span> Learn at your own pace, anytime, anywhere.</li>
                <li><span>Accessibility:</span> Break down geographical barriers and ensure education for all.</li>
                <li><span>Collaboration:</span> Foster virtual teamwork and interaction.</li>
                <li><span>Efficiency:</span>Receive up-to-date content with seamless delivery.</li>
            </ul>
            <div class="cta">
                <a href="#" class="button">Get Started</a>
            </div>
          </div> 
          <div class="eimg">
            <img src="./assets/elearn.jpg" alt="elearning">
          </div> 
           
        </div>
        
    </section>
-->
<section class="why-elearning">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="etext">
                    <h2>Why E-Learning for Mettu University?</h2>
                    <p>Embrace the future of education with Mettu University's E-Learning platform. Discover the key reasons why e-learning is an essential part of our educational vision.</p>
                    <ul>
                        <li><span>Flexibility:</span> Learn at your own pace, anytime, anywhere.</li>
                        <li><span>Accessibility:</span> Break down geographical barriers and ensure education for all.</li>
                        <li><span>Collaboration:</span> Foster virtual teamwork and interaction.</li>
                        <li><span>Efficiency:</span> Receive up-to-date content with seamless delivery.</li>
                    </ul>
                    <div class="cta">
                        <a href="#" class="button">Get Started</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="eimg">
                    <img src="./assets/elearn.jpg" class="img-fluid" alt="elearning">
                </div>
            </div>
        </div>
    </div>
</section>

    <section class="developer-team">
        <h1>Developer Team</h1>
        <div class="team-members">
            <div class="team-member">
                <img src="./assets/team-member1.jpg" alt="Team Member 1">
                <h2>John Doe</h2>
                <p>Lead Developer</p>
            </div>
            <div class="team-member">
                <img src="./assets/team-member2.jpg" alt="Team Member 2">
                <h2>Jane Smith</h2>
                <p>Frontend Developer</p>
            </div>
            <div class="team-member">
                <img src="./assets/team-member1.jpg" alt="Team Member 1">
                <h2>John Doe</h2>
                <p>Lead Developer</p>
            </div>
            <div class="team-member">
                <img src="./assets/team-member2.jpg" alt="Team Member 2">
                <h2>Jane Smith</h2>
                <p>Frontend Developer</p>
            </div>
        </div>
    </section>
    <section class="testimonials">
        <div class="container">
            <h2 class="text-center">Testimonials</h2>
            <div id="testimonialCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row justify-content-center">
                            <div class="col-md-8 col-lg-6">
                                <div class="testimonial-item text-center">
                                    <img src="./assets/team-member2.jpg" class="img-fluid rounded-circle" alt="Testimonial 1">
                                    <p class="testimonial-text">"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris vehicula enim non suscipit suscipit. Nulla ac efficitur magna."</p>
                                    <h4 class="testimonial-author">John Doe</h4>
                                    <p class="testimonial-role">Student</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row justify-content-center">
                            <div class="col-md-8 col-lg-6">
                                <div class="testimonial-item text-center">
                                    <img src="./assets/team-member1.jpg" class="img-fluid rounded-circle" alt="Testimonial 2">
                                    <p class="testimonial-text">"Quisque et dolor et nulla vehicula commodo nec sit amet nunc. Nam auctor diam quis odio consequat viverra."</p>
                                    <h4 class="testimonial-author">Jane Smith</h4>
                                    <p class="testimonial-role">Alumnus</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Add more carousel items as needed -->
                </div>
                <!-- Carousel Controls -->
                <a class="carousel-control-prev" href="#testimonialCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#testimonialCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </section>
    
    <section class="services">
        <div class="container">
            <h2 class="text-center">Our Services</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="service-item">
                        <h3>Online Courses</h3>
                        <p>Access a wide range of online courses taught by expert instructors.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-item">
                        <h3>Interactive Learning</h3>
                        <p>Engage in interactive learning experiences to enhance your understanding.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-item">
                        <h3>Support & Resources</h3>
                        <p>Receive lifetime support and access to resources to aid your learning journey.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="faqs">
        <div class="container">
            <h2 class="text-center">Frequently Asked Questions</h2>
            <div class="accordion" id="accordionFaqs">
                <div class="card">
                    <div class="card-header" id="faqHeading1">
                        <h3 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                                It is real wok on our country?
                            </button>
                        </h3>
                    </div>
                    <div id="faqCollapse1" class="collapse show" aria-labelledby="faqHeading1" data-parent="#accordionFaqs">
                        <div class="card-body">
                           yes why not
                        </div>
                    </div>
                </div>
                <!-- Add more FAQ items as needed -->
            </div>
        </div>
    </section>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h3>Contact Us</h3>
                    <p>Mettu University<br>Address: [Insert Address]<br>Phone: [I</p>
                </div>
                <div class="col-md-6">
                    <h3>Connect with Us</h3>
                    <ul class="social-icons">
                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                        <!-- Add more social icons as needed -->
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    
        
    
    <!-- Bootstrap JS, jQuery, and any other library -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
</body>
</html>    
