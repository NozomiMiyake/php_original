<?php

// class HttpRequest
// {
//     private $_httpRequest;

//     public function __construct($httpRequest)
//     {
//         $this->_httpRequest = $httpRequest;
//     }

//     public function setUrl($url)
//     {
//         $this->_httpRequest->setUrl($url);
//     }
    
//     public function send(array $data = array())
//     {
//         $this->_httpRequest->addQueryData($data);
//         try {
//             $this->_httpRequest->send();
//             if ($this->_httpRequest->getResponseCode() == 200) {
//                 return json_decode($this->_httpRequest->getResponseBody(), true);
//             }
//             return array();
//         } catch (\HttpException $exception) {
//             return false;
//         } catch (\Exception $e) {
//             throw $e;
//         }
//     }
// }

session_start();

require_once ("../config/config.php");
require_once ("../model/User.php");


// そもそもユーザーIDがあるのかないのかチェック
// その人のグーグルIDと会っているか？
// 普通にログインしてきた人　グーグルIDをアップデート
// 連携いきなりした人　パスワード
// 場合分けして考えてみる　矛盾が生まれるなら見直す

// いくつか綺麗なデータ作っておく

// google連携
define('CLIENT_ID', '840134749710-eh96ep3qhe9e7lcqjh0elnmebh9rcflu.apps.googleusercontent.com');
define('CLIENT_SECRET', 'evipMqEMKUPyPd9pVgqzMtRQ');
define('CALLBACK', 'http://localhost/php_original/products/recipe_list.php');
$code = $_REQUEST['code'];
// access_token 取得
$baseURL = 'https://accounts.google.com/o/oauth2/token';
$params = array(
	'code'          => $code,
	'client_id'     => CLIENT_ID,
	'client_secret' => CLIENT_SECRET,
	'redirect_uri'  => CALLBACK,
	'grant_type'    => 'authorization_code'
);
$ch = curl_init($baseURL);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$json = json_decode($response, true);
curl_close($ch);
// print_r($json);
// $userEmail = $json["email"];
// echo $userEmail;

// APIを叩きに行く
$url = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token='.$json['access_token'];
// $ch = curl_init($url);
// $response = curl_exec($ch);
// $json = json_decode($response, true);
// curl_close($ch);
// print_r($json);

$json = file_get_contents($url);
$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$arr = json_decode($json,true);
// print_r($arr);

// $json1 = json_decode($json, true);
// print_r($json1);
// print $json->{"id"};

// exit;
// 再発行API
// 名前、Eメール、トークン　パスワード空だとログインできないようにしないと
// パスワード持ってる人と持ってない人　プロフィールでパスワードも　念のためパスワード設定できるようにしてあげると親切
// jsで非同期通信　サーバーと同期したらダメな物はjs サーバーと同期していいものはphp


// $req = new HttpRequest($baseURL, HttpRequest::METH_POST);
// $req->setPostFields($params);
// $req->send();
// $response = json_decode($req->getResponseBody());

// if(isset($response->error)){
// 	// getCodeへ戻す
// 	echo 'エラー発生。<a href="getCode.php">最初からやりなおす</a>';
// 	exit;
// }
// $access_token = $response->access_token;
// // ユーザ情報取得
// $userInfo = json_decode(
// 	file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?'.
// 	'access_token=' . $access_token)
// );
// // google の id + name(表示名)をセット
// $user_id = $userInfo->id;
// $user_name    = $userInfo->name;



// exit;

// ログイン画面を経由しているかどうか確認する
// if(!isset($_SESSION['User'])) {
// 	header('Location: /php_original/products/entry.php');
// 	exit;
// }

// 一般ユーザーか閲覧ユーザーか
// if ($_SESSION['User']['role'] == 0) {
// 	$user_id = $_SESSION['User']['user_id'];
// 	$user_id = 3;
// 	$message = "サンプルページです";
// } else {
// 	$user_id = $_SESSION['User']['user_id'];
// 	// echo "$user_id";
// }


try {
    $user = new User($host, $dbname, $user, $pass);
    $user->connectDB();

    // // 初回ユーザかチェックするロジック
    if (!empty($arr)) {
    	$google_id = $arr['id'];
		// Google ID連携しているかどうかチェック
		$result = $user->loginGoogleId($google_id);

		if(!empty($result)){
			// 全レシピ参照
			$_SESSION['User'] = $result;
			// print_r($_SESSION['User']);
			$user_id = $_SESSION['User']['user_id'];
		} else {
			$user->addGoogleId($arr);
			// Google ID連携しているかどうかチェック
			$result = $user->loginGoogleId($google_id);
			// 全レシピ参照
			$_SESSION['User'] = $result;
			// print_r($_SESSION['User']);
			$user_id = $_SESSION['User']['user_id'];
		}
		// exit;
    }

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
		<div class="main_wrapper clearfix">
			<?php foreach ($result as $value): ?>
			<form action="recipe.php" method="post">
				<div class="recipe_list clearfix">
					<div class="list_photo clearfix">
						<div class="<?php if(empty($value['recipe_tweet'])){echo"list_photo_img_none";} else {echo"list_photo_img clearfix";} ?>">
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
