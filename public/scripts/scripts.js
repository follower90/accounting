$(document).ready(function () {
	$('table tbody tr:even').addClass('tr-even');
	$('table tbody tr:odd').addClass('tr-odd');

	$('.datepicker').datepicker({
		format: 'dd.mm.yyyy',
		weekStart: 1
	});

	$('table tr').on('dblclick', function () {
		var id = parseInt($(this).attr('num'));
		if (id != 0) {
			editEntry(id);
		}
	});
});

var saveEntry = function (id) {
	var name = $('#name-edit-' + id).val();
	var date = $('#date-edit-' + id).val();
	var sum = $('#sum-edit-' + id).val();
	var cat = $('#types-edit-' + id + ' :selected').val();

	$.ajax({
		type: "POST", url: "/api.php?method=Entry.save", dataType: "json", data: {name: name, date: date, sum: sum, cat: cat, id: id}, cache: false, success: function (data) {
			$('#tr-' + id).refreshEntry(id);
			editEntry(id);
		}
	});
};

var editEntry = function (id) {
	$('#tr-' + id).toggleClass('hidden');
	$('#tr-' + id + '-edit').toggleClass('hidden');
};

var deleteEntry = function (id) {
	if (confirm('Вы уверены что хотите удалить?')) {
		var dataString = 'id=' + id;
		$.ajax({
			type: "POST", url: "/api.php?method=Entry.delete", data: dataString, cache: false, success: function (data) {
				$('#tr-' + id).fadeOut('slow');
			}
		});
	}

	return false;
};

$.fn.refreshEntry = function (id) {
	$.ajax({
		type: "POST",
		url: "/api.php?method=Entry.get",
		data: {id: id},
		dataType: "json",
		cache: false,
		success: function (data) {
			var elem = $('#tr-' + id);
			var cl = 'tr-odd';
			if (elem.hasClass(cl)) {
				cl = 'tr-even';
			}
			console.log(data);

			var image = '<img src="/public/images/plus.png" alt="">';
			if (data.category.type == '-') {
				image = '<img src="/public/images/minus.png" alt="">';
			}

			$('#tr-' + id + ' .icon-edit').html(image);
			$('#tr-' + id + ' .date-edit').html(data.date);
			$('#tr-' + id + ' .name-edit').html(data.name);
			$('#tr-' + id + ' .type-edit').html(data.category.name);
			$('#tr-' + id + ' .sum-edit').html(data.sum + ' ₴');
		}
	});
};