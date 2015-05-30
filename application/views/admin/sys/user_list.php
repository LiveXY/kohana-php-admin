<script type="text/javascript" src="<?= RESOURCE ?>js/my97datepicker/wdatepicker.js"></script>

<div class="content-wrapper">
	<div class="content-operate">
		<table class="table-search autowidth">
			<tr>
				<td><img src="<?=RESOURCE?>images/ico/search.gif" class="ico" /> 搜索用户：</td>
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
				<th class="center"><a href="?begin=<?=$begin?>&end=<?=$end?>&key=<?=$key?>&oc=1&os=<?=Util::getOrderStatus(1, $oc, $os) ?>" class="orderby <?=$oc == 1 ? $os : "" ?>">用户ID</a></th>
				<th class="center">昵称</th>
				<th class="center">性别</th>
				<th class="center">手机</th>
				<th class="center">生日</th>
				<th class="center">登录次</th>
				<th class="center">注册时间</th>
				<th class="center"><a href="?begin=<?=$begin?>&end=<?=$end?>&key=<?=$key?>&oc=2&os=<?=Util::getOrderStatus(2, $oc, $os) ?>" class="orderby <?=$oc == 2 ? $os : "" ?>">最后登录时间</a></th>
				<th class="center">平台</th>
				<th class="center">管理 <?php if ($user_right['add']):?><a href="<?=BASEURI?>admin/sys/user_op" id="addUser" class="tab_open"><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加用户</a><?php endif;?></th>
				<th class="center">注册ip</th>
				<th class="center">登录ip</th>
			</tr>
			<?php if(count($users)==0):?>
			<tr><td colspan="26" class="center">暂无数据！ <?php if ($user_right['add']):?><a href="<?=BASEURI?>admin/sys/user_op" id="addUser" class="tab_open"><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加用户</a><?php endif;?></td></tr>
			<?php endif?>
			<?php foreach($users as $user):?>
			<tr>
				<td class="center"><?=$user->uid?></td>
				<td class="pl5"><a href="#" class="userControl" uid="<?=$user->uid?>"><?=$user->nickname?></a></td>
				<td class="center"><?=$user->gender == "1" ? "男" : ($user->gender == "2" ? "女" : '保密')?></td>
				<td class="pl5"><?=$user->tel?></td>
				<td class="center"><?=$user->birthday?></td>
				<td class="right pr5"><?=$user->login_times?></td>
				<td class="center"><?=$user->reg_date == 0 ? '' : date('Y-m-d H:i', $user->reg_date)?></td>
				<td class="center"><?=$user->login_date == 0 ? '' : date('Y-m-d H:i', $user->login_date)?></td>
				<td class="center"><?=$user->utype?></td>
				<td class="pl5">
					<?php if($user_right['edit']):?>
					<a href="<?=BASEURI?>admin/sys/user_op?uid=<?=$user->uid ?>" id="User<?=$user->uid?>" class="tab_open"><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" /> 修改用户</a>
					<?php else:?>
					<a href="<?=BASEURI?>admin/sys/user_op?uid=<?=$user->uid ?>" id="User<?=$user->uid?>" class="tab_open"><img src="<?=RESOURCE?>images/ico/view.gif" class="ico" /> 查看用户</a>
					<?php endif;?>
					<?php if($user_right['delete']):?>
					<a href="<?=BASEURI?>admin/sys/user_delete?uid=<?=$user->uid ?>" onclick="return confirm('删除不可恢复，您确实要删除数据吗？')"><img src="<?=RESOURCE?>images/ico/delete.gif" class="ico" /> 删除用户</a>
					<?php endif;?>
				</td>
				<td class="pl5"><?=$user->reg_ip?>(<?=IpLocation::getAddressByIP($user->reg_ip)?>)</td>
				<td class="pl5"><?=$user->login_ip?>(<?=IpLocation::getAddressByIP($user->login_ip)?>)</td>
			</tr>
			<?php endforeach;?>
		</table>
		<div class="pager t_l"></div>
	</div>
</div>
<script type="text/javascript">
happi.pro.admin.pageLoad(false);

$("#btnSearch").click(function () {
	top.tab.refresh();
	var begin = $("#begin").val();
	var end = $("#end").val();
	var key = $("#key").val();
	var oc = <?=$oc?>;
	var os = '<?=$os?>';
	location.href = "?begin={0}&end={1}&key={2}&oc={3}&os={4}&page=1".format(begin, end, key, oc, os);
}).btnSubmit($(".table-search"));

$('.pager').pager({
	align: 'left',
	page: <?=$page?>,
	pageSize: 25,
	total: <?=$totals?>,
	showGo: true,
	url: "?page={0}&key=" + $("#key").val() + "&begin=" + $("#begin").val() + "&end=" + $("#end").val() + "&oc=<?=$oc?>&os=<?=$os?>"
});

$(".pager").width($(".gridview").width());

</script>