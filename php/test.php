
<?php

 

	if (isset($_REQUEST['authcode'])) {

		session_start();
		$id=$_SESSION['user_id'];
 

		if (strtolower($_REQUEST['authcode'])==$_SESSION['authcode']) {
			$temp = $info['id'];

			echo'<font color ="#0000CC"> 输出正确</font>';

			echo "<script>alert('登录成功!');window.location.href='../html/index.html?id=$temp'</script>";

		}else{

			echo $_REQUEST['authcode'];

			echo $_SESSION['authcode'];

			echo'<font color ="#CC0000"> 输出错误</font>';

		}

 

		exit();

 

	}

?>

 

<!DOCTYPE html>

<html>

	<head>

		<meta charset="utf-8" />

		<title>确认验证码</title>

	</head>

	<body>

		<form method="post" action="test.php">

			<p>验证码图片：

				<img id="captcha_img"  src="../resources/image1.png" alt="验证码" width="100" height="30">
				<a href="javascript:void(0)" onclick="change()">换一个?</a>

			</p>
			<p>请输入图片中的内容：

				<input type="text" name="authcode" value="" />

			</p>
			<p>

				<input type="submit" value="提交" style="padding: 6px 20px;">

			</p>
		</form>
		<script>
			window.onload=function change(){
			window.location.href="./captcha.php";
			}
			</script>
	</body>
</html>
