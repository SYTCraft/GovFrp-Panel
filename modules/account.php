<?php
namespace SYTCraftPanel;
use SYTCraftPanel;

$group_title = "账号管理";
$page_title = "我的账号";

$pm = new SYTCraftPanel\ProxyManager();
$rs = Database::querySingleLine("users", Array("username" => $_SESSION['user']));

if(!$rs) {
	exit("<script>location='?page=login';</script>");
}

//账号数据
$um          = new SYTCraftPanel\UserManager();
$ls          = $um->getLimit($_SESSION['user']);
$inbound     = round($ls['inbound'] / 1024 * 8);
$outbound    = round($ls['outbound'] / 1024 * 8);
$speed_limit = "{$inbound}Mbps 上行 / {$outbound}Mbps 下行";
$signinfo    = Database::querySingleLine("sign", Array("username" => $_SESSION['user']));
$token       = Database::querySingleLine("tokens", Array("username" => $_SESSION['user']))["token"] ?? "Unknown";

//签到数据
include(ROOT . "/core/Parsedown.php");
global $_config;
$markdown = new Parsedown();
$markdown->setSafeMode(true);
$markdown->setBreaksEnabled(true);
$markdown->setUrlsLinked(true);
$pm = new SYTCraftPanel\ProxyManager();
$nm = new SYTCraftPanel\NodeManager();
if(!$rs) {
	exit("<script>location='?page=login';</script>");
}
$user_traffic = $rs['traffic'] - ($um->getTodayTraffic($_SESSION['user']) / 1024 / 1024);
if(isset($_GET['sign'])) {
	ob_clean();
	SYTCraftPanel\Utils::checkCsrf();
	if(!$_config['sign']['enable']) {
		exit("签到功能暂不可用");
	}
	// 欧皇判定范围
	$good_rand = round($_config['sign']['max'] * 0.7);
	// 非酋判定范围
	$bad_rand = round($_config['sign']['max'] * 0.2);
	// 随机流量
	$rand = mt_rand($_config['sign']['min'], $_config['sign']['max']);
	
	$rs = Database::querySingleLine("sign", Array("username" => $_SESSION['user']));
	if($rs) {
		if(isset($rs['signdate'])) {
			if(Intval(date("Ymd")) >= Intval(date("Ymd", $rs['signdate'])) + 1) {
				$totaltraffic = $rs['totaltraffic'] == "" ? "0" : $rs['totaltraffic'];
				$totalsign    = $rs['totalsign']    == "" ? "0" : $rs['totalsign'];
				Database::update("sign", Array("signdate" => time(), "totaltraffic" => $totaltraffic + $rand, "totalsign" => $totalsign + 1), Array("username" => $_SESSION['user']));
				Database::update("users", Array("traffic" => $user_traffic + ($rand * 1024)), Array("username" => $_SESSION['user']));
				Database::update("proxies", Array("status" => "0"), Array("username" => $_SESSION['user'], "status" => "2"));
				$randtext = "今天运气不错，";
				if($rand >= $good_rand) {
					$randtext = "今天欧皇手气，共";
				} elseif($rand <= $bad_rand) {
					$randtext = "今天是非酋，只";
				}
				exit("签到成功，{$randtext}获得了 {$rand}GB 流量，目前您的剩余流量为 " . round(($user_traffic + ($rand * 1024)) / 1024, 2) . "GB。");
			} else {
				exit("您今天已经签到过了，请明天再来");
			}
		} else {
			Database::insert("sign", Array("id" => null, "username" => $_SESSION['user'], "signdate" => time(), "totaltraffic" => $rand, "totalsign" => 1));
			Database::update("users", Array("traffic" => $user_traffic + ($rand * 1024)), Array("username" => $_SESSION['user']));
			Database::update("proxies", Array("status" => "0"), Array("username" => $_SESSION['user'], "status" => "2"));
			exit("签到成功，这是你第一次签到，获得了 {$rand}GB 流量。");
		}
	} else {
		Database::insert("sign", Array("id" => null, "username" => $_SESSION['user'], "signdate" => time(), "totaltraffic" => $rand, "totalsign" => 1));
		Database::update("users", Array("traffic" => $user_traffic + ($rand * 1024)), Array("username" => $_SESSION['user']));
		Database::update("proxies", Array("status" => "0"), Array("username" => $_SESSION['user'], "status" => "2"));
		exit("签到成功，这是你第一次签到，获得了 {$rand}GB 流量。");
	}
}

