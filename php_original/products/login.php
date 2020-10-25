<div class="wrapper">
	<div class="back">
		<a href="javascript:history.go(-1)">
			<img src="../img/左向きの矢印のアイコン素材のコピー.png">
		</a>
	</div>
	<div class="main_wrapper">
		<?php if(isset($entry_complete)) echo '<p style="color: red;">'.$entry_complete.'</p>'; ?>
		<p class="entry_form_title">ログインフォーム</p>
		<?php if(!empty($not_login)) echo '<p style="color: red;">'.$not_login.'</p>'; ?>
		<form action="" method="post">
			<div>
				<input type="submit" name="google_login" value="Googleでログインする">
			</div>
			<div>
				<p>ユーザー名</p>
				<input type="text" name="user_name">
			</div>
			<div>
				<p>パスワード</p>
				<input type="text" name="password">
			</div>
			<div class="entry">
				<input type="submit" name="login" value="ログイン">
			</div>
		</form>
	</div>
</div>