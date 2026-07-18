<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Module slug
$mod_slug = 'grades';

// Page title
$page_title = 'Create Grades';

// Include header
include 'header.php';
?>

<main class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <h3 class="text-lg font-medium leading-6 text-blue-500">Create Grades</h3>
            <p class="mt-1 text-sm text-gray-600">Please fill in the form below to create a new grades record.</p>
        </div>
        <div class="mt-5 md:col-span-2 md:mt-0">
            <form id="create-grades-form">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700">Student ID</label>
                            <input type="text" name="student_id" id="student_id" autocomplete="off" class="mt-1 block w-full rounded-md border border-gray-300 py-2 pl-3 pr-10 text-base text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                            <input type="text" name="subject" id="subject" autocomplete="off" class="mt-1 block w-full rounded-md border border-gray-300 py-2 pl-3 pr-10 text-base text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="grade" class="block text-sm font-medium text-gray-700">Grade</label>
                            <input type="number" name="grade" id="grade" autocomplete="off" class="mt-1 block w-full rounded-md border border-gray-300 py-2 pl-3 pr-10 text-base text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                            <input type="text" name="semester" id="semester" autocomplete="off" class="mt-1 block w-full rounded-md border border-gray-300 py-2 pl-3 pr-10 text-base text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="academic_year" class="block text-sm font-medium text-gray-700">Academic Year</label>
                            <input type="text" name="academic_year" id="academic_year" autocomplete="off" class="mt-1 block w-full rounded-md border border-gray-300 py-2 pl-3 pr-10 text-base text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-orange-300 py-2 px-4 text-base font-medium text-white shadow-sm hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-grades-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/grades.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_grades.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>