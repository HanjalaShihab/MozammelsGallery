<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Check if the logged-in user is an admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin panel</title>
    <style>
    .dropdown:hover .dropdown-menu {
      display: block;
    }

    .dropdown-menu {
      display: none;
    }

     * {box-sizing: border-box}

/* Style the tab */
.tab {
  float: left;
  border: 1px solid #ccc;
  background-color: #d4f7fc;
  width: 25%;
  height: 400px;
  padding-top: 10px;
  border-radius: 10px;
}

/* Style the buttons that are used to open the tab content */
.tab button {
  display: block;
  background-color: inherit;
  color: black;
  padding: 16px;
  width: 100%;
  border: none;
  outline: none;
  text-align: left;
  cursor: pointer;
  transition: 0.3s;
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 2px;
  border-radius: 5px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current "tab button" class */
.tab button.active {
  background-color: #18d2ed;
  color: white;
}

/* Style the tab content */
.tabcontent {
  float: left;
  padding: 20px;
  border: 1px solid #ccc;
  width: 70%;
  border-left: none;
  background-color: #f9f9f9;
  border-radius: 10px;
  margin-left: 20px;
  display: none;
}

.tabcontent.active {
  display: block;
  animation: fadeIn 0.5s;
}

@keyframes fadeIn {
  from {opacity: 0;}
  to {opacity: 1;}
}

.tabcontent a {
  text-decoration: none;
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border-radius: 5px;
  font-size: 16px;
  transition: 0.3s;
}

.tabcontent a:hover {
  background-color: #0056b3;
}

input[type="submit"]{
            background-color: #1a01d8d0;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        label {
            font-weight: bold;
        }

        table, td, th {  
  border: 1px solid #ddd;
  text-align: left;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 15px;
}

    </style>
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

   <div class="tab">
       <button class="tablinks" onclick="openCity(event, 'ImageUp')">Upload Image</button>
       <button class="tablinks" onclick="openCity(event, 'BlogUp')">Upload Blog</button>
       <button class="tablinks" onclick="openCity(event, 'shopUp')">Shop Upload</button>
       <button class="tablinks" onclick="openCity(event, 'course')">Course Upload</button>
       <button class="tablinks" onclick="openCity(event, 'Subs')">Subscribers</button>
       <button class="tablinks" onclick="openCity(event, 'Users')">Users</button>
       <a class="bg-danger text-white" style="text-decoration:none; padding: 15px; margin: 10px; border-radius: 4px" href="logout.php">Log out</a>
   </div>

<div id="ImageUp" class="tabcontent">
  <h2 style="text-align: center;margin-top: 15px">Upload Image</h2>
    <div class="container mt-3">
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="artworkTitle" class="form-label">Artwork Title</label>
            <input type="text" class="form-control" id="artworkTitle" name="artworkTitle" required autocomplete="off">
        </div>
        <div class="mb-3">
            <label for="medium" class="form-label">Medium</label>
            <input type="text" class="form-control" id="medium" name="medium" required autocomplete="off">
        </div>
        <div class="mb-3">
            <label for="size" class="form-label">Size</label>
            <input type="text" class="form-control" id="size" name="size" required autocomplete="off">
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-select" id="category" name="category" required>
                    <option value="">Select a category</option>
                    <option value="Drawings">Drawings</option>
                    <option value="Still Life">Still Life</option>
                    <option value="Figure Paintings">Figure Paintings</option>
                    <option value="Landscape">Landscape</option>
                    <option value="Portrait">Portrait</option>
                </select>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="text" class="form-control" id="price" name="price" autocomplete="off">
        </div>

        <div class="mb-3">
            <label for="artworkImage" class="form-label">Upload Image</label>
            <input type="file" class="form-control" id="artworkImage" name="artworkImage" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
</div>

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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    
    // Handle file upload
    $target_dir = "uploads/"; // Make sure this directory exists and is writable
    $target_file = $target_dir . basename($_FILES["blogImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image
    $check = getimagesize($_FILES["blogImage"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (e.g., limit to 10MB)
    if ($_FILES["blogImage"]["size"] > 10000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Try to upload file
        if (move_uploaded_file($_FILES["blogImage"]["tmp_name"], $target_file)) {
            // Insert into database
            $sql = "INSERT INTO blogs (title, image, content) VALUES ('$title', '$target_file', '$content')";
            if ($conn->query($sql) === TRUE) {
                header('Location: blog.php');
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
?>


<div id="BlogUp" class="tabcontent">
  <div class="container mt-1">
    <h2 style="text-align: center;">Upload Blog Post</h2>
    <form action="uploadBlog.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Blog Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Blog Content</label>
            <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label for="blogImage" class="form-label">Upload Blog Image</label>
            <input type="file" class="form-control" id="blogImage" name="blogImage" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload Blog</button>
    </form>
</div>
</div>


<div id="shopUp" class="tabcontent">
   <div class="container mt-1">
    <h2 class="text-center mb-4">Upload Shop Item</h2>
    <form action="shopUpload.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Item Name</label>
            <input type="text" name="name" class="form-control" id="name" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" name="price" class="form-control" id="price" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" id="description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Upload Image</label>
            <input type="file" name="image" class="form-control" id="image" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
    </div>
</div>


<div id="course" class="tabcontent">
  <div class="container">
    <h2 class="text-center mb-4">Upload a New Course</h2>
    <form action="uploadCourse.php" method="POST" enctype="multipart/form-data">
      <!-- Course Title -->
      <div class="mb-3">
        <label for="courseTitle" class="form-label">Course Title</label>
        <input type="text" class="form-control" id="courseTitle" name="title" required>
      </div>
      <!-- Course Description -->
      <div class="mb-3">
        <label for="courseDescription" class="form-label">Course Description</label>
        <textarea class="form-control" id="courseDescription" name="description" rows="4" required></textarea>
      </div>
      <!-- Course Price -->
      <!--<div class="mb-3">
        <label for="coursePrice" class="form-label">Course Price</label>
        <input type="number" class="form-control" id="coursePrice" name="price" min="0" required>
      </div>
      <!-- Upload Image -->
     <!-- <div class="mb-3">
        <label for="courseImage" class="form-label">Course Image (Optional)</label>
        <input type="file" class="form-control" id="courseImage" name="image">
      </div> -->
      <!-- Submit Button -->
      <button type="submit" class="btn btn-primary">Upload Course</button>
    </form>
  </div>
</div>

<?php
session_start();

// Include database connection
$servername = "sql100.infinityfree.com";
$username = "if0_37486741";
$password = "clHO11mUrA1";
$dbname = "if0_37486741_artist_portfolio";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

                $sql = "SELECT * FROM subscribers";
                $result = $conn->query($sql);
?>



<div id="Subs" class="tabcontent">
     <div class="container mt-3">
    <button class="btn btn-primary mb-3" id="copyEmailsBtn">Copy Emails</button>
</div>

<div class="container">
    <h2>Subscribers List</h2>
    <table border="1" cellspacing="0" cellpadding="10">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody id="subscribersTable">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td class='email'>" . htmlspecialchars($row['email']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No subscribers found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
// JavaScript to copy the emails to the clipboard
document.getElementById('copyEmailsBtn').addEventListener('click', function() {
    // Collect all email addresses from the table
    const emails = Array.from(document.querySelectorAll('#subscribersTable .email'))
        .map(td => td.innerText)
        .join(', '); // Join emails with a comma and space

    // Create a temporary textarea to copy the emails
    const tempTextArea = document.createElement('textarea');
    tempTextArea.value = emails;
    document.body.appendChild(tempTextArea);

    // Select and copy the content
    tempTextArea.select();
    document.execCommand('copy');

    // Remove the temporary textarea
    document.body.removeChild(tempTextArea);

    // Notify the user that the emails have been copied
    alert('Emails copied to clipboard!');
});
</script>
</div>


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

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

?>


<div id="Users" class="tabcontent">
  <h1>Users</h1>
    <table border="2" cellspacing="0" cellpadding="10">
        <tr>
            <th>Username</th>
            <th>Email</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["username"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "0 results";
        }
        ?>
    </table>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function openCity(evt, cityName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the link that opened the tab
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>
</body>
</html>
