**edit_التقويم.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/التقويم.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Set form fields
$title = $data['title'];
$description = $data['description'];
$startDate = $data['start_date'];
$endDate = $data['end_date'];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل التقويم</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">تعديل التقويم</h2>
    <form id="edit-form">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">العنوان</label>
            <input type="text" id="title" name="title" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" value="<?= $title ?>">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">الوصف</label>
            <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" rows="4"><?= $description ?></textarea>
        </div>
        <div class="mb-4">
            <label for="start_date" class="block text-sm font-medium text-gray-700">تاريخ البداية</label>
            <input type="date" id="start_date" name="start_date" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" value="<?= $startDate ?>">
        </div>
        <div class="mb-4">
            <label for="end_date" class="block text-sm font-medium text-gray-700">تاريخ النهاية</label>
            <input type="date" id="end_date" name="end_date" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" value="<?= $endDate ?>">
        </div>
        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#edit-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'PUT',
                url: '../backend/التقويم.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.status == 'success') {
                        window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                    } else {
                        alert(data.message);
                    }
                }
            });
        });
    });
</script>

</body>
</html>


**backend/التقويم.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from URL
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$data = array(
    'title' => 'عنوان التقويم',
    'description' => 'وصف التقويم',
    'start_date' => '2022-01-01',
    'end_date' => '2022-01-31'
);

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);