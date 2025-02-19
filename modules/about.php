<?php
namespace SYTCraftPanel;
use SYTCraftPanel;

$group_title = "关于帮助";
$page_title = "关于我们";
$version = SYTCraftPanel\Settings::get("version");
?>
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">
					<?php echo $page_title; ?>&nbsp;&nbsp;<small class="text-muted text-xs">盛月映射相关内容</small>
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
<div>
	<div class="container-fluid" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
		<div class="row">
			<div class="col-lg-7" style="display:block;margin:0 auto;">
				<div class="card">
					<div class="card-header border-0">
						<div class="d-flex justify-content-between">
							<h3 class="card-title">关于我们</h3>
						</div>
					</div>
					<div class="card-body box-profile">
						<ul class="list-group list-group-unbordered mb-3">
							<li class="list-group-item">
								<b>平台版本</b>
								<a class="float-right">
									v<?php echo $version; ?>
								</a>
							</li>
							<li class="list-group-item">
								<b>发行时间</b>
								<a class="float-right">
									2025年01月11日
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>