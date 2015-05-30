<link rel="stylesheet" type="text/css" href="<?=RESOURCE?>js/ztree/ztreestyle/ztreestyle.css" />
<script type="text/javascript" src="<?=RESOURCE?>js/ztree/jquery.ztree.core-3.1.js"></script>
<script type="text/javascript" src="<?=RESOURCE?>js/ztree/jquery.ztree.excheck-3.1.js"></script>
<div class="content-wrapper">
	<div class="content-operate">
		<span class="title"><?php if ($user_id > 0) { ?><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" />  修改<?php } else { ?><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加<?php } ?>管理员</span>
	</div>
	<div class="content-list">
		<?php if(($user_id <1 && $user_right['add']) || $user_right['edit']):?>
		<form method="post" name="data" action="<?=BASEURI?>admin/sys/admin_post?user_id=<?= $user_id ?>">
		<?php endif;?>
		<table class="table-form">
			<colgroup>
				<col width="150px" />
				<col />
			</colgroup>
			<tr>
				<th class="t">管理员ID：</th>
				<?php if ($user_id < 1) { ?>
				<td class="pl5 t"><input type="text" class="input_text" name="txtUserID" value="<?= $admin ? $admin->user_id : "" ?>" /> <span class="red">* 必须输入用户ID或账号</span></td>
				<?php } else { ?>
				<td class="pl5 t"><?= $admin ? $admin->user_id : "" ?></td>
				<?php } ?>
			</tr><tr>
				<th>管理员昵称：</th>
				<td class="pl5" id="user_email"><?= $admin ? $admin->nickname : "" ?></td>
			</tr><tr>
				<th>管理员Email：</th>
				<td class="pl5" id="user_nick"><?= $admin ? $admin->email : "" ?></td>
			</tr><tr>
				<th>角色：</th>
				<td class="pl5"><div class="input_cbo"><label>请选择</label>
					<select id="cboRole" name="cboRole">
						<option value="">请选择</option>
						<?php foreach($roles as $role):?>
						<?php if ($role->role_ename == 'MarketUser') continue; ?>
						<option value="<?= $role->role_id ?>" <?= ($admin && $admin->role_id==$role->role_id) ? "selected=selected" : "" ?>><?= $role->role_name ?></option>
						<?php endforeach;?>
					</select>
				</div> <span class="require">* 必须选择</span></td>
			</tr><tr>
				<th>角色功能浏览：</th>
				<td class="pl5"><textarea class="checkboxtree" name="txtFunc" id="txtFunc" default="" nameValue=""></textarea></td>
			</tr><tr>
				<td></td>
				<td class="pl5">
					<?php if(($user_id <1 && $user_right['add']) || $user_right['edit']):?>
					<input type="submit" class="input_button4" value=" 提交數據 " />　
					<?php endif;?>
					<input type="button" class="input_button2" value=" 返回列表 " onclick="location.href='<?=BASEURI?>admin/sys/admin_list';" />
				</td>
			</tr>
		</table>
		<?php if(($user_id <1 && $user_right['add']) || $user_right['edit']):?>
		</form>
		<?php endif;?>
	</div>
</div>
<script type="text/javascript">

txtFunc = $("#txtFunc").checkBoxTree({ allTitle: "所有角色功能", checkbox: null });
$("#cboRole").change(function(){
	var val = $(this).val();
	if (val == '') txtFunc.loadJson([]);
	$.get("<?=BASEURI?>admin/sys/admin_checkFunc?role_id=" + val, function(data){
		var json = eval('(' + data + ')');
		txtFunc.loadJson(json);
	});
});

if (<?= $user_id ?><1) {
	$("input[name='txtUserID']").keyup(function(){
		var key = $("input[name='txtUserID']").val();
		$.get("<?=BASEURI?>admin/sys/admin_check?key=" + key, function(data){
			$("#user_email").html("");
			$("#user_nick").html("");
			if (data.user_id == -2) {
				$("input[name='txtUserID']").next().html("* 已是管理员");
				return false;
			}
			if (data.user_id == -1) {
				$("input[name='txtUserID']").next().html("* 用户不存在");
				return false;
			}
			if (data.user_id == 0) {
				$("input[name='txtUserID']").next().html("* 必须输入用户ID或Email");
				return false;
			}
			$("input[name='txtUserID']").val(data.user_id).next().html("");
			$("#user_nick").html(data.user_email);
			$("#user_email").html(data.user_nick);
			return false;
		});
	}).blur(function(){ $(this).keyup(); });
}
$("input[type='submit']").click(function(){
	if (<?= $user_id ?> == 0) {
		if ($("input[name='txtUserID']").val() == '') { alert("请输入用户ID或Email！"); $("input[name='txtUserID']").focus(); return false; };
	}
	if ($("select[name='cboRole']").val() == '') { alert("请选择角色！"); $("select[name='cboRole']").focus(); return false; };
	return true;
});
happi.pro.admin.pageLoad();
</script>
