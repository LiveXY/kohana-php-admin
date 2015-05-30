
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?=$title?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<?php if (Util::isMobile()) { ?><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" /><?php } ?>
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="<?=RESOURCE?>css/admin/msg.css" />
	<link rel="stylesheet" type="text/css" href="<?=RESOURCE?>css/admin/default.css" />
	<script type="text/javascript"> var root = "<?=BASEURI?>admin/main/index"; var iframeID = "", helpID = 0; if (self == top) { var url = unescape(window.location.href).split("/"); top.location = "<?=BASEURI?>admin/main/index?url=" + window.location.href.replace('http://' + url[2], ''); }; </script>
	<style type="text/css"> html, body, .body, .wrapper { width:auto; height:100%; } .syswindow .msg_mid .msg_cm { _padding-left:6px; } </style>
	<script type="text/javascript" src="<?=RESOURCE?>js/jquery-min.js"></script>
	<script type="text/javascript" src="<?=RESOURCE?>js/jquery.extend.js"></script>
	<script type="text/javascript" src="<?=RESOURCE?>js/jquery.admin.extend.js"></script>
	<script type="text/javascript" src="<?=RESOURCE?>js/happi.pro.admin.js"></script>
	<script type="text/javascript"> happi.pro.admin.iframeinit(); </script>
</head>
<body>
	<div class="body<?php if (Util::isMobile()) { ?> mobody<?php } ?>">
	<?=$main_content?>
	</div>
	<noscript><iframe src="*.html"></iframe><style type="text/css" media="screen"> html, body, .body, .wrapper { height:100%; width:100%; overflow:hidden; }.graph, .map { height:0 !important; }select {visibility:hidden;}</style><div id="noscript"></div><div id="noscriptContentBox" class="done"><div id="noscriptContent"><h3>系统运行需要的JavaScript的支持。<br /><br />请设置浏览器的“启用的JavaScript”选项。</h3></div></div></noscript>
</body>
</html>
