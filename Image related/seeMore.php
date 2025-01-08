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

// Fetch the blog based on the ID passed in the URL
if (isset($_GET['id'])) {
    $blog_id = intval($_GET['id']);
    $sql = "SELECT id, title, image, content, created_at, likes FROM blogs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $blog_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $blog = $result->fetch_assoc();
    } else {
        die("Blog not found.");
    }
} else {
    die("Invalid blog ID.");
}

// Handle comment submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    $user_name = htmlspecialchars(trim($_POST['user_name']));
    $comment = htmlspecialchars(trim($_POST['comment']));
    
    // Insert the comment into the database
    $sql = "INSERT INTO comments (blog_id, user_name, comment) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $blog_id, $user_name, $comment);
    $stmt->execute();
    $stmt->close();

    header("Location: seeMore.php?id=" . $blog_id . "#commentSection");
    exit();
}

// Fetch comments for the current blog
$sql = "SELECT id, user_name, comment, created_at FROM comments WHERE blog_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$comments_result = $stmt->get_result();

// Handle admin reply submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply'])) {
    $comment_id = intval($_POST['comment_id']);
    $reply = htmlspecialchars(trim($_POST['reply']));
    
    // Insert the reply into the database
    $sql = "INSERT INTO admin_replies (comment_id, reply) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $comment_id, $reply);
    $stmt->execute();
    $stmt->close();


    header("Location: seeMore.php?id=" . $blog_id . "#commentSection");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Londrina+Sketch&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/5295537e26.js" crossorigin="anonymous"></script>
    <title><?php echo htmlspecialchars($blog['title']); ?></title>
    <style>
        .dropdown:hover .dropdown-menu { display: block; }
        .dropdown-menu { display: none; }
        
        .blog-title {
            color: #974b62;
            font-size: 36px;
        }

        .blog-date {
            color: #777;
            font-size: 14px;
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

        footer {
            background-color: #f8f9fa;
            padding: 1rem;
            text-align: center;
            width: 100%;
        }

        .userProfile {
            border-radius: 100%;
            padding: 10px 17px !important;
            background-color: #a9a9a9;
        }

        .comment-section {
            margin-top: 40px;
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
                <li class="nav-item"><a class="nav-link" href="blog.php">Blog</a></li>
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

<main>
    <div class="container text-center mt-4 title-header">
        <div class="null"></div>
        <h3>Blog</h3>
        <div class="null"></div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 text-end">
                <img style="max-width: 400px; height: auto;" src="<?php echo htmlspecialchars($blog['image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" class="img-fluid">
            </div>
            <div class="col-md-6">
                <p class="blog-date"><?php echo date('F j, Y', strtotime($comment['created_at'])); ?></p>
                <p style="font-size: 30px; color: #c8a99d" class="blog-title"><?php echo htmlspecialchars($blog['title']); ?></p>
                <h6><i class="fa-solid fa-user fa-fw"></i>Mozammel Hoq</h6>
                <p><?php echo nl2br(htmlspecialchars($blog['content'])); ?></p>
            </div>
        </div>
    </div>

    <div class="container comment-section mb-4" id="commentSection">
    <br>
    <hr>
    <br>
    <div class="row">
        <div class="col-md-8 text-left">
           <div style="max-width: 500px;">
             <h5 style="margin-bottom: 30px;">Comment:</h5>
            <form method="post" action="#commentSection">
              <input type="text" name="user_name" required placeholder="Your Name" class="form-control mb-3" style="height: 60px !important">
              <textarea name="comment" required placeholder="Your Comment" class="form-control mb-3" style="height: 120px"></textarea>
              <button type="submit" class="btn btn-primary">Comment</button>
            </form>
           </div>
        </div>
        
       <div class="col-md-4">
              <h2 style="color: #a85a68; margin-bottom: 20px;">Comments</h2>
        <?php while ($comment = $comments_result->fetch_assoc()): ?>
            <div class="mb-4">
               <div style="background: #c2f0c5; padding: 15px; border-radius: 10px">
                   <small style="color:grey"><?php echo date('F j, Y', strtotime($comment['created_at'])); ?></small>
                <div style="line-height: 14px;">
                   <P style="font-size: 18px; margin-top: 10px;color: #00589c"><strong><?php echo htmlspecialchars($comment['user_name']); ?></strong></p>
                   <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                </div>
                </div>
                
                <?php
                // Fetch replies for this comment
                $reply_sql = "SELECT reply, created_at FROM admin_replies WHERE comment_id = ?";
                $reply_stmt = $conn->prepare($reply_sql);
                $reply_stmt->bind_param("i", $comment['id']);
                $reply_stmt->execute();
                $replies_result = $reply_stmt->get_result();
                ?>
                <?php while ($reply = $replies_result->fetch_assoc()): ?>
                    <div class="admin-reply mt-4 ms-5" style="background: #f0c2c2; padding: 15px; border-radius: 10px; line-height: 10px">
                       <p><small><?php echo date('F j, Y', strtotime($comment['created_at'])); ?></small></P>
                        <strong>Mozammel:</strong> <?php echo nl2br(htmlspecialchars($reply['reply'])); ?>
                    </div>
                <?php endwhile; ?>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <form method="post" class="reply-form mt-2">
                    <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                    <div class="mb-3">
                        <label for="reply" class="form-label">Admin Reply:</label>
                        <textarea class="form-control" id="reply" name="reply" rows="2" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Reply</button>
                </form>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
        </div>
    </div>
    </div>
</main>

<footer>
    <div class="container">
        <p>&copy; 2024 Mozammel Hoq. All Rights Reserved.</p>
    </div>
</footer>

<script>
function likeBlog(blogId) {
    fetch(window.location.href, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            like: 1,
            blog_id: blogId
        })
    })
    .then(response => response.json())  // Expect JSON response
    .then(data => {
        if (data.likes) {
            document.getElementById('likeCount').innerText = data.likes;  // Update like count
        } else if (data.error) {
            console.error('Error:', data.error);  // Log error to console
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
