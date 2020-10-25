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
			// $user_id = $result['user_id'];
	    	// $result = $user->FindRecipeAll($user_id);
	  //   	header('Location: /php_original/products/recipe_folder.php');
			// exit;
			$_SESSION = $result;
			print_r($_SESSION);
		} else {
			$result = $user->addGoogleId($arr);
			// header('Location: /php_original/products/recipe_folder.php');
			// exit;
			// print_r($arr);
			// echo "succes2";
		}
		// exit;
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
	ログイン完了
</title>
<link rel="stylesheet" type="text/css" href="../css/index.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">

</script>
</head>

<body>
	<div id="wrapper">
		<div class="main_wrapper">
			<p>ログインできました！</p>

		<?php  
			require("nav.php");
		?>
	</div>
</body>
</html>
