<?php
session_start();

$servername = "sql100.infinityfree.com"; 
$username = "if0_37486741"; 
$password = "clHO11mUrA1"; 
$dbname = "if0_37486741_artist_portfolio";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    // Validate inputs (Optional but recommended)
    if (!empty($name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Prepare SQL statement
        $sql = "INSERT INTO subscribers (name, email) VALUES (?, ?)";

        // Initialize the prepared statement
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters to the SQL query
            $stmt->bind_param("ss", $name, $email);

            // Execute the query
            if ($stmt->execute()) {
                header('Location: index.php');
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "Please provide a valid name and email.";
    }
}

// Fetch images from the database
$query = "SELECT image_path FROM gallery ORDER BY created_at DESC LIMIT 15";
$result = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mohammad Mozammel Hoq</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/5295537e26.js" crossorigin="anonymous"></script>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap"
    rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      font-family: 'Averia Serif Libre', serif;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    .navbar {
      background-image: url('images/2.jpg');
      background-size: cover;
      background-position: center;
      width: 100%;
      height: 150px;
    }

    @media only screen and (max-width: 989px){
        .navbar {
            height: auto !important;
        }

        .admin {
            width: 80px;
            height: 40px;
            text-align: center;
        }
    }
    

    .navbar-brand {
      font-size: 2rem;
      font-weight: bold;
      color: #ffffff;
    }

    .navbar-nav .nav-link {
      color: #ffffff;
      font-size: 18px;
    }

    .navbar-nav .nav-link:hover {
      color: #0d00ff;
    }

    .navbar-toggler {
      border: none;
    }

    .navbar-toggler-icon {
      color: #ffffff;
    }

    .navbar-collapse {
      justify-content: flex-end;
    }

    .navbar-nav {
      margin-right: 1rem;
    }

    .navbar-nav form {
      margin-right: 1rem;
    }

    .btn {
      background-color: #007bff;
      color: #fff;
      border: none;
    }

    .btn:hover {
      background-color: #0056b3;
    }

    main {
      padding: 2rem;
      text-align: center;
    }

    footer {
      background-color: #f8f9fa;
      padding: 1rem;
      text-align: center;
    }

    footer p {
      margin: 0;
    }

    .dropdown:hover .dropdown-menu {
      display: block;
    }

    .dropdown-menu {
      display: none;
    }

    .lead {
      font-size: 1.2rem;
      margin-top: 50px;
      line-height: 1.9em;
      text-align: left;
    }

    .about-sec img {
      border-radius: 10px;
    }

    .contact-sec {
      background-color: #3b565e;
      color: white;
      padding: 50px 0px;
    }

    .contact-sec a {
      color: rgb(255, 255, 255);
      text-decoration: none;
    }

    .contact-sec a:hover {
      color: #39c7ff;
      text-decoration: underline;
    }

    .contact-sec .social-icons a {
      color: rgb(255, 45, 45);
      text-decoration: none;
      margin-right: 10px;
    }

    .contact-sec .social-icons a:hover {
      color: #2e43ff;
    }

    .sendInfo {
      margin-top: 100px;
      background-image: url('images/sendInfo.jpeg');
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
      padding: 60px;
      color: white;
    }

    .sendInfo input {
      padding: 10px;
    }

    .sendInfo input[type="submit"]:hover {
      background-color: rgb(8, 185, 255);
    }

    .smallGallery {
      margin-top: 150px;
    }

    .smallGallery h3 {
      margin-bottom: 30px;
    }

    /* Image Gallery Styles */
    .slideshow-container {
      position: relative;
      max-width: 180px;
      height: 800px;
      overflow: hidden;
    }

    .slideshow {
      display: flex;
      flex-direction: column;
    }

    .image-container {
      position: relative;
      margin-bottom: 15px; /* Adjust space between images */
    }

    .slideImg {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border: 1px solid black;
      display: block;
      transition: transform 0.3s ease;
      cursor: pointer;
    }

    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      opacity: 0;
      transition: opacity 0.3s ease;
      display: flex;
      justify-content: center;
      align-items: center;
      pointer-events: none;
    }

    .image-container:hover .overlay {
      opacity: 1;
    }


    .overlay i {
      font-size: 24px;
      color: white;
    }

    .showImg img {
      width: 100%;
      height: 720px;
    }

    .userProfile {
      border-radius: 100%;
      padding: 10px 17px !important;
      background-color: #a9a9a9;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg bg-info">
    <div class="container-fluid">
      <!-- Left-aligned logo -->
      <a class="navbar-brand ms-5" href="index.php">
        Mozammel's Gallery
      </a>

      <!-- Navbar toggler for mobile view -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Right-aligned navigation links and search icon -->
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <!-- Navigation Links -->
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
          </li>
          <!-- Gallery Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="gallery.php" id="galleryDropdown">
              Gallery
            </a>
            <ul class="dropdown-menu" aria-labelledby="galleryDropdown">
              <li><a class="dropdown-item" href="drawings.php">Drawings</a></li>
              <li><a class="dropdown-item" href="stilllife.php">Still Life</a></li>
              <li><a class="dropdown-item" href="figure_paintings.php">Figure Paintings</a></li>
              <li><a class="dropdown-item" href="landscape.php">Landscape</a></li>
              <li><a class="dropdown-item" href="portrait.php">Portrait</a></li>
            </ul>
          </li>
          <!-- Other Links -->
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
            <a class="nav-link" href="contact.php">Contact</a>
          </li>

          <!-- Admin Link -->
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <li class="nav-item admin">
            <a class="nav-link bg-danger rounded" href="admin.php">Admin</a>
          </li>
          <?php endif; ?>

          <!-- User Profile -->
          <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'user'): ?>
          <li class="nav-item dropdown">
            <a class="nav-link userProfile" href="#" id="userDropdown">
              <i class="fa-regular fa-user"></i>
            </a>
            <ul class="dropdown-menu" aria-labelledby="userDropdown" style="position: absolute; top: 46px; right: 0px">
              <li><a class="dropdown-item" href="logout.php">Log out</a></li>
            </ul>
          </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Login/Register Links -->
  <?php if (!isset($_SESSION['role'])): ?>
  <div class="container text-center p-1">
    <div class="user">
      <a href="login.php">Login</a>
      |
      <a href="register.php">Register</a>
    </div>
  </div>
  <?php endif; ?>

  <!-- About Section -->
  <div class="container-fluid text-center mb-5 about-sec">
    <h1 class="display-6 text-info" style="margin-top: 60px; margin-bottom: 30px;">About Mohammad Mozammel Hoq</h1>
    <div style="display: flex; justify-content: center; align-items: center;">
      <div class="hr" style="width: 200px; height: 2px; background-color: rgb(255, 106, 43);margin-bottom: 80px"></div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <p class="lead">I am a professional artist and I have been painting for over 10 years. I specialize in oil
            paintings and watercolor paintings. I have a passion for creating beautiful artwork that inspires others. My
            work has been featured in galleries and exhibitions around the world. I am dedicated to sharing my love of
            art with others and helping them discover their own creativity.</p>
        </div>
        <div class="col-lg-6">
          <img src="images/uncle.jpg" alt="Profile picture" class="img-fluid" style="max-width: 100%; height: auto;">
        </div>
      </div>
    </div>
  </div>

  <!-- Small Gallery Section -->
  <div class="smallGallery">
    <div class="container text-center">
      <h3 class="text-info">Recent works!</h3>
      <div style="display: flex; justify-content: center; align-items: center;">
        <div class="hr" style="width: 120px; height: 2px; background-color: rgb(255, 106, 43);margin-bottom: 80px">
        </div>
      </div>
      <div class="row">
        <!-- Display Selected Image -->
        <div class="col-md-10 d-flex justify-content-center align-items-center">
          <div class="showImg">
            <img src="images/2.jpg" alt="Selected Image">
          </div>
        </div>
        <!-- Image Thumbnails -->
        <div class="col-md-2">
          <div class="slideshow-container">
            <div class="slideshow" id="slideshow">
              <?php
              if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                      echo "<div class='image-container'>";
                      echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='Gallery Image' class='slideImg'>";
                      echo "<div class='overlay'>";
                      echo "<i class='fa-solid fa-magnifying-glass-plus'></i>";
                      echo "</div>";
                      echo "</div>";
                  }
              } else {
                  echo "<p>No images available in the gallery.</p>";
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Subscription Section -->
  <div class="sendInfo text-center">
    <div class="container">
      <div style="max-width: 500px; margin: 40px auto;">
        <h5 style="font-size: 22px !important;">Click the button below for your FREE video and to start receiving the
          FREE monthly themed videos on Mastering Composition.</h5>
      </div>
      <div class="inputs" style="display: flex; flex-direction: column; max-width: 500px; height: auto; margin: auto;">
        <form action="" method="post">
          <input class="form-control mb-3" name="name" type="text" id="name" placeholder="Your name..." autocomplete="off">
          <input class="form-control mb-3" name="email" type="email" id="email" placeholder="Your email..."
            autocomplete="off">
          <input class="form-control mb-3" name="submit" type="submit">
        </form>
      </div>
      <p style="font-size: 20px !important; color: red;">We never share or sell our email list to anyone, ever.</p>
    </div>
  </div>

  <!-- Contact Section -->
  <div class="container-fluid contact-sec text-center">
    <div class="container p-4">
      <div class="logo mb-3" style="text-align: left;">
        <h2 style="font-family: 'Courier New', Courier, monospace;">Mozammel's Gallery</h2>
      </div>
      <div class="row" style="text-align: left;">
        <div class="col-md-6" style="line-height: 2em; font-size: 17px;">
          <p style="max-width: 430px; letter-spacing: .8px;">I specialize in oil paintings and watercolor paintings.
            I have a passion for creating beautiful artwork that inspires others.
            My work has been featured in galleries and exhibitions around the world.
            I am dedicated to sharing my love of art with others and helping them discover their own creativity.</p>
        </div>
        <div class="col-md-2" style="line-height: 1em;">
          <div class="mb-4">
            <p>Gallery:</p>
            <p><a href="drawings.php">Drawings</a></p>
            <p><a href="landscape.php">Landscape</a></p>
          </div>
          <div class="mb-4">
            <p>Courses:</p>
            <p><a href="course.php">Oil Painting</a></p>
            <p><a href="course.php">Watercolor Painting</a></p>
          </div>
          <div class="mb-2">
            <p>Shop:</p>
            <p><a href="shop.php">Paintings</a></p>
            <p><a href="shop.php">Art Supplies</a></p>
          </div>
        </div>
        <div class="col-md-2">
          <p><a href="about.html">About Mozammel</a></p>
          <p><a href="contact.php">Contact</a></p>
        </div>
        <div class="col-md-2">
          <p>Follow me on social media:</p>
          <div class="social-icons">
            <a href="https://www.facebook.com/md.mozammelh0q" target="_blank"><i class="fab fa-facebook fa-2x"></i></a>
            <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram fa-2x"></i></a>
            <a href="https://www.twitter.com" target="_blank"><i class="fab fa-twitter fa-2x"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <p>&copy; 2024 Mohammad Mozammel Hoq</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

  <!-- JavaScript -->
  <script>
    function showImg(event) {
      // Get the image source from the clicked image
      const imgSrc = event.target.src;

      // Get the container where the image should be displayed
      const showImgContainer = document.querySelector('.showImg');

      // Set the clicked image as the content of the showImg container
      showImgContainer.innerHTML = `<img src="${imgSrc}" alt="Selected Image">`;
    }

    // Attach event listeners to all images in the slideshow
    function attachEventListeners() {
      const slideshowImages = document.querySelectorAll('.slideImg');
      slideshowImages.forEach(image => {
        image.addEventListener('click', showImg);
      });
    }

    // Clone the image containers
    function cloneImages() {
      const slideshow = document.getElementById('slideshow');
      const originalContainers = document.querySelectorAll('.image-container');

      originalContainers.forEach(container => {
        const clonedContainer = container.cloneNode(true);
        slideshow.appendChild(clonedContainer);
      });
    }

    // Initialize functions after content is loaded
    document.addEventListener('DOMContentLoaded', () => {
      cloneImages();
      attachEventListeners();
    });
  </script>

  <!-- Add this inside your <head> tag -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const slideshowContainer = document.querySelector('.slideshow-container');
    let scrollAmount = 0;
    let maxScroll = slideshowContainer.scrollHeight - slideshowContainer.clientHeight;
    const scrollSpeed = 20; // Adjust speed if necessary

    function autoScroll() {
      if (scrollAmount < maxScroll) {
        scrollAmount += 2; // Adjust scroll step
        slideshowContainer.scrollTop = scrollAmount;
      } else {
        scrollAmount = 0; // Reset scroll to top when reaching the end
      }
    }

    setInterval(autoScroll, scrollSpeed); // Repeats autoScroll every 50ms
  });
</script>

</body>

</html>
