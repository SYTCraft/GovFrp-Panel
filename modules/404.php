<?php
$page_title = "404 Not found";
?>
<section class="content-header" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?php echo $page_title; ?></h1>
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
</section>
<section class="content" oncopy="return false" oncut="return false;" onselectstart="return false" oncontextmenu="return false">
    <div class="error-page">
        <h2 class="headline text-warning">404</h2>
        <div class="error-content">
			<br>
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> <b>Not found</b></h3>
            <p>抱歉，我们无法找到您所请求的页面或文件，您可以尝试 <a href="?page=panel&module=home">返回首页</a></p>
        </div>
    </div>
</section>