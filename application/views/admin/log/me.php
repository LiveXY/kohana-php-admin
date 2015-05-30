<link rel="stylesheet" type="text/css" href="<?=RESOURCE?>js/ztree/ztreestyle/ztreestyle.css" />
<script type="text/javascript" src="<?=RESOURCE?>js/ztree/jquery.ztree.core-3.1.js"></script>
<div class="ztree-wrapper">
	<ul class="ztree w150" id="ztree-container">

	</ul>
	<div class="ztree-page l162">
		<iframe id="ztree-iframe" width="100%" scrolling="auto" frameborder="0" src=""></iframe>
	</div>
</div>
<script type="text/javascript">
	var setting = {
		view: {
			dblClickExpand: false
		},
		data: {
			simpleData: {
				enable: true
			}
		},
		callback: {
			beforeClick: function (treeId, treeNode) {
				if (treeNode.isParent) {
					$.fn.zTree.getZTreeObj("ztree-container").expandNode(treeNode);
					return true;
				} else {
					top.tab.refresh();
					$("#ztree-iframe").attr("src", '<?=BASEURI ?>admin/' + treeNode.ourl);
					return true;
				}
			}
		}
	};

	$(document).ready(function () {
		$.fn.zTree.init($("#ztree-container"), setting, [
			{ "id": 1, "pId": 0, "name": "<?=$uid?>", "open": true }
			,{ "id": 2, "pId": 1, "name": "个人资料", "ourl": "sys/user_op?uid=<?=$uid?>" }
<?php if ($islog) {?>
			,{ "id": 3, "pId": 1, "name": "用户登陆日志", "ourl": "log/user_login_log?key=<?=$uid?>" }
<?php }?>
		]);
		$(window).resize(function () {
			$("#ztree-iframe").height($(window).height());
		}).resize();
		$("#ztree-iframe").load(function () { top.tab.complete(); });
		if ($.isIE6() && $("#ztree-container").length == 1) {
			$("#ztree-container").height($(window).height());
			setTimeout(function () {
				top.tab.active();
			}, 500);
		}

		$("#ztree-container_2_a").click();
	});
	var refresh = function(id) {
		if ($("#ztree-container").length == 0) top.tab.refresh(null, true);
	};
</script>