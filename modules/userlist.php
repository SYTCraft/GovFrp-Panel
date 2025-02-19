<?php
namespace SYTCraftPanel;
use SYTCraftPanel;

$group_title = "后台管理";
$page_title = "用户列表";

$um = new SYTCraftPanel\UserManager();
$rs = Database::querySingleLine("users", Array("username" => $_SESSION['user']));

if(!$rs || $rs['group'] !== "admin") {
	exit("<script>location='?page=panel';</script>");
}

if(isset($_GET['getinfo']) && preg_match("/^[0-9]{1,10}$/", $_GET['getinfo'])) {
	SYTCraftPanel\Utils::checkCsrf();
	$rs = Database::querySingleLine("users", Array("id" => $_GET['getinfo']));
	if($rs) {
		$lm = $um->getLimit($rs['username']);
		$inbound  = $lm['type'] == 1 ? $lm['inbound'] : "";
		$outbound = $lm['type'] == 1 ? $lm['outbound'] : "";
		ob_clean();
		exit(json_encode(Array(
			"id"       => $rs['id'],
			"username" => $rs['username'],
			"traffic"  => $rs['traffic'],
			"proxies"  => $rs['proxies'],
			"inbound"  => $inbound,
			"outbound" => $outbound,
			"group"    => $rs['group'],
			"status"   => $rs['status'],
			"verified" => $rs['verified'],
			"phone"    => $rs['phone']
		)));
	} else {
		ob_clean();
		Header("HTTP/1.1 403");
		exit("未找到用户");
	}
}
?>
<style type="text/css">
.fix-text p {
	margin-bottom: 4px;
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
.page-num {
	margin-right: 8px;
	margin-bottom: 8px;
	margin-top: 8px;
}
</style>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><?php echo $page_title; ?>&nbsp;&nbsp;<small class="text-muted text-xs">管理本站点的用户</small></h1></div>
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
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">用户列表</h3>
                        </div>
                        <br/>
						<div class="input-group">
							<input type="text" placeholder="可输入用户名、邮箱来进行搜索" class="form-control" id="searchdata">
							<select class="form-control" id="statusFilter" style="max-width: 150px;">
								<option value="">所有状态</option>
								<option value="0">正常</option>
								<option value="1">已封禁</option>
								<option value="2">已注销</option>
							</select>
							<select class="form-control" id="verifiedFilter" style="max-width: 150px;">
								<option value="">所有实名状态</option>
								<option value="0">未实名</option>
								<option value="1">已实名</option>
								<option value="2">已注销</option>
							</select>
							<span class="input-group-append">
								<button type="button" class="btn btn-info" onclick="search()"><i class="fas fa-search"></i></button>
							</span>
						</div>
                    </div>
                    <div class="card-body p-0 table-responsive">
						<table class="table table-bordered table-hover dataTable dtr-inline" style="width: 100%;font-size: 15px;">
							<tr>
								<th class='text-center' nowrap>ID</th>
								<th class='text-center' nowrap>用户</th>
								<th class='text-center' nowrap>邮箱</th>
								<th class='text-center' nowrap>剩余流量</th>
								<th class='text-center' nowrap>隧道上限</th>
								<th class='text-center' nowrap>分组</th>
								<th class='text-center' nowrap>注册时间</th>
								<th class='text-center' nowrap>状态</th>
								<th class='text-center' nowrap>操作</th>
							</tr>
							<?php
							$spage          = isset($_GET['p']) && preg_match("/^[0-9]{1,9}$/", $_GET['p']) && Intval($_GET['p']) > 0 ? (Intval($_GET['p'])) : 1;
							$_GET['search'] = isset($_GET['search']) ? Database::escape($_GET['search']) : "";
							$_GET['status'] = isset($_GET['status']) ? Database::escape($_GET['status']) : "";
							$_GET['verified'] = isset($_GET['verified']) ? Database::escape($_GET['verified']) : "";
							$_GET['p']      = isset($_GET['p']) && preg_match("/^[0-9]{1,9}$/", $_GET['p']) && Intval($_GET['p']) > 0 ? (Intval($_GET['p']) - 1) * 10 : "";

							$mainSQL = "SELECT * FROM `users` ";
							$whereClause = [];

							if (isset($_GET['search']) && !empty($_GET['search'])) {
								$whereClause[] = "(POSITION('{$_GET['search']}' IN `username`) OR POSITION('{$_GET['search']}' IN `email`))";
							}

							if (isset($_GET['status']) && !empty($_GET['status'])) {
								$whereClause[] = "`status` = '{$_GET['status']}'";
							}

							if (isset($_GET['verified']) && $_GET['verified'] !== "") {
								$whereClause[] = "`verified` = '{$_GET['verified']}'";
							}

							if (!empty($whereClause)) {
								$mainSQL .= "WHERE " . implode(" AND ", $whereClause) . " ";
							}

							$mainSQL .= (isset($_GET['p']) && !empty($_GET['p'])) ? "LIMIT {$_GET['p']},11" : "LIMIT 0,11";
							$rs = Database::toArray(Database::query("users", $mainSQL, true));
							$i = 0;
							foreach($rs as $user) {
								$i++;
								if($i > 10) break;
								$traffic   = round($user[4] / 1024, 2) . " GiB";
								$regtime   = date("Y年m月d日", $user[7]);
								$verifieds = Array(0 => "未实名", 1 => "已实名", 2 => "已注销");
								$verified  = $verifieds[$user[9]] ?? "未知";
								$statuss   = Array(0 => "正常", 1 => "已封禁");
								$status    = $statuss[$user[8]] ?? "未知";
								echo "<tr>
								<td class='text-center' nowrap>{$user[0]}</td>
								<td class='text-center' nowrap>{$user[1]}</td>
								<td class='text-center' nowrap>{$user[3]}</td>
								<td class='text-center' nowrap>{$traffic}</td>
								<td class='text-center' nowrap>{$user[5]}</td>
								<td class='text-center' nowrap>{$user[6]}</td>
								<td class='text-center' nowrap>{$regtime}</td>
								<td class='text-center' nowrap>{$verified}/{$status}</td>
								<td class='text-center' nowrap><a href='javascript:edit({$user[0]})'>[编辑]</a></td>
								";
							}
							?>
						</table>
						<?php
						if($i == 0) {
							echo "<p class='text-center'>没有找到符合条件的结果</p>";
						}
						?>
						<div class="text-right page-num">
						<span>第 <?php echo $spage; ?> 页&nbsp;&nbsp;</span>
						<?php
						$search = isset($_GET['search']) ? "&search=" . urlencode($_GET['search']) : "";
						$status = isset($_GET['status']) ? "&status=" . urlencode($_GET['status']) : "";
						$verified = isset($_GET['verified']) ? "&verified=" . urlencode($_GET['verified']) : "";
						$fpage = $spage - 1;
						$npage = $spage + 1;
						if($i > 10) {
							if(isset($_GET['p']) && Intval($_GET['p']) > 1) {
								echo "<a href='?page=panel&module=userlist{$search}{$status}{$verified}'><button class='btn btn-default'><i class='fa fa-home'></i></button></a>&nbsp;&nbsp;";
								echo "<a href='?page=panel&module=userlist{$search}{$status}{$verified}&p={$fpage}'><button class='btn btn-default'><i class='fa fa-angle-left'></i></button></a>&nbsp;&nbsp;";
							}
							echo "<a href='?page=panel&module=userlist{$search}{$status}{$verified}&p={$npage}'><button class='btn btn-default'><i class='fa fa-angle-right'></i></button></a>";
						} else {
							if(isset($_GET['p']) && Intval($_GET['p']) > 1) {
								echo "<a href='?page=panel&module=userlist{$search}{$status}{$verified}'><button class='btn btn-default'><i class='fa fa-home'></i></button></a>&nbsp;&nbsp;";
								echo "<a href='?page=panel&module=userlist{$search}{$status}{$verified}&p={$fpage}'><button class='btn btn-default'><i class='fa fa-angle-left'></i></button></a>";
							}
						}
						?></div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">修改用户信息</h3>
                        </div>
                    </div>
                    <div class="card-body">
						<p id="statusmsg"/>
						<div class="sub-heading">
							<span>基础设置</span>
						</div>
						<p>此部分设置如果留空将会使用用户组的设置进行覆盖</p>
						<p><b>实名认证</b>&nbsp;&nbsp;<small><a href="javascript:realnameinfo()">查看信息</a></small></p>
						<p><select class="form-control" id="verified">
							<option value="0">未实名</option>
							<option value="1">已实名</option>
							<option value="2">已注销</option>
						</select></p>
						<p><input type="phone" class="form-control" id="phone"></input></p>
						<p><b>流量设置</b>&nbsp;&nbsp;<small>单位 MB，修改后即时生效。</small></p>
						<p><input type="number" class="form-control" id="traffic"></input></p>
						<p><b>隧道数量</b>&nbsp;&nbsp;<small>用户最多可以添加的隧道数量</small></p>
						<p><input type="number" class="form-control" id="proxies"></input></p>
						<p><b>最大上传</b>&nbsp;&nbsp;<small>单位 KB/s，留空则继承组设定</small></p>
						<p><input type="number" class="form-control" id="inbound"></input></p>
						<p><b>最大下行</b>&nbsp;&nbsp;<small>单位 KB/s，留空则继承组设定</small></p>
						<p><input type="number" class="form-control" id="outbound"></input></p>
						<div class="sub-heading">
							<span>权限设置</span>
						</div>
						<p><b>用户组</b>&nbsp;&nbsp;<small>选择需要将用户分配到的用户组</small></p>
						<p><select class="form-control" id="group">
							<?php
							$gs = Database::toArray(Database::query("groups", "SELECT * FROM `groups`", true));
							foreach($gs as $gi) {
								echo "<option value='{$gi[1]}'>{$gi[2]}</option>";
							}
							?>
							<option value="admin">管理员</option>
						</select></p>
						<p><b>用户状态</b>&nbsp;&nbsp;<small>切换用户的状态</small></p>
						<select class="form-control" id="status">
							<option value="0">正常</option>
							<option value="1">封禁</option>
							<option value="2">注销</option>
						</select>
					</div>
					<div class="card-footer">
						<button class="btn btn-primary float-sm-right" onclick="save()">保存设置</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var csrf_token = "<?php echo $_SESSION['token']; ?>";
var userid = "";

$(document).ready(function() {
    var statusFilter = "<?php echo isset($_GET['status']) ? $_GET['status'] : ''; ?>";
    var verifiedFilter = "<?php echo isset($_GET['verified']) ? $_GET['verified'] : ''; ?>";
    if (statusFilter) {
        $("#statusFilter").val(statusFilter);
    }
    if (verifiedFilter) {
        $("#verifiedFilter").val(verifiedFilter);
    }
});

function search() {
    var searchText = $("#searchdata").val();
    var statusFilter = $("#statusFilter").val();
    var verifiedFilter = $("#verifiedFilter").val();
    var url = "?page=panel&module=userlist";
    
    if (searchText) {
        url += "&search=" + encodeURIComponent(searchText);
    }
    
    if (statusFilter) {
        url += "&status=" + encodeURIComponent(statusFilter);
    }
    
    if (verifiedFilter) {
        url += "&verified=" + encodeURIComponent(verifiedFilter);
    }
    
    window.location.href = url;
}

function edit(id) {
	var htmlobj = $.ajax({
		type: 'GET',
		url: "?page=panel&module=userlist&getinfo=" + id + "&csrf=" + csrf_token,
		async:true,
		error: function() {
			alert("错误：" + htmlobj.responseText);
			return;
		},
		success: function() {
			try {
				var json = JSON.parse(htmlobj.responseText);
				userid = json.id;
				$("#traffic").val(json.traffic);
				$("#proxies").val(json.proxies);
				$("#inbound").val(json.inbound);
				$("#outbound").val(json.outbound);
				$("#group").val(json.group);
				$("#phone").val(json.phone);
				$("#status").val(json.status);
				$("#verified").val(json.verified);
				$("#statusmsg").html("正在编辑用户 " + json.username + " 的设置");
			} catch(e) {
				alert("错误：无法解析服务器返回的数据");
			}
			return;
		}
	});
}

function save() {
	if(userid == "") {
	    Swal.fire("提示信息", "您未编辑任何用户信息。", "error");
		return;
	}
	var htmlobj = $.ajax({
		type: 'POST',
		url: "?action=updateuser&page=panel&module=userlist&csrf=" + csrf_token,
		async:true,
		data: {
			id: userid,
			traffic: $("#traffic").val(),
			proxies: $("#proxies").val(),
			inbound: $("#inbound").val(),
			outbound: $("#outbound").val(),
			group: $("#group").val(),
			status: $("#status").val(),
			verified: $("#verified").val(),
			phone: $("#phone").val()
		},
		error: function() {
			alert("错误：" + htmlobj.responseText);
			return;
		},
		success: function() {
			alert(htmlobj.responseText);
			window.location.reload();
			return;
		}
	});
}

function realnameinfo() {
	var htmlobj = $.ajax({
		type: 'GET',
		url: "?page=panel&module=userlist&getinfo=" + userid + "&csrf=" + csrf_token,
		async:true,
		error: function() {
			alert("错误：" + htmlobj.responseText);
			return;
		},
		success: function() {
			try {
				var json = JSON.parse(htmlobj.responseText);
				userid = json.id;
				$("#traffic").val(json.traffic);
				$("#proxies").val(json.proxies);
				$("#inbound").val(json.inbound);
				$("#outbound").val(json.outbound);
				$("#group").val(json.group);
				$("#status").val(json.status);
				$("#verified").val(json.verified);
				$("#phone").val(json.phone);
				$("#statusmsg").html("正在编辑用户 " + json.username + " 的设置");
			} catch(e) {
				alert("错误：无法解析服务器返回的数据");
			}
			return;
		}
	});
}
</script>