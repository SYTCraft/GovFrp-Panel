<?php
namespace SYTCraftPanel;
use SYTCraftPanel;

include "../configuration.php";
global $_config;

$conn = mysqli_connect($_config['db_host'], $_config['db_user'], $_config['db_pass'], $_config['db_name']);
mysqli_set_charset($conn, $_config['db_code']);
if (!$conn) {
    $err = mysqli_connect_error();
    $arr = array('code' => '137', 'msg' => '数据库连接失败: {$err}');
    echo json_encode($arr, JSON_UNESCAPED_UNICODE);  // 不编码中文
    exit();
}

$query = "UPDATE users SET traffic = 20480";
$result = $conn->query($query);

if ($result) {
    echo "流量刷新完成";
} else {
    echo "更新失败: " . $conn->error;
}

// 关闭连接
$conn->close();
?>
