<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ./login/");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bosh sahifa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full transform transition-all hover:shadow-2xl">
        <!-- User Icon -->
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
        </div>

        <!-- Welcome Message -->
        <h1 class="text-3xl font-bold text-gray-800 text-center mb-2">
            Salom, <?= htmlspecialchars($_SESSION['user']['name']) ?>!
        </h1>

        <!-- Email -->
        <div class="flex items-center justify-center space-x-2 mb-6">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            <p class="text-gray-600"><?= htmlspecialchars($_SESSION['user']['email']) ?></p>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 mb-6"></div>

        <!-- User Info Card -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Hisob tasdiqlangan</p>
                    <p class="text-sm text-gray-500">Xush kelibsiz!</p>
                </div>
            </div>
        </div>

        <!-- Logout Form -->
        <form action="./logout/" method="post" onsubmit="return confirm('Haqiqatan ham tizimdan chiqmoqchimisiz?')">
            <button type="submit" id="logoutBtn"
                class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-50 flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span>Chiqish</span>
            </button>
        </form>
    </div>
</body>

</html>