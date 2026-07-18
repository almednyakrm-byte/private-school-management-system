**list_التلاميذ.php**

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
    <title>التلاميذ</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
        .table-container {
            max-width: 1200px;
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
                <span class="mr-2">مرحباً, <?= $_SESSION['username'] ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='logout.php'">تسجيل خروج</button>
            </div>
        </div>
    </header>
    <main class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">التلاميذ</h1>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='create_التلاميذ.php'">إضافة جديد</button>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>اسم التلميذ</th>
                        <th>تاريخ الميلاد</th>
                        <th>حذف</th>
                        <th>تعديل</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <!-- Table rows will be populated here -->
                </tbody>
            </table>
        </div>
        <div class="flex justify-center items-center mb-4">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
    </main>
    <script>
        // Fetch API to get list of records
        async function getRecords() {
            try {
                const response = await fetch('../backend/التلاميذ.php', { method: 'GET' });
                const data = await response.json();
                populateTable(data);
            } catch (error) {
                console.error(error);
            }
        }

        // Populate table with records
        function populateTable(records) {
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '';
            records.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.اسم_التلميذ}</td>
                    <td>${record.تاريخ_الميلاد}</td>
                    <td>
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                    <td>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='edit_التلاميذ.php?id=${record.id}'">تعديل</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search-input').value;
            fetch('../backend/التلاميذ.php', {
                method: 'GET',
                params: { search: searchInput }
            })
            .then(response => response.json())
            .then(data => populateTable(data))
            .catch(error => console.error(error));
        }

        // Delete record
        async function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا التلميذ؟')) {
                try {
                    const response = await fetch('../backend/التلاميذ.php', {
                        method: 'DELETE',
                        params: { id: id }
                    });
                    if (response.ok) {
                        getRecords();
                    } else {
                        console.error('Error deleting record');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }

        // Initialize table
        getRecords();
    </script>
</body>
</html>

**backend/التلاميذ.php**

<?php
// Database connection
$conn = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');

// Get list of records
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $conn->prepare('SELECT * FROM التلاميذ WHERE اسم_التلميذ LIKE :search');
    $stmt->bindParam(':search', $search);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $conn->prepare('SELECT * FROM التلاميذ');
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare('DELETE FROM التلاميذ WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
}

// Output records
echo json_encode($records);
?>