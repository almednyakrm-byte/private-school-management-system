**list_خدمات.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خدمات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1d23;
        }
        .text-indigo-500 {
            color: #6b6bcf;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="container mx-auto p-4">
        <header class="bg-indigo-500 p-4 mb-4">
            <nav class="flex justify-between">
                <a href="index.php" class="text-white hover:text-indigo-400">الرئيسية</a>
                <div class="flex items-center">
                    <span class="text-white mr-2">مرحباً, <?= $_SESSION['username'] ?></span>
                    <a href="logout.php" class="text-white hover:text-indigo-400">تسجيل الخروج</a>
                </div>
            </nav>
        </header>
        <main class="bg-slate-900 p-4">
            <h1 class="text-indigo-500 text-2xl mb-4">خدمات</h1>
            <div class="flex justify-between mb-4">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_خدمات.php'">إضافة جديد</button>
                <input type="search" id="search" placeholder="بحث" class="bg-gray-200 rounded p-2 w-full">
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">اسم الخدمة</th>
                        <th class="px-4 py-2">حذف</th>
                        <th class="px-4 py-2">تعديل</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <!-- Records will be loaded here -->
                </tbody>
            </table>
        </main>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsTable = document.getElementById('records');

        searchInput.addEventListener('input', () => {
            const searchQuery = searchInput.value.toLowerCase();
            const records = Array.from(recordsTable.children);
            records.forEach(record => {
                const serviceName = record.children[0].textContent.toLowerCase();
                if (serviceName.includes(searchQuery)) {
                    record.style.display = 'table-row';
                } else {
                    record.style.display = 'none';
                }
            });
        });

        async function loadRecords() {
            try {
                const response = await fetch('../backend/خدمات.php', { method: 'GET' });
                const data = await response.json();
                recordsTable.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${record.name}</td>
                        <td class="px-4 py-2">
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                        <td class="px-4 py-2">
                            <a href="edit_خدمات.php?id=${record.id}" class="text-white hover:text-indigo-400">تعديل</a>
                        </td>
                    `;
                    recordsTable.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        loadRecords();

        async function deleteRecord(id) {
            try {
                const response = await fetch('../backend/خدمات.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) });
                if (response.ok) {
                    loadRecords();
                } else {
                    console.error('Error deleting record');
                }
            } catch (error) {
                console.error(error);
            }
        }
    </script>
</body>
</html>

This code creates a premium Tailwind UI layout with a header navigation, a table showing list of records, and actions to edit and delete records. It also includes a search bar to filter elements in real-time. The AJAX requests are handled using the Fetch API.