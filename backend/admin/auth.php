<?php
// 五十音閃卡後台鑒權：密碼以 bcrypt 雜湊存於 backend/api/admin_auth.php（該檔不存在＝尚未設定）
// 頁面用 require_page_auth()（未登入顯示設定/登入表單），管理 API 用 require_api_auth()（未登入回 401 JSON）
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    session_start();
}

function np_auth_hash_file() { return dirname(__DIR__) . '/api/admin_auth.php'; }
function np_auth_hash() {
    $f = np_auth_hash_file();
    if (!is_file($f)) return null;
    $h = include $f;
    return is_string($h) && $h !== '' ? $h : null;
}
function np_auth_save_hash($hash) {
    $content = "<?php\n// 五十音閃卡後台密碼雜湊（由管理員首次設定產生）\nreturn " . var_export($hash, true) . ";\n";
    return file_put_contents(np_auth_hash_file(), $content, LOCK_EX) !== false;
}
function np_auth_authed() { return !empty($_SESSION['np_admin']); }

function np_auth_render($mode, $error = '') {
    $title = $mode === 'set' ? '設定管理密碼' : '管理員登入';
    $self = htmlspecialchars($_SERVER['PHP_SELF'] ?? '', ENT_QUOTES, 'UTF-8');
    $err = $error !== '' ? '<div class="err">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>' : '';
    $confirm = $mode === 'set' ? '<input type="password" name="password2" placeholder="再輸入一次新密碼" autocomplete="new-password" required>' : '';
    $hint = $mode === 'set' ? '<p class="hint">這是第一次使用後台，請設定一組管理密碼（至少 6 個字元）。設定後，本頁與音頻管理、閃卡管理 API 都會用這組密碼把關。</p>' : '<p class="hint">五十音閃卡後台，請輸入管理密碼。</p>';
    $btn = $mode === 'set' ? '設定密碼並進入' : '登入';
    echo <<<HTML
<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$title} - 五十音閃卡</title>
<style>
:root { --grad-brand: linear-gradient(92deg, #7c5cff, #e044a8); --ink: #2f2a3a; --ink-soft: #6f6880; }
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: "Hiragino Sans", "Noto Sans JP", "PingFang TC", "Microsoft JhengHei", Arial, sans-serif; color: var(--ink); min-height: 100vh; display: flex; align-items: center; justify-content: center; background: radial-gradient(1100px 520px at 85% -5%, rgba(255,190,220,.55), transparent 60%), radial-gradient(900px 480px at -10% 10%, rgba(196,181,253,.5), transparent 60%), linear-gradient(180deg, #f7f3fc, #f3f0fa); padding: 20px; }
.box { background: rgba(255,255,255,.9); border-radius: 20px; padding: 32px 30px; max-width: 380px; width: 100%; border: 1px solid rgba(255,255,255,.8); box-shadow: 0 18px 40px -18px rgba(124,92,255,.3); }
h1 { font-size: 22px; text-align: center; margin-bottom: 8px; background: var(--grad-brand); -webkit-background-clip: text; background-clip: text; color: transparent; }
.hint { color: var(--ink-soft); font-size: 13.5px; text-align: center; margin-bottom: 18px; line-height: 1.6; }
.err { background: #fdf2f8; border: 1.5px solid #fbcfe8; color: #be185d; border-radius: 10px; padding: 9px 12px; font-size: 14px; text-align: center; margin-bottom: 14px; }
input { width: 100%; padding: 12px 14px; font-size: 16px; border: 1.5px solid #e5def5; border-radius: 12px; margin-bottom: 12px; outline: none; }
input:focus { border-color: #8b5cf6; box-shadow: 0 0 0 4px rgba(139,92,246,.15); }
button { width: 100%; padding: 12px; font-size: 16px; font-weight: 700; border: none; border-radius: 999px; background: var(--grad-brand); color: #fff; cursor: pointer; box-shadow: 0 10px 22px -10px rgba(224,68,168,.55); }
.back { display: block; text-align: center; margin-top: 16px; color: #6f6880; font-size: 13.5px; text-decoration: none; }
</style>
</head>
<body>
<div class="box">
<h1>{$title}</h1>
{$hint}
{$err}
<form method="post" action="{$self}">
<input type="password" name="password" placeholder="管理密碼" autocomplete="current-password" required>
{$confirm}
<button type="submit">{$btn}</button>
</form>
<a class="back" href="../../index.php">← 回練習前台</a>
</div>
</body>
</html>
HTML;
    exit;
}

function require_page_auth() {
    if (isset($_GET['logout'])) {
        $_SESSION = [];
        session_destroy();
        header('Location: ' . ($_SERVER['PHP_SELF'] ?? 'index.php'));
        exit;
    }
    $hash = np_auth_hash();
    if ($hash === null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
            $pw = (string)$_POST['password'];
            $pw2 = (string)($_POST['password2'] ?? '');
            if (strlen($pw) < 6) { np_auth_render('set', '密碼至少要 6 個字元'); }
            if ($pw !== $pw2) { np_auth_render('set', '兩次輸入的密碼不一致'); }
            if (!np_auth_save_hash(password_hash($pw, PASSWORD_DEFAULT))) { np_auth_render('set', '密碼儲存失敗，請稍後再試'); }
            $_SESSION['np_admin'] = true;
            header('Location: ' . ($_SERVER['PHP_SELF'] ?? 'index.php'));
            exit;
        }
        np_auth_render('set');
    }
    if (!np_auth_authed()) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
            if (password_verify((string)$_POST['password'], $hash)) {
                $_SESSION['np_admin'] = true;
                header('Location: ' . ($_SERVER['PHP_SELF'] ?? 'index.php'));
                exit;
            }
            sleep(1);
            np_auth_render('login', '密碼不正確');
        }
        np_auth_render('login');
    }
}

function require_api_auth() {
    if (np_auth_hash() === null || !np_auth_authed()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => '未登入或登入已過期，請先到管理入口登入']);
        exit;
    }
}