$signed = false;
$ss = Database::querySingleLine("sign", Array("username" => $_SESSION['user']));
if($ss) {
	if(isset($ss['signdate']) && Intval(date("Ymd")) < Intval(date("Ymd", $ss['signdate'])) + 1) {
		$signed = true;
	}
}

//IP信息
function getUserIP() {
    return $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
}

function getIPInfo($ip) {
    $apiUrl = "http://inip.in/search_ip?ip={$ip}";
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = "cURL Error: " . curl_error($ch);
        curl_close($ch); // 关闭 cURL 句柄
        return $error;
    }

    curl_close($ch); // 关闭 cURL 句柄

    $ipInfo = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) return "JSON Error: " . json_last_error_msg();
    if ($ipInfo['code'] !== 0) return "API Error: " . $ipInfo['msg'];

    $data = $ipInfo['data'];
    $country = $data['country_cn'] ?? 'Unknown';
    $region = $data['region_cn'] ?? 'Unknown';
    $city = $data['city_cn'] ?? 'Unknown';
    $isp = $data['organization'] ?? 'Unknown';
    $ispMap = [
        'Akamai Connected Cloud' => '阿迈卡',
        'BT Group' => '英国电信',
        'CERNET' => '教育网',
        'Charter Communications' => '特许通讯',
        'China Mobile Communications Group Co., Ltd.' => '中国移动',
        'China Telecom' => '中国电信',
        'China Unicom' => '中国联通',
        'CNC' => '长城宽带',
        'CMCC' => '中国移动',
        'CTC' => '中国电信',
        'CUCC' => '中国联通',
        'Data Communication Business Group' => '中华电信',
        'Deutsche Telekom' => '德国电信',
        'Dr.Peng' => '鹏博士',
        'HKT Limited' => '香港电讯',
        'MTN Group' => 'MTN集团',
        'NTT Communications' => '日本电报电话',
        'Singtel' => '新电信',
        'SoftBank Corp.' => '日本软银',
        'TWC-20001-PACWEST' => '特许通讯',
        'Vodafone Group' => '沃达丰'
    ];
    $ispChinese = $ispMap[$isp] ?? '未知'; // 如果ispMap没有成功转换成中文，则显示“未知”

    return [
        'ip' => $data['ip'],
        'location' => "$country$region$city",
        'isp' => $ispChinese
    ];
}

