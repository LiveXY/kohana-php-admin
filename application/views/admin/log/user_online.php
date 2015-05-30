<script type="text/javascript" src="<?= RESOURCE ?>js/my97datepicker/wdatepicker.js"></script>
<div class="content-wrapper">
	<div class="content-operate">
		<table class="table-search autowidth">
			<tr>
				<td><img src="<?=RESOURCE?>images/ico/search.gif" class="ico" /> 搜索：</td>
				<td style="width: 250px">
					<input class="input_text120 Wdate Wdate120" type="text" id="begin" value="<?=$begin?>" placeholder="请选择起始日期" />-
					<input class="input_text120 Wdate Wdate120" type="text" id="end" value="<?=$end ?>" placeholder="请选择终止日期" />
				</td>
				<td><input class="input_text120" type="text" id="key" value="<?=$key?>" placeholder="请输入关键字" /></td>
				<td><button type="submit" id="btnSearch" class="input_button4"><img src="<?=RESOURCE?>images/ico/search.gif" class="ico" /> 搜索</button></td>
			</tr>
		</table>
	</div>
	<div class="content-list">
		<table class="gridview autowidth">
			<tr>
				<th class="center">用户</th>
				<th class="center">URL</th>
				<th class="center">IP</th>
				<th class="center">操作时间</th>
			</tr>
			<?php if(count($list)==0):?>
			<tr><td colspan="4" class="center">暂无数据！</td></tr>
			<?php endif?>
			<?php foreach($list as $info):?>
			<tr>
				<td class="pl5"><a href="#" class="userControl" uid="<?=$info->user_id?>"><?=$info->user_id?></a></td>
				<td class="pl5"><?=$info->user_url?></td>
				<td class="pl5"><?=$info->url_ip?>-<?=IpLocation::getAddressByIP($info->url_ip)?></td>
				<td class="center"><?=date('Y-m-d H:i:s', $info->last_time)?></td>
			</tr>
			<?php endforeach;?>
		</table>
		<div class="pager"></div>
	</div>
</div>
<script type="text/javascript">
happi.pro.admin.pageLoad(false);

$("#btnSearch").click(function () {
	top.tab.refresh();
	var begin = $("#begin").val();
	var end = $("#end").val();
	var key = $("#key").val();
	location.href = "?begin={0}&end={1}&key={2}&page=1".format(begin, end, key);
}).btnSubmit($(".table-search"));

$('.pager').pager({
	align: 'left',
	page: <?=$page?>,
	pageSize: 25,
	total: <?=$totals?>,
	showGo: true,
	url: "?page={0}&key=" + $("#key").val() + "&begin=" + $("#begin").val() + "&end=" + $("#end").val()
});

$(".pager").width($(".gridview").width());
</script>