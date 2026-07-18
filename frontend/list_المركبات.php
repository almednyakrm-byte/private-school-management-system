**list_المركبات.php**

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
    <title>المركبات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
        }
        .bg-emerald-600 {
            background-color: #0d9488;
        }
        .text-teal-500 {
            color: #0097a7;
        }
    </style>
</head>
<body class="bg-emerald-600">
    <div class="container mx-auto p-4 md:p-6 lg:p-8">
        <header class="bg-white rounded-lg shadow-md p-4">
            <nav class="flex justify-between items-center">
                <a href="index.php" class="text-teal-500 hover:text-emerald-600">الرئيسية</a>
                <div class="flex items-center">
                    <span class="text-teal-500 mr-2">مرحباً, <?= $_SESSION['username'] ?></span>
                    <button class="bg-emerald-600 hover:bg-teal-500 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
                </div>
            </nav>
        </header>
        <main class="mt-4">
            <h2 class="text-2xl text-teal-500 font-bold mb-4">المركبات</h2>
            <div class="flex justify-between items-center mb-4">
                <button class="bg-emerald-600 hover:bg-teal-500 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_المركبات.php'">إضافة جديد</button>
                <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="بحث...">
            </div>
            <table class="w-full border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="border border-gray-200 px-4 py-2">الرقم</th>
                        <th class="border border-gray-200 px-4 py-2">النوع</th>
                        <th class="border border-gray-200 px-4 py-2">الإجراءات</th>
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
            const searchQuery = searchInput.value.trim().toLowerCase();
            const records = Array.from(recordsTable.children);
            records.forEach(record => {
                const text = record.textContent.toLowerCase();
                if (text.includes(searchQuery)) {
                    record.style.display = '';
                } else {
                    record.style.display = 'none';
                }
            });
        });

        async function loadRecords() {
            try {
                const response = await fetch('../backend/المركبات.php', { method: 'GET' });
                const data = await response.json();
                recordsTable.innerHTML = '';
                data.forEach((record, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border border-gray-200 px-4 py-2">${record.id}</td>
                        <td class="border border-gray-200 px-4 py-2">${record.type}</td>
                        <td class="border border-gray-200 px-4 py-2">
                            <a href="edit_المركبات.php?id=${record.id}" class="text-teal-500 hover:text-emerald-600">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
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
                const response = await fetch('../backend/المركبات.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) });
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

This code includes the following features:

1. Session validation: Redirects to login.php if the user is not authenticated.
2. Header navigation: Links to index.php, current user info, and logout.
3. Table showing list of records with actions: Edit (link to edit_المركبات.php?id=X) and Delete (AJAX call to backend).
4. 'Add New Item' button linking to create_المركبات.php.
5. Search bar filtering elements in real-time.
6. AJAX Javascript (Fetch API) fetching list records from '../backend/المركبات.php' (GET) and DELETE requests.

Note: This code assumes that the backend API is implemented and returns the list of records in JSON format. The `deleteRecord` function sends a DELETE request to the backend API to delete the record with the specified ID.