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

$user_id = $_SESSION['User']['user_id'];

try {
    $user = new User($host, $dbname, $user, $pass);
    $user->connectDB();
    $page_flag = 0;

	// // レシピフォルダ追加処理
	if(!empty($_POST['recipe_add'])) {
		$message = $user->validate($_POST);
		if(empty($message['folder_choice'])) {
			$user->AddRecipe($_POST);
			// print_r($_POST);
			$page_flag = 1;
		}
	}
	
	// 登録しているレシピフォルダ名の参照
    $result = $user->findfolder($_SESSION['User']['user_id']);
	// var_dump($result);

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
	レシピ
</title>
<link rel="stylesheet" type="text/css" href="../css/index.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
</script>
</head>

<body>
	<div id="wrapper">
		<?php 
			if($page_flag === 1) {
				require_once ("recipe_add_complete.php");
			} else {
		?>
		<div class="back">
			<a href="recipe_folder.php">
				<img src="../img/左向きの矢印のアイコン素材のコピー.png">
			</a>
		</div>
		<form action="" method="post">
			<div class="main_wrapper">
				<?php if(isset($message['folder_choice'])) { echo '<p style="color: red;">'.$message['folder_choice'].'</p>'; }?>
				<table>
				<tr>
					<th>○レシピの名前：</th>
					<td class="name">
						<input type="text" name="recipe_name">
					</td>
				</tr>
				<tr>
					<th>
						<p>○保存したいツイート：
							<br>※埋め込み形式で入力してください。
						</p>
						<a href="https://help.twitter.com/ja/using-twitter/how-to-embed-a-tweet">？埋め込み形式の取得方法</a>
					</th>
					<td>
						<textarea name="recipe_tweet"></textarea>
					</td>
				</tr>
				<tr>
					<th>○ひとこと感想：</th>
					<td>
						<input type="hidden" name="tag1" value="0">
						<input type="checkbox" name="tag1" value="1">うまうま
						<input type="hidden" name="tag2" value="0">
						<input type="checkbox" name="tag2" value="1">爆速！
						<input type="hidden" name="tag3" value="0">
						<input type="checkbox" name="tag3" value="1">ていねい
					</td>
				</tr>
				<tr>
					<th>
						<p>○フォルダ選択：
							<br>※5個まで選択可
						</p>
					</th>
					<td>
						<select name="folder1">
							<option value="0" selected="">選択</option>
							<?php foreach ($result as $value): ?>
							<option value="<?=$value['folder_id'] ?>"><?=$value['folder_name'] ?></option>
							<?php endforeach;?>
						</select> 
						<select name="folder2">
							<option value="0" selected="">選択</option>
							<?php foreach ($result as $value): ?>
							<option value="<?=$value['folder_id'] ?>"><?=$value['folder_name'] ?></option>
							<?php endforeach;?>
						</select> 
						<select name="folder3">
							<option value="0" selected="">選択</option>
							<?php foreach ($result as $value): ?>
							<option value="<?=$value['folder_id'] ?>"><?=$value['folder_name'] ?></option>
							<?php endforeach;?>
						</select> 
						<select name="folder4">
							<option value="0" selected="">選択</option>
							<?php foreach ($result as $value): ?>
							<option value="<?=$value['folder_id'] ?>"><?=$value['folder_name'] ?></option>
							<?php endforeach;?>
						</select> 
						<select name="folder5">
							<option value="0" selected="">選択</option>
							<?php foreach ($result as $value): ?>
							<option value="<?=$value['folder_id'] ?>"><?=$value['folder_name'] ?></option>
							<?php endforeach;?>
						</select> 
					</td>
				</tr>
				<tr>
					<th>○コメント：</th>
					<td>
						<textarea name="comment"></textarea>
					</td>
				</tr>
				</table>
				<div class="recipe_edit_menu">
					<div class="recipe_edit">
						<input type="hidden" name="user_id" value="<?=$user_id?>">
						<input type="submit" name="recipe_add" value="登録">
					</div>
				</div>
			</div>
		</form>
		<?php } ?>
		<?php  
			require("nav.php");
		?>
	</div>
</body>
</html>