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
    $id = (int)$_GET['id']; // Cast to integer for security

    // Initialize variables
    $gallery_data = null;
    $shop_data = null;

    // Fetch the image details from the gallery based on the ID
    $gallery_sql = "SELECT title, image_path, medium, size, category, description, price, DATE(created_at) as upload_date FROM gallery WHERE id = ?";
    if ($gallery_stmt = $conn->prepare($gallery_sql)) {
        $gallery_stmt->bind_param("i", $id);
        $gallery_stmt->execute();
        $gallery_stmt->bind_result($title, $image_path, $medium, $size, $category, $description, $price, $created_at);
        if ($gallery_stmt->fetch()) {
            $gallery_data = [
                'title' => $title,
                'image_path' => $image_path,
                'medium' => $medium,
                'size' => $size,
                'category' => $category,
                'description' => $description,
                'price' => $price,
                'created_at' => $created_at
            ];
        }
        $gallery_stmt->close();
    } else {
        echo "Failed to prepare gallery statement.";
    }

    // Fetch the image details from the shop based on the ID
    $shop_sql = "SELECT name, image_path, description, price, DATE(uploaded_at) as upload_date FROM shop_items WHERE id = ?";
    if ($shop_stmt = $conn->prepare($shop_sql)) {
        $shop_stmt->bind_param("i", $id);
        $shop_stmt->execute();
        $shop_stmt->bind_result($title, $image_path, $description, $price, $uploaded_at);
        if ($shop_stmt->fetch()) {
            $shop_data = [
                'title' => $title,
                'image_path' => $image_path,
                'description' => $description,
                'price' => $price,
                'uploaded_at' => $uploaded_at // Correctly retrieving uploaded_at
            ];
        }
        $shop_stmt->close();
    } else {
        echo "Failed to prepare shop statement.";
    }
} else {
    echo "No image ID provided.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/5295537e26.js" crossorigin="anonymous"></script>
    <title><?php echo htmlspecialchars($gallery_data['title'] ?? $shop_data['title'] ?? 'Image Details'); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin-bottom: 20px;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-menu {
            display: none;
        }

        .img-container img {
            max-width: 100%;
            height: auto; /* Updated to ensure proper height scaling */
        }

        .navbar {
            background-color: #e5e5e5;
        }

        .userProfile {
            border-radius: 100%;
            padding: 10px 17px !important;
            background-color: #a9a9a9;
        }

        .container .image {
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .overlay {
            position: absolute;
            background-color: black;
            opacity: 0.5;
            transition: opacity 0.3s ease;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            display: none;
        }

        .overlay i {
            color: white;
            font-size: 33px;
            z-index: 10;
            opacity: 1 !important;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .img-container .image:hover .overlay {
            display: block;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg mb-5">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
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
                <li class="nav-item"><a class="nav-link" href="course.php">Courses</a></li>
                <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="blog.php">Blog</a></li>
                <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link bg-danger rounded text-white" href="admin.php">Admin</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'user'): ?>
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
    </nav>

    <div class="container img-container">
        <div class="row">
            <div class="col-md-12 text-left">
                <div class="row">
                     <div class="col-md-6">
                     <?php if ($gallery_data): ?>
                    <a href="imageZoom.php?id=<?php echo htmlspecialchars($id); ?>">
                        <div class="image">
                            <div class='overlay'>
                                <i class='fa-solid fa-magnifying-glass-plus'></i>
                            </div>
                            <img src="<?php echo htmlspecialchars($gallery_data['image_path']); ?>" alt="<?php echo htmlspecialchars($gallery_data['title']); ?>" class="img-fluid">
                        </div>
                    </a>
                    <div style="display: flex; justify-content: space-between;">
                        <div>
                            <h2 class="my-4"><?php echo htmlspecialchars($gallery_data['title']); ?></h2>
                            <p><strong>Medium:</strong> <?php echo htmlspecialchars($gallery_data['medium']); ?></p>
                            <p><strong>Size:</strong> <?php echo htmlspecialchars($gallery_data['size']); ?></p>
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($gallery_data['category']); ?></p>
                        </div>
                        <div>
                            <p style="margin-top: 30px !important; font-size: 18px"><strong>Price:</strong> <?php echo htmlspecialchars($gallery_data['price']); ?></p>
                        </div>
                    </div>
                     </div>

                     <div class="col-md-6">
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($gallery_data['created_at']); ?></p>
                        <h3 style="color: #a05563">Description:</h3>
                        <p><?php echo nl2br(htmlspecialchars($gallery_data['description'])); ?></p>
                     </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                <?php elseif ($shop_data): ?>
                    <a href="imageZoom.php?id=<?php echo htmlspecialchars($id); ?>">
                        <div class="image">
                            <div class='overlay'>
                                <i class='fa-solid fa-magnifying-glass-plus'></i>
                            </div>
                            <img src="<?php echo htmlspecialchars($shop_data['image_path']); ?>" alt="<?php echo htmlspecialchars($shop_data['title']); ?>" class="img-fluid">
                        </div>
                    </a>
                    </div>
                    <div class="col-md-6">
                    <div>
                        <div>
                            <h2 class="my-4"><?php echo htmlspecialchars($shop_data['title']); ?></h2>
                            <p style="margin-top: 30px !important; font-size: 18px"><strong>Price:</strong> <?php echo htmlspecialchars($shop_data['price']); ?></p>
                        </div>
                    </div>
                        <h3 style="color: #a05563">Description:</h3>
                        <p><?php echo nl2br(htmlspecialchars($shop_data['description'])); ?></p>
                        <?php else: ?>
                           <p>No image found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
