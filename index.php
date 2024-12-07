<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Home Healthcare Service</title>
    <link rel="shortcut icon" href="./images/new-logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
</head>
<body>

    <div class="wrapper">
    <!-- Include header section -->
        <?php require_once './include/header.php' ?>

        <main class="main-section">
            <section id="home-section">
                <div class="container text-center py-5 my-5">
                    <h1 class="mt-5 pt-5">Welcome to Home Healthcare Service</h1>
                    <h6>Your health, our priority. Access medical care from the comfort of your home.</h6>
                    <a href="./register.php" class="btn btn-lg cta-button mb-5">Book an Appointment</a>
                </div>
            </section>

            <section>
                <div class="container text-center">
                    <h3 class="mt-5 pt-5 pb-3">We Offer Different Services to Improve Your Health</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#0B666A" class="bi bi-heart-pulse" viewBox="0 0 16 16">
                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053.918 3.995.78 5.323 1.508 7H.43c-2.128-5.697 4.165-8.83 7.394-5.857q.09.083.176.171a3 3 0 0 1 .176-.17c3.23-2.974 9.522.159 7.394 5.856h-1.078c.728-1.677.59-3.005.108-3.947C13.486.878 10.4.28 8.717 2.01zM2.212 10h1.315C4.593 11.183 6.05 12.458 8 13.795c1.949-1.337 3.407-2.612 4.473-3.795h1.315c-1.265 1.566-3.14 3.25-5.788 5-2.648-1.75-4.523-3.434-5.788-5"/>
                        <path d="M10.464 3.314a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8H.5a.5.5 0 0 0 0 1H4a.5.5 0 0 0 .416-.223l1.473-2.209 1.647 4.118a.5.5 0 0 0 .945-.049l1.598-5.593 1.457 3.642A.5.5 0 0 0 12 9h3.5a.5.5 0 0 0 0-1h-3.162z"/>
                    </svg>
                    <p class="mt-2">Experience convenient and personalized home healthcare services. We offer a wide range of services to meet your needs.</p>
                </div>
            </section>

            <section id="services-section">
                <div class="container text-center py-5 my-5">
                    <h2>Our Services</h2>
                    <div class="row row-cols-1 row-cols-md-3 g-4 my-5">
                        <div class="col">
                            <div class="card h-100 rounded-4 shadow-lg">
                                <div class="d-flex flex-column h-100">
                                    <h3 class="mt-5 pt-5 display-6 lh-1 fw-bold">Appointment Booking</h3>
                                    <p class="mx-5 mb-5">Book your home healthcare appointments conveniently online. Our user-friendly booking system allows you to select your preferred date and time, choose a suitable healthcare provider, and receive timely appointment confirmations.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 rounded-4 shadow-lg">
                                <div class="d-flex flex-column h-100">
                                    <h3 class="mt-5 pt-5 display-6 lh-1 fw-bold">Home Checkups</h3>
                                    <p class="mx-5 mb-5">Our dedicated team of healthcare professionals is committed to providing comprehensive home checkups tailored to your specific needs. We understand that visiting a healthcare facility can be inconvenient. That's why we offer the convenience and comfort of home-based checkups.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 rounded-4 shadow-lg">
                                <div class="d-flex flex-column h-100">
                                    <h3 class="mt-5 pt-5 display-6 lh-1 fw-bold">Online Payments</h3>
                                    <p class="mx-5 mb-5">We offer a variety of secure and convenient payment options to make it easy for you to pay for our services. Your payment information is always handled securely. We use industry-standard encryption to protect your personal and financial data.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="container text-center">
                    <h3 class="mt-5 pt-5 pb-3">We Maintain A Sterile Environment At Every Residence</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#0B666A" class="bi bi-heart-pulse" viewBox="0 0 16 16">
                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053.918 3.995.78 5.323 1.508 7H.43c-2.128-5.697 4.165-8.83 7.394-5.857q.09.083.176.171a3 3 0 0 1 .176-.17c3.23-2.974 9.522.159 7.394 5.856h-1.078c.728-1.677.59-3.005.108-3.947C13.486.878 10.4.28 8.717 2.01zM2.212 10h1.315C4.593 11.183 6.05 12.458 8 13.795c1.949-1.337 3.407-2.612 4.473-3.795h1.315c-1.265 1.566-3.14 3.25-5.788 5-2.648-1.75-4.523-3.434-5.788-5"/>
                        <path d="M10.464 3.314a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8H.5a.5.5 0 0 0 0 1H4a.5.5 0 0 0 .416-.223l1.473-2.209 1.647 4.118a.5.5 0 0 0 .945-.049l1.598-5.593 1.457 3.642A.5.5 0 0 0 12 9h3.5a.5.5 0 0 0 0-1h-3.162z"/>
                    </svg>
                    <p class="mt-2">Our commitment to hygiene extends beyond our clinic. We ensure a sterile environment in every home we visit, safeguarding your health and well-being.</p>
                </div>
            </section>

            <section id="doctors-section">
                <div class="container text-center py-5 my-5">
                    <h2>Our Top Doctors</h2>
                    <div class="row row-cols-1 row-cols-md-3 g-4 my-5">
                        <div class="col">
                            <div class="card h-100 rounded-4 shadow-lg">
                                <div class="d-flex flex-column h-100">
                                    <div class="card-body">
                                        <img src="./images/sreehari_cs.jpg" style="border-radius: 50%; width: 170px;">
                                        <h3 class="mt-4 display-6 lh-1 fw-bold">General Practitioner</h3>
                                        <h4>Dr. Sreehari C S</h4>
                                        <p class="mx-5">Dr. Sreehari C S provides general medical consultations and treatments.</p>
                                    </div>
                                    <div class="card-footer">
                                        <small class="text-body-secondary">sreehari.doc@hhs.org <br> +91 4325621734</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 rounded-4 shadow-lg">
                                <div class="d-flex flex-column h-100">
                                    <div class="card-body">
                                        <img src="./images/saju_p_varghese.jpg" style="border-radius: 50%; width: 170px;">
                                        <h3 class="mt-4 display-6 lh-1 fw-bold">Ophthalmologist</h3>
                                        <h4>Dr. Saju P Varghese</h4>
                                        <p class="mx-5">Dr. Saju P Varghese specializes in eye care and vision correction surgeries.</p>
                                    </div>
                                    <div class="card-footer">
                                        <small class="text-body-secondary">saju.doc@hhs.org <br> +91 7561637123</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 rounded-4 shadow-lg">
                                <div class="d-flex flex-column h-100">
                                    <div class="card-body">
                                        <img src="./images/liam_smith.jpg" style="border-radius: 50%; width: 170px;">
                                        <h3 class="mt-4 display-6 lh-1 fw-bold">Orthopedic Surgeon</h3>
                                        <h4>Dr. Liam Smith</h4>
                                        <p class="mx-5">Dr. Liam Smith has over 15 years of experience in orthopedic surgeries.</p>
                                    </div>
                                    <div class="card-footer">
                                        <small class="text-body-secondary">liamsmith@hhs.org <br> +91 7452516429</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="more-doctors-btn btn btn-lg" href="./doctor_profiles.php">View More Doctors >></a>
                </div>
            </section>

            
            <section>
                <div class="container text-center">
                    <h3 class="mt-5 pt-5 pb-3">We Are Always Ready To Help You And Your Family</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#0B666A" class="bi bi-heart-pulse" viewBox="0 0 16 16">
                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053.918 3.995.78 5.323 1.508 7H.43c-2.128-5.697 4.165-8.83 7.394-5.857q.09.083.176.171a3 3 0 0 1 .176-.17c3.23-2.974 9.522.159 7.394 5.856h-1.078c.728-1.677.59-3.005.108-3.947C13.486.878 10.4.28 8.717 2.01zM2.212 10h1.315C4.593 11.183 6.05 12.458 8 13.795c1.949-1.337 3.407-2.612 4.473-3.795h1.315c-1.265 1.566-3.14 3.25-5.788 5-2.648-1.75-4.523-3.434-5.788-5"/>
                        <path d="M10.464 3.314a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8H.5a.5.5 0 0 0 0 1H4a.5.5 0 0 0 .416-.223l1.473-2.209 1.647 4.118a.5.5 0 0 0 .945-.049l1.598-5.593 1.457 3.642A.5.5 0 0 0 12 9h3.5a.5.5 0 0 0 0-1h-3.162z"/>
                    </svg>
                    <p class="mt-2">Our dedicated team is always available to provide compassionate and reliable care for you and your loved ones.</p>
                </div>
            </section>

            <section>
                <div class="container text-center py-5 my-5">
                    <div class="row row-cols-1 row-cols-md-3 g-4 my-5">
                        <div class="col">
                            <div class="card h-100 rounded-4 shadow-lg">
                                <div class="d-flex flex-column h-100">
                                    <h3 class="mt-5 pt-5 display-6 lh-1 fw-bold">Emergency Help</h3>
                                    <p class="mx-5 mb-5">Our emergency response team consists of highly trained healthcare professionals 
                                        who are committed to providing prompt and effective care. We prioritize patient safety and well-being 
                                        in every situation.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 rounded-4 shadow-lg">
                                <div class="d-flex flex-column h-100">
                                    <h3 class="mt-5 pt-5 display-6 lh-1 fw-bold">Modern Pharmacy</h3>
                                    <p class="mx-5 mb-5">We can help you manage your medications and ensure you receive timely refills. We prioritize medication safety and follow strict protocols to prevent errors.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 rounded-4 shadow-lg">
                                <div class="d-flex flex-column h-100">
                                    <h3 class="mt-5 pt-5 display-6 lh-1 fw-bold">Medical Treatment</h3>
                                    <p class="mx-5 mb-5">We are dedicated to providing the highest quality of care to our patients. Our team of skilled healthcare 
                                        professionals is committed to following evidence-based practices and adhering to strict safety standards.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <section>
                <div class="container text-center">
                    <h3>We Are Always Ready To Help You.
                        <br>
                        Book An Appointment Now!
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#0B666A" class="bi bi-heart-pulse" viewBox="0 0 16 16">
                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053.918 3.995.78 5.323 1.508 7H.43c-2.128-5.697 4.165-8.83 7.394-5.857q.09.083.176.171a3 3 0 0 1 .176-.17c3.23-2.974 9.522.159 7.394 5.856h-1.078c.728-1.677.59-3.005.108-3.947C13.486.878 10.4.28 8.717 2.01zM2.212 10h1.315C4.593 11.183 6.05 12.458 8 13.795c1.949-1.337 3.407-2.612 4.473-3.795h1.315c-1.265 1.566-3.14 3.25-5.788 5-2.648-1.75-4.523-3.434-5.788-5"/>
                        <path d="M10.464 3.314a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8H.5a.5.5 0 0 0 0 1H4a.5.5 0 0 0 .416-.223l1.473-2.209 1.647 4.118a.5.5 0 0 0 .945-.049l1.598-5.593 1.457 3.642A.5.5 0 0 0 12 9h3.5a.5.5 0 0 0 0-1h-3.162z"/>
                    </svg>
                    <p class="mt-2">Contact us today to learn more about our medical treatment services and how we can help you achieve optimal health and well-being.</p>
                    <a href="./register.php" class="btn btn-lg cta-button mb-5">Book an Appointment</a>
                </div>
            </section>
        </main>

        <?php require_once './include/footer.php'?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./js/index.js"></script>
</body>
</html>