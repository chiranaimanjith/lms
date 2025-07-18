<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
// Add database connection and student count
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$student_count = 0;
$sql = "SELECT COUNT(*) as total FROM Student";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $student_count = $row['total'];
}
// Fetch total modules count
$module_count = 0;
$sql = "SELECT COUNT(*) as total FROM modules";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $module_count = $row['total'];
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">
        <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
            <div class="p-6 text-2xl font-bold border-b border-gray-700">LMS Admin</div>
            <nav class="mt-6">
                <a href="#" class="block py-3 px-6 bg-gray-700 text-white font-semibold">Dashboard</a>
                <a href="courses.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Modules</a>
                <a href="users.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Users</a>
                <a href="students.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Students</a>
                <a href="settings.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Settings</a>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            <header class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Welcome, Admin!</h1>
                <div class="flex items-center space-x-4">
                    <button onclick="window.location.href='courses.php'" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-200">
  Add New Module
</button>
                    <!-- <img src="https://i.pravatar.cc/40?u=admin" alt="Admin Avatar" class="w-10 h-10 rounded-full"> -->
                    <form action="logout.php" method="post" class="inline">
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-200">Logout</button>
                    </form>
                </div>
            </header>

            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg font-semibold text-gray-600">Total Students</h2>
                    <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo number_format($student_count); ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg font-semibold text-gray-600">Total Modules</h2>
                    <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo number_format($module_count); ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg font-semibold text-gray-600">Modules Active</h2>
                    <p class="text-3xl font-bold text-gray-900 mt-2">6</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg font-semibold text-gray-600">Revenue (Month)</h2>
                    <p class="text-3xl font-bold text-gray-900 mt-2">Rs.12,300</p>
                </div>
            </section>

            <section class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Enrollments</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="p-3 font-semibold">Student Name</th>
                                <th class="p-3 font-semibold">Module Name</th>
                                <th class="p-3 font-semibold">Date</th>
                                <th class="p-3 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">Saman Kumara</td>
                                <td class="p-3">Programming Fundamentals</td>
                                <td class="p-3">2025-07-14</td>
                                <td class="p-3"><span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">Paid</span></td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">Kamara Rajapaksha</td>
                                <td class="p-3">Web Development</td>
                                <td class="p-3">2025-07-13</td>
                                <td class="p-3"><span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">Paid</span></td>
                            </tr>
                             <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">Dasun Shanaka</td>
                                <td class="p-3">Mobile Development</td>
                                <td class="p-3">2025-07-13</td>
                                <td class="p-3"><span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm">Pending</span></td>
                            </tr>
                             <tr class="hover:bg-gray-50">
                                <td class="p-3">Ravindu Lakmal</td>
                                <td class="p-3">Cloud Computing</td>
                                <td class="p-3">2025-07-12</td>
                                <td class="p-3"><span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">Paid</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

</body>
</html>