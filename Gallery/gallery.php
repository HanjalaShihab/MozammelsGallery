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

// Pagination setup
$limit = 12; // Number of artworks per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $limit; // Offset for SQL query

$time_filter = $_GET['time_filter'] ?? 'all'; // Default to 'all'

// Define the SQL query based on the selected time filter
switch ($time_filter) {
    case 'specific_date':
        $specific_date = $_GET['specific_date'] ?? '';
        if (!empty($specific_date)) {
            $sql = "SELECT * FROM gallery WHERE DATE(created_at) = '$specific_date' LIMIT $limit OFFSET $offset";
        } else {
            $sql = "SELECT * FROM gallery LIMIT $limit OFFSET $offset"; // Fallback if no date is selected
        }
        break;

    case 'specific_month':
        $specific_month = $_GET['specific_month'] ?? '';
        if (!empty($specific_month)) {
            // Extract year and month from the input
            $year = date('Y', strtotime($specific_month));
            $month = date('m', strtotime($specific_month));
            $sql = "SELECT * FROM gallery WHERE YEAR(created_at) = '$year' AND MONTH(created_at) = '$month' LIMIT $limit OFFSET $offset";
        } else {
            $sql = "SELECT * FROM gallery LIMIT $limit OFFSET $offset"; // Fallback if no month is selected
        }
        break;

    case 'specific_year':
        $specific_year = $_GET['specific_year'] ?? '';
        if (!empty($specific_year)) {
            $sql = "SELECT * FROM gallery WHERE YEAR(created_at) = '$specific_year' LIMIT $limit OFFSET $offset";
        } else {
            $sql = "SELECT * FROM gallery LIMIT $limit OFFSET $offset"; // Fallback if no year is selected
        }
        break;

    default:
        $sql = "SELECT * FROM gallery LIMIT $limit OFFSET $offset"; // Show all images if no filter is applied
        break;
}

// Execute the query and fetch results
$result = $conn->query($sql);

// Get the total number of artworks for pagination
$total_sql = "SELECT COUNT(*) AS total FROM gallery";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_artworks = $total_row['total'];
$total_pages = ceil($total_artworks / $limit); // Calculate total pages
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
      <script src="https://kit.fontawesome.com/5295537e26.js" crossorigin="anonymous"></script>
    <title>Gallery</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        .image-container p {
            text-align: left;
        }
        

        .title {
            font-size: 19px;
            font-weight: bold;
        }

        .medium {
            font-style: normal;
        }

        .size {
            font-style: normal;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-menu {
            display: none;
        }

        

        .links a:hover {
            text-decoration: underline;
            color: blue;
        }

        .userProfile{
        border-radius: 100%;
        padding: 10px 17px !important;
        background-color: #a9a9a9;
    }

    footer {
      background-color: #f8f9fa;
      padding: 1rem;
      text-align: center;
    }

    footer p {
      margin: 0;
    }

       .container .image {
    position: relative;
    overflow: hidden; /* Ensure the overlay is contained */
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    width: 100%; /* Make sure the image takes full container width */
    height: auto; /* Let the height adjust based on the image aspect ratio */
}

.image img {
    display: block;
    max-width: 100%; /* Ensures the image fits within the container */
    height: auto;
    border: 1px solid #b0b8b2;
    padding: 8px;
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.5); /* Adjust opacity */
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0; /* Initially hidden */
    transition: opacity 0.3s ease; /* Smooth transition */
}

.overlay i {
    color: white;
    font-size: 24px;
    z-index: 10;
}

.image:hover .overlay {
    opacity: 1; /* Show the overlay when hovering */
}

    </style>
</head>

<body>
    <header>
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
                            <a class="nav-link active dropdown-toggle" href="gallery.php" id="galleryDropdown">
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
    </header>



    <div class="container">
        <div class="row">
              <div class="col-md-10">
                <div class="galleryInfo" style="max-width: 500px;">
                    <h3 style="margin: 30px 0;">Recent Daily Paintings Archive</h3>
                    <p style="margin: 0 0 30px 0; font-size: 20px">Welcome to my gallery! Here you can find a collection of my daily paintings.
                        Click on an image to view it in full size and to read more about it.
                        You will find the image picture name(title), medium, size and category and a brief description of the picture.
                    </p>
              </div>
            </div>
            <div class="col-md-2 category-links">
                <div style="margin-top: 30px;">
                    <input class="form-control me-2 mb-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit" onclick="searchImg(event)">Search</button>
                </div>
        </div>
</div>

            </div>
        </div>
    </div>

