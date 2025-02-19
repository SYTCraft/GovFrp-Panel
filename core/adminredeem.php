<?php
// ../core/adminredeem.php

session_start();
require '../configuration.php'; // 引入配置文件

// 数据库连接
$dsn = "mysql:host={$_config['db_host']};dbname={$_config['db_name']};port={$_config['db_port']};charset={$_config['db_code']}";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new PDO($dsn, $_config['db_user'], $_config['db_pass'], $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

// 检查用户是否为管理员
if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => '用户未登录。']);
    exit;
}

$username = $_SESSION['user'];
$stmt = $pdo->prepare("SELECT `group` FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['group'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => '您没有权限访问此页面。']);
    exit;
}

// 处理添加兑换码请求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $code = generateRandomCode();
    $traffic_value = intval($_POST['traffic_value']);

    $stmt = $pdo->prepare("INSERT INTO redeem_codes (code, traffic_value, is_used) VALUES (?, ?, FALSE)");
    $stmt->execute([$code, $traffic_value]);

    echo json_encode(['status' => 'success', 'message' => "兑换码 $code 已成功添加。"]);
    exit;
}

// 处理删除兑换码请求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $code = $_POST['code'];

    $stmt = $pdo->prepare("DELETE FROM redeem_codes WHERE code = ?");
    $stmt->execute([$code]);

    echo json_encode(['status' => 'success', 'message' => "兑换码 $code 已成功删除。"]);
    exit;
}

// 获取所有兑换码
$stmt = $pdo->prepare("SELECT * FROM redeem_codes");
$stmt->execute();
$redeem_codes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 生成随机兑换码
function generateRandomCode($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>