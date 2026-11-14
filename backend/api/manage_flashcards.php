<?php
// 包含数据库连接
require_once 'db.php';

// v2 鑒權：管理 API 需先登入
require_once __DIR__ . '/../admin/auth.php';
require_api_auth();

// 设置响应头
header('Content-Type: application/json');

// 获取POST数据
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['action'])) {
    echo json_encode([
        'success' => false,
        'message' => '缺少必要参数'
    ]);
    exit;
}

try {
    switch ($data['action']) {
        case 'add':
            // 添加闪卡
            if (!isset($data['japanese']) || !isset($data['hiragana']) || !isset($data['romaji'])) {
                echo json_encode([
                    'success' => false,
                    'message' => '缺少闪卡数据'
                ]);
                exit;
            }
            
            // 如果meaning不存在，设置为空字符串
            if (!isset($data['meaning'])) {
                $data['meaning'] = '';
            }
            
            $stmt = $pdo->prepare("INSERT INTO flashcards (japanese, hiragana, romaji, meaning) VALUES (:japanese, :hiragana, :romaji, :meaning)");
            $stmt->execute([
                ':japanese' => $data['japanese'],
                ':hiragana' => $data['hiragana'],
                ':romaji' => $data['romaji'],
                ':meaning' => $data['meaning']
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => '闪卡添加成功'
            ]);
            break;
            
        case 'update':
            // 修改闪卡
            if (!isset($data['id']) || !isset($data['japanese']) || !isset($data['hiragana']) || !isset($data['romaji'])) {
                echo json_encode([
                    'success' => false,
                    'message' => '缺少闪卡数据'
                ]);
                exit;
            }
            
            // 如果meaning不存在，设置为空字符串
            if (!isset($data['meaning'])) {
                $data['meaning'] = '';
            }
            
            $stmt = $pdo->prepare("UPDATE flashcards SET japanese = :japanese, hiragana = :hiragana, romaji = :romaji, meaning = :meaning WHERE id = :id");
            $stmt->execute([
                ':id' => $data['id'],
                ':japanese' => $data['japanese'],
                ':hiragana' => $data['hiragana'],
                ':romaji' => $data['romaji'],
                ':meaning' => $data['meaning']
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => '闪卡修改成功'
            ]);
            break;
            
        case 'delete':
            // 删除闪卡
            if (!isset($data['id'])) {
                echo json_encode([
                    'success' => false,
                    'message' => '缺少闪卡ID'
                ]);
                exit;
            }
            
            $stmt = $pdo->prepare("UPDATE flashcards SET is_active = false WHERE id = :id");
            $stmt->execute([':id' => $data['id']]);
            
            echo json_encode([
                'success' => true,
                'message' => '闪卡删除成功'
            ]);
            break;
            
        case 'toggle_active':
            // 切换闪卡激活状态
            if (!isset($data['id']) || !isset($data['is_active'])) {
                echo json_encode([
                    'success' => false,
                    'message' => '缺少必要参数'
                ]);
                exit;
            }
            
            // 确保is_active是整数类型
            $is_active = (int)$data['is_active'];
            
            $stmt = $pdo->prepare("UPDATE flashcards SET is_active = :is_active WHERE id = :id");
            $stmt->execute([
                ':id' => $data['id'],
                ':is_active' => $is_active
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => '操作成功'
            ]);
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'message' => '无效的操作'
            ]);
            break;
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => '操作失败: ' . $e->getMessage()
    ]);
}
?>