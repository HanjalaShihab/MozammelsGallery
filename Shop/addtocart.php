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

// Initialize user ID (change this to session-based user ID in the future)
$user_id = 1; // Example: Use session-based user_id for real login system

// Check if the item ID is provided
if (isset($_GET['id'])) {
    $item_id = intval($_GET['id']);
    $quantity = 1; // Default quantity is 1, this can be modified later for quantity updates

    // Check if the item already exists in the cart
    $checkCart = $conn->prepare("SELECT id FROM cart WHERE course_id = ? AND user_id = ?");
    $checkCart->bind_param("ii", $item_id, $user_id);
    $checkCart->execute();
    $result = $checkCart->get_result();

    if ($result->num_rows > 0) {
        // Item already exists in the cart
        $_SESSION['message'] = "Item already in cart!";
    } else {
        // Add a new item to the cart
        $addToCart = $conn->prepare("INSERT INTO cart (course_id, user_id, quantity) VALUES (?, ?, ?)");
        $addToCart->bind_param("iii", $item_id, $user_id, $quantity);
        $addToCart->execute();
        $addToCart->close();

        $_SESSION['message'] = "Item added to cart!";
    }

    $checkCart->close();
    header("Location: cart.php");
    exit;
}

$conn->close();
?>
