<!-- register.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen">
    <div class="container mx-auto p-4 h-full">
        <div class="flex justify-center h-full">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Register</h2>
                <form id="register-form">
                    <div class="mb-4">
                        <label for="username" class="block text-slate-900 text-sm font-bold mb-2">Username</label>
                        <input type="text" id="username" name="username" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-slate-900 text-sm font-bold mb-2">Email</label>
                        <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Email" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-slate-900 text-sm font-bold mb-2">Password</label>
                        <input type="password" id="password" name="password" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Password" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                    </div>
                    <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Register</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../backend/auth.php?action=register',
                    data: formData,
                    success: function(response) {
                        if (response == 'success') {
                            alert('Registration successful!');
                            window.location.href = 'login.php';
                        } else {
                            alert('Registration failed!');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


This code uses Tailwind CSS to create a premium-looking registration form. The form fields are validated using JavaScript and the form is submitted via AJAX to the backend PHP script. The color palette used is slate-900 and indigo-500 as instructed.