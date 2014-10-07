<div id="header">
	<div class="h1000">
		<div id="login">
			<?php
				if(isset($_SESSION['user_id']) && $_SESSION['user_id']!='') { ?>

					<p><span>[</span><a href="/"><?php echo $_SESSION['name'];?></a><span>]</span></p>

				<?php } else { ?>

					<form method="post" action="/">
						<input type="text" name="name" placeholder="Логин" />
						<input type="password" name="pass" placeholder="Пароль" />
						<button id="enter" type="submit">Вход</button>
					</form>

				<?php } ?>
			
		</div>
		<div id="logout">
			<a href="/profile">Мой профиль</a>&nbsp;
			<a href="/logout">Выход</a>
		</div>
	</div>
</div>