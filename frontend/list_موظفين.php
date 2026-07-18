**list_موظفين.php**

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
    <title>موظفين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Kufi Arabic', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2c3e50;
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
            background-color: #2c3e50;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            margin: 1rem auto;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 1.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <a href="profile.php">حسابي</a>
        <a href="logout.php">تسجيل الخروج</a>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span class="text-lg font-bold">مركز إدارة الموظفين</span>
        <span