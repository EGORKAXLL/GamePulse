<?php
session_start();

define('ROOT_PATH', __DIR__ . '/');

// Автозагрузка моделей
spl_autoload_register(function ($class) {
    $prefix = 'Models\\';
    $base_dir = ROOT_PATH . 'models/';
    if (strpos($class, $prefix) === 0) {
        $relative_class = substr($class, strlen($prefix));
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) require $file;
    }
});

require_once ROOT_PATH . 'settings/pdo.php';
$conn = getConnection();
if (!$conn[0]) die("Ошибка БД: " . $conn[1]);
$pdo = $conn[1];

// Получаем URI без параметров
$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = strtok($requestUri, '?'); // убираем ?id=123
$requestUri = str_replace('/src', '', $requestUri);
$requestUri = trim($requestUri, '/');

// Если пусто — это главная (feed)
if ($requestUri == '') {
    $requestUri = 'feed';
}

// Маршруты
switch ($requestUri) {
    case 'feed':
        require_once ROOT_PATH . 'controllers/GameController.php';
        $ctrl = new GameController($pdo);
        $ctrl->feed();
        break;
        
    case 'catalog':
        require_once ROOT_PATH . 'controllers/GameController.php';
        $ctrl = new GameController($pdo);
        $ctrl->catalog();
        break;
        
    case 'game':
        require_once ROOT_PATH . 'controllers/GameController.php';
        $ctrl = new GameController($pdo);
        $ctrl->show($_GET['id'] ?? 0);
        break;
        
    case 'collection':
        require_once ROOT_PATH . 'controllers/CollectionController.php';
        $ctrl = new CollectionController($pdo);
        $ctrl->index();
        break;
        
    case 'collection/add':
        require_once ROOT_PATH . 'controllers/CollectionController.php';
        $ctrl = new CollectionController($pdo);
        $ctrl->add();
        break;
        
    case 'collection/edit':
        require_once ROOT_PATH . 'controllers/CollectionController.php';
        $ctrl = new CollectionController($pdo);
        $ctrl->edit();
        break;
        
    case 'collection/delete':
        require_once ROOT_PATH . 'controllers/CollectionController.php';
        $ctrl = new CollectionController($pdo);
        $ctrl->delete();
        break;
        
    case 'review/add':
        require_once ROOT_PATH . 'controllers/ReviewController.php';
        $ctrl = new ReviewController($pdo);
        $ctrl->add();
        break;
        
    case 'review/delete':
        require_once ROOT_PATH . 'controllers/ReviewController.php';
        $ctrl = new ReviewController($pdo);
        $ctrl->delete();
        break;
        
    case 'friends':
        require_once ROOT_PATH . 'controllers/FriendController.php';
        $ctrl = new FriendController($pdo);
        $ctrl->index();
        break;
        
    case 'friends/add':
        require_once ROOT_PATH . 'controllers/FriendController.php';
        $ctrl = new FriendController($pdo);
        $ctrl->add();
        break;
        
    case 'friends/accept':
        require_once ROOT_PATH . 'controllers/FriendController.php';
        $ctrl = new FriendController($pdo);
        $ctrl->accept();
        break;
        
    case 'friends/reject':
        require_once ROOT_PATH . 'controllers/FriendController.php';
        $ctrl = new FriendController($pdo);
        $ctrl->reject();
        break;
        
    case 'friends/remove':
        require_once ROOT_PATH . 'controllers/FriendController.php';
        $ctrl = new FriendController($pdo);
        $ctrl->remove();
        break;
        
    case 'login':
        require_once ROOT_PATH . 'controllers/UserController.php';
        $ctrl = new UserController($pdo);
        $ctrl->login();
        break;
        
    case 'register':
        require_once ROOT_PATH . 'controllers/UserController.php';
        $ctrl = new UserController($pdo);
        $ctrl->register();
        break;
        
    case 'logout':
        require_once ROOT_PATH . 'controllers/UserController.php';
        $ctrl = new UserController($pdo);
        $ctrl->logout();
        break;
        
    default:
        http_response_code(404);
        echo "<h1>404 - Страница не найдена</h1>";
        echo "<p>Запрошенный путь: /$requestUri</p>";
        break;
}