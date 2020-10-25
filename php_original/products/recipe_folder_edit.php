	<div class="back">
		<a href="recipe_folder.php">
			<img src="../img/左向きの矢印のアイコン素材のコピー.png">
		</a>
	</div>
<div class="main_wrapper">
	<div class="demo">
		<?php if($_SESSION['User']['role'] == 0) { echo '<p style="color: red;">'.$message.'</p>'; } ?>
	</div>
	<div class="folder_edit">
		<?php foreach ($result as $value): ?>
		<form action="" method="post">
			<ul>
				<li>
					<div class="circle"></div>
					<input type="text" name="folder_name" value="<?=$value['folder_name'] ?>">
					<input type="hidden" name="user_id" value="<?php print_r($_SESSION['User']['user_id'])?>">
					<input type="hidden" name="folder_id" value="<?=$value['folder_id'] ?>">
					<?php if($_SESSION['User']['role'] != 0): ?>
					<div class="folder_register">
						<input type="submit" value="編集" name="folder_edit">
					</div>
					<?php endif; ?>
				</li>
			</ul>
		</form>
		<?php endforeach; ?>
		<form action="" method="post">
			<ul>
				<?php if ($edit_flag === 1) { ?>
					<li>
						<div class="circle"></div>
						<input type="text" name="folder_name" value="">
						<input type="hidden" name="user_id" value="<?php print_r($_SESSION['User']['user_id'])?>">
						<?php if($_SESSION['User']['role'] != 0): ?>
						<div class="folder_register">
							<input type="submit" value="追加" name="folder_add">
						</div>
						<?php endif; ?>	
					</li>
				<?php }  elseif ($edit_flag === 0) { ?>
				<div class="folder_edit_plus">
					<input type="submit" name="new_folder_name" value=" ">
				</div>
				<?php } ?>
			</ul>
		</form>
	</div>
</div>