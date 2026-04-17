<?php

function getConnection() {
    $dsn = 'mysql:host=127.0.1.31;dbname=gamepulse;charset=utf8';
    $user = 'root';
    $password = '';
    
    try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return array(true, $pdo);
    } catch (PDOException $e) {
        return array(false, 'Ошибка подключения: ' . $e->getMessage());
    }
}

?>