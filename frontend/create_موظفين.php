**create_موظفين.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

// Include form
include 'create_form.php';

// Include footer
include 'footer.php';
?>


**create_form.php**

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">إضافة موظف</h2>
        <form id="create-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">اسم الموظف</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="name" type="text" required>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="email">بريد إلكتروني</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="email" type="email" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="phone">رقم الهاتف</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="phone" type="tel" required>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="department">القسم</label>
                    <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="department" required>
                        <option value="">اختر القسم</option>
                        <!-- Add options here -->
                    </select>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="role">الوظيفة</label>
                    <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="role" required>
                        <option value="">اختر الوظيفة</option>
                        <!-- Add options here -->
                    </select>
                </div>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/موظفين.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_موظفين.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>


**header.php**

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الموظفين</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <header class="bg-white shadow-md p-4 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
        <!-- Navigation menu -->
    </header>


**footer.php**

<footer class="bg-white shadow-md p-4 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
    <!-- Footer content -->
</footer>


**navigation.php**

<nav class="bg-white shadow-md p-4 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
    <ul class="flex justify-between">
        <li><a href="list_موظفين.php" class="text-gray-700 hover:text-gray-900">قائمة الموظفين</a></li>
        <li><a href="create_موظفين.php" class="text-gray-700 hover:text-gray-900">إضافة موظف</a></li>
    </ul>
</nav>