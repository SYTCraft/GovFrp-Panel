<?php
global $_config;
?>
<!DOCTYPE HTML>
<html lang="zh-CN">
	<head>
		<title>登录 - <?php echo $_config['sitename']; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="盛月堂内网穿透官方网站登录入口，公益、免费、高速的国创内网穿透">
		<meta name="keywords" content="SYTCraftFrp,免费公益内网穿透,免费内网穿透工具,我的世界内网穿透,我的世界内网映射,我的世界端口映射,免费内网穿透,公益内网穿透,免费内网穿透,不限流量映射,内网穿透,内网映射,端口映射,反向代理,网站搭建,本地建站,监控管理,公益,免费,高速,稳定,免费frp,frp,nat123,cpolar,极点云,花生壳,贝锐">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=11">
		<meta name="msapplication-TileColor" content="#F1F1F1">
		<link rel="icon" href="../assets/home/src/images/favicon.ico">
		<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" crossorigin="anonymous">
		<link rel="stylesheet" href="../assets/panel/plugins/mdui/mdui.css">
		<script src="../assets/panel/plugins/mdui/mdui.global.js"></script>
		<script src="../assets/panel/dist/js/sweetalert2.all.min.js"></script>
		<script src="../assets/panel/plugins/jquery/jquery.min.js"></script>
		<?php if($_config['recaptcha']['enable']) echo '<script src="https://www.recaptcha.net/recaptcha/api.js?render=' . $_config['recaptcha']['sitekey'] . '" defer></script>'; ?>
		<style type="text/css">.full-width{width:100%;}.logo{font-weight:400;}body:before{content:"";display:block;position:fixed;left:0;top:0;width:100%;height:100%;z-index:-10;}body,body:before{background-color:#F5F5F5;background-image:url();background-size:cover;background-position:center;background-attachment:fixed;background-repeat:no-repeat;-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;}.main-box{width:100%;background:rgba(255,255,255,0.9);border:32px solid rgba(0,0,0,0);border-radius: 32px 96px;border-bottom:16px solid rgba(0,0,0,0);box-shadow:0px 0px 100px rgba(0,0,0,0.1);}.copyright{position:fixed;bottom:16px;left:32px;color:#FFF;font-size:16px;text-shadow:0px 0px 8px rgba(0,0,0,0.75);}@media screen and (max-width:992px){.padding-content{display:none;}.main-content{width:100%;max-width:100%;flex:0 0 100%;}.main-box{width:70%;}}@media screen and (max-width:768px){.padding-content{display:none;}.main-content{width:100%;max-width:100%;flex:0 0 100%;}.main-box{width:100%;}}</style>
	</head>
	<body oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
		<div class="container">
			<div class="row">
				<div class="col-sm-3 padding-content"></div>
				<div class="col-sm-6 main-content">
					<table style="width: 100%;height: 100vh;">
						<tr style="height: 100%;">
							<td style="height: 100%;padding-bottom: 64px;">
								<center>
									<?php
									if(isset($data['status']) && isset($data['message'])) {
										$alertType = $data['status'] ? "success" : "danger";
										echo '<div class="alert alert-' . $alertType . ' alert-dismissable"><a type="button" class="close" data-dismiss="alert" href="?page=login" aria-hidden="true">&times;</a>' . $data['message'] . '</div>';
									}
									?>
									<div class="main-box text-left">
										<h2 class="logo"><?php echo $_config['sitename']; ?></h2>
										<p><?php echo $_config['description']; ?></p>
										<hr />
										<form method="POST" action="?action=login&page=login">
											<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" />
											<p><b>账号</b></p>
											<p><input type="text" class="form-control" name="username" id="username" require /></p>
											<p><b>密码</b></p>
											<p><input type="password" class="form-control" name="password" id="password" require /></p>
											<p><button type="submit" class="btn btn-primary full-width">登录</button></p>
											<p class='text-center'>
											<?php
											if($_config['register']['enable']) {
												echo "<a href='?page=register'>注册账号</a> | ";
											}
											?>
											<a href='?page=findpass'>忘记密码</a></p>
											<p class='text-center'>
											<a href='javascript:authlogin();'>使用 SYTCraft Auth 登录</a></p>
										</form>
									</div>
								</center>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<p class="copyright">&copy; <?php echo date("Y") . " {$_config['sitename']}"; ?></p>
		<?php
		if($_config['recaptcha']['enable']) {
			echo <<<EOF
		<script type="text/javascript">
			window.onload = function() {
				grecaptcha.ready(function() {
					grecaptcha.execute('{$_config['recaptcha']['sitekey']}', {action:'validate_captcha'}).then(function(token) {
						document.getElementById('g-recaptcha-response').value = token;
					});
				});
			}
		</script>
EOF;
		}
		?>
		<script>
        function authlogin(){
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
