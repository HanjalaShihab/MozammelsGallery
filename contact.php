<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/5295537e26.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
    <title>Contact</title>
    <style>
        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-menu {
            display: none;
        }

        main .container .row .picture {
            max-width: 600px;
            height: auto;
        }

        main .container .row .picture img {
            width: 100%;
            height: 100%;
            border-radius: 10px;
        }

        footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #bffffa;
            padding: 1rem;
            text-align: center;
        }

        footer p {
            margin: 0;
        }

        .userProfile {
            border-radius: 100%;
            padding: 10px 17px !important;
            background-color: #a9a9a9;
        }

        .error {
            background: #E99A9A;
            color: #C80000;
            padding: 15px 10px;
            border-radius: 5px;
        }

        .success {
            background: #97E8A9;
            color: #00C82C;
            padding: 15px 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg" style="margin-bottom: 50px;background-color: #e3f2fd;">
        <div class="container-fluid">
            <a class="navbar-brand ms-5" href="index.php">
        Mozammel's Gallery
      </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="gallery.php" id="galleryDropdown">Gallery</a>
                        <ul class="dropdown-menu" aria-labelledby="galleryDropdown">
                            <li><a class="dropdown-item" href="drawings.php">Drawings</a></li>
                            <li><a class="dropdown-item" href="stilllife.php">Still Life</a></li>
                            <li><a class="dropdown-item" href="figure_paintings.php">Figure Paintings</a></li>
                            <li><a class="dropdown-item" href="landscape.php">Landscape</a></li>
                            <li><a class="dropdown-item" href="portrait.php">Portrait</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="course.php">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blog.php">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.html">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contact.php">Contact</a>
                    </li>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link bg-danger rounded text-white" href="admin.php">Admin</a>
                    </li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'user'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link userProfile" href="#" id="galleryDropdown">
                            <i class="fa-regular fa-user"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="galleryDropdown"
                            style="position: absolute; top: 46px; right: 0px">
                            <li><a class="dropdown-item" href="logout.php">Log out</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <main style="padding: 30px;">
        <section class="container">
            <div class="row">
                <div class="col-md-8">
                    <h5>Email: </h5>
                    <p style="color: #b5606e; font-size: 18px">mozammelhoqmon@gmail.com</p>
                    <div class="picture">
                        <img src="images/uncle.jpg" alt="Picture">
                    </div>
                </div>
                <div class="col-md-4">
                    <h2>Get in Touch</h2>
                    <p>Fill out the form below to get in touch with us.</p>

                    <!-- Correcting error and success message display -->
                    <?php if (isset($_GET['error'])): ?>
                    <p class="error"><?=htmlspecialchars($_GET['error']);?></p>
                    <?php endif; ?>

                    <?php if (isset($_GET['success'])): ?>
                    <p class="success"><?=htmlspecialchars($_GET['success']);?></p>
                    <?php endif; ?>

                    <form id="contactForm" action="contact.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" id="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" name="message" id="message" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                    <?php
                    use PHPMailer\PHPMailer\PHPMailer;
                    use PHPMailer\PHPMailer\Exception;

                    require 'PHPMailer/src/Exception.php';
                    require 'PHPMailer/src/PHPMailer.php';
                    require 'PHPMailer/src/SMTP.php';

                    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
                        isset($_POST['name'], $_POST['email'], $_POST['subject'], $_POST['message'])) {

                        $name = htmlspecialchars(trim($_POST['name']));
                        $email = htmlspecialchars(trim($_POST['email']));
                        $subject = htmlspecialchars(trim($_POST['subject']));
                        $message = htmlspecialchars(trim($_POST['message']));

                        $mail = new PHPMailer(true);

                        try {
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'hanjalashihab1@gmail.com'; // Your email
                            $mail->Password = 'xmog bvkw suvo xkpq'; // Your app-specific password
                            $mail->SMTPSecure = 'ssl';
                            $mail->Port = 465;

                            $mail->setFrom($email, $name);
                            $mail->addAddress('hanjalashihab1@gmail.com');

                            $mail->isHTML(true);
                            $mail->Subject = $subject;
                            $mail->Body = "
                                <h3>Contact form submission</h3>
                                <p><strong>Name:</strong> $name</p>
                                <p><strong>Email:</strong> $email</p>
                                <p><strong>Subject:</strong> $subject</p>
                                <p><strong>Message:</strong> $message</p>
                            ";

                            $mail->send();
                            header('Location: contact.php?success=Message+sent+successfully');
                        } catch (Exception $e) {
                            header('Location: contact.php?error=Message+could+not+be+sent.+Please+try+again.');
                        }
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-RHtL/QAIFFUJHvvj54qvcYAkAzO8Wc9bB07VPi3pksuJHCkOnkL6x9l5M/NH3cGi"
        crossorigin="anonymous"></script>

         <footer>
           <p>&copy; 2024 Mohammad Mozammel Hoq</p>
         </footer>
</body>

</html>
