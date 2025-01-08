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

// Pagination logic
$blogsPerPage = 3; // Number of blogs per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get current page
$start = ($page - 1) * $blogsPerPage; // Starting position for the query

// Check if the user has selected a specific month and year, or 'All Blogs'
$whereClause = "";
if (isset($_GET['month']) && isset($_GET['year']) && $_GET['month'] != "" && $_GET['year'] != "") {
    $selectedMonth = $_GET['month'];
    $selectedYear = $_GET['year'];
    $whereClause = "WHERE MONTH(created_at) = $selectedMonth AND YEAR(created_at) = $selectedYear";
} elseif (isset($_GET['month']) && $_GET['month'] != "") {
    $selectedMonth = $_GET['month'];
    $whereClause = "WHERE MONTH(created_at) = $selectedMonth";
} elseif (isset($_GET['year']) && $_GET['year'] != "") {
    $selectedYear = $_GET['year'];
    $whereClause = "WHERE YEAR(created_at) = $selectedYear";
}

// Fetch total number of blogs for pagination
$totalBlogsSql = "SELECT COUNT(*) as total FROM blogs $whereClause";
$totalResult = $conn->query($totalBlogsSql);
$totalBlogs = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalBlogs / $blogsPerPage);

// Fetch blogs with pagination and optional filtering
$sql = "SELECT id, title, image, content, created_at FROM blogs $whereClause ORDER BY created_at DESC LIMIT $start, $blogsPerPage";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://kit.fontawesome.com/5295537e26.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Londrina+Sketch&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Blogs</title>
    <style>
        .dropdown:hover .dropdown-menu { display: block; }
        .dropdown-menu { display: none; }
        footer {
            width: 100%;
            background-color: #bffffa;
            padding: 1rem;
            text-align: center;
        }
        footer p {
            margin: 0;
        }
        .title-header {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            font-family: "Libre Baskerville", serif;
            font-weight: 700;
            font-style: normal;
            color: #974b62;
        }
        .title-header .null {
            width: 100px;
            height: 1px;
            background-color: #ccc;
            margin: 10px 20px 20px 20px !important;
        }
        .userProfile{
            border-radius: 100%;
            padding: 10px 17px !important;
            background-color: #a9a9a9;
        }

         body {
            background-color: #f9f9f9;
        }

        .blog-post {
            font-family: 'Libre Baskerville', serif;
            margin-bottom: 50px;
            max-width: 100%;
            margin: 40px auto;
        }

        .blog-post img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .blog-title {
            font-size: 2rem;
            font-weight: bold;
            margin-top: 20px;
            color: #974b62;
        }

        .blog-content {
            margin-top: 15px;
            font-size: 1.1rem;
            color: #333;
        }

        .read-more {
            margin-top: 20px;
            display: inline-block;
            font-weight: bold;
            color: #007bff;
            text-decoration: none;
        }

        .read-more:hover {
            text-decoration: underline;
        }

        .blog-date {
            margin-top: 10px;
            font-size: 0.9rem;
            color: #777;
        }

        .social-share a {
    font-size: 20px;
    color: #6c757d; /* Bootstrap's default secondary color */
}

.social-share a:hover {
    color: red; /* Darker color on hover */
}


    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg" style="background-color: #e3f2fd;">
    <div class="container-fluid">
        <a class="navbar-brand ms-5" href="index.php">
        Mozammel's Gallery
      </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" aria-current="page" href="index.php">Home</a></li>
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
                <li class="nav-item"><a class="nav-link active" href="blog.php">Blog</a></li>
                <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link bg-danger rounded text-white" href="admin.php">Admin</a></li>
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

<div class="container text-center mt-4 title-header">
    <div class="null"></div>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <h3 style="margin-bottom: 20px">Manage Blogs</h3>
    <?php else: ?>
        <h3>Blogs</h3>
    <?php endif; ?>
    <div class="null"></div>
</div>

