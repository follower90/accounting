$(document).ready(function () {
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

	editEntry(id);
	toggleLoader(id);

	$.ajax({
		type: "POST",
		url: "/api.php?method=Entry.save",
		dataType: "json",
		data: {name: name, date: date, sum: sum, cat: cat, id: id},
		cache: false,
		success: function (data) {
			$('#tr-' + id).refreshEntry(id);
		}
	});
};

var toggleLoader = function(id) {
	$('#tr-' + id).toggleClass('loading');
};

var editEntry = function (id) {
	$('#tr-' + id).toggleClass('hidden');
	$('#tr-' + id + '-edit').toggleClass('hidden');
};

var deleteEntry = function (id) {
	if (confirm('Вы уверены что хотите удалить?')) {
		var dataString = 'id=' + id;
		$.ajax({
			type: "POST",
			url: "/api.php?method=Entry.delete",
			data: dataString,
			cache: false,
			success: function (data) {
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

			data = data.response;
			var image = '<img src="/public/images/' + ((data.category.type == '+') ? 'plus' : 'minus') + '.png" alt="">';

			$.each(['#tr-' + id, '#tr-' + id + '-edit'], function(i, item) {
				$(item).find('.icon-edit').html(image);
				$(item).find('.date-edit').html(data.date);
				$(item).find('.name-edit').html(data.name);
				$(item).find('.type-edit').html(data.category.name);
				$(item).find('.sum-edit').html(data.sum + ' ₴');
			});

			toggleLoader(id);
		}
	});
};