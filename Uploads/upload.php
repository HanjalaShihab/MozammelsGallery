<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Upload Image</title>
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
<header>
    <nav class="navbar navbar-expand-lg" style="background-color: #e3f2fd;">
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
      </ul>
    </div>
  </div>
</nav>
</header>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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

// Handle the image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if 'artworkTitle', 'artworkImage', and 'description' are set
    if (isset($_POST['artworkTitle']) && isset($_FILES['artworkImage']) && isset($_POST['description'])) {
        $artworkTitle = $_POST['artworkTitle'];
        $medium = $_POST['medium'];
        $size = $_POST['size'];
        $category = $_POST['category'];
        $description = $_POST['description']; // Get the description
        $price = $_POST['price'];

        // Define the target directory
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["artworkImage"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an image
        $check = getimagesize($_FILES["artworkImage"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if the file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size (optional)
        if ($_FILES["artworkImage"]["size"] > 10000000) { // Limit file size to 10 MB
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif", "heic"])) {
            echo "Sorry, only JPG, JPEG, PNG, GIF & HEIC files are allowed.";
            $uploadOk = 0;
        }

        // Check if everything is okay to upload
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["artworkImage"]["tmp_name"], $target_file)) {
                // Prepare the SQL statement
                $sql = "INSERT INTO gallery (title, image_name, image_path, medium, size, category, description, created_at, price) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("ssssssss", $artworkTitle, htmlspecialchars(basename($_FILES["artworkImage"]["name"])), $target_file, $medium, $size, $category, $description, $price);
                    
                    if ($stmt->execute() === TRUE) {
                        echo "The file has been uploaded successfully";
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "Error preparing the statement: " . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "Please make sure you filled out all fields.";
    }
}

// Close the database connection
$conn->close();
?>
</body>
</html>
<?php
if ($_FILES['file']['name']) {
    // Directory where the image will be uploaded
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file is an image
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if ($check !== false) {
        // Move the uploaded image to the target directory
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            // Return the uploaded image URL in JSON format
            echo json_encode(['location' => $targetFile]);
        } else {
            // Return an error message
            echo json_encode(['error' => 'Failed to upload image']);
        }
    } else {
        echo json_encode(['error' => 'File is not an image']);
    }
}
?>
