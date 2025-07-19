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
    <title>LMS Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 font-sans">

    <div class="flex min-h-screen">
        <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
            <div class="p-6 text-2xl font-bold border-b border-gray-700">My LMS</div>
            <nav class="mt-6">
                <a href="#" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Dashboard</a>
                <a href="#" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">My Courses</a>
                <a href="#" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Grades</a>
                <a href="#" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Calendar</a>
                <a href="#" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Profile</a>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            <header class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Welcome, Alex!</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Your Progress: 68%</span>
                    <img src="https://i.pravatar.cc/40?u=student" alt="Student Avatar" class="w-10 h-10 rounded-full">
                </div>
                <form action="logout.php" method="post" class="inline">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-200">Logout</button>
                </form>
            </header>

            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">My Courses</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                        <div class="bg-gray-200 h-40 flex items-center justify-center text-gray-400">Course Image</div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2">Introduction to Web Development</h3>
                            <p class="text-gray-600 mb-4">Learn the fundamentals of HTML, CSS, and JavaScript.</p>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 75%"></div>
                            </div>
                            <span class="text-sm text-gray-500">75% Complete</span>
                            <a href="#" class="mt-4 block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-200">Continue Learning</a>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                        <div class="bg-gray-200 h-40 flex items-center justify-center text-gray-400">Course Image</div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2">Advanced CSS Techniques</h3>
                            <p class="text-gray-600 mb-4">Master Flexbox, Grid, and modern CSS animations.</p>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 45%"></div>
                            </div>
                            <span class="text-sm text-gray-500">45% Complete</span>
                            <a href="#" class="mt-4 block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-200">Continue Learning</a>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                        <div class="bg-gray-200 h-40 flex items-center justify-center text-gray-400">Course Image</div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2">Data Structures in Python</h3>
                            <p class="text-gray-600 mb-4">Explore lists, dictionaries, trees, and graphs.</p>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 10%"></div>
                            </div>
                            <span class="text-sm text-gray-500">10% Complete</span>
                            <a href="#" class="mt-4 block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-200">Start Course</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Upcoming Deadlines</h2>
                    <ul>
                        <li class="flex justify-between items-center py-2 border-b">
                            <span>Web Dev - Final Project</span>
                            <span class="font-semibold text-red-600">July 25, 2025</span>
                        </li>
                        <li class="flex justify-between items-center py-2 border-b">
                            <span>Advanced CSS - Assignment 3</span>
                            <span class="font-semibold">August 05, 2025</span>
                        </li>
                        <li class="flex justify-between items-center py-2">
                            <span>Python - Quiz 1</span>
                            <span class="font-semibold">August 10, 2025</span>
                        </li>
                    </ul>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Announcements</h2>
                    <ul>
                        <li class="py-2 border-b">
                            <p class="font-semibold">System Maintenance</p>
                            <p class="text-sm text-gray-600">The LMS will be down for maintenance on July 20th from 2-4 AM.</p>
                        </li>
                        <li class="py-2">
                            <p class="font-semibold">New Course Available!</p>
                            <p class="text-sm text-gray-600">Check out the new "React for Beginners" course in the catalog.</p>
                        </li>
                    </ul>
                </div>
            </section>

        </main>
    </div>

</body>

</html>