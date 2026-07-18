**edit_موظفين.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$url = '../backend/موظفين.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (isset($data['error'])) {
    echo 'Error: ' . $data['error'];
    exit;
}

// Set page title and mod slug
$page_title = 'تعديل موظف';
$mod_slug = 'موظفين';

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6">
    <h1 class="text-3xl font-bold text-emerald-600 mb-4"><?= $page_title ?></h1>

    <form id="edit-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم الموظف</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $data['name'] ?>">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
            <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $data['email'] ?>">
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">رقم الهاتف</label>
            <input type="tel" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $data['phone'] ?>">
        </div>

        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">تعديل</button>
    </form>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/موظفين.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('phone').value = data.phone;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();

        // Create AJAX PUT request
        fetch('../backend/موظفين.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: <?= $id ?>,
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to list page
                window.location.href = 'list_<?= $mod_slug ?>.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/موظفين.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID not set']);
    exit;
}

// Get ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Fetch existing record details
$sql = "SELECT * FROM موظفين WHERE id = $id";
$result = $conn->query($sql);

// Check if record exists
if ($result->num_rows > 0) {
    // Get record details
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Record not found']);
}

// Close connection
$conn->close();
?>


**header.php**

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body>
    <header class="bg-white shadow-md">
        <nav class="container mx-auto p-4 pt-6">
            <ul class="flex justify-between items-center">
                <li><a href="index.php" class="text-gray-700 text-sm font-bold">الرئيسية</a></li>
                <li><a href="list_<?= $mod_slug ?>.php" class="text-gray-700 text-sm font-bold">قائمة <?= $mod_slug ?></a></li>
                <li><a href="logout.php" class="text-gray-700 text-sm font-bold">تسجيل الخروج</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?= $content ?>
    </main>
    <footer class="bg-white shadow-md">
        <p class="text-center text-gray-700 text-sm font-bold py-4">جميع الحقوق محفوظة</p>
    </footer>
</body>
</html>


**footer.php**

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+DwXbhAgpSQR9ebcQomQFkh32OsA" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>