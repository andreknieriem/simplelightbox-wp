jQuery(document).ready(function($) {
	var options = {
		overlay: 		(php_vars.ar_sl_overlay == '1') ? true : false,
		spinner: 		(php_vars.ar_sl_spinner == '1') ? true : false,
		nav: 			(php_vars.ar_sl_nav === '1') ? true : false,
		navText:		[php_vars.ar_sl_navtextPrev,php_vars.ar_sl_navtextNext],
		captions:		(php_vars.ar_sl_caption === '1') ? true : false,
		captionSelector:php_vars.ar_sl_captionSelector,
		captionType: 	php_vars.ar_sl_captionType,
		captionsData:	php_vars.ar_sl_captionData,
		close:			(php_vars.ar_sl_close === '1') ? true : false,
		closeText:		php_vars.ar_sl_closeText,
		showCounter:	(php_vars.ar_sl_counter === '1') ? true : false,
	 	fileExt:		(php_vars.ar_sl_fileExt == 'false') ? false : php_vars.ar_sl_fileExt,
	 	animationSpeed:	parseInt(php_vars.ar_sl_animationSpeed,10),
	 	preloading:		(php_vars.ar_sl_preloading === '1') ? true : false,
	 	enableKeyboard:	(php_vars.ar_sl_enableKeyboard === '1') ? true : false,
	 	loop:			(php_vars.ar_sl_loop === '1') ? true : false,
	 	docClose: 	 	(php_vars.ar_sl_docClose === '1') ? true : false,
	 	swipeTolerance: parseInt(php_vars.ar_sl_swipeTolerance,10),
	 	className:		php_vars.ar_sl_className,
	 	widthRatio: 	php_vars.ar_sl_widthRatio,
	 	heightRatio: 	php_vars.ar_sl_heightRatio,
	 	disableRightClick:	(php_vars.ar_sl_disableRightClick == '1') ? true : false,
	 	disableScroll:		(php_vars.ar_sl_disableScroll == '1') ? true : false,
	}
	if($('a.simplelightbox ').length ) {
		var simplelightbox = $("a.simplelightbox").simpleLightbox(options);
	}
});