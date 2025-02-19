<?php
namespace SYTCraftPanel;
use SYTCraftPanel;

global $_config;
$module = $_GET['module'] ?? "";

$rs = Database::querySingleLine("users", Array("username" => $_SESSION['user']));

//封禁检测
if (date($rs['status'])!=0)
{
	exit("<script>location='?page=banned';</script>");
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<title>控制台 | 盛月映射</title>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
<link rel="icon" href="#">
<link rel="stylesheet" href="../assets/panel/dist/css/adminlte.min.css">
<link rel="stylesheet" href="../assets/panel/plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="https://s4.zstatic.net/npm/@sweetalert2/theme-borderless/borderless.css">
<style>
::-webkit-scrollbar {
    width: 0;
    height: 0;
}

body {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

body::-webkit-scrollbar {
    display: none;
}
.lighter {
	font-weight: lighter;
}
.normal {
	font-weight: normal;
}
.bold {
	font-weight: bold;
}
.bolder {
	font-weight: bolder;
}
</style>
</head>
<body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" >
<div class="wrapper">
	<nav class="main-header navbar navbar-expand navbar-white navbar-light" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link" data-widget="pushmenu" href="#">
					<i class="fas fa-bars"></i>
				</a>
			</li>
			<li class="nav-item d-none d-sm-inline-block">
				<a href="?page=panel&module=home" class="nav-link">主页</a>
			</li>
		</ul>
		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<a class="nav-link" href="javascript:notice();">
					通知&nbsp;&nbsp;
					<i class="nav-icon far fa-bell"></i>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="?page=panel&module=account">
					<?php echo htmlspecialchars($_SESSION[ 'user']); ?>&nbsp;&nbsp;
					<i class="nav-icon far fa-user"></i>
				</a>
			</li>
		</ul>
	</nav>
	<aside class="main-sidebar sidebar-light-primary elevation-2" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
		<a href="?page=panel&module=home" class="brand-link">
			<center>
				<img  height="auto" width="160" src="https://img.sytcraft.com/SYTCraft(2-透).svg" alt="Title">
			</center>
		</a>
		<div class="sidebar lighter">
		<!--
			<div class="user-panel mt-3 pb-3 mb-3 d-flex">
				<div class="image">
					<img src="https://cravatar.cn/avatar/<?php echo md5($_SESSION['mail']); ?>?s=64?d=mp" class="img-circle elevation-2" alt="User Image">
				</div>
				<div class="info">
					<a href="#" class="d-block">
						欢迎，<?php echo htmlspecialchars($_SESSION[ 'user']); ?>
					</a>
				</div>
			</div>
		-->
			<nav class="mt-2">
				<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				    <li />
					<li class="nav-header">盛月映射</li>
					<li class="nav-item">
						<a href="?page=panel&module=home" class="nav-link <?php echo $module == "home" ? "active" : "" ; ?>">
							<i class="nav-icon fas fa-home"></i>
							<p>管理面板</p>
						</a>
					</li>
					<li class="nav-header">账号管理</li>
					<li class="nav-item">
						<a href="?page=panel&module=account" class="nav-link <?php echo $module == "account" ? "active" : "" ; ?>">
							<i class="nav-icon fas fa-user"></i>
							<p>我的账号</p>
						</a>
					</li>
					<li class="nav-header">内网穿透</li>
					<li class="nav-item">
						<a href="?page=panel&module=status" class="nav-link <?php echo $module == "status" ? "active" : "" ; ?>">
							<i class="nav-icon fas fa-cloud"></i>
							<p>节点状态</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="?page=panel&module=domainname" class="nav-link <?php echo $module == "domainname" ? "active" : "" ; ?>">
							<i class="nav-icon fas fa-globe"></i>
							<p>域名管理</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="?page=panel&module=proxies" class="nav-link <?php echo $module == "proxies" ? "active" : "" ; ?>">
							<i class="nav-icon fas fa-list"></i>
							<p>隧道列表</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="?page=panel&module=addproxy" class="nav-link <?php echo $module == "addproxy" ? "active" : "" ; ?>">
							<i class="nav-icon fas fa-plus"></i>
							<p>创建隧道</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="?page=panel&module=configuration" class="nav-link <?php echo $module == "configuration" ? "active" : "" ; ?>">
							<i class="nav-icon fas fa-file-code"></i>
							<p>配置文件</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="?page=panel&module=download" class="nav-link <?php echo $module == "download" ? "active" : "" ; ?>">
							<i class="nav-icon fas fa-download"></i>
							<p>软件下载</p>
						</a>
					</li>
					<li class="nav-header">关于帮助</li>
					<li class="nav-item">
						<a href="?page=panel&module=about" class="nav-link <?php echo $module == "about" ? "active" : "" ; ?>">
							<i class="nav-icon fas fa-trademark"></i>
							<p>关于我们</p>
						</a>
					</li>
					<?php
							if($rs['group'] == "admin") {
								?>
					<li class="nav-header">后台管理</li>
					<li class="nav-item">
						<a href="?page=panel&module=userlist" class="nav-link <?php echo $module == "userlist" ? "active" : "" ; ?>">
							<i class="nav-icon fas fa-users"></i>
							<p>用户管理</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="?page=panel&module=adminredeem" class="nav-link <?php echo $module == "adminredeem" ? "active" : "" ; ?>">
							<i class="nav-icon fas fa-cogs"></i>
							<p>兑换管理</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="?page=panel&module=nodes" class="nav-link <?php echo $module == "nodes" ? "active" : "" ; ?>">
							<i class="nav-icon fas fa-server"></i>
							<p>节点管理</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="?page=panel&module=traffic" class="nav-link <?php echo $module == "traffic" ? "active" : "" ; ?>">
							<i class="nav-icon fas fa-paper-plane"></i>
							<p>流量统计</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="?page=panel&module=settings" class="nav-link <?php echo $module == "settings" ? "active" : "" ; ?>">
							<i class="nav-icon fas fa-wrench"></i>
							<p>平台设置</p>
						</a>
					</li>
					<?php } ?>
				</ul>
			</nav>
		</div>
	</aside>
	<div class="content-wrapper">
		<?php
				$page = new SYTCraftPanel\Pages();
				if(isset($_GET['module']) && preg_match("/^[A-Za-z0-9\_\-]{1,16}$/", $_GET['module'])) {
					$page->loadModule($_GET['module']);
				} else {
					$page->loadModule("home");
				}
				?>
	</div>
	<aside class="control-sidebar control-sidebar-dark"></aside>
	<footer class="main-footer">
		<li style="display: block; text-align: center;">
			<a>Copyright &copy; 2023-<script>document.write(new Date().getFullYear());</script> <a href="http://sytcraft.com/">合肥市包河区盛月堂网络科技工作室</a> All rights reserved.</a>
			</a>&emsp;<a href="https://beian.miit.gov.cn/" target="_blank">无ICP备##########号</a>
		</li>
	</footer>
</div>
<script src = "../src/jquery/jquery-3.7.1.min.js"></script>
<script src="../assets/panel/plugins/jquery/jquery.min.js"></script>
<script src="../assets/adminlte/dist/js/adminlte.min.js"></script>
<script src="../assets/panel/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/panel/dist/js/sweetalert2.all.min.js"></script>
<script>
function notice() {
	Swal.fire({
		title: "通知消息",
		html: "<a>网址更为：https://www.govfrp.com/</a>",
		icon: "info",
		confirmButtonColor: '#7066e0',
		confirmButtonText: '确认'
	});
}

function errormethod() {
	Swal.fire({
		title: "发生错误",
		text: "服务未开放，请稍后重试",
		icon: "error",
		confirmButtonColor: '#7066e0',
		confirmButtonText: '确认'
	});
}

</script>
</body>
</html>