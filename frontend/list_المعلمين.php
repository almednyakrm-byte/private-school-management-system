**list_المعلمين.php**

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
    <title>المعلمين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <header class="bg-gray-800 text-white p-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">الصفحة الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="document.location='logout.php'">تسجيل الخروج</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">المعلمين</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="document.location='create_المعلمين.php'">إضافة جديد</button>
        <div class="flex justify-between mb-4">
            <input type="search" class="w-full p-2 border border-gray-400 rounded" placeholder="بحث" id="search">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>اسم المعلم</th>
                    <th>تاريخ الميلاد</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch records from backend
        async function fetchRecords() {
            try {
                const response = await fetch('../backend/المعلمين.php', { method: 'GET' });
                const data = await response.json();
                const records = document.getElementById('records');
                records.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.name}</td>
                        <td>${record.birthdate}</td>
                        <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                        <td><a href="edit_المعلمين.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a></td>
                    `;
                    records.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value.trim();
            if (searchValue) {
                fetchRecords(searchValue);
            } else {
                fetchRecords();
            }
        }

        // Delete record
        async function deleteRecord(id) {
            try {
                const response = await fetch('../backend/المعلمين.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) });
                if (response.ok) {
                    fetchRecords();
                } else {
                    console.error('Error deleting record');
                }
            } catch (error) {
                console.error(error);
            }
        }

        // Initialize records
        fetchRecords();
    </script>
</body>
</html>

**backend/المعلمين.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get records
$query = "SELECT * FROM المعلمين";
$result = $conn->query($query);

// Fetch records
$records = array();
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

// Output records
echo json_encode($records);

// Close connection
$conn->close();
?>

// Delete record
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM المعلمين WHERE id = '$id'";
    $conn->query($query);
    echo 'Record deleted successfully';
}