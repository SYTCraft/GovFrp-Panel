<?php
namespace SYTCraftPanel;
use SYTCraftPanel;

global $_config;
$module = $_GET['module'] ?? "";

$rs = Database::querySingleLine("users", Array("username" => $_SESSION['user']));

//封禁检测
if (date($rs['status'])==0)
{
	exit("<script>location='?page=panel&module=home';</script>");
}
?>
<title>控制台 | 盛月映射</title>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
<meta name="author" content="合肥市包河区盛月堂网络科技工作室">
<meta name="language" content="zh-CN">
<meta name="copyright" content="govfrp.com">
<meta name="robots" content="All">
<link rel="icon" href="../assets/home/src/images/favicon.ico">
<script src="../assets/jquery/dist/js/jquery.min.js"></script>
<script src="../assets/panel/dist/js/sweetalert2.all.min.js"></script>
<!--页面通知-->
<script type="text/javascript">
	window.onload = function() {
		Swal.fire({
				title: '提示信息',
				text: "账号已被封禁",
				icon: 'warning',
				showCancelButton: false,
				confirmButtonColor: '#7066e0',
				cancelButtonColor: '#6e7881',
				confirmButtonText: '确认',
				cancelButtonText: '取消'
			})
			.then((result) => {
				if (result.isConfirmed) {
					location = "?page=logout&csrf=<?php echo $_SESSION['token']; ?>";
				}
			})
	}
</script>