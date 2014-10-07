<div id="addnew">
	<h2>Новая запись</h2>
	<form id="add-form" method="post" action="/" autocomplete="off">
			<input type="text" id="date-add" name="date" value="<?=date("d.m.Y");?>" placeholder="Когда ?" />
			<input type="text" name="name" value="" placeholder="Что ?" />
			<input type="text" name="amount" value=""  placeholder="Сколько ?"/>
			<select name="types" id="types"><?=$vars['types'];?></select>
			<input type="submit" class="btn btn-default" name="AddNew" value="Добавить" />
	</form>
</div>