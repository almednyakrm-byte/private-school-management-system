**edit_خدمات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$record = json_decode(file_get_contents('../backend/خدمات.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-2xl font-bold text-slate-900 mb-4">Edit Service</h1>
        <form id="edit-service-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $record['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $record['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">Update Service</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-service-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/خدمات.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_خدمات.php';
                        } else {
                            alert('Error updating service');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/خدمات.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(404);
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$record = array();
// Your database query to fetch the record
// For example:
// $record = mysqli_query($conn, "SELECT * FROM services WHERE id = '$id'");

// Output JSON response
echo json_encode($record);