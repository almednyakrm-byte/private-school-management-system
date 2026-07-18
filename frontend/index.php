**index.php**

<?php
session_start();

// Check if user is authenticated
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
    <title>نظام إدارة مدارس خاصة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-slate-900 text-white">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">نظام إدارة مدارس خاصة</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold mb-2">مرحباً بكم</h2>
            <p class="text-lg">نظام إدارة مدارس خاصة</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <div class="glassmorphism-card p-4">
                <h2 class="text-2xl font-bold mb-2">إحصائيات</h2>
                <div id="stats-grid"></div>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-2xl font-bold mb-2">إدارة الطلاب</h2>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='students.php'">إدارة الطلاب</button>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-2xl font-bold mb-2">إدارة المواعيد</h2>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='schedules.php'">إدارة المواعيد</button>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-2xl font-bold mb-2">إدارة الخدمات</h2>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='services.php'">إدارة الخدمات</button>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-2xl font-bold mb-2">إدارة المدرسين</h2>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='teachers.php'">إدارة المدرسين</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios@0.21.1/dist/axios.min.js"></script>
    <script>
        axios.get('/api/stats')
            .then(response => {
                const statsGrid = document.getElementById('stats-grid');
                response.data.forEach(stat => {
                    const statElement = document.createElement('div');
                    statElement.classList.add('bg-indigo-500', 'hover:bg-indigo-700', 'text-white', 'font-bold', 'py-2', 'px-4', 'rounded');
                    statElement.innerHTML = `
                        <h2 class="text-lg">${stat.title}</h2>
                        <p class="text-sm">${stat.value}</p>
                    `;
                    statsGrid.appendChild(statElement);
                });
            })
            .catch(error => {
                console.error(error);
            });
    </script>
</body>
</html>


**Note:** This code assumes you have a backend API set up to fetch stats data. You'll need to replace `/api/stats` with the actual URL of your API endpoint. Also, this code uses Tailwind CSS for styling, so make sure to include the necessary CSS files in your project.

**Backend API (example using PHP and MySQL):**

**stats.php**

<?php
header('Content-Type: application/json');

require_once 'db.php';

$stats = array();

// Fetch stats from database
$stmt = $mysqli->prepare("SELECT * FROM stats");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $stats[] = array(
        'title' => $row['title'],
        'value' => $row['value']
    );
}

echo json_encode($stats);
?>


**db.php**

<?php
$mysqli = new mysqli('localhost', 'username', 'password', 'database');

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit;
}
?>


This is just a basic example to get you started. You'll need to modify the code to fit your specific requirements and database schema.