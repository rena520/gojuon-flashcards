<?php
// 包含数据库连接
require_once 'db.php';

// 设置响应头
header('Content-Type: application/json');

// 获取POST数据
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['total_cards']) || !isset($data['total_time']) || !isset($data['total_errors']) || !isset($data['records'])) {
    echo json_encode([
        'success' => false,
        'message' => '缺少必要参数'
    ]);
    exit;
}

// v2：練習模式（visual=看字, listening=聽寫），白名單校驗
$mode = isset($data['mode']) && in_array($data['mode'], ['visual', 'listening'], true) ? $data['mode'] : 'visual';

try {
    // 开始事务
    $pdo->beginTransaction();

    // 插入练习会话
    $stmt = $pdo->prepare("INSERT INTO practice_sessions (total_cards, total_time, total_errors, mode) VALUES (:total_cards, :total_time, :total_errors, :mode)");
    $stmt->execute([
        ':total_cards' => $data['total_cards'],
        ':total_time' => $data['total_time'],
        ':total_errors' => $data['total_errors'],
        ':mode' => $mode
    ]);

    // 获取会话ID
    $session_id = $pdo->lastInsertId();

    // 插入练习记录
    $stmt = $pdo->prepare("INSERT INTO practice_records (session_id, flashcard_id, time_taken, is_correct, showed_answer) VALUES (:session_id, :flashcard_id, :time_taken, :is_correct, :showed_answer)");

    foreach ($data['records'] as $record) {
        // 确保布尔值被正确转换为整数
        $is_correct = isset($record['is_correct']) && $record['is_correct'] ? 1 : 0;
        $showed_answer = isset($record['showed_answer']) && $record['showed_answer'] ? 1 : 0;

        $stmt->execute([
            ':session_id' => $session_id,
            ':flashcard_id' => $record['flashcard_id'],
            ':time_taken' => $record['time_taken'],
            ':is_correct' => $is_correct,
            ':showed_answer' => $showed_answer
        ]);
    }

    // 提交事务
    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => '练习记录保存成功'
    ]);
} catch (PDOException $e) {
    // 回滚事务
    $pdo->rollBack();

    echo json_encode([
        'success' => false,
        'message' => '保存练习记录失败: ' . $e->getMessage()
    ]);
}
