**list_مواعيد.php**

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
    <title>مواعيد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f1f1f;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
        }
        .table th {
            background-color: #1f1f1f;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            font-size: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span style="font-size: 1.5rem; font-weight: bold; color: #fff;">|</span>
        <span style="font-size: 1.5rem; font-weight: bold; color: #fff;">مواعيد</span>
        <span style="font-size: 1.5rem; font-weight: bold; color: #fff;">|</span>
        <span style="font-size: 1.5rem; font-weight: bold; color: #fff;"><?php echo $_SESSION['username']; ?></span>
        <span style="font-size: 1.5rem; font-weight: bold; color: #fff;">|</span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مواعيد.php'">إضافة جديد</button>
        <input type="search" class="search-bar" placeholder="بحث..." id="search-input">
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>تاريخ</th>
                    <th>وقت</th>
                    <th>حالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <?php
                // Fetch data from backend
                $response = file_get_contents('../backend/مواعيد.php');
                $data = json_decode($response, true);
                foreach ($data as $item) {
                    echo '<tr>';
                    echo '<td>' . $item['اسم'] . '</td>';
                    echo '<td>' . $item['تاريخ'] . '</td>';
                    echo '<td>' . $item['وقت'] . '</td>';
                    echo '<td>' . $item['حالة'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_مواعيد.php?id=' . $item['id'] . '" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>';
                    echo '<button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(' . $item['id'] . ')">حذف</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        // Search bar filtering
        const searchInput = document.getElementById('search-input');
        searchInput.addEventListener('input', function() {
            const searchValue = searchInput.value.toLowerCase();
            const tableBody = document.getElementById('table-body');
            const tableRows = tableBody.getElementsByTagName('tr');
            for (let i = 0; i < tableRows.length; i++) {
                const row = tableRows[i];
                const cells = row.getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toLowerCase().includes(searchValue)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            }
        });

        // Delete item
        function deleteItem(id) {
            if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                fetch('../backend/مواعيد.php', {
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
                        alert('حدث خطأ أثناء الحذف');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

Note: This code assumes that the backend API is already implemented and returns a JSON response with the list of records. The `deleteItem` function sends a DELETE request to the backend API to delete the record with the specified ID.