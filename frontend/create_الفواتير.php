**create_الفواتير.php**

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

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $errors = [];
    if (empty($_POST['name'])) {
        $errors[] = 'Name is required';
    }
    if (empty($_POST['description'])) {
        $errors[] = 'Description is required';
    }
    if (empty($_POST['amount'])) {
        $errors[] = 'Amount is required';
    }
    if (empty($_POST['date'])) {
        $errors[] = 'Date is required';
    }

    // If no errors, insert data into database
    if (empty($errors)) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $amount = $_POST['amount'];
        $date = $_POST['date'];

        $sql = "INSERT INTO الفواتير (name, description, amount, date) VALUES ('$name', '$description', '$amount', '$date')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Redirect back to list page
            header('Location: list_الفواتير.php');
            exit;
        } else {
            $errors[] = 'Error inserting data';
        }
    }
}

// Include header and footer
require_once '../backend/header.php';
require_once '../backend/footer.php';
?>

<!-- Create الفواتير form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold mb-4">Create New الفواتير</h2>
    <form id="create-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
            <input type="number" id="amount" name="amount" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" required>
        </div>
        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
            <input type="date" id="date" name="date" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" required>
        </div>
        <button type="submit" name="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Create</button>
    </form>
</div>

<!-- Include AJAX script -->
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/الفواتير.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_الفواتير.php';
                    } else {
                        alert('Error creating الفواتير');
                    }
                }
            });
        });
    });
</script>


**alfawateer.php (backend)**

<?php
// Include database connection
require_once '../backend/db.php';

// Check if form data has been sent
if (isset($_POST['submit'])) {
    // Validate form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    // Insert data into database
    $sql = "INSERT INTO الفواتير (name, description, amount, date) VALUES ('$name', '$description', '$amount', '$date')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo 'success';
    } else {
        echo 'Error creating الفواتير';
    }
}