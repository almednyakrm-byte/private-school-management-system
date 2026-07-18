**edit_مواعيد.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/مواعيد.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مواعيد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 text-lg font-bold mb-4">تعديل مواعيد</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="title" class="text-slate-900">العنوان</label>
                <input type="text" id="title" name="title" class="w-full p-2 pl-10 text-slate-900 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['title'] ?>">
            </div>
            <div>
                <label for="date" class="text-slate-900">التاريخ</label>
                <input type="date" id="date" name="date" class="w-full p-2 pl-10 text-slate-900 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['date'] ?>">
            </div>
            <div>
                <label for="time" class="text-slate-900">الوقت</label>
                <input type="time" id="time" name="time" class="w-full p-2 pl-10 text-slate-900 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['time'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">تعديل</button>
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
                    url: '../backend/مواعيد.php',
                    data: formData,
                    success: function(response) {
                        window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مواعيد.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    die('Error: ID not set');
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = array(
    'title' => 'مثال',
    'date' => '2022-01-01',
    'time' => '10:00'
);

// Update existing record
// Replace with actual database update code
$updatedRecord = $existingRecord;

// Output updated record as JSON
echo json_encode($updatedRecord);
?>