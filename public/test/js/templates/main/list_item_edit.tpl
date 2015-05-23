<tr id="tr-'.$row['id'].'-edit" num="'.$row['id'].'" class="edit-row hidden">
    <td>&nbsp;</td>
    <td><input type="text" class="datepicker form-control input-sm" id="date-edit-'.$row['id'].'" value="'.date("d.m.Y", strtotime($row['date'])).'" /></td>
    <td><input type="text" class="form-control input-sm" id="name-edit-'.$row['id'].'" value="'.$row['name'].'" /></td>
    <td><select name="types" class="form-control input-sm" id="types-edit-'.$row['id'].'">';
            foreach($vars['categories'] as $id => $type) {
            $sel = '';
            if($id==$row['category']['id']) $sel='selected="selected"';
            echo '<option value='.$id.' '.$sel.'>'.$type.'</option>';
            }
            echo '</select>';
        echo '</td>
    <td><input type="text" class="form-control input-sm" id="sum-edit-'.$row['id'].'" value="'.$row['sum'].'"/></td>
    <td class="links">
        <a class="label label-success" href="javascript:void(0);" onclick="saveEntry('.$row['id'].');">сохранить</a>
        <a class="label label-danger" href="javascript:void(0);" onclick="editEntry('.$row['id'].');">отмена</a>
    </td>
</tr>
