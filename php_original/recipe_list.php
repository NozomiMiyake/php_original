<?php

// ログイン画面を経由しているかどうか確認する
// if(!isset($_SESSION['User'])) {
// 	header('Location: /php_original/products/entry.php');
// 	exit;
// }

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

	if (!empty($_POST)) {
    	// レシピ画面に遷移
	    if(!empty($_POST['recipe_page'])) {
			header('Location: /php_original/products/recipe.php');
			exit;
		}

	    // 選択したフォルダのレシピを参照
		$folder_id = $_POST['folder_id'];
		$result = $user->FindRecipe($folder_id);
		// print_r($result);

	// teg1が選択されているレシピのみ参照
    } elseif (isset($_GET['tag1'])) {
    	$result = $user->FindRecipeTag1($user_id);
    } elseif (isset($_GET['tag2'])) {
    	$result = $user->FindRecipeTag2($user_id);
    } elseif (isset($_GET['tag3'])) {
    	$result = $user->FindRecipeTag3($user_id);
    	// print_r($result);
    
	} else {
    	// 全レシピ参照
    	$result = $user->FindRecipeAll($user_id);
    	// print_r($result);
    }

} 
catch (PDOException $e) {
	print "接続失敗: " . $e->getMessage();

}

// exit;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>
	レシピ一覧
</title>
<link rel="stylesheet" type="text/css" href="../css/index.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">

</script>
</head>

<body>
	<div id="wrapper">
		<div class="main_wrapper">
			<?php foreach ($result as $value): ?>
			<form action="recipe.php" method="post">
				<div class="recipe_list">
					<div class="list_photo">
						<div class="<?php if(empty($value['recipe_tweet'])){echo"list_photo_img_none";} else {echo"list_photo_img";} ?>">
							<?=$value['recipe_tweet'] ?>
						</div>
						<div class="list_photo_circle"></div>
					</div>
					<div class="list_contents">
						<div class="list_name"><?=$value['recipe_name'] ?></div>
						<p class="list_comment"><?=$value['comment'] ?></p>
						<input type="hidden" name="recipe_id" value="<?=$value['recipe_id'] ?>">
						<input type="submit" name="recipe_page" value="もっと見る">
					</div>
				</div>
			</form>
			<?php endforeach; ?>
			<div class="plus">
				<a href="recipe_add.php">
					<img src="../img/プラスのアイコン素材中.png">
				</a>
			</div>
		</div>
		<form action="" method="post">
			<ul id="list_tab">
				<li>
					<a href="?tag1" class="tab">うまうま</a>
				</li>
				<li>
					<a href="?tag2" class="tab">爆速！</a>
				</li>
				<li>
					<a href="?tag3" class="tab">ていねい</a>
				</li>
			</ul>
		</form>

		<?php  
			require("nav.php");
		?>
	</div>
</body>
</html>
