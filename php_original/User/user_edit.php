<?php
session_start();

require_once ("../config/config.php");
require_once ("../model/User.php");

// ログアウト処理
if(isset($_GET['logout'])) {
	// セッション情報を破棄する
	$_SESSION = array();
	session_destroy();
}

// ログイン画面を経由しているかどうか確認する
if(!isset($_SESSION['User'])) {
	header('Location: /php_original/products/entry.php');
	exit;
}

try {
    $user = new User($host, $dbname, $user, $pass);
    $user->connectDB();

    // ユーザー編集
    if (isset($_POST['user_edit_complete'])) {
    	$user->EditUser($_POST);
    	header('Location: /php_original/User/user_top.php');
		exit;
    } else {

	// ユーザー参照
	$user_id = $_SESSION['User']['user_id'];
	$result = $user->FindUser($user_id);
	// print_r($result);
	}

} 
catch (PDOException $e) {
	print "接続失敗: " . $e->getMessage();

}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>
	マイページ
</title>
<link rel="stylesheet" type="text/css" href="../css/index.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">

</script>
</head>

<body>
	<div class="wrapper">
		<div class="main_wrapper">
		<?php foreach ($result as $value): ?>
		<?php endforeach; 
			// print_r($result);
		?>
			<?php if ($value['role']==0) { ?>
				<form action="../products/entry.php" method="post">
				<div>
					<input type="hidden" name="user_id" value="<?=$value['user_id']?>">
					<input type="hidden" name="user_name" value="<?=$value['user_name']?>">
					<input type="hidden" name="email" value="<?=$value['email']?>">
					<input type="hidden" name="password" value="<?=$value['password']?>">
					<input type="hidden" name="role" value="<?=$value['role']?>">
					<input type="submit" name="google" value="google連携する">
				</div>
				</form>
			<?php } ?>
		<form action="" method="post">
			<table>
			<tr>
				<th>名前</th>
				<td>
					<input type="text" name="user_name" value="<?=$value['user_name']?>">
				</td>
			</tr>
			<tr>
				<th>メールアドレス</th>
				<td>
					<input type="text" name="email" value="<?=$value['email']?>">
				</td>
			</tr>
			<tr>
				<th>パスワード</th>
				<td>
					<input type="text" name="password" value="<?=$value['password']?>">
				</td>
			</tr>
			</table>
			<div class="recipe_edit_menu">
				<div class="recipe_edit">
					<input type="hidden" name="user_id" value="<?=$value['user_id']?>">
					<input type="hidden" name="role" value="<?=$value['role']?>">
					<input type="submit" name="user_edit_complete" value="登録">
				</div>
			</div>
		</form>
		</div>
		<?php  
			require("../products/nav.php");
		?>
	</div>
</body>
</html>