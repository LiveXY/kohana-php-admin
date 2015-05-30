<div class="content-wrapper">
	<div class="content-operate">
		<span class="title"><img src="<?=RESOURCE?>images/ico/back.gif" class="ico" /> 功能管理：  </span>
		<?php if ($user_right['add']):?><a href="<?=BASEURI?>admin/sys/app_op"><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加应用</a><?php endif;?>
	</div>
	<div class="content-list">
		<?php foreach($funcs as $app):?>
		<table class="gridview">
			<colgroup>
				<col class="_w8" />
				<col class="_w15"  />
				<col />
				<col />
				<col />
				<col class="_w8"  />
				<col class="_w8"  />
				<col class="_w18" />
			</colgroup>
			<tr>
				<th class="pl5" colspan="8">
					<img src="<?=RESOURCE?>images/ico/<?=$app['app_img']?>" class="ico" /> <?=$app['app_name']?>(<?=$app['app_ename']?> - <?=$app['app_status'] == 1 ? "可用":"不可用"?> - <?=$app['app_order']?>)
					<?php if($user_right['edit']):?>
					<a href="<?=BASEURI?>admin/sys/app_op?app_id=<?=$app['app_id']?>"><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" /> 修改应用</a>
					<?php else:?>
					<a href="<?=BASEURI?>admin/sys/app_op?app_id=<?=$app['app_id']?>"><img src="<?=RESOURCE?>images/ico/view.gif" class="ico" /> 查看应用</a>
					<?php endif;?>
					<?php if($user_right['delete']):?>
					<a href="<?=BASEURI?>admin/sys/app_delete?app_id=<?=$app['app_id']?>" onclick="return confirm('删除不可恢复，您确实要删除数据吗？')"><img src="<?=RESOURCE?>images/ico/delete.gif" class="ico" /> 删除应用</a>
					<?php endif;?>
				</th>
			</tr><tr style="height:20px">
				<th class="center">编号</th>
				<th class="center">功能代碼</th>
				<th class="center">功能名称</th>
				<th class="center">功能网址</th>
				<th class="center">图标16x16</th>
				<th class="center">排序</th>
				<th class="center">状态</th>
				<th class="center">操作 <?php if ($user_right['add']):?><a href="<?=BASEURI?>admin/sys/func_op?app_id=<?=$app['app_id']?>"><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加功能</a><?php endif;?></th>
			</tr><?php foreach($app['children'] as $func):?><tr>
				<td class="pl5"><?=$func['func_id']?></td>
				<td class="pl5" nobr><?=$func['func_ename']?></td>
				<td class="pl5" nobr><img src="<?=RESOURCE?>images/ico/<?=$func['func_img']?>" class="ico" /> <?=$func['func_name']?></td>
				<td class="pl5" nobr><?=$func['func_url']?></td>
				<td class="pl5" nobr><?=$func['func_img']?></td>
				<td class="pl5" nobr><?=$func['func_order']?></td>
				<td class="pl5" nobr><?=$func['func_status'] == 1 ? "可用":"不可用"?></td>
				<td class="center">
					<?php if($user_right['edit']):?>
					<a href="<?=BASEURI?>admin/sys/func_op?func_id=<?=$func['func_id']?>"><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" /> 修改功能</a>
					<?php else:?>
					<a href="<?=BASEURI?>admin/sys/func_op?func_id=<?=$func['func_id']?>"><img src="<?=RESOURCE?>images/ico/view.gif" class="ico" /> 查看功能</a>
					<?php endif;?>
					<?php if($user_right['delete']):?>
					<a href="<?=BASEURI?>admin/sys/func_delete?func_id=<?=$func['func_id']?>" onclick="return confirm('删除不可恢复，您确实要删除数据吗？')"><img src="<?=RESOURCE?>images/ico/delete.gif" class="ico" /> 删除功能</a>
					<?php endif;?>
				</td>
			</tr><?php endforeach;?>
		</table><br />
		<?php endforeach;?>
		<?php foreach($napps as $app):?>
		<table class="gridview">
			<colgroup>
				<col class="_w8" />
				<col class="_w15"  />
				<col />
				<col />
				<col />
				<col class="_w8"  />
				<col class="_w8"  />
				<col class="_w18" />
			</colgroup>
			<tr>
				<th class="pl5" colspan="8">
					<?=$app->app_name?>(<?=$app->app_ename?> - <?=$app->status == 1 ? "可用":"不可用"?>)
					<?php if($user_right['edit']):?>
					<a href="<?=BASEURI?>admin/sys/app_op?app_id=<?=$app->app_id?>"><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" /> 修改应用</a>
					<?php else:?>
					<a href="<?=BASEURI?>admin/sys/app_op?app_id=<?=$app->app_id?>"><img src="<?=RESOURCE?>images/ico/view.gif" class="ico" /> 查看应用</a>
					<?php endif;?>
					<?php if($user_right['delete']):?>
					<a href="<?=BASEURI?>admin/sys/app_delete?app_id=<?=$app->app_id?>" onclick="return confirm('删除不可恢复，您确实要删除数据吗？')"><img src="<?=RESOURCE?>images/ico/delete.gif" class="ico" /> 删除应用</a>
					<?php endif;?>
				</th>
			</tr><tr style="height:20px">
				<th class="center">编号</th>
				<th class="center">功能代碼</th>
				<th class="center">功能名称</th>
				<th class="center">功能网址</th>
				<th class="center">图标16x16</th>
				<th class="center">排序</th>
				<th class="center">状态</th>
				<th class="center">操作 <?php if ($user_right['add']):?><a href="<?=BASEURI?>admin/sys/func_op?app_id=<?=$app->app_id?>"><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加功能</a><?php endif;?></th>
			</tr><tr><td class="center" colspan="8">暂无功能  <?php if ($user_right['add']):?><a href="<?=BASEURI?>admin/sys/func_op?app_id=<?=$app->app_id?>"><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加功能</a><?php endif;?></td></tr>
		</table><br />
		<?php endforeach;?>
	</div>
</div>