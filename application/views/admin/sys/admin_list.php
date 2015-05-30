<?php $last_role = ''; ?>
<div class="content-wrapper">
	<div class="content-operate">
		<span class="title"><img src="<?=RESOURCE?>images/ico/back.gif" class="ico" /> 管理员管理：</span>
	</div>
	<div class="content-list">
		<table class="gridview autowidth">
			<tr>
				<th class="center">账号</th>
				<th class="center">邮箱</th>
				<th class="center">角色权限</th>
				<th class="center">添加时间</th>
				<th class="center">管理 <?php if ($user_right['add']):?><a href="<?=BASEURI?>admin/sys/admin_op"><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加管理员</a><?php endif;?></th>
			</tr>
			<?php if(count($admins)==0):?>
			<tr><td colspan="5" class="center">暂无数据！ <?php if ($user_right['add']):?><a href="<?=BASEURI?>admin/sys/admin_op"><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加管理员</a><?php endif;?></td></tr>
			<?php endif?>
			<?php foreach($admins as $admin):?>
			<?php if ($last_role != $admin->role_name) {?>
			<?php 		$last_role = $admin->role_name; ?>
			<tr class="tree_table_node"><td colspan="6" class="item pl25"><?=$last_role ?></td></tr>
			<?php }?>
			<tr class="tree_table_item">
				<td class="item pl25"><a href="#" class="userControl" uid="<?=$admin->user_id?>"></a></td>
				<td class="pl5"><?=$admin->email?></td>
				<td class="pl5 tdnofix" title="<?=$admin->role_funcnames?>"><?=$admin->role_funcnames?></td>
				<td class="center"><?=date('Y-m-d H:i', $admin->reg_date)?></td>
				<td class="center">
					<?php if($user_right['edit']):?>
					<a href="<?=BASEURI?>admin/sys/admin_op?user_id=<?=$admin->user_id ?>"><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" /> 修改管理员</a>
					<?php else:?>
					<a href="<?=BASEURI?>admin/sys/admin_op?user_id=<?=$admin->user_id ?>"><img src="<?=RESOURCE?>images/ico/view.gif" class="ico" /> 查看管理员</a>
					<?php endif;?>
					<?php if($user_right['delete']):?>
					<a href="<?=BASEURI?>admin/sys/admin_delete?user_id=<?=$admin->user_id ?>" onclick="return confirm('删除不可恢复，您确实要删除数据吗？')"><img src="<?=RESOURCE?>images/ico/delete.gif" class="ico" /> 删除管理员</a>
					<?php endif;?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
	</div>
</div>
<script type="text/javascript">
happi.pro.admin.pageLoad();
$(".gridview").treeTable(true);
</script>