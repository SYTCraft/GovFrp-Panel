<?php
namespace SYTCraftPanel;
use SYTCraftPanel;

$group_title = "内网穿透";
$page_title = "节点状态";

$um = new SYTCraftPanel\NodeManager();
$rs = Database::querySingleLine("users", Array("username" => $_SESSION['user']));

?>
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">
					<?php echo $page_title; ?>&nbsp;&nbsp;<small class="text-muted text-xs">查看节点当前状态</small>
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
			<div class="col-lg-4">
				<div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">注意事项</h3>
                        </div>
                    </div>
                    <div class="card-body fix-text">
						<p>未知状态的节点请询问管理员或前往以下页面查看</p>
						<a href="javascript:luochancystatus();" class="btn btn-outline-primary btn-block lighter"><b>luochancy Status</b></a>
                    </div>
                </div>
                <div class="card" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
                            <h3 class="card-title">广告</h3>
                        </div>
                    </div>
					<div class="card-body fix-text fix-image">
						<p>雨云服务器7折狂欢，高性能稳定护航，24/7专业支持，限时抢购，助力业务极速起飞！</p>
						<a href="https://www.rainyun.com/sytcraft_?s=GovFrp" target="_blank" class="btn btn-outline-primary btn-block lighter">
							<b>立享优惠</b>
						</a>
					</div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body p-0 table-responsive">
						<table class="table table-bordered table-hover dataTable dtr-inline">
							<tr>
								<th class='text-center' nowrap>ID</th>
								<th class='text-center' nowrap>节点</th>
								<th class='text-center' nowrap>状态</th>
							</tr>
							<?php
							$rs = Database::toArray(Database::query("users", "SELECT * FROM `nodes`", true));
							$i = 0;
							foreach($rs as $node) {
								$i++;
								$statuss = Array(200 => "<a style='color:green;'>正常</a>", 403 => "<a style='color:red;'>禁用</a>", 500 => "<a style='color:red;'>离线</a>", 401 => "隐藏");
								$status  = $statuss[Intval($node[10])] ?? "未知";
								echo "<tr>
								<td class='text-center' nowrap>{$node[0]}</td>
								<td class='text-float-right' nowrap>{$node[1]}</td>
								<td class='text-center' nowrap>{$status}</td>
								";
							}
							?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function luochancystatus() {
	Swal.fire({
			title: '提示信息',
			text: "即将跳转至第三方网站，您的安全将会失去保护",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#28a745',
			cancelButtonColor: '#6e7881',
			confirmButtonText: '确认',
			cancelButtonText: '取消'
		})
		.then((result) => {
			if (result.isConfirmed) {
				window.open("https://status.luochancy.govfrp.com/status/sytcraft");
			}
		})
}
</script>