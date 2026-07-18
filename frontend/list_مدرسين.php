**list_مدرسين.php**

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
    <title>مدرسين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1a1a;
        }
        .text-indigo-500 {
            color: #6b5ce6;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body class="bg-slate-900 text-indigo-500">
    <header class="bg-slate-900 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex justify-between items-center">
                <a href="index.php" class="text-indigo-500 hover:text-white">الرئيسية</a>
                <div class="flex items-center">
                    <span class="text-indigo-500"><?= $_SESSION['username'] ?></span>
                    <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">تسجيل الخروج</button>
                </div>
            </nav>
        </div>
    </header>
    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl text-indigo-500">مدرسين</h1>
        <div class="flex justify-between items-center mb-4">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مدرسين.php'">إضافة جديد</button>
            <input type="search" class="w-full py-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="بحث" id="search">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المدرس</th>
                    <th>البريد الإلكتروني</th>
                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>
    <script>
        const searchInput = document.getElementById('search');
        const recordsContainer = document.getElementById('records');

        searchInput.addEventListener('input', () => {
            const searchQuery = searchInput.value.toLowerCase();
            const records = Array.from(recordsContainer.children);
            records.forEach((record, index) => {
                const text = record.textContent.toLowerCase();
                if (text.includes(searchQuery)) {
                    record.style.display = 'table-row';
                } else {
                    record.style.display = 'none';
                }
            });
        });

        async function loadRecords() {
            try {
                const response = await fetch('../backend/مدرسين.php', { method: 'GET' });
                const data = await response.json();
                const recordsHtml = data.map((record) => {
                    return `
                        <tr>
                            <td>${record.اسم_المدرس}</td>
                            <td>${record.البريد_الإلكتروني}</td>
                            <td>
                                <a href="edit_مدرسين.php?id=${record.id}" class="text-indigo-500 hover:text-white">تعديل</a>
                                <button class="text-red-500 hover:text-white" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        </tr>
                    `;
                }).join('');
                recordsContainer.innerHTML = recordsHtml;
            } catch (error) {
                console.error(error);
            }
        }

        loadRecords();

        async function deleteRecord(id) {
            try {
                const response = await fetch('../backend/مدرسين.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) });
                if (response.ok) {
                    loadRecords();
                } else {
                    alert('حدث خطأ أثناء الحذف');
                }
            } catch (error) {
                console.error(error);
            }
        }
    </script>
</body>
</html>

**backend/مدرسين.php**

<?php
// Assuming you have a database connection established
// and a table named 'teachers' with columns 'id', 'اسم_المدرس', 'البريد_الإلكتروني'
// You should replace this with your actual database logic

if (isset($_GET['id'])) {
    // Handle delete request
    $id = $_GET['id'];
    // Delete record from database
    // ...
    echo json_encode(['success' => true]);
} elseif (isset($_GET['GET'])) {
    // Handle GET request
    $records = [];
    // Fetch records from database
    // ...
    foreach ($records as $record) {
        $record['id'] = $record['id'];
        $record['اسم_المدرس'] = $record['اسم_المدرس'];
        $record['البريد_الإلكتروني'] = $record['البريد_الإلكتروني'];
        $records[] = $record;
    }
    echo json_encode($records);
} else {
    // Handle POST request (create new record)
    // ...
}