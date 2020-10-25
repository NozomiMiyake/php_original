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
	$result['User'] = $_SESSION['User'];
}


try {
    $user = new User($host, $dbname, $user, $pass);
    $user->connectDB();
    $page_flag = 0;

    // 作ったらほめほめ画面へ遷移
    if(!empty($_GET['recipe_cook'])) {
		$page_flag = 1;
	} 

	// レシピ編集画面へ遷移
	elseif(!empty($_POST['recipe_edit'])) {
		header('Location: /php_original/products/recipe_edit.php');
		exit;
	} 

	// レシピ削除処理
	elseif(!empty($_GET['del'])) {
		if ($_SESSION['User']['role'] != 0) {
			$page_flag = 2;
			$user->DeleteRecipe($_GET['del']);
		}
	} else {

    // 選択したレシピを参照
	$recipe_id = $_POST['recipe_id'];
	$result = $user->FindRecipeOne($recipe_id);
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
				require_once ("recipe_cook.php");
			} elseif ($page_flag === 2) {
				require_once ("recipe_add_complete.php");
			} else {
		?>
		<div class="back">
			<a href="javascript:history.go(-1)">
				<img src="../img/左向きの矢印のアイコン素材のコピー.png">
			</a>
		</div>
		<?php foreach ($result as $value): ?>
		<?php endforeach; ?>
			<p class="main_recipe_name">
				<?=$value['recipe_name'] ?>
			</p>
		
			<div class="main_wrapper clearfix">
				<div class="<?php if(empty($value['recipe_tweet'])){echo"recipe_photo_img_none";} else {echo"recipe_twetter";} ?>">
					<?=$value['recipe_tweet'] ?>
				</div>
				<div class="tweet_under">
					<div class="recipe_tab">
						<div class="<?php if($value['tag1']!=1){ echo("circle_grey"); }else{ echo("circle"); } ?>"></div>
						<p>うまうま</p>
					</div>
					<div class="recipe_tab">
						<div class="<?php if($value['tag2']!=1){ echo("circle_grey"); }else{ echo("circle"); } ?>"></div>
						<p>爆速!</p>
					</div>
					<div class="recipe_tab">
						<div class="<?php if($value['tag3']!=1){ echo("circle_grey"); }else{ echo("circle"); } ?>"></div>
						<p>ていねい</p>
					</div>
					<div class="recipe_edit recipe_cook">
						<a href="?recipe_cook=<?=$value['recipe_id']?>">これをつくる！</a>
					</div>
				</div>
				<div class="recipe_comment">
					<?=$value['comment'] ?>
				</div>
				<?php if($_SESSION['User']['role'] != 0): ?>
				<form action="recipe_edit.php" method="post">
					<input type="hidden" name="recipe_id" value="<?=$value['recipe_id']?>">
					<div class="recipe_edit_menu">
						<div class="recipe_edit">
							<a href="?del=<?=$value['recipe_id']?>" onclick="if(!confirm('「<?=$value['recipe_name']?>」のレシピを削除しますか？')) return false;">削除</a>
						</div>
						<div class="recipe_edit">
							<input type="submit" name="recipe_edit" value="編集">
						</div>
					</div>
				</form>
				<?php endif; ?>
			</div>
		<?php } ?>
		<?php  
			require("nav.php");
		?>
	</div>
</body>
</html>