<?php
namespace SYTCraftPanel;
use SYTCraftPanel;

$page_title = "管理面板";

include(ROOT . "/core/Parsedown.php");

$markdown = new Parsedown();
$markdown->setSafeMode(true);
$markdown->setBreaksEnabled(true);
$markdown->setUrlsLinked(true);
$rs = Database::querySingleLine("users", Array("username" => $_SESSION['user']));

if(!$rs) {
	exit("<script>location='?page=login';</script>");
}

$um = new SYTCraftPanel\UserManager();
$ls = $um->getLimit($_SESSION['user']);
$inbound = round($ls['inbound'] / 1024 * 8);
$outbound = round($ls['outbound'] / 1024 * 8);
$speed_limit = "{$inbound}Mbps 上行 / {$outbound}Mbps 下行";
$traffic = $rs['traffic'] - round($um->getTodayTraffic($_SESSION['user']) / 1024 / 1024);
if($traffic < 0) {
	$traffic = 0;
}
?>
<style type="text/css">
.fix-text p {
	margin-bottom: 4px;
}
.fix-text pre {
	background: rgba(0,0,0,0.05);
	border-radius: 4px;
}
.fix-image img {
	max-width: 100%;
}
</style>
<div class="content-header" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><?php echo $page_title; ?>&nbsp;&nbsp;<small class="text-muted text-xs">欢迎使用盛月堂内网穿透</small></h1>
            </div>
            <div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item">
						<a href="?page=panel&module=home">
							<i class="nav-icon fas fa-home"></i>
						</a>
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
					<div class="card-body" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
						<div class="callout callout-success">
							<h5>隧道数量</h5>
							<p>
								<?php echo htmlspecialchars($rs['proxies']); ?> 条
							</p>
						</div>
						<div class="callout callout-success">
							<h5>剩余流量</h5>
							<p>
								<?php echo htmlspecialchars(round($traffic / 1024, 2)); ?> GiB
							</p>
						</div>
						<div class="callout callout-success">
							<h5>宽带速率</h5>
							<p>
								<?php echo htmlspecialchars($speed_limit); ?>
							</p>
						</div>
						<div class="callout callout-success">
							<h5>今日已用</h5>
							<p>
								<?php echo htmlspecialchars(round($um->getTodayTraffic($_SESSION['user']) / 1024 / 1024 / 1024, 2)); ?> GiB
							</p>
						</div>
					</div>
				</div>
                <div class="card" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
                            <h3 class="card-title">广告</h3>
                        </div>
                    </div>
					<div class="card-body fix-text fix-image">
						<p>雨云服务器限时优惠7折起，高性能、稳定可靠，24/7支持，立即抢购，助力业务腾飞！</p>
						<a href="https://www.rainyun.com/sytcraft_?s=GovFrp" target="_blank" class="btn btn-outline-primary btn-block lighter">
							<b>立即前往</b>
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
                        <p><a href="https://cloud.comcorn.cn/aff/XIWKRJWQ" target="_blank"><img style="border-radius:10px;" src="../assets/images/hfnpoq.png" /></a></p>
                    </div>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="card" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">平台公告</h3>
                        </div>
                    </div>
                    <div class="card-body fix-text fix-image">
						<?php echo $markdown->text(Settings::get("broadcast", "暂时没有公告信息")); ?>
                    </div>
                </div>
				<div class="card">
                    <div class="card-header border-0" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">使用帮助</h3>
                        </div>
                    </div>
                    <div class="card-body fix-text fix-image">
						<?php echo $markdown->text(Settings::get("helpinfo", "暂时没有帮助信息")); ?>
                    </div>
                </div>
                <div class="card" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
                            <h3 class="card-title">广告</h3>
                        </div>
                    </div>
                    <div class="card-body fix-text fix-image">
						<p>广告位招租，有意请联系负责人或管理员</p>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>
