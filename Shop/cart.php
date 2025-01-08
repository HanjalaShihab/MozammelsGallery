<?php
session_start();

// Database connection
$servername = "sql100.infinityfree.com";
$username = "if0_37486741";
$password = "clHO11mUrA1";
$dbname = "if0_37486741_artist_portfolio";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize user ID
$user_id = 1; // Example: Use session-based user_id for real login system

// Fetch cart items for the user
$sql = "SELECT si.id, si.name, si.price, si.description, si.image_path, c.quantity 
        FROM cart c
        JOIN shop_items si ON c.course_id = si.id
        WHERE c.user_id = $user_id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Your Cart</title>
</head>
<body>
<nav class="navbar navbar-expand-lg mb-4" style="background-color: #e3f2fd;">
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
                        <a class="nav-link bg-danger rounded text-white" href="admin.php">Admin</a>
                    </li>
                <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Your Cart</h2>
        <?php if ($result->num_rows > 0): ?>
            <div class="row">
                <?php while ($item = $result->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="product-card">
                            <h5 class="product-title mt-3"><?php echo htmlspecialchars($item['name']); ?></h5>
                            <p class="product-price"><?php echo htmlspecialchars($item['price']); ?>Tk</p>
                            <p class="product-quantity">Quantity: <?php echo $item['quantity']; ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <button class="btn btn-primary">Order</button>
        <?php else: ?>
            <p class="text-center">Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>
