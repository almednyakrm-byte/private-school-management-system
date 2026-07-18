<!-- edit_التلاميذ.php -->
<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

require_once '../backend/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $record = fetchRecord($id);
}

function fetchRecord($id) {
    global $conn;
    $query = "SELECT * FROM التلاميذ WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $record = mysqli_fetch_assoc($result);
    return $record;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل التلاميذ</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">تعديل التلاميذ</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <input type="hidden" id="id" name="id" value="<?php echo $record['id']; ?>">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">الاسم</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $record['name']; ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $record['email']; ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $record['phone']; ?>">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
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
                    url: '../backend/التلاميذ.php',
                    data: formData,
                    success: function(response) {
                        window.location.href = 'list_التلاميذ.php';
                    }
                });
            });
        });
    </script>
</body>
</html>



// backend/config.php
<?php
$conn = mysqli_connect('localhost', 'username', 'password', 'database');
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}
?>



// backend/التلاميذ.php
<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $record = fetchRecord($id);
}

if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $query = "UPDATE التلاميذ SET name = '$name', email = '$email', phone = '$phone' WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'Record updated successfully';
    } else {
        echo 'Error updating record';
    }
}

function fetchRecord($id) {
    global $conn;
    $query = "SELECT * FROM التلاميذ WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $record = mysqli_fetch_assoc($result);
    return $record;
}
?>