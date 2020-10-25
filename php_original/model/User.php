<?php
require_once ("DB.php");

class User extends DB {

	// 仮登録 
	public function add0($arr) {
		$sql = "INSERT INTO users(user_name, email, password, role, google_id, created) VALUES (:user_name, :email, :password, :role, :google_id, :created)";
		$stmt = $this->connect->prepare($sql);
		$params = array(
			':user_name'=>$arr['user_name'],
			':email'=>0,
			':password'=>'demo',
			':role'=>0,
			':google_id'=>0,
			':created'=>date('Y-m-d H:i:s')
		);
		$stmt->execute($params);
	}
	// 本登録 
	public function add1($arr) {
		$sql = "INSERT INTO users(user_name, email, password, role, google_id, created) VALUES (:user_name, :email, :password, :role, :google_id, :created)";
		$stmt = $this->connect->prepare($sql);
		$params = array(
			':user_name'=>$arr['user_name'],
			':email'=>$arr['email'],
			':password'=>$arr['password'],
			':role'=>1,
			':google_id'=>0,
			':created'=>date('Y-m-d H:i:s')
		);
		$stmt->execute($params);
	}
	// 本登録 Googleログイン後
	public function addGoogleId($arr) {
		$sql = "INSERT INTO users(user_name, email, password, role, google_id, created) VALUES (:user_name, :email, :password, :role, :google_id, :created)";
		$stmt = $this->connect->prepare($sql);
		$params = array(
			':user_name'=>$arr['name'],
			':email'=>$arr['email'],
			':password'=>0,
			':role'=>1,
			':google_id'=>$arr['id'],
			':created'=>date('Y-m-d H:i:s')
		);
		$stmt->execute($params);
	}

	// ユーザー参照
	public function FindUser($user_id) {
		$sql = "SELECT * FROM users WHERE user_id = :user_id";
		$stmt = $this->connect->prepare($sql);
		$params = array(':user_id'=>$user_id);
		$stmt->execute($params);
		$result = $stmt->fetchAll();
		return $result;
	}

	// ユーザー編集
	public function EditUser($arr) {
		$sql = "UPDATE users SET user_name = :user_name, email = :email, password = :password, role = :role WHERE user_id = :user_id";
		$stmt = $this->connect->prepare($sql);
		$params = array(
			':user_id'=>$arr['user_id'], 
			':user_name'=>$arr['user_name'], 
			':email'=>$arr['email'], 
			':password'=>$arr['password'],
			':role'=>1
		);
		$stmt->execute($params);
	}

	// ユーザー削除
	public function DeleteUser($user_id = null) {
		if(isset($user_id)) {
			$sql = "DELETE FROM users WHERE user_id = :user_id";
			$stmt = $this->connect->prepare($sql);
			$params = array(':user_id'=>$user_id);
			$stmt->execute($params);
		}
	}

	// 入力チェック
	public function validate($arr) {
		$message = array();
		// ユーザー名
		if(empty($arr['user_name'])) {
			$message['user_name'] = '氏名を入力してください。';
		}
		// フォルダ名
		if(empty($arr['folder_name'])) {
			$message['folder_name'] = 'フォルダ名を入力してください。';
		}
		// フォルダ選択
		if(empty($arr['folder1']) && empty($arr['folder2']) && empty($arr['folder3']) && empty($arr['folder4']) && empty($arr['folder5'])) {
			$message['folder_choice'] = 'フォルダを選択してください。';
		}


		return $message;
	}

	// ログイン
	public function login($arr) {
		$sql = 'SELECT * FROM users WHERE user_name = :user_name AND password = :password';
		$stmt = $this->connect->prepare($sql);
		$params = array(
			':user_name'=>$arr['user_name'],
			':password'=>$arr['password']
		);
		$stmt->execute($params);
		$result = $stmt->fetch();
		return $result;
	}

	// googleログイン
	public function loginGoogleId($google_id) {
		$sql = 'SELECT * FROM users WHERE google_id = :google_id';
		$stmt = $this->connect->prepare($sql);
		$params = array(
			':google_id'=>$google_id
		);
		$stmt->execute($params);
		$result = $stmt->fetch();
		return $result;
	}

	// レシピフォルダ参照
	public function FindFolder($user_id) {
		$sql = "SELECT * FROM folders WHERE user_id = :user_id";
		$stmt = $this->connect->prepare($sql);
		$params = array(':user_id'=>$user_id);
		$stmt->execute($params);
		$result = $stmt->fetchAll();
		return $result;
	}

	// レシピフォルダ登録
	public function AddFolder($arr) {
		$sql = "INSERT INTO folders(folder_name, user_id) VALUES (:folder_name, :user_id)";
		$stmt = $this->connect->prepare($sql);
		$params = array(
			':folder_name'=>$arr['folder_name'], 
			':user_id'=>$arr['user_id']
		);
		$stmt->execute($params);
	}

	// レシピフォルダ編集　
	public function EditFolder($arr) {
		$sql = "UPDATE folders SET folder_name = :folder_name, folder_id = :folder_id, user_id = :user_id WHERE folder_id = :folder_id";
		$stmt = $this->connect->prepare($sql);
		$params = array(
			':folder_name'=>$arr['folder_name'], 
			':user_id'=>$arr['user_id'], 
			':folder_id'=>$arr['folder_id']
		);
		$stmt->execute($params);
	}

