jQuery(function($){
	$('.ar-sl-wrap h3').click(function(e){
		e.preventDefault();
		var elem = $(this),
			table = elem.next('.form-table');
		elem.toggleClass('closed');
		table.toggleClass('closed');
	});
	
	$('.colorSelector').each(function(i,item){
		var item = $(item),
			input = item.prev(),
			color = input.val(),
			div = item.find('div');
			div.css('backgroundColor', color);
		item.ColorPicker({
			color: color,
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				div.css('backgroundColor', '#' + hex);
				input.val('#' + hex);
			}
		});
	});
});
