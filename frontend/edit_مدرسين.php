**edit_مدرسين.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$url = '../backend/مدرسين.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data was fetched successfully
if ($data) {
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
} else {
    echo 'Error fetching data';
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مدرس</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded shadow-md">
        <h1 class="text-2xl font-bold text-slate-900 mb-4">تعديل مدرس</h1>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">اسم المدرس</label>
                <input type="text" id="name" name="name" value="<?= $name ?>" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-slate-900">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" value="<?= $email ?>" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-slate-900">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" value="<?= $phone ?>" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-lg hover:bg-indigo-700 focus:ring-indigo-500 focus:border-indigo-500">تعديل</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مدرسين.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating record');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr, status, error);
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مدرسين.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'ID not set'));
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details
$query = "SELECT * FROM مدرسين WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Check if data was fetched successfully
if ($data) {
    echo json_encode($data);
} else {
    echo json_encode(array('error' => 'Error fetching data'));
}
?>

Note: This code assumes you have a MySQL database connection established in your backend script. You should replace the `mysqli_query` and `mysqli_fetch_assoc` functions with your actual database connection and query methods.