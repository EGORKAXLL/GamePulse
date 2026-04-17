<?php
session_start();
define('ROOT_PATH', __DIR__ . '/');
include_once ROOT_PATH . 'settings/pdo.php';
include_once ROOT_PATH . 'models/Gamer.php';

use Models\Gamer;

$conn = getConnection();
$pdo = $conn[0] ? $conn[1] : null;
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Заполните все поля';
    } elseif ($password !== $confirm) {
        $error = 'Пароли не совпадают';
    } elseif (strlen($password) < 4) {
        $error = 'Пароль должен быть не менее 4 символов';
    } else {
        $gamer = new Gamer($pdo);
        $gamer->username = $username;
        $gamer->email = $email;
        $gamer->setPassword($password);
        $gamer->role = 'gamer';
        
        if ($gamer->save()) {
            $success = 'Регистрация успешна! Теперь можете войти.';
        } else {
            $error = 'Ошибка регистрации. Возможно, такой логин или email уже существует.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация - GamePulse</title>
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
        .register-box {
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
        .success {
            color: #7cff9e;
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
    <div class="register-box">
        <h1>Регистрация</h1>
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Логин" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <input type="password" name="confirm_password" placeholder="Повторите пароль" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
        <div class="link">
            <a href="login.php">Уже есть аккаунт? Войти</a>
        </div>
    </div>
</body>
</html>