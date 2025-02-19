<?php
global $_config;
if(!$_config['register']['enable']) {
	exit("<script>location='?page=login';</script>");
}
?>
<!DOCTYPE HTML>
<html lang="zh_CN">
	<head>
		<title>注册 | 盛月映射</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=11">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
		<link rel="icon" href="#">
		<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" crossorigin="anonymous">
		<script src="../assets/panel/dist/js/sweetalert2.all.min.js"></script>
		<script src="../assets/panel/plugins/jquery/jquery.min.js"></script>
		<?php if($_config['recaptcha']['enable']) echo '<script src="https://www.recaptcha.net/recaptcha/api.js?render=' . $_config['recaptcha']['sitekey'] . '" defer></script>'; ?>
		<style type="text/css">
			.full-width{width:100%;}.logo{font-weight:400;}body:before{content:"";display:block;position:fixed;left:0;top:0;width:100%;height:100%;z-index:-10;}body,body:before{background-color:#F5F5F5;background-image:url();background-size:cover;background-position:center;background-attachment:fixed;background-repeat:no-repeat;-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;}.main-box{width:100%;background:rgba(255,255,255,0.9);border:32px solid rgba(0,0,0,0);border-radius: 32px 96px;border-bottom:16px solid rgba(0,0,0,0);box-shadow:0px 0px 100px rgba(0,0,0,0.1);}.copyright{position:fixed;bottom:16px;left:32px;color:#FFF00;font-size:16px;text-shadow:0px 0px 8px rgba(0,0,0,0);}@media screen and (max-width:992px){.padding-content{display:none;}.main-content{width:100%;max-width:100%;flex:0 0 100%;}.main-box{width:70%;}}@media screen and (max-width:768px){.padding-content{display:none;}.main-content{width:100%;max-width:100%;flex:0 0 100%;}.main-box{width:100%;}}.email-container {display: flex;align-items: center;gap: 10px;margin-bottom: 16px;}.email-container .email-prefix {flex: 8;}.email-container .email-suffix {flex: 4;}
		</style>
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
										echo '<div class="alert alert-' . $alertType . ' alert-dismissable"><a type="button" class="close" data-dismiss="alert" href="?page=register" aria-hidden="true">&times;</a>' . $data['message'] . '</div>';
									}
									?>
									<div class="main-box text-left">
										<h2 class="logo">
											<?php echo $_config['sitename']; ?>
										</h2>
										<p>
											<?php echo $_config['description']; ?>
										</p>
										<hr>
										<form method="POST" action="?action=register&page=register" onsubmit="return validateEmail()">
											<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" />
											<p>
												<b>账号</b>
											</p>
											<p>
												<input type="text" class="form-control" name="username" id="username" required />
											</p>
											<p>
												<b>邮箱</b>
											</p>
											<div class="email-container">
												<div class="email-prefix">
													<input type="text" class="form-control" id="emailPrefix" required />
												</div>
												<div class="email-suffix">
													<select class="form-control" id="emailSuffix" required>
														<option value="@qq.com">@qq.com</option>
														<option value="@163.com">@163.com</option>
														<option value="@126.com">@126.com</option>
														<option value="@yeah.com">@yeah.net</option>
														<option value="@outlook.com">@outlook.com</option>
														<option value="@sytcraft.com">@sytcraft.com</option>
													</select>
												</div>
											</div>
											<input type="hidden" name="email" id="email" />
											<?php
											if($_config['smtp']['enable']) {
											?>
											<p>
												<b>验证代码</b>
												<small>
													<a href="javascript:sendcode()">[点击发送]</a>
												</small>
											</p>
											<p>
												<input type="number" class="form-control" name="verifycode" id="verifycode" required />
											</p>
											<?php
											}
											if($_config['register']['invite']) {
											?>
											<p>
												<b>邀请代码</b>
											</p>
											<p>
												<input type="text" class="form-control" name="invitecode" id="invitecode" required />
											</p>
											<?php
											}
											?>
											<p>
												<b>密码</b>
											</p>
											<p>
												<input type="password" class="form-control" name="password" id="password" required />
											</p>
											<p>
												<input type="checkBox" onclick="if (this.checked) {disable()} else {enable()}"> 我已阅读并同意<a href="https://www.sytcraft.com/terms.html" target="_blank">《服务条款》</a>
												</input>
											</p>
											<p>
												<button type="submit" class="btn btn-primary full-width" id="accept" disabled="true">注册</button>
											</p>
											<p class='text-center'>
												<a href='?page=login'>返回登录</a> | <a href='?page=findpass'>忘记密码</a>
											</p>
										</form>
									</div>
								</center>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<p class="copyright">&copy;
			<?php echo date("Y") . " {$_config['sitename']}"; ?>
		</p>
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

<script type="text/javascript">
    function sendcode() {
        var emailPrefix = document.getElementById('emailPrefix').value;
        var emailSuffix = document.getElementById('emailSuffix').value;
        var email = emailPrefix + emailSuffix;
        Swal.fire({
            title: '少女祈祷中',
            text: '请稍候...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            toast: true,
            position: 'bottom-end',
            timer: 10000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        $.ajax({
            type: 'POST',
            url: "?action=sendmail",
            data: {
                mail: email
            },
            success: function(response) {
                Swal.fire({
                    title: '提示信息',
                    text: response,
                    icon: 'info',
                    showConfirmButton: false,
                    toast: true,
                    position: 'bottom-end',
                    timer: 3000,
                    timerProgressBar: true
                });
            },
            error: function() {
                Swal.fire({
                    title: '错误',
                    text: '获取验证代码时发送未知错误，请稍后重试或联系工作人员',
                    icon: 'error',
                    showConfirmButton: false,
                    toast: true,
                    position: 'bottom-end',
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        });
    }

    function validateEmail() {
        var emailPrefix = document.getElementById('emailPrefix').value;
        var emailSuffix = document.getElementById('emailSuffix').value;
        var email = emailPrefix + emailSuffix;
        document.getElementById('email').value = email;
        return true;
    }

    function disable() {
        document.getElementById("accept").disabled = false;
    }

    function enable() {
        document.getElementById("accept").disabled = true;
    }
</script>
	</body>
</html>