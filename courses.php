<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
$host = 'localhost';
$db   = 'lms';
$user = 'root'; 
$pass = '';    

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add Module
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $conn->query("INSERT INTO modules (name, description) VALUES ('$name', '$description')");
    header("Location: courses.php");
    exit();
}

// Edit Module
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $conn->query("UPDATE modules SET name='$name', description='$description' WHERE id=$id");
    header("Location: courses.php");
    exit();
}

// Delete Module
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM modules WHERE id=$id");
    header("Location: courses.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules - LMS Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    // Modal logic
    let editingId = null;
    function openModal(id = null, name = '', description = '') {
        document.getElementById('modal').classList.remove('hidden');
        document.getElementById('moduleForm').reset();
        if (id) {
            document.getElementById('modalTitle').innerText = 'Edit Module';
            document.getElementById('form-action').value = 'edit';
            document.getElementById('module-id').value = id;
            document.getElementById('module-name').value = name;
            document.getElementById('module-description').value = description;
        } else {
            document.getElementById('modalTitle').innerText = 'Add Module';
            document.getElementById('form-action').value = 'add';
            document.getElementById('module-id').value = '';
            document.getElementById('module-name').value = '';
            document.getElementById('module-description').value = '';
        }
    }
    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this module?')) {
            window.location.href = 'courses.php?delete=' + id;
        }
    }
    </script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
            <div class="p-6 text-2xl font-bold border-b border-gray-700">LMS Admin</div>
            <nav class="mt-6">
                <a href="admindash.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Dashboard</a>
                <a href="courses.php" class="block py-3 px-6 bg-gray-700 text-white font-semibold">Modules</a>
                <a href="users.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Users</a>
                <a href="students.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Students</a>
                <a href="settings.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Settings</a>
            </nav>
        </aside>
        <main class="flex-1 p-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Modules</h1>
                <button onclick="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Module</button>
            </div>
            <form class="mb-4 flex" method="get">
                <input type="text" name="search" placeholder="Search modules..." class="px-4 py-2 border rounded-l w-64 focus:outline-none" />
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-r hover:bg-gray-700">Search</button>
            </form>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Description</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $search = isset($_GET['search']) ? $_GET['search'] : '';
                        $sql = "SELECT * FROM modules";
                        if (!empty($search)) {
                            $search = mysqli_real_escape_string($conn, $search);
                            $sql .= " WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
                        }
                        $sql .= " ORDER BY id DESC"; // Order by ID for consistency
                        $result = $conn->query($sql);

                        if ($result) {
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr class='border-b'>";
                                    echo "<td class='px-4 py-2'>" . htmlspecialchars($row['id']) . "</td>";
                                    echo "<td class='px-4 py-2'>" . htmlspecialchars($row['name']) . "</td>";
                                    echo "<td class='px-4 py-2'>" . htmlspecialchars($row['description']) . "</td>";
                                    echo "<td class='px-4 py-2'>";
                                    // Pass data to JS for edit
                                    echo "<button onclick=\"openModal('" . htmlspecialchars($row['id']) . "', '" . htmlspecialchars(addslashes($row['name'])) . "', '" . htmlspecialchars(addslashes($row['description'])) . "')\" class='text-blue-600 hover:underline mr-2'>Edit</button>";
                                    echo "<button onclick=\"confirmDelete('" . htmlspecialchars($row['id']) . "')\" class='text-red-600 hover:underline'>Delete</button>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='px-4 py-2 text-center'>No modules found.</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='px-4 py-2 text-center'>Error fetching modules: " . $conn->error . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- Modal -->
            <div id="modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-lg p-8 w-full max-w-md relative">
                    <h2 id="modalTitle" class="text-xl font-bold mb-4">Add Module</h2>
                    <form id="moduleForm" method="post" action="">
                        <input type="hidden" name="action" id="form-action" value="add">
                        <input type="hidden" name="id" id="module-id" value="">
                        <div class="mb-4">
                            <label class="block mb-1 font-semibold">Name</label>
                            <input type="text" name="name" id="module-name" class="w-full border px-3 py-2 rounded" required />
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 font-semibold">Description</label>
                            <textarea name="description" id="module-description" class="w-full border px-3 py-2 rounded" required></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" onclick="closeModal()" class="mr-2 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                        </div>
                    </form>
                    <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
