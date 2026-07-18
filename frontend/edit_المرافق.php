**edit_المرافق.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/المرافق.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المرافق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Zb145n85HB9wNq0PueIQuanEE4K1s2YN54YHgtkElDUV+YKB4u/wYhMzGJC7yoy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-j0CNU7e38Dc3NC7GSxFrEUFhLnU8arB4nD7Y5S3S+5UrdIfgMNoU0GxOk89sPh8Co" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.10/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@0.27.2/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6pTY6bawfWVLzINCJXAyms6F3GXk4ZWtluK5OONSvlzFx8JTrfgCBZA=" crossorigin="anonymous"></script>
    <script src="edit_المرافق.js"></script>
</head>
<body>
    <div class="container mx-auto p-4 mt-12">
        <h1 class="text-3xl font-bold mb-4">تعديل المرافق</h1>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم المرافق</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $data['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">وصف المرافق</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= $data['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
        </form>
    </div>
</body>
</html>


**edit_المرافق.js**
javascript
$(document).ready(function() {
    // Fetch existing record details via GET
    axios.get('../backend/المرافق.php?id=<?= $_GET['id'] ?>')
        .then(response => {
            const data = response.data;
            $('#name').val(data.name);
            $('#description').val(data.description);
        })
        .catch(error => {
            console.error(error);
        });

    // Handle form submission
    $('#edit-form').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        axios.put('../backend/المرافق.php', formData)
            .then(response => {
                if (response.data.success) {
                    Swal.fire({
                        title: 'تم التعديل بنجاح',
                        text: 'تم تعديل المرافق بنجاح',
                        icon: 'success',
                    }).then(() => {
                        window.location.href = 'list_المرافق.php';
                    });
                } else {
                    Swal.fire({
                        title: 'خطأ',
                        text: 'حدث خطأ أثناء التعديل',
                        icon: 'error',
                    });
                }
            })
            .catch(error => {
                console.error(error);
            });
    });
});


**backend/المرافق.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch existing record details
$id = $_GET['id'];
$sql = "SELECT * FROM المرافق WHERE id = '$id'";
$result = $conn->query($sql);

// Check if record exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(array('error' => 'Record not found'));
}

// Close connection
$conn->close();


**backend/edit_المرافق.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update record
$id = $_GET['id'];
$name = $_POST['name'];
$description = $_POST['description'];

$sql = "UPDATE المرافق SET name = '$name', description = '$description' WHERE id = '$id'";
if ($conn->query($sql) === TRUE) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('success' => false));
}

// Close connection
$conn->close();