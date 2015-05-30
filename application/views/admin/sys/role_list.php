<div class="content-wrapper">
	<div class="content-operate">
		<span class="title"><img src="<?=RESOURCE?>images/ico/back.gif" class="ico" /> 角色管理：</span>
	</div>
	<div class="content-list">
		<table class="gridview nofix">
			<colgroup>
				<col class="_w8" />
				<col class="_w15" />
				<col class="_w15" />
				<col />
				<col class="_w8" />
				<col class="_w18" />
			</colgroup>
			<tr>
				<th class="center">ID</th>
				<th class="center">角色名称</th>
				<th class="center">角色代码</th>
				<th class="center">权限</th>
				<th class="center">状态</th>
				<th class="center">管理 <?php if ($user_right['add']):?><a href="<?=BASEURI?>admin/sys/role_op"><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加角色</a><?php endif?></th>
			</tr>
			<?php if(count($roles)==0):?>
			<tr><td colspan="5" class="center">暂无数据！ <?php if ($user_right['add']):?><a href="<?=BASEURI?>admin/game/op"><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加角色</a><?php endif?></td></tr>
			<?php endif?>
			<?php foreach($roles as $role):?>
			<tr>
				<td class="center"><?=$role->role_id?></td>
				<td class="center"><?=$role->role_name?></td>
				<td class="center"><?=$role->role_ename?></td>
				<td class="pl5" title="<?=$role->role_funcnames?>"><?=$role->role_funcnames?></td>
				<td class="center"><?=$role->status == 1 ? "可用":"不可用"?></td>
				<td class="center">
					<?php if($user_right['edit']):?>
					<a href="<?=BASEURI?>admin/sys/role_op?role_id=<?=$role->role_id ?>"><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" /> 修改角色</a>
					<?php else:?>
					<a href="<?=BASEURI?>admin/sys/role_op?role_id=<?=$role->role_id ?>"><img src="<?=RESOURCE?>images/ico/view.gif" class="ico" /> 查看角色</a>
					<?php endif;?>
					<?php if($user_right['delete']):?>
					<a href="<?=BASEURI?>admin/sys/role_delete?role_id=<?=$role->role_id ?>" onclick="return confirm('删除不可恢复，您确实要删除数据吗？')"><img src="<?=RESOURCE?>images/ico/delete.gif" class="ico" /> 删除角色</a>
					<?php endif;?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
	</div>
</div>
<script type="text/javascript"> happi.pro.admin.pageLoad(true); </script>