<div class="container">
        <div class="row">
            <div class="col-md-2">
                <div class="filter mt-3">
                    <form action="gallery.php" method="get">
                        <label for="time_filter" class="mv-2">Filter by Date:</label>
                        <select name="time_filter" class="form-control" id="time_filter" onchange="toggleFilterFields()">
                            <option value="all" <?php if ($time_filter == 'all') echo 'selected'; ?>>All Time</option>
                            <option value="specific_date" <?php if ($time_filter == 'specific_date') echo 'selected'; ?>>Specific Date</option>
                            <option value="specific_month" <?php if ($time_filter == 'specific_month') echo 'selected'; ?>>Specific Month</option>
                            <option value="specific_year" <?php if ($time_filter == 'specific_year') echo 'selected'; ?>>Specific Year</option>
                        </select>

                        <!-- Hidden fields that will show up based on the selected filter -->
                        <div id="date_filter" style="<?php echo ($time_filter === 'specific_date') ? 'display: block;' : 'display: none;'; ?>">
                            <label for="specific_date">Select Date:</label>
                            <input type="date" name="specific_date" class="form-control" value="<?php echo htmlspecialchars($specific_date ?? ''); ?>">
                        </div>

                        <div id="month_filter" style="<?php echo ($time_filter === 'specific_month') ? 'display: block;' : 'display: none;'; ?>">
                            <label for="specific_month">Select Month:</label>
                            <input type="month" name="specific_month" class="form-control" value="<?php echo htmlspecialchars($specific_month ?? ''); ?>">
                        </div>

                        <div id="year_filter" style="<?php echo ($time_filter === 'specific_year') ? 'display: block;' : 'display: none;'; ?>">
                            <label for="specific_year">Select Year:</label>
                            <input type="number" name="specific_year" class="form-control" min="2000" max="2100" placeholder="Enter year" value="<?php echo htmlspecialchars($specific_year ?? ''); ?>">
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">Filter</button>
                    </form>

                    <h3 style="margin-top: 20px;">Categories</h3>
                    <div class="links mb-3">
                        <a class="dropdown-item" href="drawings.php">Drawings</a>
                        <a class="dropdown-item" href="stilllife.php">Still Life</a>
                        <a class="dropdown-item" href="figure_paintings.php">Figure Paintings</a>
                        <a class="dropdown-item" href="landscape.php">Landscape</a>
                        <a class="dropdown-item" href="portrait.php">Portrait</a>
                    </div>
                </div>
            </div>

            <div class="col-md-10">
                <div class="row">
                    <form action="delete.php" method="post" class="delete-form">
            <div class="row"> <!-- Added row here to wrap the images correctly -->
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='col-md-3 col-sm-12 image-container mb-4'>";  // Use col-md-3 for 4 images per row
                        echo "<a href='image.php?id=" . $row['id'] . "'>";
                        echo "<div class='image'>";
                        echo "<div class='overlay'>";
                        echo "<i class='fa-solid fa-magnifying-glass-plus'></i>";
                        echo "</div>";
                        echo "<img class='img-fluid' src='" . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['title']) . "'>";
                        echo "</div>";
                        echo "</a>";
                        echo "<div class='d-flex justify-content-between'>";
                        echo "<p class='title'><strong>" . htmlspecialchars($row['title']) . "</strong></p>";
                        echo "<p style'font-size: 18px'>" . number_format($row['price'], 2) . "</p>";
                        echo "</div>";

                        // Check if the user is an admin to show the checkbox
                        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                            echo "<input type='checkbox' class='image-checkbox' name='delete_ids[]' value='" . $row['id'] . "'>";
                        }
                        echo "</div>";  // Close col-md-3
                    }
                } else {
                    echo "<p>No artworks found in the gallery.</p>";
                }
                ?>
            </div> <!-- Close the row for images -->
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <input type="submit" value="Delete Selected Images" class="btn btn-danger my-4">
            <?php endif; ?>
            </form>
                </div>

                <!-- Pagination Links -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&time_filter=<?php echo $time_filter; ?>&specific_date=<?php echo htmlspecialchars($specific_date ?? ''); ?>&specific_month=<?php echo htmlspecialchars($specific_month ?? ''); ?>&specific_year=<?php echo htmlspecialchars($specific_year ?? ''); ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&time_filter=<?php echo $time_filter; ?>&specific_date=<?php echo htmlspecialchars($specific_date ?? ''); ?>&specific_month=<?php echo htmlspecialchars($specific_month ?? ''); ?>&specific_year=<?php echo htmlspecialchars($specific_year ?? ''); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&time_filter=<?php echo $time_filter; ?>&specific_date=<?php echo htmlspecialchars($specific_date ?? ''); ?>&specific_month=<?php echo htmlspecialchars($specific_month ?? ''); ?>&specific_year=<?php echo htmlspecialchars($specific_year ?? ''); ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

      <script>
    function toggleFilterFields() {
        const filterType = document.getElementById("time_filter").value;
        document.getElementById("date_filter").style.display = "none";
        document.getElementById("month_filter").style.display = "none";
        document.getElementById("year_filter").style.display = "none";

        if (filterType === "specific_date") {
            document.getElementById("date_filter").style.display = "block";
        } else if (filterType === "specific_month") {
            document.getElementById("month_filter").style.display = "block";
        } else if (filterType === "specific_year") {
            document.getElementById("year_filter").style.display = "block";
        }
    }
    </script>


    <script>
        function searchImg(event) {
            event.preventDefault(); // Prevent the default form submission
            const input = document.querySelector(".form-control").value.toLowerCase(); // Get the search input value
            const imageContainers = document.querySelectorAll(".image-container"); // Select all image containers

            // Loop through all image containers
            imageContainers.forEach((container) => {
                const title = container.querySelector(".title").textContent.toLowerCase(); // Get the title text in lowercase

                // Compare search input with title, show if it matches, hide if it doesn't
                if (title.includes(input)) {
                    container.style.display = "block"; // Show the image if the title matches
                } else {
                    container.style.display = "none"; // Hide the image if it doesn't match
                }
            });
        }
    </script>

    <footer>
    <p>&copy; 2024 Mohammad Mozammel Hoq</p>
  </footer>
</body>

</html>

<?php
$conn->close();
?>

