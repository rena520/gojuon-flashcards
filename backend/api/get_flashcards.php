<?php
// 包含数据库连接
require_once 'db.php';

// 设置响应头
header('Content-Type: application/json');

try {
    // 准备查询语句，获取所有闪卡（包括未激活的）和每个闪卡的平均计时
    $stmt = $pdo->prepare("
        SELECT
            f.id,
            f.japanese,
            f.hiragana,
            f.romaji,
            f.meaning,
            f.is_active,
            (SELECT AVG(time_taken)
             FROM practice_records pr
             WHERE pr.flashcard_id = f.id
             ORDER BY pr.created_at DESC
             LIMIT 3)
            AS avg_time
        FROM flashcards f
        ORDER BY f.id ASC
    ");
    // 执行查询
    $stmt->execute();
    // 获取结果
    $flashcards = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // v2：標記每張卡是否有自錄音頻（audio/<romaji>.<ext>），有就優先用它播放
    $audioDir = dirname(__DIR__, 2) . '/audio';
    $exts = ['mp3', 'm4a', 'wav', 'ogg'];
    foreach ($flashcards as &$card) {
        $card['audio'] = null;
        $romaji = basename(trim((string)$card['romaji']));
        if ($romaji !== '') {
            foreach ($exts as $ext) {
                if (is_file($audioDir . '/' . $romaji . '.' . $ext)) {
                    $card['audio'] = $romaji . '.' . $ext;
                    break;
                }
            }
        }
    }
    unset($card);

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
