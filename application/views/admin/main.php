<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>happi － <?=$title?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<?php if (Util::isMobile()) { ?><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" /><?php } ?>
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="<?=RESOURCE?>css/admin/msg.css" />
	<link rel="stylesheet" type="text/css" href="<?=RESOURCE?>css/admin/default.css" />
	<script> var root = "<?=BASEURI?>admin/main/index"; if (self != top) { top.location = "<?=BASEURI?>admin/main/index"; }; </script>
	<style type="text/css"> html, body, .body, .wrapper { width:100%; height:100%; overflow:hidden; } </style>
</head>
<body>
	<div class="body">
		<div class="wrapper">
			<div class="nav-tab-right-menu">
				<a href="#" class="refresh"><img src="<?=RESOURCE?>images/ico/refresh.gif" />　刷新</a>
				<i></i>
				<a href="#" class="close_current"><img src="<?=RESOURCE?>images/ico/stop.gif" />　关闭当前选项卡</a>
				<a href="#" class="close_all"><img src="<?=RESOURCE?>images/t.gif" />　关闭所有选项卡</a>
				<i></i>
				<a href="#" class="cancel"><img src="<?=RESOURCE?>images/t.gif" />　取消操作</a>
			</div>
			<div class="mheader miniheader clearfix">
				<div class="header-nav">
					<img src="<?=RESOURCE?>images/ico/admin.gif" width="16px" height="16px" /> <?=CacheManager::getUser()->nickname ?>　
					<a href="<?=BASEURI?>admin/login/logout"><img src="<?=RESOURCE?>images/ico/exit.png" /> 退出系統</a>
				</div>
				<div class="header-logo"><img src="<?=RESOURCE?>images/t.gif" height="35px" alt="LOGO" class="logo" /></div>
			</div>
			<div class="content clearfix">
				<div class="content-left">
					<div class="content-left-nav bt0 clearfix"><i></i>功能列表</div>
					<div class="nav-tree" mobile="<?=Util::isMobile()?>">
						<a href="<?=BASEURI?>admin/main/home" id="home"><img src="<?=RESOURCE?>images/ico/home.gif" /> 管理首页</a>
						<?php foreach($admin_menus as $menus):?>
						<div class="node close"><img src="<?=RESOURCE?>images/ico/<?=$menus['img']?>" /> <?=$menus['title']?></div>
						<div class="item">
							<?php foreach($menus['children'] as $children):?>
							<a hidefocus="true" href="<?=BASEURI?><?=$children['url']?>" id="<?=$children['code']?>"><img src="<?=RESOURCE?>images/ico/<?=$children['img']?>" /> <?=$children['title']?></a>
							<?php endforeach;?>
						</div>
						<?php endforeach;?>
					</div>
				</div>
				<div class="content-left content-left-block hide"><i></i><span>功<br />能<br />列<br />表</span></div>
				<div class="content-right ml170">
					<div class="nav-tab-close"><i></i></div>
					<ul class="nav-tab" nowrap></ul>
					<div class="nav-tab-page"></div>
				</div>
			</div>
			<div class="footer"></div>
		</div>
	</div>
	<noscript><iframe src="*.html"></iframe><style type="text/css" media="screen">html, body, .body, .wrapper { height:100%; width:100%; overflow:hidden; }.graph, .map { height:0 !important; } select {visibility:hidden;}</style><div id="noscript"></div><div id="noscriptContentBox" class="done"><div id="noscriptContent"><h3>系统运行需要的JavaScript的支持。<br /><br />请设置浏览器的“启用的JavaScript”选项。</h3></div></div></noscript>
	<script type="text/javascript" src="<?=RESOURCE?>js/jquery-min.js"></script>
	<script type="text/javascript" src="<?=RESOURCE?>js/jquery.hotkeys.js"></script>
	<script type="text/javascript" src="<?=RESOURCE?>js/jquery.extend.js"></script>
	<script type="text/javascript" src="<?=RESOURCE?>js/jquery.admin.extend.js"></script>
	<script type="text/javascript" src="<?=RESOURCE?>js/happi.pro.admin.js"></script>
	<script type="text/javascript">	$(document).ready(function () { happi.pro.admin.init(); if (<?=Util::isMobile()?>) $(".content-left-nav > i").click(); }); </script>
</body>
</html>
