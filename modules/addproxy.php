<?php
namespace SYTCraftPanel;
use SYTCraftPanel;

$group_title = "内网穿透";
$page_title = "创建隧道";

global $_config;

$rs = Database::querySingleLine("users", Array("username" => $_SESSION['user']));

if(!$rs) {
	exit("<script>location='?page=login';</script>");
}

$nm = new SYTCraftPanel\NodeManager();
$pm = new SYTCraftPanel\ProxyManager();
$un = $nm->getUserNode($rs['group']);

$proxies_max = $rs['proxies'] == "-1" ? "无限制" : $rs['proxies'];

// 分类节点
$nodes = [
    '中国大陆' => [],
    '港澳台区' => [],
    '非中国区' => []
];

foreach($un as $server) {
    $nodeName = $server[1]; // 假设节点名称在数组的第二个位置
    if (strpos($nodeName, '香港') !== false || strpos($nodeName, '澳门') !== false || strpos($nodeName, '台湾') !== false) {
        $nodes['港澳台区'][] = $server;
    } elseif (strpos($nodeName, '中国') !== false || strpos($nodeName, '北京') !== false || strpos($nodeName, '上海') !== false || strpos($nodeName, '广州') !== false) {
        $nodes['中国大陆'][] = $server;
    } else {
        $nodes['非中国区'][] = $server;
    }
}

// 对每个区域的节点按字母顺序排序
foreach ($nodes as &$regionNodes) {
    usort($regionNodes, function($a, $b) {
        return strcmp($a[1], $b[1]); // 按节点名称（$a[1]）排序
    });
}

