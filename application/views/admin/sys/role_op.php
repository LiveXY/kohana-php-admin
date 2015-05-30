<div class="content-wrapper">
	<div class="content-operate">
		<span class="title"><?php if ($role_id > 0) { ?><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" />  修改<?php } else { ?><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加<?php } ?>角色</span>
	</div>
	<div class="content-list">
		<?php if(($role_id <1 && $user_right['add']) || $user_right['edit']):?>
		<form method="post" name="data" action="<?=BASEURI?>admin/sys/role_post?role_id=<?= $role_id ?>">
		<?php endif;?>
		<table class="table-form">
			<colgroup>
				<col width="150px" />
				<col />
			</colgroup>
			<tr>
				<th class="t">角色名称：</th>
				<td class="pl5 t"><input type="text" class="input_text" name="txtName" value="<?= $role ? $role->role_name : "" ?>" /> <span class="red">* 必须输入</span></td>
			</tr><tr>
				<th>角色代码：</th>
				<td class="pl5"><input type="text" class="input_text" name="txtEName" value="<?= $role ? $role->role_ename : "" ?>" /> <span class="red">* 必须输入</span></td>
			</tr><tr>
				<th>角色权限：</th>
				<td class="pl5">
					<textarea class="hide" name="txtFuncsName" id="txtFuncsName"><?= $role ? $role->role_funcnames : "" ?></textarea>
					<textarea class="hide" name="txtFuncsID" id="txtFuncsID"><?= $role ? $role->role_funcids : "" ?></textarea>
					<table class="gridview" style="width:500px">
						<colgroup>
							<col width="180px" />
							<col />
						</colgroup>
						<tr>
							<th class="center t" style="padding-top:7px;">功能名称</th>
							<th class="center t" style="padding-top:7px;"><label><input type="checkbox" id="chkAll" /> 功能操作</label></th>
						</tr>
						<?php foreach($apps as $app):?>
						<tr class="tree_table_node">
							<td class="item pl25" colspan="2"><img src="<?=RESOURCE?>images/ico/<?=$app->app_img?>" /> <?=$app->app_name?></td>
						</tr>
						<?php foreach($funcs[$app->app_id] as $func):?>
						<tr class="funcitem tree_table_item" name="<?=$func->func_name?>" value="<?=$func->func_id?>">
							<td class="item pl25" title="<?=$func->func_name?>" nobr><img src="<?=RESOURCE?>images/ico/<?=$func->func_img?>" /> <label><input type="checkbox" class="chkFunc" name="chkFunc<?=$func->func_id?>" /> <?=$func->func_name?></label></td>
							<td class="pl5">
								<label><input type="checkbox" class="chkOp" name="view">浏览</label>&nbsp;&nbsp;
								<label><input type="checkbox" class="chkOp" name="add">添加</label>&nbsp;&nbsp;
								<label><input type="checkbox" class="chkOp" name="edit">修改</label>&nbsp;&nbsp;
								<label><input type="checkbox" class="chkOp" name="delete">删除</label>&nbsp;&nbsp;
							</td>
						</tr>
						<?php endforeach;?>
						<?php endforeach;?>
					</table>
				</td>
			</tr><tr>
				<th>状态：</th>
				<td class="pl5"><div class="input_cbo"><label>请选择</label>
					<select name="cboStatus">
						<option value="0" <?= ($role && $role->status==0) ? "selected=selected" : "" ?>>不可用</option>
						<option value="1" <?= ($role && $role->status==1) ? "selected=selected" : "" ?>>可用</option>
					</select>
				</div> <span class="require">* 状态可用时才能正式使用</span></td>
			</tr><tr>
				<td></td>
				<td class="pl5">
					<?php if(($role_id <1 && $user_right['add']) || $user_right['edit']):?>
					<input type="submit" class="input_button4" value=" 提交數據 " />　
					<?php endif;?>
					<input type="button" class="input_button2" value=" 返回列表 " onclick="location.href='<?=BASEURI?>admin/sys/role_list';" />
				</td>
			</tr>
		</table>
		<?php if(($role_id <1 && $user_right['add']) || $user_right['edit']):?>
		</form>
		<?php endif;?>
	</div>
</div>
<script type="text/javascript">
var func_list = $("#txtFuncsID").val().split(';');
$(func_list).each(function(i, item){
	var list = item.split('-');
	if (list.length == 5) {
		var tr = $(".funcitem[value='" + list[4] + "']");
		tr.find(".chkFunc").attr("checked", true);
		for(var i=0; i<5; i++){
			tr.find(".chkOp[name='" + list[i] + "']").attr("checked", true);
		}
	}
});
function checkValue(){
	var funcitem = $(".funcitem"), check = false, funcnames = '', funcids = '', me = null;
	funcitem.each(function(){
		me = $(this);
		check = me.find(".chkFunc").attr("checked") || false;
		if (check) {
			funcnames += me.attr("name") + ";";
			me.find(".chkOp").each(function(){ funcids += (this.checked ? this.name : "") + "-"; });
			funcids += me.attr("value") + ";";
		}
	});
	$("#txtFuncsName").val(funcnames);
	$("#txtFuncsID").val(funcids);
}
$("#chkAll").click(function(){
	$(".gridview input[type='checkbox']:not('#chkAll')").attr("checked", $(this).attr("checked") || false);
	checkValue();
});
$(".chkOp").click(function(){
	var parent = $(this).parent().parent();
	var len = parent.find("input[type='checkbox']:checked").length;
	parent.prev().find("input[type='checkbox']:eq(0)").attr("checked", len == 0 ? false : true);
	checkValue();
});
$(".chkFunc").click(function(){
	$(this).parent().parent().next().find("input[type='checkbox']").attr("checked", $(this).attr("checked") || false);
	checkValue();
});
$("input[type='submit']").click(function(){
	if ($("input[name='txtName']").val() == '') { alert("请输入角色名称！"); $("input[name='txtName']").focus(); return false; };
	if ($("input[name='txtEName']").val() == '') { alert("请输入角色代码！"); $("input[name='txtEName']").focus(); return false; };
	checkValue();
	return true;
});
$(".gridview").treeTable(true);
happi.pro.admin.pageLoad(true);
</script>
