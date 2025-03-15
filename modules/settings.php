<?php
namespace SYTCraftPanel;
use SYTCraftPanel;

$group_title = "后台管理";
$page_title = "平台设置";

$um = new SYTCraftPanel\UserManager();
$nm = new SYTCraftPanel\NodeManager();
$pm = new SYTCraftPanel\ProxyManager();
$rs = Database::querySingleLine("users", Array("username" => $_SESSION['user']));

if(!$rs || $rs['group'] !== "admin") {
	exit("<script>location='?page=panel';</script>");
}

$broadcast = SYTCraftPanel\Settings::get("broadcast");
$helpinfo  = SYTCraftPanel\Settings::get("helpinfo");
$version = SYTCraftPanel\Settings::get("version");
?>
<style type="text/css">
.fix-text p {
	margin-bottom: 4px;
}
.infotable th {
	width: 30%;
}
#broadcast, #helpinfo {
	width: 100%;
	height: 256px;
	max-width: 100%;
	min-width: 100%;
	min-height: 256px;
}
</style>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><?php echo $page_title; ?>&nbsp;&nbsp;<small class="text-muted text-xs">更改网站的相关设置</small></h1></div>
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
                    <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
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
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">编辑公告</h3>
                        </div>
                    </div>
                    <div class="card-body">
						<p style="margin-top: -16px;">在此处填写公告内容，支持 Markdown 语法。</p>
						<textarea class="form-control" id="broadcast"><?php echo $broadcast; ?></textarea>
					</div>
					<div class="card-footer">
						<button type="button" class="btn btn-default" onclick="preview(broadcast.value)">预览更改</button>
						<button type="button" class="btn btn-primary float-right" onclick="saveBroadcast()">保存修改</button>
					</div>
                </div>
				<div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">编辑帮助</h3>
                        </div>
                    </div>
                    <div class="card-body">
						<p style="margin-top: -16px;">在此处填写帮助内容，让用户更好的了解如何使用，支持 Markdown 语法。</p>
						<textarea class="form-control" id="helpinfo"><?php echo $helpinfo; ?></textarea>
                    </div>
					<div class="card-footer">
						<button type="button" class="btn btn-default" onclick="preview(helpinfo.value)">预览更改</button>
						<button type="button" class="btn btn-primary float-right" onclick="saveHelpInfo()">保存修改</button>
					</div>
                </div>
			</div>
			<div class="col-lg-4">
				<div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">站点信息</h3>
                        </div>
                    </div>
                    <div class="card-body fix-text table-responsive p-0">
						<table class="table table-striped table-valign-middle infotable" style="width: 100%;font-size: 15px;margin-top: 0px;margin-bottom: 0px;">
							<tr>
								<th>面板版本</th>
								<td>v<?php echo $version; ?><span id="update-prompt" style="display: none; margin-left: 10px;"></span></td>
							</tr>
							<tr>
								<th>服务软件</th>
								<td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
							</tr>
							<tr>
								<th>运行模式</th>
								<td><?php echo php_sapi_name(); ?></td>
							</tr>
							<tr>
								<th>用户数量</th>
								<td><?php echo $um->getTotalUsers(); ?></td>
							</tr>
							<tr>
								<th>节点数量</th>
								<td><?php echo $nm->getTotalNodes(); ?></td>
							</tr>
							<tr>
								<th>隧道数量</th>
								<td><?php echo $pm->getTotalProxies(); ?></td>
							</tr>
						</table>
						<p style="padding: 12px;">Powered by SYTCraft</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">更新版本</h3>
                        </div>
                    </div>
                    <div class="card-body">
						<p style="margin-top: -16px;">修改面板版本</p>
						<textarea class="form-control" id="version"><?php echo $version; ?></textarea>
                    </div>
					<div class="card-footer">
						<button type="button" class="btn btn-default" onclick="preview(version.value)">预览更改</button>
						<button type="button" class="btn btn-primary float-right" onclick="saveVersion()">保存修改</button>
					</div>
                </div>
            </div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-default" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="msg-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body" id="msg-body"></div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">确定</button></div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
