<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <span class="text-lg font-bold">Welcome, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Teachers List</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded">
                <a href="create_teachers.php">Add New Item</a>
            </button>
            <input type="search" id="search" class="py-2 px-4 rounded" placeholder="Search...">
        </div>
        <table id="teachers-table" class="w-full table-auto border-collapse border border-gray-300">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="py-2 px-4 border border-gray-300">ID</th>
                    <th class="py-2 px-4 border border-gray-300">Name</th>
                    <th class="py-2 px-4 border border-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody id="teachers-tbody">
                <!-- Table content will be populated via AJAX -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get teachers list
        fetch('../backend/teachers.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('teachers-tbody');
                data.forEach(teacher => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="py-2 px-4 border border-gray-300">${teacher.id}</td>
                        <td class="py-2 px-4 border border-gray-300">${teacher.name}</td>
                        <td class="py-2 px-4 border border-gray-300">
                            <a href="edit_teachers.php?id=${teacher.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteTeacher(${teacher.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete teacher via AJAX
        function deleteTeacher(id) {
            fetch('../backend/teachers.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error deleting teacher');
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('#teachers-tbody tr');
            rows.forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                if (name.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>