<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Healthcare Service</title>
    <link rel="shortcut icon" href="./images/new-logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Include header section -->
    <?php require_once './include/header.php' ?>
    <div class="wrapper">
    <section id="services-section">
                <div class="container text-center py-5 my-5">
                    <h1>Our Services</h1>
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
        <?php require_once './include/footer.php'?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./js/index.js"></script>
</body>
</html>