<?php
/**
 * 日语闪卡应用安装脚本
 * 用于在服务器上部署应用
 */

// 定义应用根目录
define('APP_ROOT', __DIR__);

// 安装步骤
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

// 安装状态
$errors = [];
$success = false;

// 步骤1：环境检查
if ($step === 1) {
    // 检查PHP版本
    $php_version = PHP_VERSION;
    $php_required = '7.0';
    $php_ok = version_compare($php_version, $php_required, '>=');
    
    // 检查必要的PHP扩展
    $extensions = [
        'pdo' => extension_loaded('pdo'),
        'pdo_mysql' => extension_loaded('pdo_mysql')
    ];
    
    // 检查文件权限
    $permissions = [
        'backend/api' => is_writable(APP_ROOT . '/backend/api')
    ];
    
    // 检查是否已安装
    $db_config_file = APP_ROOT . '/backend/api/db.php';
    $already_installed = file_exists($db_config_file) && filesize($db_config_file) > 0;
}

// 步骤2：数据库配置
if ($step === 2) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $db_host = $_POST['db_host'] ?? 'localhost';
        $db_port = $_POST['db_port'] ?? '3306';
        $db_name = $_POST['db_name'] ?? '';
        $db_user = $_POST['db_user'] ?? '';
        $db_pass = $_POST['db_pass'] ?? '';
        
        // 验证输入
        if (empty($db_name)) {
            $errors[] = '数据库名称不能为空';
        }
        if (empty($db_user)) {
            $errors[] = '数据库用户名不能为空';
        }
        
        if (empty($errors)) {
            // 测试数据库连接
            try {
                $pdo = new PDO(
                    "mysql:host=$db_host;port=$db_port;dbname=$db_name",
                    $db_user,
                    $db_pass
                );
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // 保存数据库配置
                $db_config_content = '<?php
// 数据库连接配置
$config = [
    "host" => "' . $db_host . '",
    "port" => "' . $db_port . '",
    "dbname" => "' . $db_name . '",
    "user" => "' . $db_user . '",
    "pass" => "' . $db_pass . '"
];

// 如果是被install.php调用，返回配置数组
if (basename($_SERVER[\'PHP_SELF\']) === \'install.php\') {
    return $config;
}

// 否则创建数据库连接
try {
    // 创建数据库连接
    $pdo = new PDO(
        "mysql:host=" . $config[\'host\'] . ";port=" . $config[\'port\'] . ";dbname=" . $config[\'dbname\'] . ";charset=utf8mb4",
        $config[\'user\'],
        $config[\'pass\']
    );
    // 设置错误模式为异常
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 处理连接错误
    die(\'数据库连接失败: \' . $e->getMessage());
}
?>'
                ;
                
                if (file_put_contents(APP_ROOT . '/backend/api/db.php', $db_config_content) === false) {
                    $errors[] = '无法写入数据库配置文件';
                } else {
                    // 跳转到步骤3
                    header('Location: install.php?step=3');
                    exit;
                }
            } catch (PDOException $e) {
                $errors[] = '数据库连接失败: ' . $e->getMessage();
            }
        }
    }
}

// 步骤3：导入数据库
if ($step === 3) {
    // 加载数据库配置
    $db_config = require APP_ROOT . '/backend/api/db.php';
    
    try {
        $pdo = new PDO(
            "mysql:host={$db_config['host']};port={$db_config['port']};dbname={$db_config['dbname']};charset=utf8mb4",
            $db_config['user'],
            $db_config['pass']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // 读取SQL文件
        $sql_file = APP_ROOT . '/backend/api/database.sql';
        if (file_exists($sql_file)) {
            $sql = file_get_contents($sql_file);
            
            // 执行SQL语句
            $pdo->exec($sql);
            $success = true;
        } else {
            $errors[] = '数据库SQL文件不存在';
        }
    } catch (PDOException $e) {
        $errors[] = '导入数据库失败: ' . $e->getMessage();
    }
}

// 步骤4：安装完成
if ($step === 4) {
    // 检查是否已安装
    $db_config_file = APP_ROOT . '/backend/api/db.php';
    $already_installed = file_exists($db_config_file) && filesize($db_config_file) > 0;
    
    if (!$already_installed) {
        header('Location: install.php?step=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>日语闪卡应用 - 安装向导</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #4CAF50;
        }
        
        h2 {
            margin-top: 20px;
            margin-bottom: 15px;
            color: #333;
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        
        .step {
            flex: 1;
            text-align: center;
            padding: 10px;
            background-color: #e0e0e0;
            border-radius: 4px;
            position: relative;
        }
        
        .step.active {
            background-color: #4CAF50;
            color: #fff;
        }
        
        .step.completed {
            background-color: #8BC34A;
            color: #fff;
        }
        
        .step::after {
            content: '';
            position: absolute;
            top: 50%;
            right: -20px;
            width: 40px;
            height: 2px;
            background-color: #e0e0e0;
            transform: translateY(-50%);
        }
        
        .step:last-child::after {
            display: none;
        }
        
        .step.active::after,
        .step.completed::after {
            background-color: #4CAF50;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        
        .btn:hover {
            background-color: #45a049;
        }
        
        .btn-secondary {
            background-color: #9e9e9e;
        }
        
        .btn-secondary:hover {
            background-color: #757575;
        }
        
        .error {
            color: #f44336;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #ffebee;
            border-radius: 4px;
        }
        
        .success {
            color: #4CAF50;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #e8f5e8;
            border-radius: 4px;
        }
        
        .check-item {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 4px;
        }
        
        .check-item.success {
            background-color: #e8f5e8;
            color: #4CAF50;
        }
        
        .check-item.error {
            background-color: #ffebee;
            color: #f44336;
        }
        
        .info {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #e3f2fd;
            border-radius: 4px;
            color: #1976D2;
        }
        
        .btn-group {
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>日语闪卡应用 - 安装向导</h1>
        
        <div class="step-indicator">
            <div class="step <?php echo $step >= 1 ? ($step === 1 ? 'active' : 'completed') : ''; ?>">环境检查</div>
            <div class="step <?php echo $step >= 2 ? ($step === 2 ? 'active' : 'completed') : ''; ?>">数据库配置</div>
            <div class="step <?php echo $step >= 3 ? ($step === 3 ? 'active' : 'completed') : ''; ?>">导入数据库</div>
            <div class="step <?php echo $step >= 4 ? 'active' : ''; ?>">安装完成</div>
        </div>
        
        <?php if ($step === 1): ?>
            <h2>步骤 1: 环境检查</h2>
            
            <?php if ($already_installed): ?>
                <div class="info">
                    <p>检测到应用已经安装。如果需要重新安装，请先删除 <code>backend/api/db.php</code> 文件。</p>
                </div>
                <div class="btn-group">
                    <a href="index.php" class="btn">访问应用</a>
                </div>
            <?php else: ?>
                <div class="check-item <?php echo $php_ok ? 'success' : 'error'; ?>">
                    PHP版本: <?php echo $php_version; ?> (要求: <?php echo $php_required; ?>)
                </div>
                
                <?php foreach ($extensions as $ext => $status): ?>
                    <div class="check-item <?php echo $status ? 'success' : 'error'; ?>">
                        PHP扩展 <?php echo $ext; ?>: <?php echo $status ? '已安装' : '未安装'; ?>
                    </div>
                <?php endforeach; ?>
                
                <?php foreach ($permissions as $path => $status): ?>
                    <div class="check-item <?php echo $status ? 'success' : 'error'; ?>">
                        目录权限 <?php echo $path; ?>: <?php echo $status ? '可写' : '不可写'; ?>
                    </div>
                <?php endforeach; ?>
                
                <?php if ($php_ok && !in_array(false, $extensions) && !in_array(false, $permissions)): ?>
                    <div class="btn-group">
                        <a href="install.php?step=2" class="btn">下一步</a>
                    </div>
                <?php else: ?>
                    <div class="error">
                        <p>环境检查失败，请修复上述问题后再继续。</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php elseif ($step === 2): ?>
            <h2>步骤 2: 数据库配置</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="error">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="install.php?step=2">
                <div class="form-group">
                    <label for="db_host">数据库主机:</label>
                    <input type="text" id="db_host" name="db_host" value="localhost" required>
                </div>
                
                <div class="form-group">
                    <label for="db_port">数据库端口:</label>
                    <input type="text" id="db_port" name="db_port" value="3306" required>
                </div>
                
                <div class="form-group">
                    <label for="db_name">数据库名称:</label>
                    <input type="text" id="db_name" name="db_name" required>
                </div>
                
                <div class="form-group">
                    <label for="db_user">数据库用户名:</label>
                    <input type="text" id="db_user" name="db_user" required>
                </div>
                
                <div class="form-group">
                    <label for="db_pass">数据库密码:</label>
                    <input type="password" id="db_pass" name="db_pass">
                </div>
                
                <div class="btn-group">
                    <a href="install.php?step=1" class="btn btn-secondary">上一步</a>
                    <button type="submit" class="btn">下一步</button>
                </div>
            </form>
        <?php elseif ($step === 3): ?>
            <h2>步骤 3: 导入数据库</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="error">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
                <div class="btn-group">
                    <a href="install.php?step=2" class="btn btn-secondary">上一步</a>
                </div>
            <?php elseif ($success): ?>
                <div class="success">
                    <p>数据库导入成功！</p>
                </div>
                <div class="btn-group">
                    <a href="install.php?step=4" class="btn">下一步</a>
                </div>
            <?php else: ?>
                <div class="info">
                    <p>点击下方按钮开始导入数据库结构。</p>
                </div>
                <div class="btn-group">
                    <a href="install.php?step=2" class="btn btn-secondary">上一步</a>
                    <a href="install.php?step=3" class="btn">导入数据库</a>
                </div>
            <?php endif; ?>
        <?php elseif ($step === 4): ?>
            <h2>步骤 4: 安装完成</h2>
            
            <div class="success">
                <p>恭喜！日语闪卡应用安装成功！</p>
            </div>
            
            <div class="info">
                <h3>应用访问地址：</h3>
                <p><a href="index.php">http://<?php echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']); ?>/index.php</a></p>
                
                <h3>管理后台地址：</h3>
                <p><a href="backend/admin/index.php">http://<?php echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']); ?>/backend/admin/index.php</a></p>
            </div>
            
            <div class="info">
                <h3>使用说明：</h3>
                <ul>
                    <li>1. 首先登录管理后台，添加和管理闪卡</li>
                    <li>2. 在前台页面进行日语练习</li>
                    <li>3. 系统会记录您的练习时间和正确率</li>
                </ul>
            </div>
            
            <div class="btn-group">
                <a href="index.php" class="btn">访问应用</a>
                <a href="backend/admin/index.php" class="btn">进入管理后台</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>