**edit_المركبات.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/المركبات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (!isset($data['id'])) {
    echo 'Error: Record not found.';
    exit;
}

// Set form fields
$name = $data['name'];
$description = $data['description'];
$price = $data['price'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit المركبات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-emerald-600">Edit المركبات</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-sm font-bold text-gray-700">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 border border-gray-300 rounded-md" value="<?= $name ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-bold text-gray-700">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 border border-gray-300 rounded-md"><?= $description ?></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-bold text-gray-700">Price:</label>
                <input type="number" id="price" name="price" class="block w-full p-2 border border-gray-300 rounded-md" value="<?= $price ?>">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/المركبات.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_المركبات.php';
                        } else {
                            alert('Error updating record.');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/المركبات.php**

<?php
// Check if id exists
if (!isset($_GET['id'])) {
    echo 'Error: ID not found.';
    exit;
}

// Get id
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch existing record details
$query = "SELECT * FROM المركبات WHERE id = '$id'";
$result = $conn->query($query);

// Check if record exists
if ($result->num_rows > 0) {
    // Fetch record details
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo 'Error: Record not found.';
    exit;
}

// Close connection
$conn->close();
?>


**backend/update.php**

<?php
// Check if id exists
if (!isset($_GET['id'])) {
    echo 'Error: ID not found.';
    exit;
}

// Get id
$id = $_GET['id'];

// Get form data
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to update record
$query = "UPDATE المركبات SET name = '$name', description = '$description', price = '$price' WHERE id = '$id'";
$conn->query($query);

// Check if update was successful
if ($conn->affected_rows > 0) {
    echo 'success';
} else {
    echo 'Error updating record.';
}

// Close connection
$conn->close();
?>