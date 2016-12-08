$(document).ready(function(){
	$('input.delivery-id').on('change', function(){
		var key = $(this).attr('data-key');
		$('div.delivery:not(.delivery-' + key + ')').hide();
		$('div.delivery-' + key).show();
	});

	function load_filials()
	{
		var select = $('#filial-id');
		select.html('');
		$.post('/order/request/', {'method':'get_filials', 'city_name':$('#city-name').val(), '_csrf':GUI.token}, function(answer){
			if (typeof answer.result == 'string' && answer.result == 'ok')
			{
				if (typeof answer.filials == 'object')
				{
					for (var f in answer.filials)
					{
						$('<option />', {'value':f, 'html':answer.filials[f]['address']}).appendTo(select);
					}
					select.val(select.attr('data-sel'));
				}
				if (typeof answer.city_id != 'undefined')
				{
					$('#city-id').val(answer.city_id);
				}
			}
		}, 'json');
	}
	load_filials();

	$('#city-name').autocomplete({
		delay: 500,
		minLength: 1,
		source: function(request, responce){
			var data = np_cities || [];
			var result = [];
			if(request.term.length)
			{
				var reg = new RegExp('^'+request.term, 'i');
				for (var i in data)
				{
					if (data[i].match(reg))
					{
						result.push(data[i]);
					}
					if (result.length >= 10)
					{
						break;
					}
				}
			}

			responce(result);
		},
		//	Выбор города и подстановка списка филиалов
		select: function(event, ui){
			$(this).val(ui.item.value);
			load_filials();
		}
	}).on('change', function(){
		$('#filial-id').html('');
		$('#city-id').val('');
	});
});