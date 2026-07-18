**list_students.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-blue-500 {
            background-color: #1a73fe;
        }
        .text-orange-300 {
            color: #ff9900;
        }
        .bg-orange-300 {
            background-color: #ff9900;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-blue-500 p-4 text-white">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <button class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">Logout</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Students List</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_students.php'">Add New Item</button>
            <input type="search" class="w-full p-2 mb-4" id="search" placeholder="Search...">
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">Name</th>
                    <th class="border border-gray-400 p-2">Email</th>
                    <th class="border border-gray-400 p-2">Actions</th>
                </tr>
            </thead>
            <tbody id="students-list">
                <?php
                // Fetch data from backend
                $url = '../backend/students.php';
                $response = file_get_contents($url);
                $data = json_decode($response, true);
                foreach ($data as $student) {
                    ?>
                    <tr>
                        <td class="border border-gray-400 p-2"><?= $student['name'] ?></td>
                        <td class="border border-gray-400 p-2"><?= $student['email'] ?></td>
                        <td class="border border-gray-400 p-2">
                            <a href="edit_students.php?id=<?= $student['id'] ?>" class="text-orange-300 hover:text-orange-400">Edit</a>
                            <button class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded ml-4" onclick="deleteStudent(<?= $student['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </main>
    <script>
        // Search bar filtering
        const searchInput = document.getElementById('search');
        const studentsList = document.getElementById('students-list');
        searchInput.addEventListener('input', () => {
            const searchQuery = searchInput.value.toLowerCase();
            const students = studentsList.children;
            for (let i = 0; i < students.length; i++) {
                const student = students[i];
                const name = student.children[0].textContent.toLowerCase();
                const email = student.children[1].textContent.toLowerCase();
                if (name.includes(searchQuery) || email.includes(searchQuery)) {
                    student.style.display = 'table-row';
                } else {
                    student.style.display = 'none';
                }
            }
        });

        // Delete student
        function deleteStudent(id) {
            fetch(`../backend/delete_student.php?id=${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error deleting student');
                }
            })
            .catch(error => console.error(error));
        }
    </script>
</body>
</html>

**Note:** This code assumes that you have a backend PHP file `students.php` that returns a JSON array of students, and a `delete_student.php` file that handles the DELETE request to delete a student. You will need to create these files and implement the necessary logic to fetch and delete students.