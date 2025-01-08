<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "sql100.infinityfree.com";
$username = "if0_37486741";
$password = "clHO11mUrA1";
$dbname = "if0_37486741_artist_portfolio";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = '';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = 'user'; // Default role for a new user

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format";
    } else {
        // Check if the email already exists
        $emailCheckStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $emailCheckStmt->bind_param("s", $email);
        $emailCheckStmt->execute();
        $emailCheckStmt->store_result();

        if ($emailCheckStmt->num_rows > 0) {
            $error_message = "Email already registered.";
        } else {
            // Prepare the SQL statement to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $password, $role);

            if ($stmt->execute()) {
                echo "Registration successful!";
                header('Location: login.php');
                exit(); // Stop executing the script after redirect
            } else {
                $error_message = "Error: " . $stmt->error; // More specific error output
            }

            $stmt->close(); // Close the prepared statement
        }

        $emailCheckStmt->close(); // Close the email check statement
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Register</title>
</head>
<body>
<nav class="navbar navbar-expand-lg" style="background-color: #e3f2fd;">
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
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
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
          <li class="nav-item"><a class="nav-link" href="course.php">Courses</a></li>
          <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
          <li class="nav-item"><a class="nav-link" href="blog.php">Blog</a></li>
          <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
              <li class="nav-item"><a class="nav-link bg-danger rounded" href="admin.php">Admin</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Register</h2>
    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>
    <form action="register.php" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" name="register" class="btn btn-primary">Register</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
