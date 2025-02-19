<?php
namespace SYTCraftPanel;
use SYTCraftPanel;

$group_title = "内网穿透";
$page_title = "软件下载";

$rs = Database::querySingleLine("users", Array("username" => $_SESSION['user']));

if(!$rs) {
	exit("<script>location='?page=login';</script>");
}
?>
<style type="text/css">
.fix-text p {
	margin-bottom: 4px;
}
.system-img {
	height: 32px;
}
.download tr td {
	vertical-align: middle;
}
</style>
<!--页面通知-->
<!--<script type="text/javascript">
window.onload = function() {
	Swal.fire({
		title: "提示",
		text: "当前处于使用高峰，下载将会进行限速。",
		icon: "warning"
	});
}
</script>-->
<div class="content-header" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><?php echo $page_title; ?>&nbsp;&nbsp;<small class="text-muted text-xs">下载各种版本的 Frp 客户端</small></h1></div>
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
<div class="content" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <div class="card" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
			    	<div class="card-header border-0">
                        <div class="d-flex justify-content-between" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
                            <h3 class="card-title">提示</h3>
                        </div>
                    </div>
                    <div class="card-body fix-text fix-image">
                        <p>没有你想要的资源？<a href="https://github.com/fatedier/frp/releases/tag/v0.28.2" target="_blank">点击前往GitHub寻找！</a></p>
                        <p>请注意，我们支持各位用户搭建下载镜像源，但无论您选择任何镜像源都无法保证资源的更新及安全性能。</p>
						<br />
						<a href="javascript:defaultNode();" class="btn btn-outline-primary btn-block lighter">
							<b>官方源</b>
						</a>
						<a href="javascript:meByphNode();" class="btn btn-outline-warning btn-block lighter">
							<b>三方源</b>
						</a>
                    </div>
			    </div>
                <div class="card" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
                            <h3 class="card-title">广告</h3>
                        </div>
                    </div>
					<div class="card-body fix-text fix-image">
						<p>企业级雨云服务器，7折限时特惠！ 卓越性能，超强稳定，99.9% Uptime 保障，24/7 专业支持，助力企业业务高效运转！</p>
						<a href="https://www.rainyun.com/sytcraft_?s=GovFrp" target="_blank" class="btn btn-outline-primary btn-block lighter">
							<b>立即咨询</b>
						</a>
					</div>
                </div>
            </div>
            <div class="col-lg-8" class="content">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">客户端下载</h3>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
						<div class="callout callout-warning">
							<p style="color:#d39e00;font-size:18px;">
							   <i style="color:#d39e00;font-size:28px;" class="nav-icon fas fas fa-info-circle"></i>
								当前处于使用高峰，下载将会进行限速。
							</p>
						</div>
					</div>
                    <div class="card-body p-0 table-responsive">
                        <table class="download table table-striped table-valign-middle">
							<thead>
								<tr>
									<th style="width: 32px;"></th>
									<th nowrap>系统类型</th>
									<th nowrap>系统架构</th>
									<th nowrap>最新版本</th>
									<th nowrap>资源来源</th>
									<th nowrap>下载文件</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><img src="../assets/download/images/windows.png" class="system-img"></td>
									<td nowrap>Windows</td>
									<td nowrap>i386</td>
									<td nowrap>v1.0.0.1</td>
									<td nowrap><code id="dlnodeWindowsi386">官方源</code></td>
									<td nowrap><a id="dlnodeWindowsi386url" href="https://files.sytcraft.com/govfrp/frpc/frp_0.28.2_windows_386.zip"><button class="btn btn-sm btn-success">点击下载</button></a></td>
								</tr>
								<tr>
									<td><img src="../assets/download/images/windows.png" class="system-img"></td>
									<td nowrap>Windows</td>
									<td nowrap>amd64</td>
									<td nowrap>v1.0.0.1</td>
									<td nowrap><code id="dlnodeWindowsamd64">官方源</code></td>
									<td nowrap><a id="dlnodeWindowsamd64url" href="https://files.sytcraft.com/govfrp/frpc/frp_0.28.2_windows_amd64.zip" target="_blank"><button class="btn btn-sm btn-success">点击下载</button></a></td>
								</tr>
								<tr>
									<td><img src="../assets/download/images/android.png" class="system-img"></td>
									<td nowrap>Android</td>
									<td nowrap>amd64</td>
									<td nowrap>v0.39.1.1</td>
									<td nowrap><code id="dlnodeAndroidamd64">官方源</code></td>
									<td nowrap><a id="dlnodeAndroidamd64url" href="https://files.sytcraft.com/govfrp/frpc/frpc_adnroid-v0.39.1.1.apk"><button class="btn btn-sm btn-success">点击下载</button></a></td>
								</tr>
							</tbody>
						</table>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">;
function defaultNode() {
	var heading = document.getElementById("dlnodeWindowsi386");
	heading.innerHTML = "官方源";
	var heading = document.getElementById("dlnodeWindowsamd64");
	heading.innerHTML = "官方源";
	var heading = document.getElementById("dlnodeAndroidamd64");
	heading.innerHTML = "官方源";
	document.getElementById("dlnodeWindowsi386url")
		.setAttribute("href", "https://files.sytcraft.com/govfrp/frpc/frp_0.28.2_windows_386.zip");
	document.getElementById("dlnodeWindowsamd64url")
		.setAttribute("href", "https://files.sytcraft.com/govfrp/frpc/frp_0.28.2_windows_amd64.zip");
	document.getElementById("dlnodeAndroidamd64url")
		.setAttribute("href", "https://files.sytcraft.com/govfrp/frpc/frpc_adnroid-v0.39.1.1.apk");
}

function meByphNode() {
	var heading = document.getElementById("dlnodeWindowsi386");
	heading.innerHTML = "镜像源";
	var heading = document.getElementById("dlnodeWindowsamd64");
	heading.innerHTML = "镜像源";
	var heading = document.getElementById("dlnodeAndroidamd64");
	heading.innerHTML = "镜像源";
	document.getElementById("dlnodeWindowsi386url")
		.setAttribute("href", "https://objectstorage.us-ashburn-1.oraclecloud.com/p/CBObEpmnx-VJvRvAtxw7VbwK5eWQD4_jEWluUDcUSFiCGkzfJvCJ85JcGQKdBE7r/n/idcx49p7o0ty/b/SYTCraft_Frp_Downloads_Bucket/o/frp_0.28.2_windows_386.zip");
	document.getElementById("dlnodeWindowsamd64url")
		.setAttribute("href", "https://objectstorage.us-ashburn-1.oraclecloud.com/p/CBObEpmnx-VJvRvAtxw7VbwK5eWQD4_jEWluUDcUSFiCGkzfJvCJ85JcGQKdBE7r/n/idcx49p7o0ty/b/SYTCraft_Frp_Downloads_Bucket/o/frp_0.28.2_windows_amd64.zip");
	document.getElementById("dlnodeAndroidamd64url")
		.setAttribute("href", "https://objectstorage.us-ashburn-1.oraclecloud.com/p/CBObEpmnx-VJvRvAtxw7VbwK5eWQD4_jEWluUDcUSFiCGkzfJvCJ85JcGQKdBE7r/n/idcx49p7o0ty/b/SYTCraft_Frp_Downloads_Bucket/o/frpc_adnroid-v0.39.1.1.apk");
}
</script>