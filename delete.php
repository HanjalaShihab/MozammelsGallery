<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Delete Images</title>
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
<nav class="navbar navbar-expand-lg bg-info">
  <div class="container-fluid">
    <!-- Left-aligned logo -->
    <a class="navbar-brand ms-5" href="#">
      Mozammel's Gallery <!-- Replace with your logo -->
    </a>

    <!-- Navbar toggler for mobile view -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Right-aligned navigation links and search icon -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
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
          <a class="nav-link" href="course.html">Courses</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="shop.html">Shop</a>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

<?php
// Database connection parameters
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

// Check if delete_ids are provided
if (isset($_POST['delete_ids'])) {
    $delete_ids = $_POST['delete_ids']; // Get array of selected IDs

    foreach ($delete_ids as $id) {
        $id = intval($id); // Sanitize input

        // Get the image path before deleting
        $sql = "SELECT image_path FROM gallery WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $image_path = $row['image_path']; // Get the full image path

            // Delete the image file from the server
            if (file_exists($image_path)) {
                unlink($image_path); // Remove the file
            }

            // Now delete the record from the database
            $sql = "DELETE FROM gallery WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
        }

        // Close the statement after each query
        $stmt->close();
    }

    echo "Selected images and their records have been deleted.";
} else {
    echo "No images selected for deletion.";
}

     // Check if delete_ids are provided
if (isset($_POST['delete_ids'])) {
    $delete_ids = $_POST['delete_ids']; // Get array of selected IDs

    foreach ($delete_ids as $id) {
        $id = intval($id); // Sanitize input

        // Get the image path before deleting
        $sql = "SELECT image FROM blogs WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $image_path = $row['image']; // Get the full image path

            // Delete the image file from the server
            if (file_exists($image_path)) {
                unlink($image_path); // Remove the file
            }

            // Now delete the blog record from the database
            $sql = "DELETE FROM blogs WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
        }

        // Close the statement after each query
        $stmt->close();
    }

    echo "Selected blogs and their records have been deleted.";
} else {
    echo "No blogs selected for deletion.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    // Database connection
    $servername = "sql100.infinityfree.com";
    $username = "if0_37486741";
    $password = "clHO11mUrA1";
    $dbname = "if0_37486741_artist_portfolio";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = $_POST['id'];
    
    // Delete item from database
    $sql = "DELETE FROM shop_items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect back to shop page after deletion
        header("Location: shop.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
