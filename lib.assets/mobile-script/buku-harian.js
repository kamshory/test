var to = setTimeout(function(){}, 100);

function initArea(selector)
{
    $(document).on('click', selector, function(e){
		var id = $(this).attr('data-id');
		var container = $(this).closest('.cuaca');
		var target = $('.menu-cuaca');
		var containerPos = container.offset();
		var containerDim = [container.width(), container.height()];
		var targetDim = [target.width(), target.height()];
		var offsetX = - 15 - $('.body')[0].offsetLeft;
		var tagetLeft = offsetX + containerPos.left + ((containerDim[0] - targetDim[0]) / 2);
		var tagetTop = containerPos.top + ((containerDim[1] - targetDim[1]) / 2) - 48;
		target.css({'left':tagetLeft+'px', 'top':tagetTop+'px'});
		clearTimeout(to);
		to = setTimeout(function(){
			target.fadeOut(200);
		}, 5000);
		target.attr('data-id', id);
		target.fadeIn(40);
		e.preventDefault();
	});
}

function initMenu(selector)
{
	$(document).on('click', selector, function(e){
		let value = $(this).closest('li').attr('data-value');
		let id = $(this).closest('.menu-cuaca').attr('data-id');
		$('span.icon-cuaca[data-id="'+id+'"]').attr('data-value', value);
		$(this).closest('.menu-cuaca').fadeOut(200);
		
		$.post('lib.mobile-tools/ajax-save-cuaca.php', {buku_harian_id:bukuHarianId, id:id, value:value}, function(answer){
					
		});

		e.preventDefault();
	});
}

function renderCuaca(selector, data)
{
	var html = '<span class="icon-cuaca" id="icon-cuaca-1"></span>';
	$('.cuaca').each(function(index, element) {
        let obj = $(this); 
		let id = '';
		let cuaca = '';
		obj.find(selector).each(function(index, element) {
			id = $(this).attr('data-id');
            let obj2 = $('<span class="icon-cuaca"></span>');
			let c = $(this).attr('coords');			
			let coords = getCoords(c);
			let coord = getCentroid(coords);
			let offsetX = 17;
			let offsetY = 17;
			obj2.css({'left':(coord[0]-offsetX)+'px', 'top':(coord[1]-offsetY)+'px', 'z-index':-1});
			obj2.attr('data-id', id);
			
			if(typeof data[id] != 'undefined')
			{
				cuaca = data[id];
				obj2.attr('data-value', cuaca);
			}
			
			obj.append(obj2);
        });
    });
}

var getCoords = function(str)
{
	var arr = str.split(',');
	var coords = [[parseInt(arr[0]), parseInt(arr[1])], [parseInt(arr[2]), parseInt(arr[3])], [parseInt(arr[4]), parseInt(arr[5])]];
	return coords;
};

var getCentroid = function (coord) 
{
	var center = coord.reduce(function (x,y) {
		return [x[0] + y[0]/coord.length, x[1] + y[1]/coord.length] 
	}, [0,0])
	return center;
};
