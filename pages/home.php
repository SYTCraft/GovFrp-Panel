<?php
namespace SYTCraftPanel;
use SYTCraftPanel;
$rs = Database::querySingleLine("users", Array("username" => $_SESSION['user']));
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<title>盛月映射 - 免费端口映射 | 恶意流量防御 | 智能带宽分配 | 领先公益服务</title>
		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="msvalidate.01" content="EC2BE2FC6CB77116114E35B9E99729AA" />
		<meta name="baidu-site-verification" content="codeva-D2zm6QQDG6" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
		<link rel="icon" href="#">
		<link rel="stylesheet" href="../assets/index/css/open-iconic-bootstrap.min.css">
		<link rel="stylesheet" href="../assets/index/css/animate.css">
		<link rel="stylesheet" href="../assets/index/css/owl.carousel.min.css">
		<link rel="stylesheet" href="../assets/index/css/owl.theme.default.min.css">
		<link rel="stylesheet" href="../assets/index/css/magnific-popup.css">
		<link rel="stylesheet" href="../assets/index/css/aos.css">
		<link rel="stylesheet" href="../assets/index/css/ionicons.min.css">
		<link rel="stylesheet" href="../assets/index/css/bootstrap-datepicker.css">
		<link rel="stylesheet" href="../assets/index/css/jquery.timepicker.css">
		<link rel="stylesheet" href="../assets/index/css/flaticon.css">
		<link rel="stylesheet" href="../assets/index/css/icomoon.css">
		<link rel="stylesheet" href="../assets/index/css/style.css">
	</head>
	<body oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false" id="home">
		<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
			<div class="container">
				<a class="navbar-brand" href="/">
					<img height="auto" width="160" src="https://img.sytcraft.com/SYTCraft(2-透).svg" alt="Title">
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="oi oi-menu"></span>菜单
				</button>
				<div class="collapse navbar-collapse" id="ftco-nav">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item">
							<a class="nav-link" href="#home">首页</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#about">关于</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#contact">联系</a>
						</li>
						<?php if ($rs): ?>
						<li class="nav-item dropdown">
							<a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-trigger="hover">
								<?php echo htmlspecialchars($_SESSION['user']); ?>
							</a>
							<div class="dropdown-menu" aria-labelledby="userDropdown">
								<a class="dropdown-item" target="_blank" href="?page=panel&module=account">我的账户</a>
								<a class="dropdown-item" target="_blank" href="?page=logout&csrf=<?php echo $_SESSION['token']; ?>">退出登录</a>
							</div>
						</li>
						<?php endif; ?>
						<li class="nav-item cta">
							<a class="nav-link" target="_blank" href="?page=panel&module=home">
								<span>
									<?php if (!$rs) { echo "登录"; } elseif ($rs) { echo "控制台"; } ?>
								</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="hero-wrap js-fullheight">
			<div class="overlay"></div>
			<div class="container-fluid px-0">
				<div class="row d-md-flex no-gutters slider-text align-items-center js-fullheight justify-content-end">
					<img class="one-third js-fullheight align-self-end order-md-last img-fluid" src="../assets/index/images/undraw_pair_programming_njlp.svg" alt="img">
					<div class="one-forth d-flex align-items-center ftco-animate js-fullheight">
						<div class="text mt-5">
							<span class="subheading">SYTcraft</span>
							<h1 class="mb-3" style="font-weight: normal; font-size: 32px">互联公益，连接你我</h1>
							<p>公益、免费、高速</p>
							<p>
								<a class="btn btn-primary px-4 py-3" target="_blank" href="?page=login">开始使用</a>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<section class="ftco-section services-section bg-light" id="about">
			<div class="container">
				<div class="row justify-content-center mb-5 pb-3">
					<div class="col-md-7 text-center heading-section ftco-animate">
						<h2 class="mb-4">创新领先，服务卓越</h2>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 d-flex align-self-stretch ftco-animate">
						<div class="media block-6 services d-flex align-items-center">
							<div class="icon d-flex align-items-center justify-content-center order-md-last">
								<span class="flaticon-cloud"></span>
							</div>
							<div class="media-body pl-4 pl-md-0 pr-md-4 text-md-right">
								<h3 class="heading">极速畅享</h3>
								<p class="mb-0">充裕的免费带宽资源，如同无尽的数字洪流，为您的网络之旅提供源源不断的动力与自由。</p>
							</div>
						</div>
					</div>
					<div class="col-md-6 d-flex align-self-stretch ftco-animate">
						<div class="media block-6 services d-flex align-items-center">
							<div class="icon d-flex align-items-center justify-content-center">
								<span class="flaticon-server"></span>
							</div>
							<div class="media-body pl-4">
								<h3 class="heading">多元抉择</h3>
								<p class="mb-0">多样化的区域支持，如同繁星点点的夜空，为您提供广泛的选择，让您的服务无论何处都能落地生根，茁壮成长。</p>
							</div>
						</div>
					</div>
					<div class="col-md-6 d-flex align-self-stretch ftco-animate">
						<div class="media block-6 services d-flex align-items-center">
							<div class="icon d-flex align-items-center justify-content-center order-md-last">
								<span class="flaticon-customer-service"></span>
							</div>
							<div class="media-body pl-4 pl-md-0 pr-md-4 text-md-right">
								<h3 class="heading">专员支持</h3>
								<p>我们提供专业客服和活跃社区支持，确保您在技术旅程中始终有伙伴同行。</p>
							</div>
						</div>
					</div>
					<div class="col-md-6 d-flex align-self-stretch ftco-animate">
						<div class="media block-6 services d-flex align-items-center">
							<div class="icon d-flex align-items-center justify-content-center">
								<span class="flaticon-life-insurance"></span>
							</div>
							<div class="media-body pl-4">
								<h3 class="heading">数据无忧</h3>
								<p>我们重视您的网络安全，采取有效措施确保您的数字安全。</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<footer class="ftco-footer ftco-bg-dark ftco-section">
			<div class="container">
				<div class="row mb-5 pb-5 align-items-center d-flex">
					<div class="col-md-6">
						<div class="heading-section heading-section-white ftco-animate">
							<span class="subheading">Get an GovFrp account</span>
							<h2 style="font-size: 30px;">立即注册内网穿透账户</h2>
						</div>
					</div>
					<div class="col-md-3 ftco-animate">
						<div class="price">
							<h3>¥0.00<span>/年</span>
							</h3>
						</div>
					</div>
					<div class="col-md-3 ftco-animate">
						<p class="mb-0">
							<a href="?page=register" target="_blank" class="btn btn-primary py-3 px-4">前往注册</a>
						</p>
					</div>
				</div>
				<div class="row mb-5">
					<div class="col-md">
						<div class="ftco-footer-widget mb-4 ml-md-5">
							<h2 class="ftco-heading-2">合作伙伴</h2>
							<ul class="list-unstyled">
								<li>
									<a href="https://www.sytcraft.com/" target="_blank" class="py-2 d-block">盛月堂工作室</a>
								</li>
								<li>
									<a href="https://www.aliyun.com/minisite/goods?userCode=8pd8wvqh" target="_blank" class="py-2 d-block">阿里云计算</a>
								</li>
								<li>
									<a href="https://muhan.co/" target="_blank" class="py-2 d-block">木韩网络</a>
								</li>
								<li>
									<a href="https://cloud.comcorn.cn/aff/XIWKRJWQ" target="_blank" class="py-2 d-block">裕米科技</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="col-md">
						<div class="ftco-footer-widget mb-4">
							<h2 class="ftco-heading-2">赞助厂商</h2>
							<ul class="list-unstyled">
								<li>
									<a href="https://muhan.co/" target="_blank" class="py-2 d-block">木韩网络</a>
								</li>
								<li>
									<a href="https://cloud.comcorn.cn/aff/XIWKRJWQ" target="_blank" class="py-2 d-block">裕米科技</a>
								</li>
								<li>
									<a href="https://cloud.locyan.cn/" target="_blank" class="py-2 d-block">乐青云</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="col-md">
						<div class="ftco-footer-widget mb-4">
							<h2 class="ftco-heading-2">友情链接</h2>
							<ul class="list-unstyled">
								<li>
									<a href="/" target="_blank" class="py-2 d-block">暂无内容</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="col-md" id="contact">
						<div class="ftco-footer-widget mb-4">
							<h2 class="ftco-heading-2">关于我们</h2>
							<div class="block-23 mb-3">
								<ul>
									<li>
										<span class="icon icon-map-marker"></span>
										<span class="text">安徽省合肥市包河区望湖街道周谷堆村民恢复楼2号楼</span>
									</li>
									<li>
										<a href="tel:+8613705652534">
											<span class="icon icon-phone"></span>
											<span class="text">+86 137 0565 2534</span>
										</a>
									</li>
									<li>
										<a href="mailto:service@sytcraft.com">
											<span class="icon icon-envelope"></span>
											<span class="text">service@sytcraft.com</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-9">
						<p>Copyright &copy; 2023-<script>
								document.write(new Date().getFullYear());
							</script>
							<a href="http://sytcraft.com/">合肥市包河区盛月堂网络科技工作室</a> All rights reserved.</a>&emsp;<a href="https://www.sytcraft.com/terms.html" target="_blank">服务条款</a>&emsp;<a href="https://www.sytcraft.com/privacy.html" target="_blank">隐私协议</a>
						</p>
						<p>
							<a href="https://beian.miit.gov.cn/" target="_blank">无ICP备##########号</a>&emsp;<a href="https://govfrp.com/assets/images/viewLicPdf.gif" target="_blank">营业执照</a>&emsp;<a href="https://www.gsxt.gov.cn/" target="_blank">统一社会信用代码：92340111MAD2LARQ9N</a>&emsp;<a href="https://xn--v6qw21h0gd43u.xn--fiqs8s/" target="_blank">CFU识别码：NUS4Q4QV</a>
						</p>
						<p>
							<a href="https://www.12377.cn/" target="_blank">中国互联网违法和不良信息举报中心</a>
						</p>
						<p>
							<a>全国统一的反诈专线电话 “ <a href="tel:96110">96110</a> ” ，如遇到此号码来电务必接听</a>
						</p>
					</div>
					<div class="col-md-3 d-none d-md-block">
						<img width="100%" src="https://img.sytcraft.com/SYTCraft(1-%E9%80%8F).svg" alt="SYTCraft" style="filter: brightness(0) invert(1);">
					</div>
				</div>
			</div>
		</footer>
		<div id="ftco-loader" class="show fullscreen">
			<svg class="circular" width="48px" height="48px">
				<circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
				<circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00" />
			</svg>
		</div>
		<script src="../src/jquery/jquery-3.7.1.min.js"></script>
		<script src="../src/jquery/migrate/jquery-migrate-3.5.2.min.js"></script>
		<script src="../src/bootstrap/v4.6.2/dist/js/bootstrap.bundle.min.js"></script>
		<script src="../src/jquery/easing/jquery.easing.min.js"></script>
		<script src="../src/jquery/waypoints/lib/jquery.waypoints.min.js"></script>
		<script src="../src/jquery/jquery.stellar.min.js"></script>
		<script src="../src/jquery/owlcarousel2/dist/owl.carousel.min.js"></script>
		<script src="../src/jquery/magnific-popup-1.2.0/dist/jquery.magnific-popup.min.js"></script>
		<script src="../src/jquery/aos-master/dist/aos.js"></script>
		<script src="../src/jquery/vue-animate-number/dist/jquery.animateNumber.min.js"></script>
		<script src="../src/jquery/scrollax/scrollax.min.js"></script>
		<script src="../src/jquery/main.js"></script>
		<style>
			.hidden {
							display: none;
						}
		</style>
	</body>
</html>