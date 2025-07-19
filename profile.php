<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Connect to your database
$conn = new mysqli("localhost", "root", "", "lms");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user's name and email using their session email
$email = $_SESSION['email'];
$sql = "SELECT name, email FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($name, $user_email);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 font-sans min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-lg">
        <div class="flex flex-col items-center">
            <h1 class="text-2xl font-bold text-gray-800 mb-1"><?php echo htmlspecialchars($name); ?></h1>
            <p class="text-gray-500 mb-4"><?php echo htmlspecialchars($user_email); ?></p>
        </div>
        <div class="mt-8 text-center">
            <a href="studentdash.php" class="text-blue-600 hover:underline">&larr; Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
