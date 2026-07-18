**create_المرافق.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    // Check for empty fields
    if (empty($name) || empty($description) || empty($address) || empty($phone) || empty($email)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO المرافق (name, description, address, phone, email) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $description, $address, $phone, $email);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_المرافق.php');
        exit;
    }
}

// Include header and navigation
require_once '../includes/header.php';
?>

<!-- Create new record form -->
<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-lg font-medium text-gray-900">Create New المرافق</h2>
    <form id="create-form" class="mt-4 space-y-6" method="POST">
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <input type="text" id="address" name="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>
        </div>
        <button type="submit" id="submit-btn" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>


**create_المرافق.js**
javascript
// Get form elements
const form = document.getElementById('create-form');
const submitBtn = document.getElementById('submit-btn');

// Add event listener to form submit
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    try {
        const response = await fetch('../backend/المرافق.php', {
            method: 'POST',
            body: formData,
        });
        const data = await response.json();
        if (data.success) {
            window.location.href = 'list_المرافق.php';
        } else {
            console.error(data.error);
        }
    } catch (error) {
        console.error(error);
    }
});


**../backend/المرافق.php**

<?php
// Include database connection
require_once '../config/database.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['address']) && isset($_POST['phone']) && isset($_POST['email'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    // Check for empty fields
    if (empty($name) || empty($description) || empty($address) || empty($phone) || empty($email)) {
        echo json_encode(['success' => false, 'error' => 'Please fill in all fields']);
        exit;
    }

    // Insert data into database
    $sql = "INSERT INTO المرافق (name, description, address, phone, email) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $description, $address, $phone, $email);
    $stmt->execute();

    // Return success message
    echo json_encode(['success' => true]);
    exit;
}