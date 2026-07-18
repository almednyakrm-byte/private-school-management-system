**create_مواعيد.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);

    if (empty($name) || empty($description) || empty($date) || empty($time)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $query = "INSERT INTO مواعيد (name, description, date, time) VALUES ('$name', '$description', '$date', '$time')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list_{mod_slug}.php
            header('Location: list_مواعيد.php');
            exit;
        } else {
            $error = 'Error inserting data';
        }
    }
}

// Include header
require_once '../backend/header.php';

// Include premium Tailwind UI form
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-8 xl:p-12 2xl:p-16">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12 2xl:p-16">
        <h2 class="text-slate-900 text-lg font-bold mb-4">Create New مواعيد</h2>
        <form id="create-form" class="space-y-4" method="POST">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label for="name" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">Name</label>
                    <input type="text" id="name" name="name" class="appearance-none block w-full bg-gray-50 text-gray-900 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label for="description" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">Description</label>
                    <textarea id="description" name="description" class="appearance-none block w-full bg-gray-50 text-gray-900 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 xl:w-1/3 px-3 mb-6 md:mb-0">
                    <label for="date" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">Date</label>
                    <input type="date" id="date" name="date" class="appearance-none block w-full bg-gray-50 text-gray-900 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div class="w-full md:w-1/2 xl:w-1/3 px-3 mb-6 md:mb-0">
                    <label for="time" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">Time</label>
                    <input type="time" id="time" name="time" class="appearance-none block w-full bg-gray-50 text-gray-900 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
        </form>
        <?php if (isset($error)) : ?>
            <p class="text-red-500 text-xs mt-2"><?= $error ?></p>
        <?php endif; ?>
    </div>
</div>

<?php
// Include footer
require_once '../backend/footer.php';
?>


**create_مواعيد.js**
javascript
$(document).ready(function() {
    $('#create-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '../backend/مواعيد.php',
            data: $(this).serialize(),
            success: function(response) {
                if (response === 'success') {
                    window.location.href = 'list_مواعيد.php';
                } else {
                    alert('Error creating new مواعيد');
                }
            }
        });
    });
});


**مواعيد.php (backend)**

<?php
// Include database connection
require_once 'db.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);

    if (empty($name) || empty($description) || empty($date) || empty($time)) {
        echo 'error';
    } else {
        // Insert data into database
        $query = "INSERT INTO مواعيد (name, description, date, time) VALUES ('$name', '$description', '$date', '$time')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
}