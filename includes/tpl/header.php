<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toogle Menu</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">Моя Бухгалтерия</a>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

			<?php if($this->authorized()) { ?>

				<ul class="nav navbar-nav navbar-right">
					<li><a href="#"><i><?php echo $_SESSION['name'];?></i></a></li>
					<li><a href="/profile">Настройки</a></li>
					<li><a href="/logout">Выйти</a></li>
				</ul>

			<?php } else { ?>

				<ul class="nav navbar-nav navbar-right">
					<form method="post" action="/" class="form-inline" role="form" style="margin-top: 7px;">
						<div class="form-group">
							<input type="text" class="form-control" name="name" placeholder="Логин">
						</div>
						<div class="form-group">
							<input type="password" class="form-control" name="pass" placeholder="Пароль">
						</div>
						<button type="submit" class="btn btn-default">Войти</button>
					</form>
				</ul>

			<?php } ?>

		</div>
	</div>
</nav>