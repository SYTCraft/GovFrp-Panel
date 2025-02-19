<?php
$group_title = "后台管理";
$page_title = "兑换管理";

session_start();
require 'configuration.php'; // 引入配置文件

// 数据库连接
$dsn = "mysql:host={$_config['db_host']};dbname={$_config['db_name']};port={$_config['db_port']};charset={$_config['db_code']}";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new PDO($dsn, $_config['db_user'], $_config['db_pass'], $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

// 检查用户是否为管理员
if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => '用户未登录。']);
    exit;
}

$username = $_SESSION['user'];
$stmt = $pdo->prepare("SELECT `group` FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['group'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => '您没有权限访问此页面。']);
    exit;
}

// 获取所有兑换码
$stmt = $pdo->prepare("SELECT * FROM redeem_codes");
$stmt->execute();
$redeem_codes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="content-header" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">
					<?php echo $page_title; ?>&nbsp;&nbsp;<small class="text-muted text-xs">添加或删除兑换代码</small>
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
<div class="content" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header">
						<h6 class="mb-0">兑换代码列表</h6>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>兑换码</th>
									<th>流量值 (MB)</th>
									<th>是否已使用</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($redeem_codes as $redeem_code): ?>
								<tr>
									<td>
										<?php echo htmlspecialchars($redeem_code['code']); ?>
									</td>
									<td>
										<?php echo htmlspecialchars($redeem_code['traffic_value']); ?>
									</td>
									<td>
										<?php echo $redeem_code['is_used'] ? '是' : '否'; ?>
									</td>
									<td>
										<button class="btn btn-danger btn-sm" onclick="deleteRedeemCode('<?php echo htmlspecialchars($redeem_code['code']); ?>')">删除</button>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="card">
					<div class="card-header">
						<h6 class="mb-0">添加兑换代码</h6>
					</div>
					<div class="card-body">
						<form id="add-redeem-code-form">
							<div class="form-group">
								<label for="traffic_value">流量值 (MB):</label>
								<input type="number" class="form-control" id="traffic_value" name="traffic_value" required>
							</div>
							<button type="submit" class="btn btn-primary">添加兑换码</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="../assets/jquery/dist/js/jquery.min.js"></script>
<script>
$(document).ready(function() {
	$('#add-redeem-code-form').on('submit', function(e) {
		e.preventDefault(); // 阻止表单的默认提交行为
		var traffic_value = $('#traffic_value').val();

		$.ajax({
			type: 'POST',
			url: '../core/adminredeem.php', // 确保路径正确
			data: {
				action: 'add',
				traffic_value: traffic_value
			},
			dataType: 'json',
			success: function(response) {
				if (response.status === 'success') {
					Swal.fire({
						title: "成功",
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
						title: "错误",
						text: response.message,
						icon: "error",
						confirmButtonColor: '#7066e0',
						confirmButtonText: '确认'
					});
				}
			},
			error: function() {
				Swal.fire({
					title: "错误",
					text: "请求过程中发生错误，请稍后重试。",
					icon: "error",
					confirmButtonColor: '#7066e0',
					confirmButtonText: '确认'
				});
			}
		});
	});
});

function deleteRedeemCode(code) {
	Swal.fire({
		title: "确认删除",
		text: "您确定要删除兑换码 " + code + " 吗？",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: '#dd3333',
		cancelButtonColor: '#6e7881',
		confirmButtonText: '确认',
		cancelButtonText: '取消'
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				type: 'POST',
				url: '../core/adminredeem.php', // 确保路径正确
				data: {
					action: 'delete',
					code: code
				},
				dataType: 'json',
				success: function(response) {
					if (response.status === 'success') {
						Swal.fire({
							title: "成功",
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
							title: "错误",
							text: response.message,
							icon: "error",
							confirmButtonColor: '#7066e0',
							confirmButtonText: '确认'
						});
					}
				},
				error: function() {
					Swal.fire({
						title: "错误",
						text: "请求过程中发生错误，请稍后重试。",
						icon: "error",
						confirmButtonColor: '#7066e0',
						confirmButtonText: '确认'
					});
				}
			});
		}
	});
}

</script>