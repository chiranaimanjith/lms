<?php
session_start();

// Define PASSWORD_DEFAULT if not defined (for PHP < 5.5)
if (!defined('PASSWORD_DEFAULT')) {
    define('PASSWORD_DEFAULT', 1);
}

// Polyfill for password_hash and password_verify for PHP < 5.5
if (!function_exists('password_hash')) {
    function password_hash($password, $algo, $options = array()) {
        if ($algo !== PASSWORD_DEFAULT) {
            trigger_error('password_hash(): Unknown hashing algorithm: ' . $algo, E_USER_WARNING);
            return null;
        }
        $cost = isset($options['cost']) ? $options['cost'] : 10;
        $salt = isset($options['salt']) ? $options['salt'] : (
            function_exists('openssl_random_pseudo_bytes')
                ? openssl_random_pseudo_bytes(22)
                : substr(md5(mt_rand() . microtime()), 0, 22)
        );
        $salt = substr(str_replace('+', '.', base64_encode($salt)), 0, 22);
        return crypt($password, sprintf('$2y$%02d$%s$', $cost, $salt));
    }
}
if (!function_exists('password_verify')) {
    function password_verify($password, $hash) {
        return crypt($password, $hash) === $hash;
    }
}

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // update if your MySQL has a password
$dbname = "lms"; // update to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);

    // Simple SQL injection prevention
    $name = $conn->real_escape_string($name);
    $email = $conn->real_escape_string($email);

    // Hash the password securely
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    // Check if email exists
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check && $check->num_rows > 0) {
        $error = "Email already exists.";
    } else {
        $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$hashed_pass', 'student')";
        if ($conn->query($sql) === TRUE) {
            $success = "Signup successful! <a href='login.php' class='text-blue-600 underline'>Login here</a>.";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            background: linear-gradient(135deg, #0f172a 0%, #581c87 50%, #0f172a 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-sm">
        <h2 class="text-2xl font-bold mb-6 text-center">Signup</h2>
        <?php if ($error) echo "<p class='text-red-500 mb-4 text-center'>$error</p>"; ?>
        <?php if ($success) echo "<p class='text-green-600 mb-4 text-center'>$success</p>"; ?>
        <form method="post" action="">
            <label class="block mb-2 font-semibold">Name:</label>
            <input type="text" name="name" required class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <label class="block mb-2 font-semibold">User Name:</label>
            <input type="email" name="email" required class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <label class="block mb-2 font-semibold">Password:</label>
            <input type="password" name="password" required class="w-full px-3 py-2 border rounded mb-6 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <input type="submit" value="Signup" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 cursor-pointer font-semibold">
        </form>
        <p class="mt-4 text-center text-gray-600">Already have an account? <a href="login.php" class="text-blue-600 underline">Login</a></p>
    </div>
</body>
</html>
