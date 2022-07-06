(function() {
	var options = {
		sourceAttr: 	php_vars.ar_sl_sourceAttr,
		overlay: 		(php_vars.ar_sl_overlay == '1') ? true : false,
		overlayOpacity: parseFloat(php_vars.ar_sl_overlayOpacity),
		spinner: 		(php_vars.ar_sl_spinner == '1') ? true : false,
		nav: 			(php_vars.ar_sl_nav === '1') ? true : false,
		navText:		[php_vars.ar_sl_navtextPrev,php_vars.ar_sl_navtextNext],
		captions:		(php_vars.ar_sl_caption === '1') ? true : false,
		captionSelector:php_vars.ar_sl_captionSelector,
		captionType: 	php_vars.ar_sl_captionType,
		captionsData:	php_vars.ar_sl_captionData,
		captionPosition:php_vars.ar_sl_captionPosition,
		captionDelay:   parseInt(php_vars.ar_sl_captionDelay,10),
		captionClass:   php_vars.ar_sl_captionClass,
		close:			(php_vars.ar_sl_close === '1') ? true : false,
		closeText:		php_vars.ar_sl_closeText,
		showCounter:	(php_vars.ar_sl_showCounter === '1') ? true : false,
		fileExt:		(php_vars.ar_sl_fileExt == 'false') ? false : php_vars.ar_sl_fileExt,
		animationSpeed:	parseInt(php_vars.ar_sl_animationSpeed,10),
		animationSlide: (php_vars.ar_sl_animationSlide === '1') ? true : false,
		preloading:		(php_vars.ar_sl_preloading === '1') ? true : false,
		enableKeyboard:	(php_vars.ar_sl_enableKeyboard === '1') ? true : false,
		loop:			(php_vars.ar_sl_loop === '1') ? true : false,
		rel:			(php_vars.ar_sl_rel == 'false') ? false : php_vars.ar_sl_rel,
		docClose: 	 	(php_vars.ar_sl_docClose === '1') ? true : false,
		swipeTolerance: parseInt(php_vars.ar_sl_swipeTolerance,10),
		className:		php_vars.ar_sl_className,
		widthRatio: 	php_vars.ar_sl_widthRatio,
		heightRatio: 	php_vars.ar_sl_heightRatio,
		scaleImageToRatio: (php_vars.ar_sl_scaleImageToRatio == '1') ? true : false,
		disableRightClick:(php_vars.ar_sl_disableRightClick == '1') ? true : false,
		disableScroll:	(php_vars.ar_sl_disableScroll == '1') ? true : false,
		alertError:     (php_vars.ar_sl_alertError == '1') ? true : false,
		alertErrorMessage:php_vars.ar_sl_alertErrorMessage,
		additionalHtml: php_vars.ar_sl_additionalHtml,
		history:		(php_vars.ar_sl_history == '1') ? true : false,
		throttleInterval:parseInt(php_vars.ar_sl_throttleInterval,10),
		doubleTapZoom:	parseInt(php_vars.ar_sl_doubleTapZoom,10),
		maxZoom:		parseInt(php_vars.ar_sl_maxZoom,10),
		htmlClass:		php_vars.ar_sl_htmlClass,
		rtl:			(php_vars.ar_sl_rtl == '1') ? true : false,
		fixedClass:		php_vars.ar_sl_fixedClass,
		fadeSpeed:		parseInt(php_vars.ar_sl_fadeSpeed,10),
		uniqueImages:	(php_vars.ar_sl_uniqueImages == '1') ? true : false,
		focus:			(php_vars.ar_sl_focus == '1') ? true : false,
		scrollZoom:		(php_vars.ar_sl_scrollZoom == '1') ? true : false,
		scrollZoomFactor:parseFloat(php_vars.ar_sl_scrollZoomFactor)
	}
	// fixing not working lightbox in some themes
	var anchors = document.querySelectorAll("a");
	var thumbnails = Array.from(anchors).filter(function(item) {
		return /\.(jpe?g|png|gif|mp4|webp|bmp|pdf)(\?[^/]*)*$/i.test(item.getAttribute("href"));
	});

	for (const thumbnail of thumbnails) {
		thumbnail.classList.add('simplelightbox');
	}

	if(document.querySelectorAll('a.simplelightbox').length ) {
		var simplelightbox = new SimpleLightbox('a.simplelightbox', options);
	}

	if(php_vars.ar_sl_additionalSelectors) {
		var selectors = php_vars.ar_sl_additionalSelectors.split(',');
		for(var i = 0; i <= selectors.length; i++) {
			var selector = selectors[i];
			if(selector) {
				selector = selector.trim();
				if(selector != '' && document.querySelectorAll(selector).length) {
					new SimpleLightbox(selector, options);
				}
			}
		}
	}
})();