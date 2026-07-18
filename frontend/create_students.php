**create_students.php**

<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

require_once '../backend/config.php';

$mod_slug = 'students';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-blue-500 mb-4">Create Student</h1>
        <form id="create-student-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="John Doe">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="john.doe@example.com">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone:</label>
                <input type="tel" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="123-456-7890">
            </div>
            <div class="mb-4">
                <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address:</label>
                <textarea id="address" name="address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="123 Main St, Anytown, USA"></textarea>
            </div>
            <button type="submit" class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded">Create Student</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-student-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../backend/students.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                        } else {
                            alert('Error creating student: ' + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error creating student: ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>

**students.php (backend)**

<?php
require_once '../backend/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $query = "INSERT INTO students (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$address')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'Error creating student: ' . mysqli_error($conn);
    }
}
?>

Note: This code assumes you have a `config.php` file that sets up the database connection and a `students` table with the necessary columns. You'll need to modify the code to fit your specific database schema and configuration. Additionally, this code does not include any validation or sanitization of user input, which is a security risk. You should add proper validation and sanitization to prevent SQL injection and other security vulnerabilities.