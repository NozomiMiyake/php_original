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
    $page_flag = 0;

    // レシピ編集処理
    if (!empty($_POST['recipe_edit_complete'])) {
    	$message = $user->validate($_POST);
    	// print_r($_POST);
		if(empty($message['folder_choice'])) {
			$user->EditRecipe($_POST);
			$page_flag = 1;
		}
    }

    // フォルダの情報を参照
    $folder_result = $user->findfolder($_SESSION['User']['user_id']);
    
    // 選択したレシピを参照
	$recipe_id = $_POST['recipe_id'];
	$result = $user->FindRecipeOne($recipe_id);

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
	<div class="wrapper">
		<?php if(isset($message['folder_choice'])) { echo '<p style="color: red;">'.$message['folder_choice'].'</p>'; }?>
		<div class="main_wrapper">
			<?php 
				if($page_flag === 1) {
					require_once ("recipe_add_complete.php");
				} else {
			?>
			<?php foreach ($result as $value): ?>
			<?php endforeach; ?>
			<form action="" method="post">
				<?php if(isset($message['folder_choice'])) { echo '<p style="color: red;">'.$message['folder_choice'].'</p>'; }?>
				<table>
				<tr>
					<th>○レシピの名前：</th>
					<td class="name">
						<input type="text" name="recipe_name" value="<?=$value['recipe_name'] ?>">
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
						<textarea name="recipe_tweet"><?=$value['recipe_tweet'] ?></textarea>
					</td>
				</tr>
				<tr>
					<th>○ひとこと感想：</th>
					<td>
						<input type="hidden" name="tag1" value="0">
						<input type="checkbox" name="tag1" value="<?=$value['tag1']?>"  <?php if($value['tag1'] == 1){ echo "checked"; } ?>>うまうま
						<input type="hidden" name="tag2" value="0">
						<input type="checkbox" name="tag2" value="<?=$value['tag2'] ?>" <?php if($value['tag2'] == 1){ echo "checked"; } ?>>爆速！
						<input type="hidden" name="tag3" value="0">
						<input type="checkbox" name="tag3" value="<?=$value['tag3'] ?>" <?php if($value['tag3'] == 1){ echo "checked"; } ?>>ていねい
					</td>
				</tr>
				<tr>
					<th>
						<p>○フォルダ選択：
							<br>※5個まで選択可
						</p>
					</th>
					<td>
						<p>今の選択フォルダ</p>
						<select name="folder1">
							<option value="<?=$value['folder1'] ?>">
								<?php if ($value['folder1'] != 0) {
									echo $value['folder_name'];
								} else {
									echo "選択";
								}?>
							</option>
							<?php foreach ($folder_result as $row): ?>
							<option value="<?=$row['folder_id'] ?>">
								<?=$row['folder_name'] ?>
							</option>
							<?php endforeach;?>
						</select> 
						<select name="folder2">
							<option value="<?=$value['folder2'] ?>">
								<?php if ($value['folder2'] != 0) {
									echo $value['folder_name'];
								} else {
									echo "選択";
								}?>
							</option>
							<?php foreach ($folder_result as $row): ?>
							<option value="<?=$row['folder_id'] ?>">
								<?=$row['folder_name'] ?>
							</option>
							<?php endforeach;?>
						</select> 
						<select name="folder3">
							<option value="<?=$value['folder3'] ?>">
								<?php if ($value['folder3'] != 0) {
									echo $value['folder_name'];
								} else {
									echo "選択";
								}?>
							</option>
							<?php foreach ($folder_result as $row): ?>
							<option value="<?=$row['folder_id'] ?>">
								<?=$row['folder_name'] ?>
							</option>
							<?php endforeach;?>
						</select> 
						<select name="folder4">
							<option value="<?=$value['folder4'] ?>">
								<?php if ($value['folder4'] != 0) {
									echo $value['folder_name'];
								} else {
									echo "選択";
								}?>
							</option>
							<?php foreach ($folder_result as $row): ?>
							<option value="<?=$row['folder_id'] ?>">
								<?=$row['folder_name'] ?>
							</option>
							<?php endforeach;?>
						</select> 
						<select name="folder5">
							<option value="<?=$value['folder5'] ?>">
								<?php if ($value['folder5'] != 0) {
									echo $value['folder_name'];
								} else {
									echo "選択";
								}?>
							</option>
							<?php foreach ($folder_result as $row): ?>
							<option value="<?=$row['folder_id'] ?>">
								<?=$row['folder_name'] ?>
							</option>
							<?php endforeach;?>
						</select> 
					</td>
				</tr>
				<tr>
					<th>○コメント：</th>
					<td>
						<textarea name="comment"><?=$value['comment'] ?></textarea>
					</td>
				</tr>
				</table>
				<div class="recipe_edit_menu">
					<div class="recipe_edit">
						<input type="hidden" name="recipe_id" value="<?=$recipe_id?>">
						<input type="submit" name="recipe_edit_complete" value="登録">
					</div>
				</div>
			</form>
			<?php } ?>
		</div>
		<?php  
			require("nav.php");
		?>
	</div>
</body>
</html>