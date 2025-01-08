<?php
// Start session
session_start();

// Database connection
$servername = "sql100.infinityfree.com";
$username = "if0_37486741";
$password = "clHO11mUrA1";
$dbname = "if0_37486741_artist_portfolio";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //validate inputs
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    // Ensure required fields are not empty
    if (empty($title) || empty($description)) {
        echo "All fields (title and description) are required.";
        exit;
    }

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO courses (title, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $description);

    if ($stmt->execute()) {
        // Redirect to courses page with success message
        $_SESSION['message'] = "Course uploaded successfully.";
        header("Location: course.php");
        exit;
    } else {
        echo "Database error: " . $stmt->error;
    }

    $stmt->close();
}

// Close the connection
$conn->close();
?>
