<?php if (!Util::isMobile()) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript"> if (self != top) { top.location = "<?=BASEURI?>admin/login/index"; }; </script>
<title>管理员登陆</title>
<style type="text/css">
body { font-size:13px; }
.login_main { margin:180px auto; width:380px; border:2px solid #E4E4E4; border-radius:3px; }
.login_title { padding:8px; background:#E4E4E4 }
.login_box { padding:25px 8px; }
.login_msg { color:red; text-align:center }
.login_box table { margin:0 auto; }
.login_box th { text-align:right; }
.login_box th, .login_box td{ padding:3px; }
</style>
</head>
<body>
<div class="login_main">
	<div class="login_title"><strong>管理员登陆</strong></div>
	<div class="login_box">
		<form method="post" action="<?=BASEURI?>admin/login/post">
			<?php if(isset($msg)):?><div class="login_msg"><?=$msg?></div><?php endif;?>
			<table>
				<tr>
					<th>用户名：</th>
					<td><input type="text" name="username" style='width:150px' value="admin" /></td>
					<td></td>
				</tr><tr>
					<th>密码：</th>
					<td><input type="password" name="password" style='width:150px' value="admin" /></td>
					<td></td>
				</tr><tr style="display:none">
					<th>验证码：</th>
					<td><input type="text" name="captcha" style='width:150px' /></td>
					<td></td>
				</tr><tr style="display:none">
					<th></th>
					<td><img src="<?=BASEURI?>captcha" onclick="this.src='<?=BASEURI?>captcha?'+Math.random()" style="cursor:pointer" /></td>
					<td></td>
				</tr><tr>
					<th></th>
					<td><input type="submit" value=" 登  陆 " />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/">‹‹首页</a></td>
					<td></td>
				</tr>
			</table>
		</form>
	</div>
</div>
</body>
</html>
<?php } else { ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>管理员登陆</title>
<style type="text/css">
body,* { font-size:14px; padding:0; margin:0; }
.login_main { margin:0px auto; }
.login_title { padding:8px; text-align:center; background:#E4E4E4 }
.login_msg { color:red; text-align:center;padding:10px; }
.login_box table { width:96%; }
.login_box td{ padding:3px; text-align:center; }
.input { width:100%; padding:5px; }
.button { height:40px; display:block; }
</style>
</head>
<body>
<div class="login_main">
	<div class="login_title"><strong>管理员登陆</strong></div>
	<div class="login_box">
		<form method="post" action="<?=BASEURI?>admin/login/post">
			<?php if(isset($msg)):?><div class="login_msg"><?=$msg?></div><?php endif;?>
			<table>
				<tr>
					<td><input type="text" class="input" name="username" placeholder="请输入用户名" /></td>
				</tr><tr>
					<td><input type="password" class="input" name="password" placeholder="请输入密码" /></td>
				</tr><tr style="display:none">
					<td><input type="text" class="input" name="captcha" placeholder="请输入验证码" /></td>
				</tr><tr style="display:none">
					<td><img src="<?=BASEURI?>captcha" onclick="this.src='<?=BASEURI?>captcha?'+Math.random()" style="cursor:pointer" /></td>
				</tr><tr>
					<td><input type="submit" class="input button" value=" 登  陆 " /></td>
				</tr>
			</table>
		</form>
	</div>
</div>
</body>
</html>
<?php } ?>