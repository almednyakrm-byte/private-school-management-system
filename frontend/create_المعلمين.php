**create_المعلمين.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'nav.php';

// Include form script
include 'create_المعلمين_form.php';

// Include footer
include 'footer.php';
?>


**create_المعلمين_form.php**

<?php
// Include form header
include 'form_header.php';
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold mb-4">إضافة جديد</h2>
    <form id="create-المعلمين-form" class="space-y-4">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">اسم المعلم</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">العنوان</label>
                <textarea id="address" name="address" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
        </div>
        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">حفظ</button>
    </form>
</div>

<?php
// Include form footer
include 'form_footer.php';
?>


**form_header.php**

<?php
// Include form header
?>
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold mb-4">إضافة جديد</h2>
    <form id="create-المعلمين-form" class="space-y-4">


**form_footer.php**

<?php
// Include form footer
?>
    </form>
</div>


**create_المعلمين.js**
javascript
$(document).ready(function() {
    $('#create-المعلمين-form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '../backend/المعلمين.php',
            data: formData,
            success: function(response) {
                if (response === 'success') {
                    window.location.href = 'list_المعلمين.php';
                } else {
                    alert('Error: ' + response);
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    });
});


**backend/المعلمين.php**

<?php
// Include database connection
include 'db.php';

// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['address'])) {
    // Prepare SQL query
    $sql = "INSERT INTO المعلمين (name, email, phone, address) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address']);
    // Execute query
    $stmt->execute();
    // Check if query was successful
    if ($stmt->affected_rows === 1) {
        echo 'success';
    } else {
        echo 'Error: ' . $stmt->error;
    }
    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo 'Error: No form data submitted';
}
?>