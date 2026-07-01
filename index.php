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
</head>

<body>

    <h1>Salom, <?= $_SESSION['user']['name'] ?>!</h1>
    <p>Email: <?= $_SESSION['user']['email'] ?></p>

    <form action="./logout/" method="post" onsubmit="return confirm('Haqiqatan ham tizimdan chiqmoqchimisiz?')">
        <input type="submit" value="Chiqish" id="logoutBtn">
    </form>
</body>

</html>