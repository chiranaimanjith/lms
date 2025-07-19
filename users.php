<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
            <div class="p-6 text-2xl font-bold border-b border-gray-700">LMS Admin</div>
            <nav class="mt-6">
                <a href="admindash.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Dashboard</a>
                <a href="courses.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Modules</a>
                <a href="users.php" class="block py-3 px-6 bg-gray-700 text-white font-semibold">Users</a>
                <a href="students.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Students</a>
                <a href="settings.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Settings</a>
            </nav>
        </aside>
        <main class="flex-1 p-8">
            <header class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Users</h1>
                <a href="register_user.php" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-200">Register New User</a>
            </header>
            <section class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-gray-800 mb-4">User List</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="p-3 font-semibold">Name</th>
                                <th class="p-3 font-semibold">Email</th>
                                <th class="p-3 font-semibold">Role</th>
                                <th class="p-3 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Connect to database
                            $conn = new mysqli("localhost", "root", "", "lms");
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }
                            $sql = "SELECT user_id, name, email, role FROM users";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo '<tr class="border-b hover:bg-gray-50">';
                                    echo '<td class="p-3">' . htmlspecialchars($row['name']) . '</td>';
                                    echo '<td class="p-3">' . htmlspecialchars($row['email']) . '</td>';
                                    echo '<td class="p-3">' . htmlspecialchars($row['role']) . '</td>';
                                    echo '<td class="p-3">
                                            <a href="edit_user.php?id=' . $row['user_id'] . '" class="text-blue-600 hover:underline">Edit</a>
                                            <a href="delete_user.php?id=' . $row['user_id'] . '" class="text-red-600 hover:underline ml-2" onclick="return confirm(\'Are you sure?\')">Delete</a>
                                          </td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4" class="p-3 text-gray-500">No users found.</td></tr>';
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
