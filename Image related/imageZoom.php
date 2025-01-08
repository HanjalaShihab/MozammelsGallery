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

// Check if an ID is passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the image details based on the ID
    $sql = "SELECT title, image_path FROM gallery WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($title, $image_path);
    $stmt->fetch();
    $stmt->close();

    // Fetch previous and next image IDs
    $prev_sql = "SELECT id FROM gallery WHERE id < ? ORDER BY id DESC LIMIT 1";
    $next_sql = "SELECT id FROM gallery WHERE id > ? ORDER BY id ASC LIMIT 1";

    // Get previous ID
    $stmt = $conn->prepare($prev_sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($prev_id);
    $stmt->fetch();
    $stmt->close();

    // Get next ID
    $stmt = $conn->prepare($next_sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($next_id);
    $stmt->fetch();
    $stmt->close();

} else {
    echo "No image ID provided.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/5295537e26.js" crossorigin="anonymous"></script>
  <title>Image Zoom with Overlay</title>
  <style>
    body {
      display: flex;
      justify-content: center;
    }
    .image-container {
      position: relative;
      display: inline-block; /* Ensures container only takes up necessary space */
    }

    .gallery {
      max-width: 90%;
      height: 80%;
      margin: auto;
      overflow: hidden;
      position: relative;
    }
    
    .gallery img {
        width: 100%;
        height: 100%;
    }

    #zoom-image {
      width: 100%;
      transition: transform 0.2s ease;
      cursor: zoom-in;
    }

    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: rgba(0, 0, 0, 0.6);
      color: white;
      font-size: 40px;
      z-index: 10; /* Keep the overlay above the image */
      opacity: 1; /* Ensure overlay is visible by default */
      transition: opacity 0.3s ease; /* Smooth transition */
    }

    /* Hide overlay on image hover */
    .image-container:hover .overlay {
      opacity: 0; /* Fade out the overlay */
      pointer-events: none; /* Prevent mouse events on the overlay */
    }

    .image-container button {
      display: block;
      margin: auto;
      padding: 13px 20px;
      border-radius: 4px;
      background-color: #333;
      color: white;
      border: none;
      cursor: pointer;
      position: absolute;
      top: 0px;
      right: -20px;
      z-index: 11; /* Above overlay */
    }

    .image-container button:hover {
      color: red;
    }

    .prev-btn {
      position: absolute;
      top: 50%;
      left: 0px;
      background: none;
      border: none;
      font-size: 40px;
      color: red;
      cursor: pointer;
    }

    .next-btn {
      position: absolute;
      top: 50%;
      right: 0px;
      background: none;
      border: none;
      font-size: 40px;
      color: red;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <div class="image-container">
    <div class="gallery">
      <div class="overlay">
        <i class='fa-solid fa-magnifying-glass-plus'></i>
      </div>
      <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($title); ?>" id="zoom-image">
    </div>

      <!-- Previous and Next Buttons -->

     <button onclick="goBack()">CLOSE &nbsp; X</button>
  </div>

    <?php if (!empty($prev_id)): ?>
      <button class="prev-btn" onclick="window.location.href='?id=<?php echo $prev_id; ?>'"><i class="fa-solid fa-circle-chevron-left"></i></button>
    <?php endif; ?>

    <?php if (!empty($next_id)): ?>
      <button class="next-btn" onclick="window.location.href='?id=<?php echo $next_id; ?>'"><i class="fa-solid fa-circle-chevron-right"></i></button>
    <?php endif; ?>

  <script>
    const image = document.getElementById('zoom-image');

    // Zoom functionality on mouse move
    image.addEventListener('mousemove', function (e) {
      const { offsetX, offsetY } = e; // Get mouse position relative to the image
      const { width, height } = image; // Get dimensions of the image
      const x = (offsetX / width) * 100; // Calculate x percentage
      const y = (offsetY / height) * 100; // Calculate y percentage

      // Apply transform based on mouse position
      image.style.transformOrigin = `${x}% ${y}%`; // Set the zoom center
      image.style.transform = 'scale(2.5)'; // Set your desired zoom level
    });

    // Reset zoom when mouse leaves the image
    image.addEventListener('mouseleave', function () {
      image.style.transform = 'scale(1)'; // Reset zoom when mouse leaves
    });

  </script>

<script>
  function goBack() {
    // Get the current image id from the URL
    const urlParams = new URLSearchParams(window.location.search);
    const currentId = urlParams.get('id');

    // Redirect to gallery.php with the current image id
    window.location.href = 'image.php?id=' + currentId;
  }
</script>



</body>

</html>
