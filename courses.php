<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - LMS Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
            <div class="p-6 text-2xl font-bold border-b border-gray-700">LMS Admin</div>
            <nav class="mt-6">
                <a href="admindash.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Dashboard</a>
                <a href="courses.php" class="block py-3 px-6 bg-gray-700 text-white font-semibold">Courses</a>
                <a href="users.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Users</a>
                <a href="students.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Students</a>
                <a href="settings.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Settings</a>
            </nav>
        </aside>
        <main class="flex-1 p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Courses</h1>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <p class="text-gray-700">This is the Courses page. List and manage courses here.</p>
            </div>
        </main>
    </div>
</body>
</html>
