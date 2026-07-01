<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: ../");
    exit;
}

include '../db.php';
$db = new DB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['name'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Ism kiritilmagan!'
        ]);
        exit;
    }

    if (empty($data['email'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Email kiritilmagan!'
        ]);
        exit;
    }

    if (empty($data['password'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Parol kiritilmagan!'
        ]);
        exit;
    }

    $name = $data['name'];
    $email = $data['email'];
    $password = $data['password'];

    $existingUser = $db->select('users', '*', [
        'email' => $email
    ]);

    if (!empty($existingUser)) {
        echo json_encode([
            'success' => false,
            'message' => 'Bunday foydalanuvchi mavjud!'
        ]);
        exit;
    }

    $userId = $db->insert('users', [
        'name' => $name,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
    ]);

    if ($userId) {
        $_SESSION['loggedin'] = true;
        $_SESSION['user']['id'] = $userId;
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;

        echo json_encode([
            'success' => true,
            'message' => "Ro'yxatdan o'tish muvaffaqiyatli bajarildi"
        ]);
        exit;
    }

    echo json_encode([
        'success' => false,
        'message' => "Ro'yxatdan o'tishda xatolik yuz berdi!"
    ]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ro'yxatdan o'tish</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); }
            20%, 40%, 60%, 80% { transform: translateX(4px); }
        }
        .alert-enter {
            animation: slideIn 0.3s ease-out;
        }
        .alert-exit {
            animation: slideOut 0.3s ease-in;
        }
        .shake {
            animation: shake 0.6s ease-in-out;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-purple-50 via-pink-50 to-blue-50 min-h-screen flex items-center justify-center p-4">
    
    <!-- Alert Container -->
    <div id="alertContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Main Container -->
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full mb-4 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-800">Ro'yxatdan o'tish</h2>
            <p class="text-gray-500 mt-2">Yangi hisob yarating</p>
        </div>

        <!-- Register Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form id="registerForm" action="" method="post" class="space-y-6">
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Ism
                        </span>
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="name" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 ease-in-out placeholder-gray-400"
                            placeholder="Ismingizni kiriting"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email
                        </span>
                    </label>
                    <div class="relative">
                        <input 
                            type="email" 
                            id="email" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 ease-in-out placeholder-gray-400"
                            placeholder="sizning@email.com"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Parol
                        </span>
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            required
                            minlength="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 ease-in-out placeholder-gray-400"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button"
                            id="togglePassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Password strength indicator -->
                    <div class="mt-2">
                        <div class="flex space-x-1">
                            <div id="strengthBar1" class="h-1 flex-1 bg-gray-200 rounded-full transition-all duration-300"></div>
                            <div id="strengthBar2" class="h-1 flex-1 bg-gray-200 rounded-full transition-all duration-300"></div>
                            <div id="strengthBar3" class="h-1 flex-1 bg-gray-200 rounded-full transition-all duration-300"></div>
                        </div>
                        <p id="strengthText" class="text-xs text-gray-500 mt-1"></p>
                    </div>
                </div>

                <!-- Terms Checkbox -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="terms" 
                        required
                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                    >
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        Men <a href="#" class="text-purple-600 hover:text-purple-500">shartlar</a> va <a href="#" class="text-purple-600 hover:text-purple-500">maxfiylik siyosati</a>ga roziman
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    id="submitBtn"
                    class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50 flex items-center justify-center shadow-lg"
                >
                    <span id="btnText">Ro'yxatdan o'tish</span>
                    <svg id="loadingSpinner" class="hidden w-5 h-5 ml-2 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">yoki</span>
                </div>
            </div>

            <!-- Login Link -->
            <div class="text-center">
                <p class="text-gray-600">
                    Hisobingiz bormi? 
                    <a href="../login/" class="text-purple-600 hover:text-purple-700 font-semibold transition duration-200 ease-in-out">
                        Kirish
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        const registerForm = document.getElementById('registerForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const alertContainer = document.getElementById('alertContainer');
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const strengthBar1 = document.getElementById('strengthBar1');
        const strengthBar2 = document.getElementById('strengthBar2');
        const strengthBar3 = document.getElementById('strengthBar3');
        const strengthText = document.getElementById('strengthText');

        // Toggle password visibility
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Change icon
            const icon = this.querySelector('svg');
            if (type === 'text') {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
            } else {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        });

        // Password strength checker
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password) && /[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            // Reset bars
            [strengthBar1, strengthBar2, strengthBar3].forEach(bar => {
                bar.className = 'h-1 flex-1 bg-gray-200 rounded-full transition-all duration-300';
            });
            
            if (password.length === 0) {
                strengthText.textContent = '';
            } else if (strength <= 2) {
                strengthBar1.className = 'h-1 flex-1 bg-red-500 rounded-full transition-all duration-300';
                strengthText.textContent = 'Kuchsiz parol';
                strengthText.className = 'text-xs text-red-500 mt-1';
            } else if (strength <= 4) {
                strengthBar1.className = 'h-1 flex-1 bg-yellow-500 rounded-full transition-all duration-300';
                strengthBar2.className = 'h-1 flex-1 bg-yellow-500 rounded-full transition-all duration-300';
                strengthText.textContent = 'O\'rtacha parol';
                strengthText.className = 'text-xs text-yellow-600 mt-1';
            } else {
                [strengthBar1, strengthBar2, strengthBar3].forEach(bar => {
                    bar.className = 'h-1 flex-1 bg-green-500 rounded-full transition-all duration-300';
                });
                strengthText.textContent = 'Kuchli parol';
                strengthText.className = 'text-xs text-green-600 mt-1';
            }
        });

        // Show alert function
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert-enter transform transition-all duration-300';
            
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const icon = type === 'success' ? 
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' :
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
            
            alertDiv.innerHTML = `
                <div class="${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 min-w-[300px] max-w-md">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${icon}
                    </svg>
                    <span class="flex-grow">${message}</span>
                    <button onclick="this.closest('.alert-enter').remove()" class="flex-shrink-0 hover:opacity-75 transition-opacity">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            
            alertContainer.appendChild(alertDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.classList.add('alert-exit');
                    setTimeout(() => alertDiv.remove(), 300);
                }
            }, 5000);
        }

        // Form submit handler
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Validate password length
            if (password.length < 6) {
                showAlert('Parol kamida 6 ta belgidan iborat bo\'lishi kerak', 'error');
                passwordInput.focus();
                return;
            }

            // Show loading state
            btnText.classList.add('hidden');
            loadingSpinner.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

            try {
                const response = await fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name,
                        email,
                        password
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('Muvaffaqiyatli ro\'yxatdan o\'tdingiz!', 'success');
                    setTimeout(() => {
                        window.location.href = '../';
                    }, 1500);
                } else {
                    showAlert(result.message || 'Ro\'yxatdan o\'tishda xatolik', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Server bilan bog\'lanishda xatolik', 'error');
            } finally {
                // Reset loading state
                btnText.classList.remove('hidden');
                loadingSpinner.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        });
    </script>
</body>

</html>