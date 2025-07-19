<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TODO: Add user registration logic here (e.g., insert into database)
    // For now, just redirect back to users.php
    header("Location: users.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
            <div class="p-6 text-2xl font-bold border-b border-gray-700">LMS Admin</div>
            <nav class="mt-6">
                <a href="admindash.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Dashboard</a>
                <a href="#" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Modules</a>
                <a href="users.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Users</a>
                <a href="students.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Students</a>
                <a href="#" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Settings</a>
            </nav>
        </aside>
        <main class="flex-1 p-8">
            <header class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Register New User</h1>
            </header>
            <section class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
                <form action="register_user.php" method="post" class="space-y-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2" for="name">Name</label>
                        <input type="text" id="name" name="name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2" for="email">Email</label>
                        <input type="email" id="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2" for="password">Password</label>
                        <input type="password" id="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2" for="role">Role</label>
                        <select id="role" name="role" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="Student">Student</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-200">Register</button>
                        <a href="users.php" class="ml-4 text-gray-600 hover:underline">Cancel</a>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
