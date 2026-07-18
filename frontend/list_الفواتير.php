**list_الفواتير.php**

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
    <title>الفواتير</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
        .table-container {
            max-width: 800px;
            margin: 40px auto;
        }
        .table-container table {
            border-collapse: collapse;
            width: 100%;
        }
        .table-container th, .table-container td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table-container th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <header class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الصفحة الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">تسجيل الخروج</button>
            </div>
        </div>
    </header>
    <main class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">الفواتير</h1>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_الفواتير.php'">إضافة جديد</button>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>تاريخ الفاتورة</th>
                        <th>المبلغ</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <!-- Table data will be populated here -->
                </tbody>
            </table>
        </div>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
    </main>
    <script>
        // Fetch API to get list of records
        async function getRecords() {
            try {
                const response = await fetch('../backend/الفواتير.php', { method: 'GET' });
                const data = await response.json();
                const tableBody = document.getElementById('table-body');
                tableBody.innerHTML = '';
                data.forEach((record) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.id}</td>
                        <td>${record.date}</td>
                        <td>${record.amount}</td>
                        <td>
                            <a href="edit_الفواتير.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }
        getRecords();

        // Search functionality
        function searchRecords() {
            const searchInput = document.getElementById('search-input').value;
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '';
            fetch('../backend/الفواتير.php', { method: 'GET' })
                .then((response) => response.json())
                .then((data) => {
                    data.forEach((record) => {
                        if (record.id.includes(searchInput) || record.date.includes(searchInput) || record.amount.includes(searchInput)) {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.id}</td>
                                <td>${record.date}</td>
                                <td>${record.amount}</td>
                                <td>
                                    <a href="edit_الفواتير.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        }
                    });
                })
                .catch((error) => console.error(error));
        }

        // Delete record functionality
        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/الفواتير.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            getRecords();
                        } else {
                            alert('حدث خطأ أثناء الحذف');
                        }
                    })
                    .catch((error) => console.error(error));
            }
        }
    </script>
</body>
</html>

Note: This code assumes that you have a backend PHP script (`../backend/الفواتير.php`) that handles GET and DELETE requests for the `الفواتير` module. The backend script should return a JSON response with the list of records or a success/failure message for the delete request.