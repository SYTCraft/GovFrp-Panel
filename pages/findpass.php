<?php
namespace SYTCraftPanel;

use SYTCraftPanel;

global $_config;

if(isset($_GET['link']) && $_GET['link'] !== "") {
	$um = new SYTCraftPanel\UserManager();
	if($um->resetPass($_GET['link'])) {
		exit("<script>alert('密码重置成功，请使用新密码登录。');location='?page=login';</script>");
	} else {
		exit("<script>alert('无效的找回密码链接，请重新获取。');location='?page=login';</script>");
	}
}
?>
<!DOCTYPE HTML>
<html lang="zh">
	<head>
		<title>找回密码 | 盛月映射</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=11">
		<link rel="icon" href="#">
		<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" crossorigin="anonymous">
		<script src="../assets/panel/dist/js/sweetalert2.all.min.js"></script>
		<?php if($_config['recaptcha']['enable']) echo '<script src="https://www.recaptcha.net/recaptcha/api.js?render=' . $_config['recaptcha']['sitekey'] . '" defer></script>'; ?>
		<style type="text/css">.full-width{width:100%;}.logo{font-weight:400;}body:before{content:"";display:block;position:fixed;left:0;top:0;width:100%;height:100%;z-index:-10;}body,body:before{background-color:#F5F5F5;background-image:url();background-size:cover;background-position:center;background-attachment:fixed;background-repeat:no-repeat;-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;}.main-box{width:100%;background:rgba(255,255,255,0.9);border:32px solid rgba(0,0,0,0);border-bottom:16px solid rgba(0,0,0,0);border-radius: 32px 96px;box-shadow:0px 0px 100px rgba(0,0,0,0.1);}.copyright{position:fixed;bottom:16px;left:32px;color:#FFF;font-size:16px;text-shadow:0px 0px 8px rgba(0,0,0,0.75);}@media screen and (max-width:992px){.padding-content{display:none;}.main-content{width:100%;max-width:100%;flex:0 0 100%;}.main-box{width:70%;}}@media screen and (max-width:768px){.padding-content{display:none;}.main-content{width:100%;max-width:100%;flex:0 0 100%;}.main-box{width:100%;}}</style>
	</head>
	<body>
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
										echo '<div class="alert alert-' . $alertType . ' alert-dismissable"><a type="button" class="close" data-dismiss="alert" href="?page=findpass" aria-hidden="true">&times;</a>' . $data['message'] . '</div>';
									}
									?>
									<div class="main-box text-left">
										<h2 class="logo"><?php echo $_config['sitename']; ?></h2>
										<p><?php echo $_config['description']; ?></p>
										<hr>
										<form method="POST" action="?action=findpass&page=findpass">
											<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" />
											<p><b>账号或邮箱</b></p>
											<p><input type="text" class="form-control" name="username" id="username" require /></p>
											<p><button type="submit" class="btn btn-primary full-width">找回密码</button></p>
											<p class='text-center'>
											<?php
											if($_config['register']['enable']) {
												echo "<a href='?page=register'>注册账号</a> | ";
											}
											?>
											<a href='?page=login'>返回登录</a></p>
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
	</body>
</html>