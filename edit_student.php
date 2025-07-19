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

$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$successMsg = $errorMsg = "";

// Fetch modules for course dropdown
$modules = [];
$moduleResult = $conn->query("SELECT id, name FROM modules ORDER BY name ASC");
if ($moduleResult && $moduleResult->num_rows > 0) {
    while ($mod = $moduleResult->fetch_assoc()) {
        $modules[] = $mod;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $nic_number = trim($_POST['nic_number']);
    $gender = trim($_POST['gender']);
    $address = trim($_POST['address']);
    $contact_number = trim($_POST['contact_number']);
    $email = trim($_POST['email']);
    $course = trim($_POST['courses']);

    // Get user_id from student_id
    $stmt = $conn->prepare("SELECT user_id FROM Student WHERE student_id=?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    // Update Users table
    $stmt = $conn->prepare("UPDATE Users SET name=?, email=? WHERE user_id=?");
    $stmt->bind_param("ssi", $full_name, $email, $user_id);
    $stmt->execute();
    $stmt->close();

    // Update Student table
    $stmt = $conn->prepare("UPDATE Student SET nic=?, gender=?, address=?, contact=?, course=? WHERE student_id=?");
    $stmt->bind_param("sssssi", $nic_number, $gender, $address, $contact_number, $course, $student_id);
    $stmt->execute();
    $stmt->close();

    $conn->close();
    header("Location: students.php");
    exit();
}

// Fetch student and user data
$sql = "SELECT s.nic, s.gender, s.address, s.contact, s.course, u.name, u.email FROM Student s JOIN Users u ON s.user_id = u.user_id WHERE s.student_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->bind_result($nic, $gender, $address, $contact, $course, $name, $email);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-lg">
        <h1 class="text-2xl font-bold mb-6">Edit Student</h1>
        <form method="POST">
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($name); ?>" required class="w-full px-3 py-2 border rounded" />
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">NIC Number</label>
                <input type="text" name="nic_number" value="<?php echo htmlspecialchars($nic); ?>" required class="w-full px-3 py-2 border rounded" />
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Gender</label>
                <select name="gender" class="w-full px-3 py-2 border rounded" required>
                    <option value="male" <?php if($gender == 'male') echo 'selected'; ?>>Male</option>
                    <option value="female" <?php if($gender == 'female') echo 'selected'; ?>>Female</option>
                    <option value="other" <?php if($gender == 'other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Address</label>
                <textarea name="address" rows="2" required class="w-full px-3 py-2 border rounded"><?php echo htmlspecialchars($address); ?></textarea>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Contact Number</label>
                <input type="text" name="contact_number" value="<?php echo htmlspecialchars($contact); ?>" required class="w-full px-3 py-2 border rounded" />
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required class="w-full px-3 py-2 border rounded" />
            </div>
            <div class="mb-6">
                <label class="block mb-1 font-semibold">Modules</label>
                <select name="courses" class="w-full px-3 py-2 border rounded" required>
                    <option value="">--Select Course--</option>
                    <?php foreach ($modules as $mod): ?>
                        <option value="<?php echo htmlspecialchars($mod['id']); ?>" <?php if($course == $mod['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($mod['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex justify-between">
                <a href="students.php" class="text-gray-600 hover:underline">Cancel</a>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Update</button>
            </div>
        </form>
    </div>
</body>
</html> 