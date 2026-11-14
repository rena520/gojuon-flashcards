<?php
// v2 鑒權：先過管理密碼門
require_once __DIR__ . '/auth.php';
require_page_auth();
// 自錄音頻管理：每個假名一支音頻（audio/<romaji>.<ext>），有自錄的優先用自錄，沒有的用機器朗讀
require_once '../api/db.php';

$audioDir = dirname(__DIR__, 2) . '/audio';
$exts = ['mp3', 'm4a', 'wav', 'ogg'];
if (!is_dir($audioDir)) { mkdir($audioDir, 0775, true); }

$cards = $pdo->query("SELECT id, japanese, hiragana, romaji FROM flashcards ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$romajiList = [];
foreach ($cards as $c) { $romajiList[] = trim((string)$c['romaji']); }

function findAudio($audioDir, $exts, $romaji) {
    foreach ($exts as $ext) {
        if (is_file($audioDir . '/' . $romaji . '.' . $ext)) return $romaji . '.' . $ext;
    }
    return null;
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $romaji = isset($_POST['romaji']) ? trim((string)$_POST['romaji']) : '';
    if (!in_array($romaji, $romajiList, true)) {
        $msg = '錯誤：未知的羅馬字';
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        foreach ($exts as $ext) { @unlink($audioDir . '/' . $romaji . '.' . $ext); }
        $msg = '已刪除 ' . $romaji . ' 的自錄音頻，改回機器朗讀';
    } elseif (isset($_FILES['audio']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
        $size = $_FILES['audio']['size'];
        $ext = strtolower(pathinfo($_FILES['audio']['name'], PATHINFO_EXTENSION));
        if ($size > 3 * 1024 * 1024) {
            $msg = '錯誤：檔案超過 3MB';
        } elseif (!in_array($ext, $exts, true)) {
            $msg = '錯誤：只接受 mp3 / m4a / wav / ogg';
        } else {
            foreach ($exts as $e) { @unlink($audioDir . '/' . $romaji . '.' . $e); }
            if (move_uploaded_file($_FILES['audio']['tmp_name'], $audioDir . '/' . $romaji . '.' . $ext)) {
                $msg = '已上傳 ' . $romaji . ' 的自錄音頻';
            } else {
                $msg = '錯誤：上傳失敗（寫入不成功）';
            }
        }
    } else {
        $msg = '錯誤：沒有收到檔案';
    }
    header('Location: audio.php?msg=' . urlencode($msg));
    exit;
}
if (isset($_GET['msg'])) $msg = (string)$_GET['msg'];
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>自錄音頻管理 - 五十音閃卡</title>
    <style>
        :root { --grad-brand: linear-gradient(92deg, #7c5cff, #e044a8); --c-brand-deep: #7c5cff; --ink: #2f2a3a; --ink-soft: #6f6880; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: "Hiragino Sans", "Noto Sans JP", "PingFang TC", "Microsoft JhengHei", Arial, sans-serif;
            color: var(--ink); line-height: 1.6; min-height: 100vh;
            background:
                radial-gradient(1100px 520px at 85% -5%, rgba(255,190,220,.55), transparent 60%),
                radial-gradient(900px 480px at -10% 10%, rgba(196,181,253,.5), transparent 60%),
                linear-gradient(180deg, #f7f3fc, #f3f0fa);
            background-attachment: fixed;
        }
        .container { max-width: 900px; margin: 0 auto; padding: 28px 20px 40px; }
        h1 { text-align: center; margin-bottom: 10px; font-size: 28px; background: var(--grad-brand); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .note { text-align: center; color: var(--ink-soft); font-size: 14px; margin-bottom: 20px; }
        .msg { background: #ecfdf5; border: 1.5px solid #a7f3d0; color: #065f46; border-radius: 12px; padding: 10px 16px; margin-bottom: 18px; text-align: center; font-weight: 700; }
        .card-panel { background: rgba(255,255,255,.88); border-radius: 20px; padding: 22px; border: 1px solid rgba(255,255,255,.8); box-shadow: 0 18px 40px -18px rgba(124,92,255,.28); }
        table { width: 100%; border-collapse: collapse; font-size: 15px; }
        th, td { padding: 10px 8px; border-bottom: 1px solid #efe7fb; text-align: center; vertical-align: middle; }
        th { color: var(--ink-soft); font-size: 13px; }
        .kana { font-size: 20px; font-weight: 800; color: var(--c-brand-deep); }
        .own { color: #059669; font-weight: 700; }
        .tts { color: var(--ink-soft); }
        audio { height: 32px; max-width: 170px; vertical-align: middle; }
        .upload-form { display: flex; gap: 6px; justify-content: center; align-items: center; flex-wrap: wrap; }
        input[type="file"] { font-size: 13px; max-width: 180px; }
        button { padding: 7px 14px; font-size: 13px; border: none; border-radius: 999px; cursor: pointer; font-weight: 700; }
        .btn-up { background: var(--grad-brand); color: #fff; }
        .btn-del { background: #fff; color: #e11d63; border: 1.5px solid #fbcfe8; }
        .back { display: inline-block; margin-bottom: 14px; color: var(--c-brand-deep); text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎙️ 自錄音頻管理</h1>
        <p class="note">每個假名錄一支短音頻上傳，這個音就會改用你的錄音播放；沒錄的繼續用機器朗讀。<br>手機錄音的 m4a 可以直接傳，一支 3MB 以內。錄的時候唸一遍就好，前後留一點空白。</p>
        <a class="back" href="index.php">← 回閃卡管理</a> <a class="back" href="?logout=1" style="color:#e11d63; margin-left:14px;">登出</a>
        <?php if ($msg !== ''): ?><div class="msg"><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
        <div class="card-panel">
            <table>
                <thead><tr><th>平假名</th><th>片假名</th><th>羅馬字</th><th>目前聲音</th><th>上傳 / 管理</th></tr></thead>
                <tbody>
                <?php foreach ($cards as $c): $r = trim((string)$c['romaji']); $file = findAudio($audioDir, $exts, $r); ?>
                    <tr>
                        <td class="kana"><?php echo htmlspecialchars(trim((string)$c['japanese']), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="kana"><?php echo htmlspecialchars(trim((string)$c['hiragana']), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($r, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <?php if ($file): ?>
                                <span class="own">自錄</span> <audio controls preload="none" src="../../audio/<?php echo htmlspecialchars($file, ENT_QUOTES, 'UTF-8'); ?>"></audio>
                            <?php else: ?>
                                <span class="tts">機器朗讀</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form class="upload-form" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="romaji" value="<?php echo htmlspecialchars($r, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="file" name="audio" accept=".mp3,.m4a,.wav,.ogg,audio/*" required>
                                <button class="btn-up" type="submit">上傳</button>
                                <?php if ($file): ?><button class="btn-del" type="submit" name="action" value="delete" formnovalidate>刪除</button><?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
