<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$successMsg = $errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $nic_number = $conn->real_escape_string($_POST['nic_number']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $address = $conn->real_escape_string($_POST['address']);
    $contact_number = $conn->real_escape_string($_POST['contact_number']);
    $email = $conn->real_escape_string($_POST['email']);
    $course = $conn->real_escape_string($_POST['courses']);
    $defaultPassword = md5("123456"); // default password

    // Step 1: Insert into Users table
    $userSql = "INSERT INTO Users (name, email, password, role)
                VALUES ('$full_name', '$email', '$defaultPassword', 'student')";

    if ($conn->query($userSql) === TRUE) {
        $user_id = $conn->insert_id; // Get user_id

        // Step 2: Insert into Student table
        $studentSql = "INSERT INTO Student (user_id, nic, gender, address, contact, course)
                       VALUES ('$user_id', '$nic_number', '$gender', '$address', '$contact_number', '$course')";

        if ($conn->query($studentSql) === TRUE) {
            $successMsg = "Student registered successfully!";
        } else {
            $errorMsg = "Error inserting into Student table: " . $conn->error;
        }
    } else {
        $errorMsg = "Error inserting into Users table: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    // $conn = new mysqli(...); // your DB connection
    $stmt = $conn->prepare("DELETE FROM Student WHERE student_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: students.php");
    exit();
}

$modules = [];
$moduleResult = $conn->query("SELECT id, name FROM modules ORDER BY name ASC");
if ($moduleResult && $moduleResult->num_rows > 0) {
    while ($mod = $moduleResult->fetch_assoc()) {
        $modules[] = $mod;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<div class="flex flex-col md:flex-row min-h-screen">
    <aside class="w-full md:w-64 bg-gray-800 text-white flex-shrink-0 min-h-screen">
        <div class="p-6 text-2xl font-bold border-b border-gray-700">LMS Admin</div>
        <nav class="mt-6">
            <a href="admindash.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Dashboard</a>
            <a href="courses.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Modules</a>
            <a href="users.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Users</a>
            <a href="students.php" class="block py-3 px-6 bg-gray-700 text-white font-semibold">Students</a>
            <a href="settings.php" class="block py-3 px-6 hover:bg-gray-700 transition duration-200">Settings</a>
        </nav>
    </aside>
    <main class="flex-1 p-4 sm:p-6 md:p-10">
        <?php if ($successMsg): ?>
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded"><?php echo $successMsg; ?></div>
        <?php endif; ?>
        <?php if ($errorMsg): ?>
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded"><?php echo $errorMsg; ?></div>
        <?php endif; ?>
        <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Students</h1>
            <form method="get" class="flex items-center w-full sm:w-auto gap-2">
                <input type="text" name="search" placeholder="Search students..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="px-3 py-2 border border-gray-300 rounded w-full sm:w-64" />
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">Search</button>
            </form>
            <button onclick="document.getElementById('registerModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2 rounded shadow transition w-full sm:w-auto">+ Register Student</button>
        </div>
        <!-- Students Table Placeholder -->
        <div class="bg-white rounded shadow p-2 sm:p-4 md:p-6 overflow-x-auto">
            <table class="min-w-full table-auto text-sm">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-2 sm:px-4 py-2 text-left">Full Name</th>
                        <th class="px-2 sm:px-4 py-2 text-left">NIC</th>
                        <th class="px-2 sm:px-4 py-2 text-left">Gender</th>
                        <th class="px-2 sm:px-4 py-2 text-left">Contact</th>
                        <th class="px-2 sm:px-4 py-2 text-left">Email</th>
                        <th class="px-2 sm:px-4 py-2 text-left">Course</th>
                        <th class="px-2 sm:px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Fetch students from the database
                $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
                $sql = "SELECT s.student_id, u.name, s.nic, s.gender, s.contact, u.email, s.course
                        FROM Student s
                        JOIN Users u ON s.user_id = u.user_id";
                if ($search !== '') {
                    $sql .= " WHERE u.name LIKE '%$search%' OR u.email LIKE '%$search%' OR s.nic LIKE '%$search%' OR s.course LIKE '%$search%'";
                }
                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                ?>
                    <tr class="border-b">
                        <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($row['nic']); ?></td>
                        <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($row['gender']); ?></td>
                        <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($row['contact']); ?></td>
                        <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($row['email']); ?></td>
                        <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($row['course']); ?></td>
                        <td class="px-2 sm:px-4 py-2">
                            <a href="edit_student.php?id=<?php echo $row['student_id']; ?>" class="text-blue-600 hover:underline mr-2">Edit</a>
                            <form action="students.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                <input type="hidden" name="delete_id" value="<?php echo $row['student_id']; ?>">
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="7" class="px-2 sm:px-4 py-4 text-center text-gray-500">No students found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Modal for Register Student -->
        <div id="registerModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-2 p-4 sm:p-8 relative">
                <button onclick="document.getElementById('registerModal').classList.add('hidden')" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
                <h2 class="text-2xl font-bold mb-6 text-center">Register Student</h2>
                <form method="post" action="">
                    <div class="mb-4">
                        <label for="full_name" class="block font-semibold mb-1">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required class="w-full px-3 py-2 border border-gray-300 rounded">
                    </div>
                    <div class="mb-4">
                        <label for="nic_number" class="block font-semibold mb-1">NIC Number</label>
                        <input type="text" id="nic_number" name="nic_number" required class="w-full px-3 py-2 border border-gray-300 rounded">
                    </div>
                    <div class="mb-4">
                        <label for="gender" class="block font-semibold mb-1">Gender</label>
                        <select id="gender" name="gender" required class="w-full px-3 py-2 border border-gray-300 rounded">
                            <option value="">--Select Gender--</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="address" class="block font-semibold mb-1">Address</label>
                        <textarea id="address" name="address" rows="2" required class="w-full px-3 py-2 border border-gray-300 rounded"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="contact_number" class="block font-semibold mb-1">Contact Number</label>
                        <input type="text" id="contact_number" name="contact_number" required class="w-full px-3 py-2 border border-gray-300 rounded">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block font-semibold mb-1">Email</label>
                        <input type="email" id="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded">
                    </div>
                    <div class="mb-6">
                        <label for="courses" class="block font-semibold mb-1">Modules</label>
                        <select id="courses" name="courses" required class="w-full px-3 py-2 border border-gray-300 rounded">
                            <option value="">--Select Course--</option>
                            <?php foreach ($modules as $mod): ?>
                                <option value="<?php echo htmlspecialchars($mod['id']); ?>">
                                    <?php echo htmlspecialchars($mod['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-end gap-2">
                        <button type="button" onclick="document.getElementById('registerModal').classList.add('hidden')" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-700 w-full sm:w-auto">Cancel</button>
                        <button type="submit" class="px-6 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white font-semibold w-full sm:w-auto">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
</body>
</html>