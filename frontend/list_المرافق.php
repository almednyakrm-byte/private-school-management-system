**list_المرافق.php**

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
    <title>المرافق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <img src="profile-picture.jpg" alt="Profile Picture" class="w-8 h-8 rounded-full">
                <span class="ml-2 text-lg font-bold"><?= $_SESSION['username']; ?></span>
                <button class="ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
            </div>
        </nav>
    </header>
    <main class="max-w-7xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">المرافق</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_المرافق.php'">إضافة جديد</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="بحث...">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">اسم المرافق</th>
                    <th class="border border-gray-300 px-4 py-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $response = file_get_contents('../backend/المرافق.php');
                $records = json_decode($response, true);
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?= $record['name']; ?></td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="edit_المرافق.php?id=<?= $record['id']; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </main>
    <script>
        // Search functionality
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery !== '') {
                fetch('../backend/المرافق.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="border border-gray-300 px-4 py-2">${record.name}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <a href="edit_المرافق.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/المرافق.php')
                    .then(response => response.json())
                    .then(data => {
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="border border-gray-300 px-4 py-2">${record.name}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <a href="edit_المرافق.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    });
            }
        }

        // Delete record functionality
        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/المرافق.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

This code includes a premium Tailwind UI with a header navigation, table showing list of records, 'Add New Item' button, search bar, and AJAX Javascript for fetching and deleting records.