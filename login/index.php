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

    if (empty($data['email'])) {
        echo json_encode(['success' => false, 'message' => 'Email kiritilmagan!']);
        exit;
    }

    if (empty($data['password'])) {
        echo json_encode(['success' => false, 'message' => 'Parol kiritilmagan!']);
        exit;
    }

    $email = $data['email'];
    $password = $data['password'];

    $user = $db->select('users', '*', ['email' => $email]);

    if (empty($user)) {
        echo json_encode(['success' => false, 'message' => 'Bunday foydalanuvchi mavjud emas!']);
        exit;
    }

    $user = $user[0];

    if (password_verify($password, $user['password'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['user']['id'] = $user['id'];
        $_SESSION['user']['name'] = $user['name'];
        $_SESSION['user']['email'] = $user['email'];
        echo json_encode(['success' => true, 'message' => 'Kirish muvaffaqiyatli bajarildi']);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Parol noto‘g‘ri!']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>

    <form action="" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email">
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password">
        <br>
        <input type="submit" value="Login">
    </form>

    <script>
        const loginForm = document.querySelector('form');

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
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