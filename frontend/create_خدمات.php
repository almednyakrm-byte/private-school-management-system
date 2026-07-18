<?php
// create_خدمات.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить услугу</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 bg-slate-900 p-8 rounded-xl shadow-md">
        <h2 class="text-3xl text-indigo-500 font-bold mb-4">Добавить услугу</h2>
        <form id="create-service-form">
            <div class="mb-4">
                <label for="name" class="block text-sm text-indigo-500 font-bold mb-2">Название услуги</label>
                <input type="text" id="name" name="name" class="block w-full p-2 text-slate-900 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm text-indigo-500 font-bold mb-2">Описание услуги</label>
                <textarea id="description" name="description" class="block w-full p-2 text-slate-900 rounded-md focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm text-indigo-500 font-bold mb-2">Цена услуги</label>
                <input type="number" id="price" name="price" class="block w-full p-2 text-slate-900 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-slate-900 font-bold py-2 px-4 rounded">Добавить услугу</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-service-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/خدمات.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_خدمات.php';
                    }
                });
            });
        });
    </script>
</body>
</html>