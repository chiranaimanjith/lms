<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "lms");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    $sql = "UPDATE users SET name=?, email=?, role=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $email, $role, $user_id);
    $stmt->execute();
    $stmt->close();

    $conn->close();
    header("Location: users.php");
    exit();
}

// Fetch user data
$sql = "SELECT name, email, role FROM users WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $role);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6">Edit User</h1>
        <form method="POST">
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required class="w-full px-3 py-2 border rounded" />
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required class="w-full px-3 py-2 border rounded" />
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Role</label>
                <select name="role" class="w-full px-3 py-2 border rounded" required>
                    <option value="admin" <?php if($role == 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="student" <?php if($role == 'student') echo 'selected'; ?>>Student</option>
                </select>
            </div>
            <div class="flex justify-between">
                <a href="users.php" class="text-gray-600 hover:underline">Cancel</a>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Update</button>
            </div>
        </form>
    </div>
</body>
</html>
