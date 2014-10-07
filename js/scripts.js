
$(document).ready(function()
{
	$('table tbody tr:even').addClass('tr-even');
	$('table tbody tr:odd').addClass('tr-odd');

	$('.datepicker').datepicker({
		format: 'dd.mm.yyyy',
		weekStart: 1 
	});
	
	preventSelection(document);

	$('table tr').on('dblclick', function() {
		var id = parseInt($(this).attr('num'));
		if(id!=0) {
			editEntry(id);
		}
	})


});

var saveEntry = function(id) {
	var name = $('#name-edit-'+id).val();
	var date = $('#date-edit-'+id).val();
	var sum = $('#sum-edit-'+id).val();
	var cat = $('#types-edit-'+id+' :selected').val();

	var dataString = 'name='+name+'&date='+date+'&sum='+sum+'&cat='+cat+'&id='+id;

	$.ajax({type: "POST", url: "/ajax/editsave", data: dataString, cache: false, success:
		function(html){
			$('#edit-entry').modal('hide');
			$('#tr-'+id).refreshEntry(id);
			editEntry(id);
		}});
}

var editEntry = function(id) {
	$('#tr-'+id).toggleClass('hidden');
	$('#tr-'+id+'-edit').toggleClass('hidden');
}

var deleteEntry = function(id) 
{
		if(confirm('Вы уверены что хотите удалить?')) {
			var dataString = 'id='+id;
			$.ajax({type: "POST", url: "/ajax/deleteentry", data: dataString, cache: false, success:
				function(html){
					if(html=='success') $('#tr-'+id).fadeOut('slow');
				}});
		}

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

			$('#tr-'+id+' .icon-edit').html(image);
			$('#tr-'+id+' .date-edit').html(html.date);
			$('#tr-'+id+' .name-edit').html(html.name);
			$('#tr-'+id+' .type-edit').html(html.cat);
			$('#tr-'+id+' .sum-edit').html(html.sum + ' ₴');
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

