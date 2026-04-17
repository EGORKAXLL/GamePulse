<?php
session_start();

define('ROOT_PATH', __DIR__ . '/');

include_once ROOT_PATH . 'settings/pdo.php';
include_once ROOT_PATH . 'models/Game.php';
include_once ROOT_PATH . 'models/Gamer.php';
include_once ROOT_PATH . 'models/UserGame.php';
include_once ROOT_PATH . 'models/Review.php';
include_once ROOT_PATH . 'models/Friend.php';

use Models\Game;
use Models\Gamer;
use Models\UserGame;
use Models\Review;
use Models\Friend;

$conn = getConnection();

if (!$conn[0]) {
    die("Ошибка подключения к БД: " . $conn[1]);
}

$pdo = $conn[1];

// Проверка авторизации
$currentUser = null;
if (isset($_SESSION['user_id'])) {
    $currentUser = new Gamer($pdo);
    $currentUser->load($_SESSION['user_id']);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GamePulse - Лента рекомендаций</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        body {
            background: #1a1e2b;
            color: #eef2ff;
        }
        .header {
            background: #0f1219;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #2c3142;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #7c9eff;
        }
        .nav {
            display: flex;
            gap: 30px;
        }
        .nav a {
            color: #b9c8ff;
            text-decoration: none;
            font-size: 16px;
        }
        .nav a:hover {
            color: white;
        }
        .user-info {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 30px;
        }
        .greeting {
            font-size: 28px;
            margin-bottom: 30px;
        }
        .section {
            background: #141b2b;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 35px;
            border: 1px solid #2a334c;
        }
        .section-title {
            font-size: 24px;
            margin-bottom: 20px;
            color: #b7cdff;
        }
        .game-grid {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .game-card {
            background: #1e263b;
            border-radius: 16px;
            padding: 15px;
            width: 200px;
            border: 1px solid #36405e;
        }
        .game-card h3 {
            font-size: 18px;
            margin: 10px 0 5px;
        }
        .game-meta {
            color: #8e9fc7;
            font-size: 13px;
        }
        .stars {
            color: #ffc107;
            margin: 8px 0;
        }
        .btn {
            background: #2a3457;
            border: none;
            padding: 8px 0;
            width: 100%;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }
        .btn:hover {
            background: #3e4b74;
        }
        .friend-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .friend-card {
            background: #1e263b;
            border-radius: 16px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 250px;
        }
        .avatar {
            width: 50px;
            height: 50px;
            background: #3a4565;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .login-prompt {
            text-align: center;
            padding: 50px;
            background: #141b2b;
            border-radius: 20px;
        }
        .login-prompt a {
            color: #7c9eff;
            font-size: 18px;
        }
        .footer {
            text-align: center;
            padding: 30px;
            color: #6a7aa3;
            border-top: 1px solid #252d44;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">GamePulse</div>
        <div class="nav">
            <a href="/src/index.php">Лента</a>
            <a href="/src/catalog.php">Каталог</a>
            <?php if ($currentUser): ?>
                <a href="/src/collection.php">Моя коллекция</a>
                <a href="/src/friends.php">Друзья</a>
            <?php endif; ?>
        </div>
        <div class="user-info">
            <?php if ($currentUser): ?>
                <span>👤 <?= htmlspecialchars($currentUser->username) ?></span>
                <a href="/src/logout.php" style="color:#ff8a7a;">Выход</a>
            <?php else: ?>
                <a href="/src/login.php">Вход</a>
                <a href="/src/register.php">Регистрация</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <?php if ($currentUser): ?>
            <div class="greeting">👋 Привет, <strong><?= htmlspecialchars($currentUser->username) ?></strong>!</div>

            <!-- Блок: на основе ваших оценок -->
            <div class="section">
                <div class="section-title">🎯 На основе ваших оценок</div>
                <div class="game-grid">
                    <?php
                    $allGames = Game::getAll($pdo);
                    $displayGames = array_slice($allGames, 0, 4);
                    foreach ($displayGames as $game):
                    ?>
                    <div class="game-card">
                        <div class="poster" style="height:120px; background:#2a3457; border-radius:12px; display:flex; align-items:center; justify-content:center;">🎮</div>
                        <h3><?= htmlspecialchars($game->title) ?></h3>
                        <div class="game-meta"><?= htmlspecialchars($game->developer) ?> • <?= $game->release_year ?></div>
                        <div class="stars">★★★★☆</div>
                        <form action="/src/add_to_collection.php" method="POST">
                            <input type="hidden" name="game_id" value="<?= $game->id ?>">
                            <button type="submit" class="btn">➕ В коллекцию</button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Блок: друзья рекомендуют -->
            <div class="section">
                <div class="section-title">👥 Друзья рекомендуют</div>
                <div class="friend-row">
                    <?php
                    $friends = Friend::getFriends($pdo, $currentUser->id);
                    if (count($friends) > 0):
                        $displayFriends = array_slice($friends, 0, 4);
                        foreach ($displayFriends as $f):
                            $friendId = ($f->gamer1_id == $currentUser->id) ? $f->gamer2_id : $f->gamer1_id;
                            $friend = new Gamer($pdo);
                            $friend->load($friendId);
                    ?>
                    <div class="friend-card">
                        <div class="avatar">😎</div>
                        <div>
                            <b><?= htmlspecialchars($friend->username) ?></b><br>
                            <span style="font-size:13px;">рекомендует</span><br>
                            <i>Elden Ring</i> ★★★★★
                        </div>
                    </div>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <p>У вас пока нет друзей. Добавьте друзей, чтобы видеть их рекомендации!</p>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?>
            <div class="login-prompt">
                <h2>Добро пожаловать в GamePulse!</h2>
                <p style="margin: 20px 0;">Войдите или зарегистрируйтесь, чтобы получать персональные рекомендации игр.</p>
                <a href="/src/login.php">Войти</a> | <a href="/src/register.php">Зарегистрироваться</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        © 2025 GamePulse · Помогаем находить игры по вкусу
    </div>
</body>
</html>