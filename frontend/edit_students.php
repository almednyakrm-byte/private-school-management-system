**edit_students.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get student ID from URL
$id = $_GET['id'];

// Fetch student details via AJAX
$studentDetails = json_decode(file_get_contents('../backend/students.php?id=' . $id), true);

// Check if student exists
if (empty($studentDetails)) {
    echo 'Student not found!';
    exit;
}

// Set page title and mod slug
$pageTitle = 'Edit Student';
$modSlug = 'students';

// Include header and navigation
include_once '../includes/header.php';
include_once '../includes/navigation.php';
?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 pt-6">
    <h1 class="text-3xl font-bold text-blue-500 mb-4"><?= $pageTitle ?></h1>

    <!-- Form -->
    <form id="edit-student-form" class="bg-white p-4 rounded shadow-md">
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 text-sm text-gray-700 border-gray-300 rounded" value="<?= $studentDetails['name'] ?>">
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
            <input type="email" id="email" name="email" class="block w-full p-2 text-sm text-gray-700 border-gray-300 rounded" value="<?= $studentDetails['email'] ?>">
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone:</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 text-sm text-gray-700 border-gray-300 rounded" value="<?= $studentDetails['phone'] ?>">
        </div>
        <button type="submit" class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded">Update Student</button>
    </form>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Populate form fields
            $('#name').val('<?= $studentDetails['name'] ?>');
            $('#email').val('<?= $studentDetails['email'] ?>');
            $('#phone').val('<?= $studentDetails['phone'] ?>');

            // Submit form via AJAX
            $('#edit-student-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/students.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $modSlug ?>.php';
                        } else {
                            alert('Error updating student!');
                        }
                    }
                });
            });
        });
    </script>
</main>

<!-- Include footer -->
<?php include_once '../includes/footer.php'; ?>


**students.php (backend)**

<?php
// Check if student ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'Student ID not set!'));
    exit;
}

// Connect to database
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
    echo json_encode(array('error' => 'Database connection failed!'));
    exit;
}

// Get student details
$sql = "SELECT * FROM students WHERE id = '" . $_GET['id'] . "'";
$result = mysqli_query($conn, $sql);

// Check if student exists
if (mysqli_num_rows($result) == 0) {
    echo json_encode(array('error' => 'Student not found!'));
    exit;
}

// Fetch student details
$studentDetails = mysqli_fetch_assoc($result);

// Close database connection
mysqli_close($conn);

// Output student details
echo json_encode($studentDetails);
?>


**list_students.php (example)**

<?php
// Include header and navigation
include_once '../includes/header.php';
include_once '../includes/navigation.php';
?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 pt-6">
    <h1 class="text-3xl font-bold text-blue-500 mb-4">Student List</h1>

    <!-- Table -->
    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left">Name</th>
                <th class="px-4 py-2 text-left">Email</th>
                <th class="px-4 py-2 text-left">Phone</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Connect to database
            $conn = mysqli_connect('localhost', 'username', 'password', 'database');

            // Check connection
            if (!$conn) {
                echo 'Database connection failed!';
                exit;
            }

            // Get student list
            $sql = "SELECT * FROM students";
            $result = mysqli_query($conn, $sql);

            // Check if student list is empty
            if (mysqli_num_rows($result) == 0) {
                echo 'No students found!';
                exit;
            }

            // Fetch student list
            while ($student = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td class="px-4 py-2"><?= $student['name'] ?></td>
                    <td class="px-4 py-2"><?= $student['email'] ?></td>
                    <td class="px-4 py-2"><?= $student['phone'] ?></td>
                    <td class="px-4 py-2">
                        <a href="edit_students.php?id=<?= $student['id'] ?>" class="text-orange-300 hover:text-orange-400">Edit</a>
                        <a href="#" class="text-red-500 hover:text-red-600">Delete</a>
                    </td>
                </tr>
                <?php
            }

            // Close database connection
            mysqli_close($conn);
            ?>
        </tbody>
    </table>
</main>

<!-- Include footer -->
<?php include_once '../includes/footer.php'; ?>