<?php
// 数据库连接配置
$config = [
    "host" => "localhost",
    "port" => "3306",
    "dbname" => "japanese_learning",
    "user" => "your_db_user",
    "pass" => "your_db_password"
];

// 如果是被install.php调用，返回配置数组
if (basename($_SERVER['PHP_SELF']) === 'install.php') {
    return $config;
}

// 否则创建数据库连接
try {
    // 创建数据库连接
    $pdo = new PDO(
        "mysql:host=" . $config['host'] . ";port=" . $config['port'] . ";dbname=" . $config['dbname'] . ";charset=utf8mb4",
        $config['user'],
        $config['pass']
    );
    // 设置错误模式为异常
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 处理连接错误
    die('数据库连接失败: ' . $e->getMessage());
}
?>