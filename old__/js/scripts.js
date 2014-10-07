
$(document).ready(function()
{
	$('table tbody tr:even').addClass('tr-even');
	$('table tbody tr:odd').addClass('tr-odd');

	$('#date-add').datepicker({
		format: 'dd.mm.yyyy',
		weekStart: 1 
    });
	$('#types').selectpicker({
		'selectedText': 'cat'
	});
	
	preventSelection(document);


	$('table tbody tr').on('dblclick', function() {
		var id =parseInt($(this).attr('num'));
		var dataString = 'id='+id;

		$.ajax({type: "POST", url: "/ajax/editshow", data: dataString, dataType: 'json', cache: false, success: 
			function(html) {
				$('#name-edit').val(html.name);
				$('#sum-edit').val(html.sum);
				$('#date-edit').val(html.date);
				$('#types-edit option[value='+html.category+']').attr('selected', 'selected');
				$('#save-edit').attr('rel', id);
				$('#edit-entry').modal('toggle');
		}});

	});

	$('#save-edit').on('click', function () {
	var name = $('#name-edit').val();
	var date = $('#date-edit').val();
	var sum = $('#sum-edit').val();
	var cat = $('#types-edit :selected').val();
	var id=$('#save-edit').attr('rel');

	var dataString = 'name='+name+'&date='+date+'&sum='+sum+'&cat='+cat+'&id='+id;

	$.ajax({type: "POST", url: "/ajax/editsave", data: dataString, cache: false, success: 
		function(html){
			$('#edit-entry').modal('hide');
			$('#tr-'+id).refreshEntry(id);
		}});
	});

	setTimeout(
		function(){ 
			$('table tbody tr').hover( function() {
				$(this).toggleClass('hover-tr');
			});
		}, 0);

});


var deleteEntry = function(id) 
{
	var dataString = 'id='+id;
	$.ajax({type: "POST", url: "/ajax/deleteentry", data: dataString, cache: false, success: 
		function(html){
			if(html=='success') $('#tr-'+id).fadeOut('slow');
	}});
    return false;
}

$.fn.refreshEntry = function(id)
{
	dataString = 'id='+id;
	$.ajax({type: "POST", url: "/ajax/editshow", data: dataString, dataType: 'json', cache: false, success: 
	function(html) {
			var elem = $('#tr-'+id);
			var cl='tr-even';
			if(elem.hasClass(cl)) {
				cl = 'tr-even';
			} else cl = 'tr-odd';

			var image = '<img src="/images/plus.png" alt="">';
			if(html.type =='-') { 
				image = '<img src="/images/minus.png" alt="">';
			}

			var entry = '<td>'+image+'</td><td>'+html.date+'</td><td>'+html.name+'</td><td>'+html.cat+'</td><td>'+html.sum+' ₴</td><td><a href="javascript:void(0);" onclick="deleteEntry('+id+');">удалить</a></td>';
		   	elem.html(entry);
	}});
}

function preventSelection(element){
  var preventSelection = false;

  function addHandler(element, event, handler){
    if (element.attachEvent) 
      element.attachEvent('on' + event, handler);
    else 
      if (element.addEventListener) 
        element.addEventListener(event, handler, false);
  }
  function removeSelection(){
    if (window.getSelection) { window.getSelection().removeAllRanges(); }
    else if (document.selection && document.selection.clear)
      document.selection.clear();
  }
  function killCtrlA(event){
    var event = event || window.event;
    var sender = event.target || event.srcElement;

    if (sender.tagName.match(/INPUT|TEXTAREA/i))
      return;

    var key = event.keyCode || event.which;
    if (event.ctrlKey && key == 'A'.charCodeAt(0))  // 'A'.charCodeAt(0) можно заменить на 65
    {
      removeSelection();

      if (event.preventDefault) 
        event.preventDefault();
      else
        event.returnValue = false;
    }
  }

  // не даем выделять текст мышкой
  addHandler(element, 'mousemove', function(){
    if(preventSelection)
      removeSelection();
  });
  addHandler(element, 'mousedown', function(event){
    var event = event || window.event;
    var sender = event.target || event.srcElement;
    preventSelection = !sender.tagName.match(/INPUT|TEXTAREA/i);
  });

  addHandler(element, 'mouseup', function(){
    if (preventSelection)
      removeSelection();
    preventSelection = false;
  });

  addHandler(element, 'keydown', killCtrlA);
  addHandler(element, 'keyup', killCtrlA);
}

