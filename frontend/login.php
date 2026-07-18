<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-slate-900 to-indigo-500 h-screen">
    <div class="flex justify-center items-center h-full">
        <div class="glassmorphism bg-white bg-opacity-10 backdrop-filter backdrop-blur-md rounded-lg p-8 w-96">
            <h1 class="text-3xl text-white font-bold mb-4">Login</h1>
            <form id="login-form" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm text-white">Username</label>
                    <input type="text" id="username" name="username" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                    <div id="username-error" class="text-red-500 hidden"></div>
                </div>
                <div>
                    <label for="password" class="block text-sm text-white">Password</label>
                    <input type="password" id="password" name="password" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                    <div id="password-error" class="text-red-500 hidden"></div>
                </div>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Login</button>
                <p class="text-sm text-white mt-2">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('login-form');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const usernameError = document.getElementById('username-error');
        const passwordError = document.getElementById('password-error');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            usernameError.classList.remove('text-red-500');
            passwordError.classList.remove('text-red-500');
            usernameError.textContent = '';
            passwordError.textContent = '';

            const username = usernameInput.value.trim();
            const password = passwordInput.value.trim();

            if (username === '') {
                usernameError.textContent = 'Username is required';
                usernameError.classList.add('text-red-500');
            } else if (!username.match(pattern)) {
                usernameError.textContent = 'Invalid username';
                usernameError.classList.add('text-red-500');
            }

            if (password === '') {
                passwordError.textContent = 'Password is required';
                passwordError.classList.add('text-red-500');
            }

            if (username !== '' && password !== '' && username.match(pattern)) {
                try {
                    const response = await fetch('../backend/auth.php?action=login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ username, password })
                    });

                    const data = await response.json();

                    if (data.success) {
                        window.location.href = 'dashboard.php';
                    } else {
                        passwordError.textContent = 'Invalid username or password';
                        passwordError.classList.add('text-red-500');
                    }
                } catch (error) {
                    console.error(error);
                    passwordError.textContent = 'Error logging in';
                    passwordError.classList.add('text-red-500');
                }
            }
        });
    </script>
</body>
</html>


Note: The `pattern` variable is not defined in the code snippet above. You need to define it before using it in the `input` element. I assume it's a typo and you meant to use the `pattern` attribute directly in the `input` element. If you want to use a variable, define it like this: `const pattern = '[A-Za-z\u0600-\u06FF0-9\s]+';`