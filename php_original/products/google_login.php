<?php
session_start();

require_once ("../config/config.php");
require_once ("../model/User.php");

// セッション情報を破棄する
		$_SESSION = array();
		session_destroy();

try {
	$user = new User($host, $dbname, $user, $pass);
	$user->connectDB();
	$page_flag = 0;

 	
	// google認証 
	if(!empty($_POST['google_login'])) {

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

	<div class="wrapper">
		<div class="main_wrapper">
			<div class="app_title">
				<p>レシピスクラップ</p>
			</div>
			<?php if(isset($message['user_name'])) { echo '<p style="color: red;">'.$message['user_name'].'</p>'; }?>
			<form action="" method="post">
				<div class="entry_form">
					<p class="entry_form_title">本登録</p>
					<div class="entry_list">
						<input type="submit" name="google_login" value="Googleでログインする">
					</div>
					
				</div>
				
			</form>
		</div>
	</div>
</body>
</html>