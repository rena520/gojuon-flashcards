<?php
// 包含数据库连接
require_once 'db.php';

// v2 鑒權：管理 API 需先登入
require_once __DIR__ . '/../admin/auth.php';
require_api_auth();

// 设置响应头
header('Content-Type: application/json');

try {
    // 准备查询语句
    $stmt = $pdo->prepare("SELECT id, japanese, hiragana, romaji, meaning FROM flashcards");
    // 执行查询
    $stmt->execute();
    // 获取结果
    $flashcards = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 返回结果
    echo json_encode([
        'success' => true,
        'flashcards' => $flashcards
    ]);
} catch (PDOException $e) {
    // 处理错误
    echo json_encode([
        'success' => false,
        'message' => '获取闪卡失败: ' . $e->getMessage()
    ]);
}
?>