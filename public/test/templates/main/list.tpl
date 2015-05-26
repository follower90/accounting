<table class="table main-table" width="100%" cellpadding="0" cellspacing="0">
    <thead>
    <tr class="thead">
        <th width="10%">Тип</th>
        <th width="15%">Дата</th>
        <th width="20%">Название</th>
        <th class="cat-row" width="20%">Категория</th>
        <th width="15%">Сумма</th>
        <th width="16%"></th>
    </tr>
    </thead>
    <tbody id="list_items">
        <foreach list="{{data}}">
            <tr id="tr-{{id}}">
                <td class="icon-edit">{{icon}}</td>
                <td class="date-edit">{{date}}</td>
                <td class="name-edit">{{name}}</td>
                <td class="cat-row type-edit">{{category.name}}</td>
                <td class="sum-edit">{{sum}}</td>
                <td>
                    <a class="label edit-entry label-primary" href="javascript:void(0);">редактировать</a>
                    <a class="label remove-entry label-danger" href="javascript:void(0);">удалить</a>
                </td>
            </tr>
        </foreach>
    </tbody>
</table>