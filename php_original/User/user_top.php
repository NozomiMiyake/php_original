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

// 一般ユーザーか閲覧ユーザーか
if ($_SESSION['User']['role'] == 0) {
	$user_id = $_SESSION['User']['user_id'];
	$user_id = 3;
	$message = "サンプルページです";
} else {
	$user_id = $_SESSION['User']['user_id'];
	// echo "$user_id";
}


try {
    $user = new User($host, $dbname, $user, $pass);
    $user->connectDB();

	// ユーザー削除処理
	if(!empty($_GET['del'])) {
		if ($_SESSION['User']['role'] != 0) {
			$user->DeleteUser($_GET['del']);
			header('Location: /php_original/products/user_delete.php');
			exit;
		}
	// google連携画面へ遷移
	} elseif(!empty($_POST['google'])) {
		// セッション情報を破棄する
		$_SESSION = array();
		session_destroy();
		header('Location: /php_original/products/google_login.php');
		exit;
	// ユーザー編集処理
	// } elseif (!empty($_GET['edit'])) {
	// 	$page_flag = 2;
	} else {
	// ユーザー参照
		if ($_SESSION['User']['role'] != 0) {
			$page_flag = 1;
			// ユーザー参照
			$user_id = $_SESSION['User']['user_id'];
			$result = $user->FindUser($user_id);
			// print_r($result);
		} else {
			$page_flag = 0;
		}
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
			<?php 
				if($page_flag === 0) {
					// 表示、選択画面
			?>
			<!-- お試しユーザー画面 -->
			<div class="role0">
				<p>※※あなたはお試しユーザーです※※</p>
				<p>本登録をすると、レシピの管理をすることができます。</p>
				<a href="user_edit.php">本登録をする</a>
			</div>
			<!-- 本登録ユーザー画面 -->
			<?php 
				} elseif($page_flag === 1) {
					// 表示、選択画面
			?>
			<?php foreach ($result as $value): ?>
			<?php endforeach; 
				// print_r($result);
			?>
			<div class="user_top">
			<table>
				<tr>
					<th>
						<a href="?logout=">ログアウト</a>
					</th>
				</tr>
				<tr>
					<th>名前</th>
					<td><?=$value['user_name'] ?></td>
				</tr>
				<tr>
					<th>メールアドレス</th>
					<td><?=$value['email'] ?></td>
				</tr>
				<tr>
					<th>パスワード</th>
					<td>
						<?php if(!empty($value['password'])){
							echo $value['password'];
						} else {
							echo "設定なし";
						} ?>
					</td>
				</tr>
				<form action="" method="post">
				<tr>
					<th>Google連携</th>
					<td>
						<?php if(!empty($value['google_id'])){
							echo "連携あり";
						} else { ?>
							
							<input type="submit" name="google" value="google連携する">
						<?php } ?>
					</td>
				</tr>
				</form>
			</table>
			</div>
			<form action="" method="post">
				<input type="hidden" name="recipe_id" value="<?=$value['recipe_id']?>">
				<div class="recipe_edit_menu">
					<div class="recipe_edit">
						<a href="?del=<?=$value['user_id']?>" onclick="if(!confirm('本当に削除しますか？※※これまでのデータは全て削除されます※※')) return false;">ｱｶｳﾝﾄ削除</a>
					</div>
					<div class="recipe_edit">
						<a href="user_edit.php">編集</a>
					</div>
				</div>
			</form>
		</div>
	<?php } ?>
		<?php  
			require("../products/nav.php");
		?>
	</div>
</body>
</html>