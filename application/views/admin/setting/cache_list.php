<div class="content-wrapper">
	<div class="content-operate">
		<span class="title"><img src="<?=RESOURCE?>images/ico/back.gif" class="ico" /> 缓存管理：</span>
		<a title="removeAll" class="btn"> 清理所有缓存</a>
	</div>
	<div class="content-list">
		<table class="gridview autowidth">
			<tr>
				<th class="center">缓存</th>
				<th class="center">清理</th>
				<th class="center e">获取</th>
			</tr><tr>
				<td>用户信息：<input class="input_text120" type="text" placeholder="请输入用户uid" /></td>
				<td class="center"><a title="removeUser" class="btn">清理缓存</a></td>
				<td class="center"><a title="getUser" class="btn"> 查看缓存内容</a></td>
			</tr><tr>
				<td class="pl5">所有在线用户</td>
				<td class="center"><a title="removeOnlineUsers" class="btn"> 清理缓存</a></td>
				<td class="center"></td>
			</tr>
		</table>
	</div>
</div>
<script type="text/javascript">
happi.pro.admin.pageLoad(false);

var clearCache = function(uid, value, alert){
	$.ajax({
		type: "post", dataType: 'json',
		url: "<?=BASEURI?>admin/setting/cache_post",
		data: { action: value,uid: uid },
		success: function (json) {
			if (!alert) return false;

			if (json.status == 1) {
				$(window).alert({ text: '<font color="green">缓存清理成功！</font>' });
			} else if(json.status == 2) {
				$(window).window({ isDrag:false, width:400, html: '<textarea style="overflow:auto;width:400px;height:300px" class="green">'+json.data+'</textarea>' });
			} else {
				$(window).alert({ text: '<font color="red">操作失败！</font>' });
			}
		},
		error: function(json){
			$(window).alert({ text: '<font color="red">操作失败！</font>' });
		}
	});
}

$(".btn").click(function(){
	var me = $(this);
	var value = me.attr('title');
	var uid = me.parent().parent().find("input:first").val();
	if (value == 'removeAll') {
		$('.table-search tr').each(function(){
			if ($(this).find('input').length == 0 && $(this).find('.btn').first().attr('title')) {
				clearCache(0, $(this).find('.btn').first().attr('title'), false);
			}
		});
		$(window).alert({ text: '<font color="green">缓存清理成功！</font>' });
	} else clearCache(uid, value, true);
	return false;
});
</script>
