<div class="panel panel-default">
    <div class="panel-heading">Редактировать профиль</div>
    <div class="panel-body">
        <form method="post" class="form-horizontal">
            <div class="col-xs-12">
                <div class="form-group">
                    <label class="control-label">Имя: </label>
                    <input class="form-control" type="text" name="name" value="{{name}}"/>
                </div>
                <div class="form-group">
                    <label>Логин: </label>
                    <input class="form-control" type="text" name="login" value="{{login}}"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Пароль: </label>
                    <input class="form-control" type="text" name="password" value="">
                </div>

                <div class="form-group">
                    <label class="control-label">Категории: </label>
                </div>

                <br/>

                <div class="form-group">
                    <input type="submit" class="btn btn-default" name="save" value="Сохранить"/>
                </div>
        </form>
    </div>
</div>