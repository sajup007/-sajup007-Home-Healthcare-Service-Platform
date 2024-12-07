<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Home Healthcare Service</title>
    <link rel="shortcut icon" href="./images/new-logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Include header section -->
    <?php require_once './include/header.php' ?>
    <div class="wrapper">
        <section id="contact-section">
            <div class="container text-center py-5 my-5">
                <h1 class="display-4 mb-4">Contact Us</h1>
                <p class="lead mb-5">
                    We're here to help and answer any questions you might have. We look forward to hearing from you.
                </p>
                
                <div class="row g-4 mt-4">
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <i class="bi bi-geo-alt fs-1 text-primary mb-3"></i>
                                <h3 class="card-title h4">Address</h3>
                                <p class="card-text">
                                    123 Healthcare Street<br>
                                    Medical District<br>
                                    Kottayam, 50450
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <i class="bi bi-telephone fs-1 text-primary mb-3"></i>
                                <h3 class="card-title h4">Phone</h3>
                                <p class="card-text">
                                    Main: +91 1234567890<br>
                                    Emergency: +91 9876543210
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <i class="bi bi-envelope fs-1 text-primary mb-3"></i>
                                <h3 class="card-title h4">Email</h3>
                                <p class="card-text">
                                    info@hhs.org<br>
                                    help@hhs.org
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-5 shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title h4 mb-4">Operating Hours</h3>
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td>Monday - Friday:</td>
                                        <td>8:00 AM - 8:00 PM</td>
                                    </tr>
                                    <tr>
                                        <td>Saturday:</td>
                                        <td>9:00 AM - 5:00 PM</td>
                                    </tr>
                                    <tr>
                                        <td>Sunday:</td>
                                        <td>Closed</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td>Emergency Services:</td>
                                        <td>24/7</td>
                                    </tr>
                                </tbody>
                            </table>
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