<div class="container mt-5">
    <form action="blog.php" method="get" class="d-flex">
        <select name="month" class="form-select me-2" style="width: 130px">
            <option value="">All Blogs</option>
            <?php
            // Generate options for months
            for ($m = 1; $m <= 12; $m++) {
                $month = date('F', mktime(0, 0, 0, $m, 1)); // Get the full month name
                $selected = (isset($_GET['month']) && $_GET['month'] == $m) ? 'selected' : ''; // Retain selected option
                echo "<option value='$m' $selected>$month</option>";
            }
            ?>
        </select>
        <select name="year" class="form-select me-2" style="width: 130px">
            <option value="">All Years</option>
            <?php
            // Generate options for recent years
            for ($y = date('Y'); $y >= 2000; $y--) {
                $selected = (isset($_GET['year']) && $_GET['year'] == $y) ? 'selected' : ''; // Retain selected option
                echo "<option value='$y' $selected>$y</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <br>
</div>

<div class="container mt-5">
    <div class="container">
        <form action="delete.php" method="post">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="blog-post">
                <div class="blog-date text-center my-3"><?php echo date('F j, Y', strtotime($row['created_at'])); ?></div>
                <h2 class="blog-title text-center my-4"><?php echo htmlspecialchars($row['title']); ?></h2>
                <a href="seeMore.php?id=<?php echo $row['id']; ?>" class="read-more">
                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                </a>
                <p class="blog-content my-3"><?php echo substr($row['content'], 0, 300); ?>....</p>
                <p><a href="seeMore.php?id=<?php echo $row['id']; ?>" class="read-more">Read More</a></p>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <input type="checkbox" name="delete_ids[]" value="<?php echo $row['id']; ?>"> Select for deletion
                <?php endif; ?>
                </div>
               
               <!-- Social Share Section -->
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-auto">
            <div class="social-share d-flex my-4">
                <?php
                    // Generating the blog URL dynamically inside the loop
                    $currentBlogUrl = "https://muzammelsgallery.wuaze.com/seeMore.php?id=" . $row['id'];
                ?>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($currentBlogUrl); ?>" target="_blank" class="btn btn-sm mx-1" title="Share on Facebook">
                    <i class="fa fa-facebook"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($currentBlogUrl); ?>" target="_blank" class="btn btn-sm mx-1" title="Share on Twitter">
                    <i class="fa fa-twitter"></i>
                </a>
                <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode($currentBlogUrl); ?>" target="_blank" class="btn btn-sm mx-1" title="Share on Pinterest">
                    <i class="fa fa-pinterest"></i>
                </a>
                <a href="https://www.tumblr.com/widgets/share/tool?canonicalUrl=<?php echo urlencode($currentBlogUrl); ?>" target="_blank" class="btn btn-sm mx-1" title="Share on Tumblr">
                    <i class="fa fa-tumblr"></i>
                </a>
                <a href="#" class="btn btn-sm mx-1" title="Copy Blog Link" onclick="copyLink('<?php echo $currentBlogUrl; ?>')">
                    <i class="fa fa-link"></i>
                </a>
            </div>
            </div>
            </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No blogs available.</p>
    <?php endif; ?>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <div class="mt-3 mx-5 mb-3">
                <button type="submit" class="btn btn-danger">Delete Selected Blogs</button>
            </div>
    <?php endif; ?>
    </form>
    </div>

<script>
    function copyLink(blogUrl) {
        // Create a temporary input element to copy the blog URL
        var tempInput = document.createElement("input");
        tempInput.value = blogUrl;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        alert("Blog link copied to clipboard!");
    }
</script>



    <!-- Pagination links -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&month=<?php echo isset($selectedMonth) ? $selectedMonth : ''; ?>&year=<?php echo isset($selectedYear) ? $selectedYear : ''; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&month=<?php echo isset($selectedMonth) ? $selectedMonth : ''; ?>&year=<?php echo isset($selectedYear) ? $selectedYear : ''; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&month=<?php echo isset($selectedMonth) ? $selectedMonth : ''; ?>&year=<?php echo isset($selectedYear) ? $selectedYear : ''; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<footer>
    <p>&copy; 2024 Mohammad Mozammel Hoq</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
