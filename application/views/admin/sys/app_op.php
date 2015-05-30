<div class="content-wrapper">
	<div class="content-operate">
		<span class="title"><?php if ($app_id > 0) { ?><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" />  修改<?php } else { ?><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加<?php } ?>应用</span>
	</div>
	<div class="content-list">
		<?php if(($app_id <1 && $user_right['add']) || $user_right['edit']):?>
		<form method="post" name="data" action="<?=BASEURI?>admin/sys/app_post?app_id=<?= $app_id ?>">
		<?php endif;?>
		<table class="table-form">
			<colgroup>
				<col width="150px" />
				<col />
			</colgroup>
			<tr>
				<th class="t">应用名称：</th>
				<td class="pl5 t"><input type="text" class="input_text" name="txtName" value="<?= $app ? $app->app_name : "" ?>" /> <span class="red">* 必须输入</span></td>
			</tr><tr>
				<th>应用代碼：</th>
				<td class="pl5"><input type="text" class="input_text" name="txtEName" value="<?= $app ? $app->app_ename : "" ?>" /> <span class="red">* 必须输入</span></td>
			</tr><tr>
				<th>应用图标16x16：</th>
				<td class="pl5"><textarea class="imagelist" id="txtImg" name="txtImg"><?= $app ? $app->app_img : "" ?></textarea><span class="red">* 必须选择</span></td>
			</tr><tr>
				<th>排序：</th>
				<td class="pl5"><input type="text" class="input_text" name="txtOrder" value="<?= $app ? $app->app_order : "" ?>" /></td>
			</tr><tr>
				<th>状态：</th>
				<td class="pl5"><div class="input_cbo"><label>请选择</label>
					<select name="cboStatus">
						<option value="0" <?= ($app && $app->status==0) ? "selected=selected" : "" ?>>不可用</option>
						<option value="1" <?= ($app && $app->status==1) ? "selected=selected" : "" ?>>可用</option>
					</select>
				</div> <span class="require">* 状态可用时才能正式使用</span></td>
			</tr><tr>
				<td></td>
				<td class="pl5">
					<?php if(($app_id <1 && $user_right['add']) || $user_right['edit']):?>
					<input type="submit" class="input_button4" value=" 提交數據 " />　
					<?php endif;?>
					<input type="button" class="input_button2" value=" 返回列表 " onclick="location.href='<?=BASEURI?>admin/sys/func_list';" />
				</td>
			</tr>
		</table>
		<?php if(($app_id <1 && $user_right['add']) || $user_right['edit']):?>
		</form>
		<?php endif;?>
	</div>
</div>
<script type="text/javascript">
var txtImg = $("#txtImg").imageList({ path: "/client/images/ico/", showPath: false, checkbox: false });
txtImg.loadJson(<?= json_encode($images) ?>);

$("input[type='submit']").click(function(){
	if ($("input[name='txtName']").val() == '') { alert("请输入应用名称！"); $("input[name='txtName']").focus(); return false; };
	if ($("input[name='txtEName']").val() == '') { alert("请输入应用代码！"); $("input[name='txtEName']").focus(); return false; };
	if ($("input[name='txtImg']").val() == '') { alert("请输入应用图标！"); $("input[name='txtImg']").focus(); return false; };
	return true;
});
happi.pro.admin.pageLoad();
</script>
