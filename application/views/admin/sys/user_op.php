<script type="text/javascript" src="<?=BASEURI?>client/js/jquery.uploadify.min.js"></script>
<div class="content-wrapper">
	<div class="content-operate">
		<span class="title"><?php if ($uid > 0) { ?><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" />  修改<?php echo $user ? ' '.$user->nickname.' ' : "";} else { ?><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加<?php } ?>用户</span>
	</div>
	<div class="content-list">
		<?php if(($uid <1 && $user_right['add']) || $user_right['edit']):?>
		<form method="post" name="data" action="<?=BASEURI?>admin/sys/user_post?uid=<?= $uid ?>">
		<?php endif;?>
		<table class="table-form">
			<colgroup>
				<col width="150px" />
				<col />
				<col width="150px" />
				<col />
			</colgroup>
			<?php if ($uid < 1) {?><tr>
				<th class="t">账号：</th>
				<td class="pl5 t" colspan="3"><input type="text" class="input_text" name="user[username]" value="" /></td>
			</tr><?php } else {?><tr>
				<th class="t">账号：</th>
				<td class="pl5 t" colspan="3"><?= $user ? $user->username : "" ?></td>
			</tr><?php }?><tr>
				<th>邮箱：</th>
				<td class="pl5" colspan="3"><input type="text" class="input_text" name="user[email]" value="<?= $user ? $user->email : "" ?>" /></td>
			</tr><tr>
				<th>密码：</th>
				<td class="pl5" colspan="3"><input type="text" class="input_text" name="password" value="" /><?php if ($uid >0) {?> <span class="red">* 为空时不修改密码</span><?php }?></td>
			</tr><tr>
				<th>昵称：</th>
				<td class="pl5" colspan="3"><input type="text" class="input_text" name="user[nickname]" value="<?= $user ? $user->nickname : "" ?>" /> </td>
			</tr><tr>
				<th>性别：</th>
				<td class="pl5" colspan="3"><input type="text" class="input_text" name="user[gender]" value="<?= $user ? $user->gender : "" ?>" /> </td>
			</tr><tr>
				<th>手机：</th>
				<td class="pl5" colspan="3"><input type="text" class="input_text" name="user[tel]" value="<?= $user ? $user->tel : "" ?>" /> </td>
			</tr><tr>
				<th>生日：</th>
				<td class="pl5" colspan="3"><input type="text" class="input_text" name="user[birthday]" value="<?= $user ? $user->birthday : "" ?>" /> </td>
			</tr><tr>
				<th>用户类型：</th>
				<td class="pl5" colspan="3"><input type="text" class="input_text" name="user[utype]" value="<?= $user ? $user->utype : "" ?>" /> </td>
			</tr><tr>
				<th>语言：</th>
				<td class="pl5" colspan="3"><input type="text" class="input_text" name="user[locale]" value="<?= $user ? $user->locale : "" ?>" /> </td>
			</tr><tr>
				<td></td>
				<td class="pl5">
					<?php if(($uid <1 && $user_right['add']) || $user_right['edit']):?>
					<input type="submit" class="input_button4" value=" 提交數據 " />　
					<?php endif;?>
				</td>
				<td></td>
				<td></td>
			</tr><?php if ($uid > 0) {?><tr>
				<th>注册ip：</th>
				<td class="pl5"><?= $user ? $user->reg_ip : "" ?></td>
				<th>注册时间：</th>
				<td class="pl5"><?= $user && $user->reg_date != 0 ? date('Y-m-d h:i:s', $user->reg_date) : "" ?></td>
			</tr><tr>
				<th>登录ip：</th>
				<td class="pl5"><?= $user ? $user->login_ip : "" ?></td>
				<th>登陆时间：</th>
				<td class="pl5"><?= $user && $user->login_date != 0 ? date('Y-m-d h:i:s', $user->login_date) : "" ?></td>
			</tr><tr>
				<th>登录次数：</th>
				<td class="pl5"><?= $user ? $user->login_times : "" ?></td>
				<th>在线时长：</th>
				<td class="pl5"><?= $user ? $user->online_times : "" ?></td>
			</tr><tr>
				<th>区域：</th>
				<td class="pl5"><?= $user ? $user->regarea : "" ?> <?= $user ? $user->regcity : "" ?></td>
				<th>在线：</th>
				<td class="pl5"><?= $user ? $user->online : "" ?></td>
			</tr><?php } ?>
		</table>
		<?php if(($uid <1 && $user_right['add']) || $user_right['edit']):?>
		</form>
		<?php endif;?>
	</div>
</div>
<script type="text/javascript">
happi.pro.admin.pageLoad();
</script>
