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
</head>

<body>

    <form action="" method="post">
        <label for="name">Ism:</label>
        <input type="text" id="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" required>
        <br>
        <input type="submit" value="Ro'yxatdan o'tish">

        <a href="../login/">Kirish</a>
    </form>

    <script>
        const registerForm = document.querySelector('form');

        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

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
                    window.location.href = '../';
                } else {
                    alert(result.message);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    </script>

</body>

</html>