var csrf_token = "<?php echo $_SESSION['token']; ?>";
function alertMessage(title, body) {
	$("#msg-title").html(title);
	$("#msg-body").html(body);
	$("#modal-default").modal('toggle');
}
function saveBroadcast() {
	var htmlobj = $.ajax({
		type: 'POST',
		url: "?action=updatebroadcast&page=panel&module=settings&csrf=" + csrf_token,
		async:true,
		data: {
			data: $("#broadcast").val()
		},
		error: function() {
			alert("错误：" + htmlobj.responseText);
			return;
		},
		success: function() {
			alert(htmlobj.responseText);
			return;
		}
	});
}
function saveHelpInfo() {
	var htmlobj = $.ajax({
		type: 'POST',
		url: "?action=updatehelpinfo&page=panel&module=settings&csrf=" + csrf_token,
		async:true,
		data: {
			data: $("#helpinfo").val()
		},
		error: function() {
			alert("错误：" + htmlobj.responseText);
			return;
		},
		success: function() {
			alert(htmlobj.responseText);
			return;
		}
	});
}
function saveVersion() {
	var htmlobj = $.ajax({
		type: 'POST',
		url: "?action=updateversion&page=panel&module=settings&csrf=" + csrf_token,
		async:true,
		data: {
			data: $("#version").val()
		},
		error: function() {
			alert("错误：" + htmlobj.responseText);
			return;
		},
		success: function() {
			alert(htmlobj.responseText);
			return;
		}
	});
}
function preview(data) {
	var htmlobj = $.ajax({
		type: 'POST',
		url: "?action=preview&page=panel&module=settings&csrf=" + csrf_token,
		async:true,
		data: {
			data: data
		},
		error: function() {
			alertMessage("发生错误", htmlobj.responseText);
			return;
		},
		success: function() {
			alertMessage("预览更改", htmlobj.responseText);
			return;
		}
	});
}
function checkForUpdates() {
    var script = document.createElement('script');
    script.src = 'https://frp.api.govfrp.com/api/old.php?callback=handleVersionCheck';
    document.body.appendChild(script);
}

function handleVersionCheck(data) {
    var currentVersion = "<?php echo $version; ?>";
    if (data.versions && data.versions.includes(currentVersion)) {
        // 如果版本存在于列表中，检查是否有新版本
        var script = document.createElement('script');
        script.src = 'https://frp.api.govfrp.com/api/version.php?callback=handleNewVersion';
        document.body.appendChild(script);
    } else {
        // 如果版本不存在于列表中，提示可能是盗版
        alertMessage("警告", "你可能是盗版软件的受害者！<br>当前版本: " + currentVersion, 'warning');
    }
}

function handleNewVersion(newVersionData) {
    var currentVersion = "<?php echo $version; ?>";
    if (newVersionData.version && newVersionData.version !== currentVersion) {
        // 在右下角显示 Toast 提示
        Swal.fire({
            title: '发现新版本',
            text: '最新版本: ' + newVersionData.version,
            icon: 'info',
            toast: true, // 启用 Toast 模式
            position: 'bottom-end', // 右下角
            showConfirmButton: false, // 不显示确认按钮
            timer: 3000, // 3 秒后自动消失
            timerProgressBar: true // 显示进度条
        });

        // 在面板版本处显示“发现新版本：版本号”
        var updatePrompt = document.getElementById('update-prompt');
        updatePrompt.innerHTML = '发现新版本: ' + newVersionData.version;
        updatePrompt.style.display = 'inline';
        updatePrompt.style.cursor = 'pointer';
        updatePrompt.style.color = 'blue'; // 设置颜色为蓝色
        updatePrompt.style.textDecoration = 'underline'; // 添加下划线

        // 为提示文本添加点击事件
        updatePrompt.addEventListener('click', function() {
            alertMessage("发现新版本", "当前版本: " + currentVersion + "<br>最新版本: " + newVersionData.version);
        });
    }
}

function alertMessage(title, body, icon = 'info') {
    Swal.fire({
        title: title,
        html: body,
        icon: icon, // 支持 'info', 'warning', 'error', 'success' 等
        confirmButtonText: '确定'
    });
}

$(document).ready(function() {
    checkForUpdates();
});
</script>
