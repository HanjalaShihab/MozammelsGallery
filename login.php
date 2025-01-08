<?php
session_start(); // Start session to store user data

// Database connection settings
$servername = "sql100.infinityfree.com";
$username = "if0_37486741";
$password = "clHO11mUrA1";
$dbname = "if0_37486741_artist_portfolio";

// Create connection to MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a query to check if the email exists
    $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify if the user exists and the password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Store user details in session
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // 'admin' or 'user'

        // Redirect to home page or admin page based on role
        if ($user['role'] === 'admin') {
            header('Location: index.php');
        } else {
            header('Location: index.php');
        }
        exit();
    } else {
        // Invalid credentials message
        $error = "Invalid email or password!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
    <style>
    .dropdown:hover .dropdown-menu {
      display: block;
    }

    .dropdown-menu {
      display: none;
    }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg" style="background-color: #e3f2fd;">
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
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="index.php">Home</a>
          </li>
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
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link bg-danger rounded" href="admin.php">Admin</a>
                    </li>
                <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

<div class="container mt-5">
    <h2>Login</h2>

    <!-- Display error message if login fails -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <!-- Show Password Checkbox -->
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="showPassword" onclick="togglePassword()">
            <label class="form-check-label" for="showPassword">Show Password</label>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// JavaScript to toggle password visibility
function togglePassword() {
    var passwordField = document.getElementById('password');
    if (passwordField.type === 'password') {
        passwordField.type = 'text'; // Show password
    } else {
        passwordField.type = 'password'; // Hide password
    }
}
</script>
</body>
</html>