if(isset($_GET['portrules'])) {
    ob_clean();
    SYTCraftPanel\Utils::checkCsrf();
    echo "<p>映射的端口最小为 <code>{$_config['proxies']['min']}</code>，最大为 <code>{$_config['proxies']['max']}</code>。</p>";
    if(!empty($_config['proxies']['protect'])) {
        echo "<p>以下为系统保留的端口范围，不可使用：</p>";
        foreach($_config['proxies']['protect'] as $key => $value) {
            echo "<code>{$key}</code> - <code>{$value}</code><br/>";
        }
        echo "<span>您最多可以使用 {$proxies_max} 个端口</span>";
    }
    exit;
}
if(isset($_GET['randomport'])) {
    ob_clean();
    SYTCraftPanel\Utils::checkCsrf();
    echo $pm->getRandomPort();
    exit;
}
?>
<style type="text/css">
.fix-text p {
	margin-bottom: 4px;
}
.pdesc {
	margin-left: 8px;
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
                <h1 class="m-0 text-dark"><?php echo $page_title; ?>&nbsp;&nbsp;<small class="text-muted text-xs">创建一个新的内网穿透隧道</small></h1></div>
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
            <div class="col-lg-12">
                <div class="callout callout-warning">
                  <p>创建隧道前请先查看节点状态确认节点可用</p>
                </div>
			</div>
            <div class="col-lg-12">
                <div class="callout callout-danger">
                  <p>隧道未经许可禁止用于内容分发网络、虚拟专线网络或其他须相关行政许可业务</p>
                </div>
			</div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
							<div class="sub-heading">
								<span>基础设置</span>
							</div>
							<div class="col-sm-12">
								<p><b>选择节点</b><small class="pdesc">选择您要使用节点</small></p>
								<p><select class="form-control" id="node">
									<?php
									foreach($nodes as $region => $servers) {
									    if (!empty($servers)) {
									        echo "<optgroup label='{$region}'>";
									        foreach($servers as $server) {
									            echo "<option value='{$server[0]}'>{$server[1]} - {$server[2]} ({$server[3]})</option>";
									        }
									        echo "</optgroup>";
									    }
									}
									?>
								</select></p>
							</div>
							<div class="col-sm-6">
								<p><b>隧道名称</b><small class="pdesc">3-15 个字符，中英文和数字以及下划线组成</small></p>
								<p><input type="text" class="form-control" variant="outlined" type="text" id="proxy_name" placeholder="隧道名称" /></p>
							</div>
							<div class="col-sm-6">
								<p><b>隧道类型</b><small class="pdesc">每种隧道类型的区别请查看文档</small></p>
								<p><select class="form-control" variant="outlined" placeholder="选择类型" id="proxy_type">
									<option value="tcp">TCP 隧道</option>
									<option value="udp">UDP 隧道</option>
									<option value="http">HTTP 隧道</option>
									<option value="https">HTTPS 隧道</option>
									<!--<option value="stcp">STCP 隧道</option>-->
									<!--<option value="xtcp">XTCP 隧道</option>-->
								</select></p>
							</div>
							<div class="col-sm-6">
								<p><b>本地地址</b> <small class="pdesc">要转发到的本机 IP，默认 127.0.0.1 即可</small></p>
								<p><input type="text" class="form-control" variant="outlined" type="text" id="local_ip" value ="127.0.0.1" placeholder="本地地址" /></p>
							</div>
							<div class="col-sm-6">
								<p><b>本地端口</b> <small class="pdesc">本地服务的运行端口，例如网站是 80 端口</small></p>
								<p><input type="text" class="form-control" variant="outlined" type="text" id="local_port" placeholder="本地端口" /></p>
							</div>
							<div class="col-sm-6">
								<p><b>远程端口</b> <small class="pdesc">给访客连接时使用的外部端口 (<a href="javascript:loadPortRules();">查看规则</a>)</small></p>
								<p><input type="text" class="form-control"  variant="outlined" type="text" id="remote_port" placeholder="远程端口" /></p>
							</div>
							<div class="col-sm-6">
								<p><b>绑定域名</b> <small class="pdesc">仅限 HTTP 和 HTTPS 类型的隧道</small></p>
								<p><input type="text" class="form-control" variant="outlined" type="text" id="domain" placeholder="绑定域名" disabled /></p>
							</div>
						</div>
                    </div>
					<div class="card-footer">
						<button type="button" class="btn btn-default" onclick="randomPort()">随机端口</button>
						<button type="button" class="btn btn-primary float-right" onclick="addProxy()">完成创建</button>
					</div>
                </div>
			</div>
			<div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
							<div class="sub-heading">
								<span>高级设置</span>
							</div>
							<div class="col-sm-12">
								<p><b>URL 路由</b> <small class="pdesc">指定要转发的 URL 路由，仅限 HTTP 隧道</small></p>
								<p><input type="text" class="form-control" variant="outlined" type="text" id="locations" placeholder="/" disabled /></p>
							</div>
							<div class="col-sm-12">
								<p><b>Host 重写</b> <small class="pdesc">重写请求头部的 Host 字段，仅限 HTTP 隧道</small></p>
								<p><input type="text" class="form-control" variant="outlined" type="text" id="host_header_rewrite" placeholder="frp.sytcraft.com" disabled /></p>
							</div>
							<div class="col-sm-12">
								<p><b>请求来源</b> <small class="pdesc">给后端区分请求来源用，仅限 HTTP 隧道</small></p>
								<p><input type="text" class="form-control"  variant="outlined" type="text" id="header_X-From-Where" placeholder="frp_node_1" disabled/></p>
							</div>
							<div class="col-sm-6">
								<p><b>加密传输</b> <small class="pdesc">加密保护传输数据</small></p>
								<p><select class="form-control" variant="outlined" value="true" id="use_encryption" disabled>
									<option value="true">启用</option>
									<option value="false">关闭</option>
								</select></p>
							</div>
							<div class="col-sm-6">
								<p><b>压缩数据</b> <small class="pdesc">节省流量使用</small></p>
								<p><select class="form-control" variant="outlined" value="true" id="use_compression" disabled>
									<option value="true">启用</option>
									<option value="false">关闭</option>
								</select></p>
							</div>
						</div>
                    </div>
					<div class="card-footer">
                        <div class="col-sm-12">
                            <button type="button" id="enable-advanced-btn" class="btn btn-outline-warning btn-block lighter" onclick="confirmAdvancedSettings()">
                                <i class="fas fa-lock"></i> 启用高级设置
                            </button>
                        </div>
					</div>
                </div>
            </div>
            <div class="col-lg-12">
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
<script>
document.getElementById('proxy_type').addEventListener('change', function() {
    var selectedValue = this.value;
    if (selectedValue === 'http' || selectedValue === 'https') {
        clearPort();
    } else if (selectedValue === 'tcp' || selectedValue === 'udp') {
        randomPort();
    }
});

function clearPort() {
    console.log('clearPort 函数被调用');
}

function randomPort() {
    console.log('randomPort 函数被调用');
}

function confirmAdvancedSettings() {
    Swal.fire({
        title: '确认操作',
        text: '我已确认安全风险，继续更改高级设置',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#7066e0',
        cancelButtonColor: '#d33',
        confirmButtonText: '确认',
        cancelButtonText: '取消'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('locations').disabled = false;
            document.getElementById('host_header_rewrite').disabled = false;
            document.getElementById('use_encryption').disabled = false;
            document.getElementById('use_compression').disabled = false;
            document.getElementById('header_X-From-Where').disabled = false;
            document.getElementById('enable-advanced-btn').disabled = true;
            Swal.fire(
                '提示信息',
                '高级设置已启用',
                'success'
            );
        }
    });
}
</script>
<script type="text/javascript">
window.onload = function() {
	$("#proxy_name").val(Math.random().toString(36).slice(-8));
	var htmlobj = $.ajax({
		type: 'GET',
		url: "?page=panel&module=addproxy&randomport&csrf=" + csrf_token,
		async:true,
		error: function() {
			return;
		},
		success: function() {
			$("#remote_port").val(htmlobj.responseText);
			return;
		}
	});
}
var csrf_token = "<?php echo $_SESSION['token']; ?>";
function alertMessage(title, body) {
	$("#msg-title").html(title);
	$("#msg-body").html(body);
	$("#modal-default").modal('toggle');
}
function addProxy() {
	var node                = $("#node").val();
	var proxy_name          = $("#proxy_name").val();
	var proxy_type          = $("#proxy_type").val();
	var local_ip            = $("#local_ip").val();
	var local_port          = $("#local_port").val();
	var remote_port         = $("#remote_port").val();
	var domain              = $("#domain").val();
	var use_encryption      = $("#use_encryption").val();
	var use_compression     = $("#use_compression").val();
	var locations           = $("#locations").val();
	var host_header_rewrite = $("#host_header_rewrite").val();
	var header_X_From_Where = $("#header_X-From-Where").val();
	var sk                  = $("#sk").val();
	var htmlobj = $.ajax({
		type: 'POST',
		url: "?page=panel&module=addproxy&action=addproxy&csrf=" + csrf_token,
		data: {
			node               : node,
			proxy_name         : proxy_name,
			proxy_type         : proxy_type,
			local_ip           : local_ip,
			local_port         : local_port,
			remote_port        : remote_port,
			domain             : domain,
			use_encryption     : use_encryption,
			use_compression    : use_compression,
			locations          : locations,
			host_header_rewrite: host_header_rewrite,
			header_X_From_Where: header_X_From_Where,
			sk                 : sk
		},
		async:true,
		error: function() {
			return;
		},
		success: function() {
			Swal.fire({
				title: "提示信息",
				html: htmlobj.responseText,
				icon: "question",
			    confirmButtonColor: '#7066e0',
			    confirmButtonText: '确认'
			});
			return;
		}
	});
}
function loadPortRules() {
	var htmlobj = $.ajax({
		type: 'GET',
		url: "?page=panel&module=addproxy&portrules&csrf=" + csrf_token,
		async:true,
		error: function() {
			return;
		},
		success: function() {
			Swal.fire({
				title: "端口规则",
				html: htmlobj.responseText,
				icon: "warning",
			    confirmButtonColor: '#7066e0',
			    confirmButtonText: '确认'
			});
			return;
		}
	});
}
function randomPort() {
	$("#remote_port").prop('disabled',false);
	var htmlobj = $.ajax({
		type: 'GET',
		url: "?page=panel&module=addproxy&randomport&csrf=" + csrf_token,
		async:true,
		error: function() {
			return;
		},
			success: function() {
			$("#remote_port").val(htmlobj.responseText);
			return;
		}
	});
	$("#domain").val("");
	$("#domain").prop('disabled',true);
}
function clearPort() {
	$("#remote_port").val("");
	$("#remote_port").prop('disabled',true);
	$("#domain").val("");
	$("#domain").prop('disabled',false);
}
</script>