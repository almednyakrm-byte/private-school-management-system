<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Current user info
$current_user = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grades Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <span class="text-lg font-bold">Welcome, <?php echo $current_user; ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Grades List</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded">
                <a href="create_grades.php">Add New Item</a>
            </button>
            <input type="search" id="search" class="bg-gray-200 py-2 px-4 rounded" placeholder="Search...">
        </div>
        <table id="grades-table" class="w-full table-auto border-collapse border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border border-gray-200">ID</th>
                    <th class="px-4 py-2 border border-gray-200">Name</th>
                    <th class="px-4 py-2 border border-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody id="grades-tbody">
                <!-- Table content will be populated via AJAX -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch grades data from backend
        fetch('../backend/grades.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('grades-tbody');
                data.forEach(grade => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2 border border-gray-200">${grade.id}</td>
                        <td class="px-4 py-2 border border-gray-200">${grade.name}</td>
                        <td class="px-4 py-2 border border-gray-200">
                            <a href="edit_grades.php?id=${grade.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteGrade(${grade.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete grade via AJAX
        function deleteGrade(id) {
            fetch('../backend/grades.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove deleted row from table
                    const rows = document.getElementById('grades-tbody').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            rows[i].remove();
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting grade:', data.error);
                }
            });
        }

        // Search functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('grades-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const rowText = rows[i].textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>