	// 全レシピ参照
	public function FindRecipeAll($user_id) {
		$sql = "SELECT * FROM recipes WHERE user_id = :user_id";
		$stmt = $this->connect->prepare($sql);
		$params = array(':user_id'=>$user_id);
		$stmt->execute($params);
		$result = $stmt->fetchAll();
		return $result;
	}

	// レシピ参照
	public function FindRecipe($id) {
		$sql = "SELECT * FROM recipes WHERE folder1 LIKE :id OR folder2 LIKE :id OR folder3 LIKE :id OR folder4 LIKE :id OR folder5 LIKE :id ";
		$stmt = $this->connect->prepare($sql);
		$params = array(':id'=>$id);
		$stmt->execute($params);
		$result = $stmt->fetchAll();
		return $result;
	}

	// レシピ単体参照
	public function FindRecipeOne($recipe_id) {
		$sql = "SELECT * FROM recipes AS T1 JOIN folders AS T2 ON T1.folder1 = T2.folder_id OR T1.folder2 = T2.folder_id OR T1.folder3 = T2.folder_id OR T1.folder4 = T2.folder_id OR T1.folder5 = T2.folder_id WHERE recipe_id = :recipe_id";
		$stmt = $this->connect->prepare($sql);
		$params = array(':recipe_id'=>$recipe_id);
		$stmt->execute($params);
		$result = $stmt->fetchAll();
		return $result;
	}

	// --
	// タグありレシピ参照
	public function FindRecipeTag1($user_id) {
		$sql = "SELECT * FROM recipes WHERE tag1 = 1 AND user_id = :user_id";
		$stmt = $this->connect->prepare($sql);
		$params = array(':user_id'=>$user_id);
		$stmt->execute($params);
		$result = $stmt->fetchAll();
		return $result;
	}
	public function FindRecipeTag2($user_id) {
		$sql = "SELECT * FROM recipes WHERE tag2 = 1 AND user_id = :user_id";
		$stmt = $this->connect->prepare($sql);
		$params = array(':user_id'=>$user_id);
		$stmt->execute($params);
		$result = $stmt->fetchAll();
		return $result;
	}
	public function FindRecipeTag3($user_id) {
		$sql = "SELECT * FROM recipes WHERE tag3 = 1 AND user_id = :user_id";
		$stmt = $this->connect->prepare($sql);
		$params = array(':user_id'=>$user_id);
		$stmt->execute($params);
		$result = $stmt->fetchAll();
		return $result;
	}

	// レシピ登録
	public function AddRecipe($arr) {
		$sql = "INSERT INTO recipes(user_id, recipe_name, recipe_tweet, comment, folder1, folder2, folder3, folder4, folder5, tag1, tag2, tag3) VALUES (:user_id, :recipe_name, :recipe_tweet, :comment, :folder1, :folder2, :folder3, :folder4, :folder5, :tag1, :tag2, :tag3)";
		$stmt = $this->connect->prepare($sql);
		$params = array(
			':user_id'=>$arr['user_id'],
			':recipe_name'=>$arr['recipe_name'],
			':recipe_tweet'=>$arr['recipe_tweet'],
			':comment'=>$arr['comment'],
			':folder1'=>$arr['folder1'],
			':folder2'=>$arr['folder2'],
			':folder3'=>$arr['folder3'],
			':folder4'=>$arr['folder4'],
			':folder5'=>$arr['folder5'],
			':tag1'=>$arr['tag1'],
			':tag2'=>$arr['tag2'],
			':tag3'=>$arr['tag3']
		);
		$stmt->execute($params);
	}

	// レシピ編集
	public function EditRecipe($arr) {
		$sql = "UPDATE recipes SET recipe_name = :recipe_name, recipe_tweet = :recipe_tweet, comment = :comment, folder1 = :folder1, folder2 = :folder2, folder3 = :folder3, folder4 = :folder4, folder5 = :folder5, tag1 = :tag1, tag2 = :tag2, tag3 = :tag3 WHERE recipe_id = :recipe_id";
		$stmt = $this->connect->prepare($sql);
		$params = array(
			':recipe_id'=>$arr['recipe_id'],
			':recipe_name'=>$arr['recipe_name'], 
			':recipe_tweet'=>$arr['recipe_tweet'], 
			':comment'=>$arr['comment'], 
			':folder1'=>$arr['folder1'], 
			':folder2'=>$arr['folder2'], 
			':folder3'=>$arr['folder3'], 
			':folder4'=>$arr['folder4'], 
			':folder5'=>$arr['folder5'], 
			':tag1'=>$arr['tag1'], 
			':tag2'=>$arr['tag2'], 
			':tag3'=>$arr['tag3']
		);
		$stmt->execute($params);
	}

	// レシピ削除
	public function DeleteRecipe($recipe_id = null) {
		if(isset($recipe_id)) {
			$sql = "DELETE FROM recipes WHERE recipe_id = :recipe_id";
			$stmt = $this->connect->prepare($sql);
			$params = array(':recipe_id'=>$recipe_id);
			$stmt->execute($params);
		}
	}


	

}
