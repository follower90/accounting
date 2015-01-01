<div class="panel panel-default">
	<div class="panel-heading">Новая запись</div>
	<div class="panel-body">
		<form class="form-inline" role="form" method="post" action="/" autocomplete="off">
			<div class="form-group">
				<input type="text" class="datepicker form-control" id="date-add" name="date" value="<?=date("d.m.Y");?>" placeholder="Когда ?" />
			</div>
			<div class="form-group">
				<input type="text" class="form-control" name="name" value="" placeholder="Что ?" />
			</div>
			<div class="form-group">
				<input type="text" class="form-control" name="amount" value="" placeholder="Сколько ?"/>
			</div>
			<div class="form-group">
				<select name="types" class="form-control"><?=$vars['types'];?></select>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-default" name="AddNew">Добавить</button>
			</div>
		</form>
	</div>
</div>