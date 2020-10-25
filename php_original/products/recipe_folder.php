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
// print_r($_SESSION);

// 一般ユーザーの場合、どうするか考え中
if ($_SESSION['User']['role'] == 0) {
	$user_id = $_SESSION['User']['user_id'];
	$user_id = 3;
	$message = "サンプルページです";
} else {
	$user_id = $_SESSION['User']['user_id'];
}


try {
    $user = new User($host, $dbname, $user, $pass);
    $user->connectDB();
    $page_flag = 0;
    $edit_flag = 0;

	// レシピフォルダ編集画面へ
	if(!empty($_POST['folder_plus'])) {
		$page_flag = 1;
	} elseif (!empty($_POST['new_folder_name'])) {
		$page_flag = 1;
		$edit_flag = 1;
	}

	// レシピフォルダ追加処理
	if(!empty($_POST['folder_add'])) {
		$message = $user->validate($_POST);
		if(empty($message['folder_name'])) {
			$user->AddFolder($_POST);
		}
	}
	// レシピフォルダ編集処理
	if(!empty($_POST['folder_edit'])) {
		$message = $user->validate($_POST);
		if(empty($message['folder_name'])) {
			$user->EditFolder($_POST);
		}
	}

	// レシピ一覧画面へ遷移
	if(!empty($_POST['recipe_list'])) {
		header('Location: /php_original/products/recipe_list_nomal.php');
		exit;
	}

    // 登録しているレシピフォルダ名の参照
    $result = $user->FindFolder($user_id);
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
	<div class="wrapper">
		<?php 
			if($page_flag === 1) {
				require_once ("recipe_folder_edit.php");
			} else {
		?>
		<div class="main_wrapper">
			<div class="demo">
				<?php if($_SESSION['User']['role'] == 0) { echo '<p style="color: red;">'.$message.'</p>'; } ?>
			</div>
			<div class="folder_wrapper">
				<?php foreach ($result as $value): ?>
				<form action="recipe_list_nomal.php" method="post">
					<div class="folder">
						<input type="hidden" name="folder_id" value="<?=$value['folder_id'] ?>">
						<input type="submit" name="recipe_list" value="<?=$value['folder_name'] ?>">
					</div>
				</form>
				<?php endforeach; ?>
				<form action="" method="post">
					<div class="folder_plus">
						<input type="submit" name="folder_plus" value="フォルダ編集">
					</div>
				</form>	
			</div>	
		</div>
		<?php } ?>
		<?php  
			require("nav.php");
		?>
	</div>
</body>
</html>