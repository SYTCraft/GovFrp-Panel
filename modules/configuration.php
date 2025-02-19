<?php
namespace SYTCraftPanel;
use SYTCraftPanel;

$group_title = "内网穿透";
$page_title = "配置文件";

$pm = new SYTCraftPanel\ProxyManager();
$rs = Database::querySingleLine("users", Array("username" => $_SESSION['user']));

if(!$rs) {
	exit("<script>location='?page=login';</script>");
}

$sel_server = isset($_GET['server']) && preg_match("/^[0-9]+$/", $_GET['server']) ? Intval($_GET['server']) : 0;
if($sel_server <= 0) {
	$sel_server = 1;
}
$ss = Database::toArray(Database::search("nodes", Array("group" => "{$rs['group']};", "status" => "200")));
?>
<style type="text/css">
.sub-heading {
	width: calc(100% - 16px);
	height: 0 !important;
	border-top: 1px solid #e9f1f1 !important;
	text-align: center !important;
	margin-top: 32px !important;
	margin-bottom: 40px !important;
	margin-left: 7px;
}

.sub-heading span {
	display: inline-block;
	position: relative;
	padding: 0 17px;
	top: -11px;
	font-size: 16px;
	color: #058;
	background-color: #fff;
}

.code-wrapper {
	position: relative;
}

.code-block {
	position: relative;
}

.copy {
	font-size: 14px;
	transition: color 0.1s;
	color: hsla(0, 0%, 50%, 1.2);
	border: none;
	border-radius: 4px;
	cursor: pointer;
	z-index: 1;
}
</style>
<link href="assets/configuration/prettify.css" rel="stylesheet" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">
					<?php echo $page_title; ?>&nbsp;&nbsp;<small class="text-muted text-xs">获取用于客户端的配置文件</small>
				</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item">
						<a href="?page=panel&module=home">
							<i class="nav-icon fas fa-home"></i>
						</a>
					</li>
					<li class="breadcrumb-item active">
						<?php echo $group_title; ?>
					</li>
					<li class="breadcrumb-item active">
						<?php echo $page_title; ?>
					</li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header border-0" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
						<div class="d-flex justify-content-between">
							<h3 class="card-title">配置文件获取</h3>
						</div>
					</div>
					<div class="card-body">
						<p oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
							<b>选择节点</b>
						</p>
						<p oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
							<select class="form-control" id="server" <?php echo count($ss)==0 ? "disabled" : "" ; ?>>
							<?php
							echo "<option value=''>选择节点</option>";
							foreach($ss as $si) {
								$selected = $sel_server == $si[0] ? "selected" : "";
								echo "<option value='{$si[0]}' {$selected}>{$si[1]} ({$si[3]})</option>";
							}
							if(count($ss) == 0) {
								echo "<option>全部节点已离线，请联系管理员处理。</option>";
							}
							?>
							</select>
						</p>
						<p oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
							<b>配置文件内容</b>
						</p>
						<pre class="prettyprint linenums"><?php echo count($ss) !== 0 ? $pm->getUserProxiesConfig($_SESSION['user'], $sel_server) : "当前全部节点不可用，请联系管理员。"; ?></pre>
					</div>
				</div>
			</div>
			<div class="col-lg-4" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
				<div class="card">
					<div class="card-header border-0">
						<div class="d-flex justify-content-between">
							<h3 class="card-title">配置文件说明</h3>
						</div>
					</div>
					<div class="card-body">
						<p>每次创建隧道或删除了隧道之后配置文件都会发生变化，请在变更后及时更新您的配置文件。</p>
						<p class='text-danger'>请勿泄露配置文件中 user 字段的内容(访问密钥)，否则他人可以登录您的账号。</p>
						<p class='text-danger'>如果泄露访问密钥，可修改密码以重置。</p>
						<div class="sub-heading">
							<span>配置安装方法</span>
						</div>
						<p>
							<ol>
								<li>将左侧的内容复制。</li>
								<li>在客户端的 <code>configs</code> 目录创建一个文本文档，重命名为 <code>frpc.ini</code> (如已有此文件则无需此操作)。</li>
								<li>使用 <code>Sublime</code> 等专业的文本编辑器打开它。</li>
								<li>将复制的内容粘贴到里面并保存。</li>
							</ol>
						</p>
						<div class="sub-heading">
							<span>Windows 客户端启动方法</span>
						</div>
						<p>
							<ol>
								<li>按照上面的方法储存好你的配置文件。</li>
								<li>双击运行客户端目录里的 <code>SYTCraftFrp Launcher.exe</code> 程序。</li>
								<li>保持运行弹出的命令提示符窗口请勿关闭，否则会中断映射。</li>
							</ol>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="assets/configuration/prettify.js"></script>
<script type="text/javascript">
prettyPrint();
window.onload = function() {
	$('#server')
		.change(function() {
			location = "/?page=panel&module=configuration&server=" + $(this)
				.children('option:selected')
				.val();
		});
}
var codeBlocks = document.querySelectorAll('pre');
codeBlocks.forEach(function(codeBlock) {
	var copyButton = document.createElement('text');
	copyButton.className = 'copy';
	copyButton.textContent = '复制代码';


	// 创建包裹代码块和按钮的容器元素
	var container = document.createElement('div');
	container.className = 'code-container';

	// 将按钮添加到容器元素内
	container.appendChild(copyButton);

	// 将容器元素插入到代码块之前
	codeBlock.parentNode.insertBefore(container, codeBlock);

	// 设置容器元素样式，使其定位为相对定位（position: relative）
	container.style.position = 'relative';

	// 设置复制按钮样式，使其绝对定位于容器元素的右上角
	copyButton.style.position = 'absolute';
	copyButton.style.top = '8px';
	copyButton.style.right = '10px';

	copyButton.addEventListener('click', function() {
		// 获取代码块的文本内容
		var code = codeBlock.textContent;

		// 创建一个临时的textarea元素，并将代码块的内容设置为其值
		var textarea = document.createElement('textarea');
		textarea.value = code;

		// 将textarea元素追加到body中
		document.body.appendChild(textarea);

		// 选中textarea中的文本
		textarea.select();

		// 执行复制操作
		document.execCommand('copy');

		// 移除临时的textarea元素
		document.body.removeChild(textarea);

		// 修改复制按钮文本为“已复制”
		this.textContent = '复制成功';
	});
});
</script>