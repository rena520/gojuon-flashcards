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
    $stmt = $pdo->prepare("SELECT id, japanese, hiragana, is_active FROM flashcards");
    // 执行查询
    $stmt->execute();
    // 获取结果
    $flashcards = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 统计激活状态
    $activeCount = 0;
    $inactiveCount = 0;
    
    foreach ($flashcards as $card) {
        if ($card['is_active'] == 1) {
            $activeCount++;
        } else {
            $inactiveCount++;
        }
    }
    
    // 返回结果
    echo json_encode([
        'success' => true,
        'total' => count($flashcards),
        'active' => $activeCount,
        'inactive' => $inactiveCount,
        'flashcards' => $flashcards
    ]);
} catch (PDOException $e) {
    // 处理错误
    echo json_encode([
        'success' => false,
        'message' => '查询失败: ' . $e->getMessage()
    ]);
}
?>