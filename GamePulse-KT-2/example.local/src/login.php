<?php
session_start();
define('ROOT_PATH', __DIR__ . '/');
include_once ROOT_PATH . 'settings/pdo.php';
include_once ROOT_PATH . 'models/Gamer.php';

use Models\Gamer;

$conn = getConnection();
$pdo = $conn[0] ? $conn[1] : null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Введите логин и пароль';
    } else {
        $gamer = new Gamer($pdo);
        if ($gamer->loadByUsername($username) && $gamer->verifyPassword($password)) {
            $_SESSION['user_id'] = $gamer->id;
            $_SESSION['username'] = $gamer->username;
            $_SESSION['role'] = $gamer->role;
            header('Location: index.php');
            exit;
        } else {
            $error = 'Неверный логин или пароль';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход - GamePulse</title>
    <style>
        body {
            background: #1a1e2b;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-box {
            background: #0f1219;
            padding: 40px;
            border-radius: 20px;
            width: 400px;
            border: 1px solid #2c3142;
        }
        h1 {
            color: #7c9eff;
            margin-bottom: 30px;
            text-align: center;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background: #1e263b;
            border: 1px solid #36405e;
            border-radius: 8px;
            color: white;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #2a3457;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background: #3e4b74;
        }
        .error {
            color: #ff8a7a;
            margin: 10px 0;
        }
        .link {
            text-align: center;
            margin-top: 20px;
        }
        .link a {
            color: #7c9eff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>Вход в GamePulse</h1>
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Логин" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>
        <div class="link">
            <a href="register.php">Нет аккаунта? Зарегистрироваться</a>
        </div>
    </div>
</body>
</html>