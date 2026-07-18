<?php
// edit_grades.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_grades.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-blue-500 mb-4">Edit Grades</h2>
        <form id="edit-grades-form">
            <div class="mb-4">
                <label for="student_name" class="block text-blue-500 text-sm font-bold mb-2">Student Name</label>
                <input type="text" id="student_name" name="student_name" class="block w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="subject" class="block text-blue-500 text-sm font-bold mb-2">Subject</label>
                <input type="text" id="subject" name="subject" class="block w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="grade" class="block text-blue-500 text-sm font-bold mb-2">Grade</label>
                <input type="number" id="grade" name="grade" class="block w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded-lg">Update Grades</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-grades-form');
        const id = <?php echo $id; ?>;

        // Fetch existing record details
        fetch(`../backend/grades.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('student_name').value = data.student_name;
                document.getElementById('subject').value = data.subject;
                document.getElementById('grade').value = data.grade;
            });

        // Submit form using AJAX PUT request
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch(`../backend/grades.php?id=${id}`, {
                method: 'PUT',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_grades.php';
                } else {
                    alert('Error updating grades');
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>