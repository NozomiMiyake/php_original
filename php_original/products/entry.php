<?php
session_start();

require_once ("../config/config.php");
require_once ("../model/User.php");


try {
	$user = new User($host, $dbname, $user, $pass);
	$user->connectDB();
	$page_flag = 0;

 	// マイページからの遷移
 	if (!empty($_POST['user_id'])) {
 		$page_flag = 2;
 		// セッション情報を破棄する
		$_SESSION = array();
		session_destroy();
 	}
	// 登録処理
	if(!empty($_POST['entry0'])) {
		$message = $user->validate($_POST);
		if(empty($message['user_name'])) {
			$user->add0($_POST);
			$page_flag = 1;
			$entry_complete = "登録が完了しました！ログインしてください。パスワードは「demo」です。";
		} 
	} elseif(!empty($_POST['entry1'])) {
		$message = $user->validate($_POST);
		if(empty($message['user_name'])) {
			$user->add1($_POST);
			$page_flag = 1;
			$entry_complete = "登録が完了しました！ログインしてください。";
		} 
	}

	// ログイン
    elseif(!empty($_POST['login'])) {
    	$result = $user->login($_POST);
    	if(!empty($result)) {
    		$_SESSION['User'] = $result;
    		header('location: /php_original/products/recipe_folder.php');
    		exit;

    	} else {
    		$page_flag = 1;
    		$not_login = "ログインできませんでした。パスワードとニックネームをご確認ください。";
    	}
	}
	// google認証 
	elseif(!empty($_POST['google_login'])) {

		define('CLIENT_ID', '840134749710-eh96ep3qhe9e7lcqjh0elnmebh9rcflu.apps.googleusercontent.com');
		define('CALLBACK', 'http://localhost/php_original/products/recipe_list.php');
		$baseURL = 'https://accounts.google.com/o/oauth2/auth?';
		$scope = array(
			'https://www.googleapis.com/auth/userinfo.profile', // 基本情報(名前とか画像とか)
			'https://www.googleapis.com/auth/userinfo.email',   // メールアドレス
			);
		// 認証用URL生成
		$authURL = $baseURL . 'scope=' . urlencode(implode(' ', $scope)) .
			'&redirect_uri=' . urlencode(CALLBACK) .
			'&response_type=code' .
			'&client_id=' . CLIENT_ID;
		// Redirect
		header("Location: " . $authURL); 
		
	}

	// ログイン画面へ
	if(!empty($_POST['login_page'])) {
		$page_flag = 1;
	}
}
catch (PDOException $e) {
	print "接続失敗: ".$e->getMessage();
}
	
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>
	ログイン
</title>
<link rel="stylesheet" type="text/css" href="../css/index.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">

</script>
</head>

<body>
	<?php 
		if($page_flag === 1) {
			require_once ("login.php");
		} elseif($page_flag === 2) {
			require_once ("login_user.php");
		} else { 
	?>
	<div class="wrapper">
		<div class="main_wrapper">
			<div class="app_title">
				<p>レシピスクラップ</p>
			</div>
			<?php if(isset($message['user_name'])) { echo '<p style="color: red;">'.$message['user_name'].'</p>'; }?>
			<form action="" method="post">
				<div class="entry_form">
					<p class="entry_form_title">おためし登録</p>
					<p>1. 好きなニックネームを入力してください。</p>
					<input type="text" name="user_name">
				</div>
				<div class="entry">
					<input type="submit" name="entry0" value="登録">
				</div>
			</form>
			<form action="" method="post">
				<div class="entry_form">
					<p class="entry_form_title">本登録</p>
					<div class="entry_list">
						<input type="submit" name="google_login" value="Googleでログインする">
					</div>
					<div class="entry">
						<div class="entry_list">
							<p>連携せずに登録する</p>
						</div>
						<div class="entry_list">
							<p>1. 好きなニックネームを入力してください。</p>
							<input type="text" name="user_name">
						</div>
						<div class="entry_list">
							<p>2. emailを入力してください。</p>
							<input type="text" name="email">
						</div>
						<div class="entry_list">
							<p>3. パスワードを入力してください。</p>
							<input type="text" name="password">
						</div>
						<input type="submit" name="entry1" value="登録">
					</div>
				</div>
				<p>↓ユーザーの方はこちら</p>
				<div>
					<input type="submit" name="login_page" value="ログイン画面へ">
				</div>
			</form>
		</div>
	</div>
	<?php } ?>
</body>
</html>