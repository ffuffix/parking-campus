<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Campus Parking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6">Campus Parking System</h1>
        <h2 class="text-xl font-semibold text-center mb-6">Login</h2>

        <!-- You can add a login form here or use Laravel Breeze/Fortify -->
        <div class="space-y-4">
            <div class="text-center">
                <p class="text-gray-600 mb-4">Please log in to access the parking system</p>
                <!-- In a real application, you would use Laravel Breeze or Fortify for authentication -->
                <!-- For now, we'll create a simple message -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <p class="text-yellow-700">
                        Authentication system needs to be implemented.
                        Consider using Laravel Breeze or Fortify for full authentication.
                    </p>
                </div>
            </div>

            <!-- Example login form (non-functional without backend setup) -->
            <form method="POST" action="#" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Login
                    </button>
                </div>
            </form>

            <div class="text-center text-sm text-gray-500">
                <p>Don't have an account? <a href="#" class="text-blue-600 hover:text-blue-500">Contact administrator</a></p>
            </div>
        </div>
    </div>
</body>

</html>