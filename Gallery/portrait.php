<?php
session_start();

$servername = "sql100.infinityfree.com";
$username = "if0_37486741";
$password = "clHO11mUrA1";
$dbname = "if0_37486741_artist_portfolio";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$category = "Portrait";
$stmt = $conn->prepare("SELECT id,title, image_name, image_path, medium, size, category FROM gallery WHERE category = ?");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/5295537e26.js" crossorigin="anonymous"></script>
    <title>Portrait Gallery</title>
    <style>
        .gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }
        .image-container {
            margin: 15px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
        }
        .image-container img {
            max-width: 200px;
            height: auto;
            border-radius: 4px;
        }

        .image-container p {
            text-align: left;
        }

        .delete-form {
            margin-top: 20px;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-menu {
            display: none;
        }

        .userProfile{
        border-radius: 100%;
        padding: 10px 17px !important;
        background-color: #a9a9a9;
    }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-info">
    <div class="container-fluid">
    <!-- Left-aligned logo -->
    <a class="navbar-brand ms-5" href="index.php">
        Mozammel's Gallery
      </a>

    <!-- Navbar toggler for mobile view -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
              <li><a class="dropdown-item active" href="portrait.php">Portrait</a></li>
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

                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'user'): ?>
                    <li class="nav-item dropdown">
                    <a class="nav-link userProfile" href="#" id="galleryDropdown">
                      <i class="fa-regular fa-user"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="galleryDropdown" style="position: absolute; top: 46px; right: 0px">
                        <li><a class="dropdown-item" href="logout.php">Log out</a></li>
                    </ul>
                    </li>
                <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

    <div class="container">
        <h1 class="text-center my-4"><?= htmlspecialchars($category) ?> Gallery</h1>
        <div class="gallery">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='image-container'>";
                    echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['title']) . "'>";
                    echo "<p><strong>" . htmlspecialchars($row['title']) . "</strong> </p>";
                    echo "<p>" . htmlspecialchars($row['medium']) . "</p>";
                    echo "<p>Size: " . htmlspecialchars($row['size']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No artworks found in the gallery.</p>";
            }
            ?>
        </div>
    </div>

        <footer>
    <p>&copy; 2024 Mohammad Mozammel Hoq</p>
  </footer>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
