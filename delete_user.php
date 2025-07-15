<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // Database connection
    $conn = new mysqli("localhost", "root", "", "lms");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete user
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $stmt->close();
    $conn->close();
}

// Redirect back to users page
header("Location: users.php");
exit();
?>