$userIP = getUserIP();
$ipInfo = getIPInfo($userIP);
?>
<style type="text/css">
.lighter {
	font-weight: lighter;
}
.normal {
	font-weight: normal;
}
.bold {
	font-weight: bold;
}
.bolder {
	font-weight: bolder;
}
.sub-heading {
	width: calc(100% - 16px);
	height: 0!important;
	border-top: 1px solid #e9f1f1!important;
	text-align: center!important;
	margin-top: 32px!important;
	margin-bottom: 40px!important;
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
</style>
<div class="content-header" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">
					<?php echo $page_title; ?>&nbsp;&nbsp;<small class="text-muted text-xs">查看您的个人信息</small>
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
	<div class="container-fluid" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
		<div class="row">
			<div class="col-lg-4">
				<div class="card">
					<div class="card-header border-0">
						<div class="d-flex justify-content-between">
							<h3 class="card-title">账号信息</h3>
						</div>
					</div>
					<div class="card-body box-profile">
						<div class="text-center">
							<img class="profile-user-img img-fluid img-circle" src="https://cravatar.cn/avatar/<?php echo md5($_SESSION['mail']); ?>?s=256?d=mp" alt="User profile picture">
						</div>
						<h3 class="profile-username text-center">
							<?php echo htmlspecialchars($_SESSION[ 'user']); ?>
						</h3>
						<p class="text-muted text-center">
							<?php 
						if ($rs['group'] == "admin") {
							echo "管理员";
						} elseif ($rs['group'] == "default") {
							echo "普通会员";
						} elseif ($rs['group'] == "user") {
							echo "普通会员";
						} elseif ($rs['group'] == "vip1") {
							echo "高级会员";
						} elseif ($rs['group'] == "vip2") {
							echo "超级会员";
						} elseif ($rs['group'] == "vip3") {
							echo "合作伙伴";
						}
						?>
						</p>
						<ul class="list-group list-group-unbordered mb-3 lighter">
							<!--
							<li class="list-group-item">
								<b>账号标识</b>
								<a class="float-right"><?php echo $rs['id']; ?></a>
							</li>
							-->
							<li class="list-group-item">
								<b>绑定邮箱</b>
								<a class="float-right">
									<?php echo substr($_SESSION['mail'], 0,3) . '****' . substr($_SESSION['mail'], -10,10); ?>
								</a>
							</li>
							<li class="list-group-item">
								<b>绑定手机</b>
								<a class="float-right">
									<?php echo $rs['phone']==0?'未绑定':substr($rs['phone'], 0,3) . '****' . substr($rs['phone'], -4,4); ?>
								</a>
							</li>
							<li class="list-group-item">
								<b>注册时间</b>
								<a class="float-right">
									<?php echo date("Y年m月d日", $rs['regtime']); ?>
								</a>
							</li>
							<li class="list-group-item">
								<b>访问密钥</b>
								<a onclick="javascript:displaytoken();" class="float-right">
									显示密钥
								</a>
							</li>
							<li class="list-group-item">
								<b>实名认证</b>
								<a class="float-right">
									<?php echo htmlspecialchars($rs['verified'])==0?'未实名':'已实名'; ?>
								</a>
							</li>
							<li class="list-group-item">
								<b>登录地址</b>
								<a class="float-right">
									<?php echo $ipInfo['ip']; ?>
									<?php echo $ipInfo['location']; ?>
									<?php echo $ipInfo['isp']; ?>
								</a>
							</li>
						</ul>
						<a href="?page=logout&csrf=<?php echo $_SESSION['token']; ?>" class="btn btn-outline-primary btn-block lighter">
							<b>退出登录</b>
						</a>
						<a href="javascript:cancelaccount();" class="btn btn-outline-danger btn-block lighter">
							<b>注销账户</b>
						</a>
					</div>
				</div>
			</div>
			<div class="col-lg-8" class="content">
				<div class="card">
					<div class="card-header border-0">
						<div class="d-flex justify-content-between">
							<h3 class="card-title">每日签到</h3>
						</div>
					</div>
					<div class="card-body table-responsive">
						<div class="row">
							<div class="col-sm-9">
								<?php if($signed) { ?>
								<h3 class="text-success">已签到</h3>
								<p>继续保持签到就可以获得更多的流量</p>
								<?php } else { ?>
								<h3 class="text-warning">待签到</h3>
								<p>立即签到就可以获得免费的流量，可用于内网穿透使用</p>
								<?php } ?>
							</div>
							<div class="col-sm-3 text-center" style="padding-top: 16px;">
								<?php if($signed) { ?>
								<?php } else { ?>
								<button class="btn btn-outline-primary" onclick="sign()" <?php echo $signed ? "disabled" : "" ; ?>>立即签到</button>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header border-0">
						<div class="d-flex justify-content-between">
							<h3 class="card-title">兑换奖励</h3>
						</div>
					</div>
					<div class="card-body">
						<div class="row mb-3">
							<div class="col-sm-2">
								<h6 class="mb-0">兑换代码</h6>
							</div>
							<div class="col-sm-9 text-secondary">
								<input type="text" class="form-control" id="redeem-code" name="redeem-code">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2"></div>
							<div class="col-sm-9 text-secondary" id="EditPwdButton">
								<button type="button" class="btn btn-primary float-left" onclick="redeemCode()">立即兑换</button>
							</div>
						</div>
					</div>
				</div>
				<div class="card" class="content">
					<div class="card-header border-0">
						<div class="d-flex justify-content-between">
							<h3 class="card-title">修改密码</h3>
						</div>
					</div>
					<form method="post" action="?page=panel&module=profile&action=updatepass&csrf=<?php echo $_SESSION['token']; ?>" class="content">
						<div class="card-body">
							<div class="row mb-3">
								<div class="col-sm-2">
									<h6 class="mb-0">旧的密码</h6>
								</div>
								<div class="col-sm-9 text-secondary">
									<input type="password" class="form-control" name="oldpass">
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-sm-2">
									<h6 class="mb-0">新的密码</h6>
								</div>
								<div class="col-sm-9 text-secondary">
									<input type="password" class="form-control" name="newpass">
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-sm-2">
									<h6 class="mb-0">确认密码</h6>
								</div>
								<div class="col-sm-9 text-secondary">
									<input type="password" class="form-control" name="newpass1">
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2"></div>
								<div class="col-sm-9 text-secondary" id="EditPwdButton">
									<button type="submit" class="btn btn-primary float-left">确认修改</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
var csrf_token = "<?php echo $_SESSION['token']; ?>";
function alertMessage(title, body) {
	$("#msg-title").html(title);
	$("#msg-body").html(body);
	$("#modal-default").modal('toggle');
}

function sign() {
	var htmlobj = $.ajax({
		type: 'GET',
		url: "?page=panel&module=sign&sign&csrf=" + csrf_token,
		async: true,
		error: function() {
			return;
		},
		success: function() {
			Swal.fire({
					title: "提示信息",
					html: htmlobj.responseText,
					icon: "success",
					confirmButtonColor: '#7066e0',
					confirmButtonText: '确认'
				})
				.then((result) => {
					if (result.isConfirmed) {
						location.reload();
					}
				});
			return;
		}
	});
}

function redeemCode() {
    var code = document.getElementById('redeem-code').value;
    var csrf_token = "<?php echo $_SESSION['token']; ?>";

    $.ajax({
        type: 'POST',
        url: 'redeem.php',
        data: { code: code, csrf: csrf_token },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    title: "兑换结果",
                    html: response.message,
                    icon: "success",
                    confirmButtonColor: '#7066e0',
                    confirmButtonText: '确认'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
                Swal.fire({
                    title: "发生错误",
                    text: response.message,
                    icon: "error",
                    confirmButtonColor: '#7066e0',
                    confirmButtonText: '确认'
                });
            }
        },
        error: function() {
            Swal.fire({
                title: "发生错误",
                text: "兑换过程中发生错误，请稍后重试。",
                icon: "error",
                confirmButtonColor: '#7066e0',
                confirmButtonText: '确认'
            });
        }
    });
}

function displaytoken() {
	Swal.fire({
		title: "通知消息",
		html: "<p>妥善保管密钥,勿要共享泄露</p><p><?php echo htmlspecialchars($token); ?></p>",
		icon: "info",
		confirmButtonColor: '#7066e0',
		confirmButtonText: '确认'
	});
}

function cancelaccount() {
	Swal.fire({
			title: "确认操作",
			html: "<p>您确定要注销当前的账号？</p><p>提交注销申请即代表您放弃当前账号拥有的所有权益，请慎重考虑是否需要注销当前账号！</p>",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: '#dd3333',
			cancelButtonColor: '#6e7881',
			confirmButtonText: '确认',
			cancelButtonText: '取消'
		})
		.then((result) => {
			if (result.isConfirmed) {
				Swal.fire({
					title: "发生错误",
					text: "当前服务不可用，请联系客服处理",
					icon: "error",
					confirmButtonColor: '#7066e0',
					confirmButtonText: '确认'
				});
			}
		})
}

</script>