/**
 * method to adjust font size of $(selector) according to viewport width
 * @param selector string the object whose font size is to be adjusted
 * @param size object specifies the minimum and maximum font size
 */
function adjustFontSize(selector, size) {
	const MIN_VIEWPORT_WIDTH = 300;
	$(document).ready(function() {
		var width = 0;
		if ($(document).width() > MIN_VIEWPORT_WIDTH)
			width = $(document).width();
		else
			width = MIN_VIEWPORT_WIDTH;
		$(selector).css('fontSize', ((size.max - size.min) * (width - MIN_VIEWPORT_WIDTH) / width) + size.min);
	});
	$(window).resize(function() {
		var width = 0;
		if ($(document).width() > MIN_VIEWPORT_WIDTH)
			width = $(document).width();
		else
			width = MIN_VIEWPORT_WIDTH;
		$(selector).css('fontSize', ((size.max - size.min) * (width - MIN_VIEWPORT_WIDTH) / width) + size.min);
	});
}

$(function() {
	// adjusting font size in navbar
	adjustFontSize('nav #brand', {min: 20, max: 35});
	adjustFontSize('#options a', {min: 10, max: 17.5});
	// adjusting font size for search section buttons
	adjustFontSize('#search button', {min: 15, max: 25});
});