<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "sql100.infinityfree.com";
$username = "if0_37486741";
$password = "clHO11mUrA1";
$dbname = "if0_37486741_artist_portfolio";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch courses from the database
$query = "SELECT id, title, description FROM courses ORDER BY uploaded_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Courses - Mohammad Mozammel Hoq</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg mb-4" style="background-color: #e3f2fd;">
    <div class="container-fluid">
      <!-- Left-aligned logo -->
      <a class="navbar-brand ms-5" href="#">
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
                        <a class="nav-link bg-danger rounded text-white" href="admin.php">Admin</a>
                    </li>
                <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-2">
    <h2 class="text-center mb-4">Available Courses</h2>
    <div class="row">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="col-md-4 mb-4">
            <div class="card">
              <div class="card-body">
                <!-- Course Title -->
                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                <!-- Course Description -->
                <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                <!-- Learn More Button -->
               <!-- <a href="courseDetails.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Learn More</a> -->
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center">No courses available at the moment.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
          integrity="sha384-qQ0G0gtQxM6vCUBb+tFbfI5YoFtQUoE0w81Nz4M40nc8gM09ZrVtXlqmW/XfW9Ef" 
          crossorigin="anonymous"></script>
</body>

</html>

<?php
// Close the connection
$conn->close();
?>
