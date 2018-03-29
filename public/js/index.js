// function to set the top padding
function setTopPadding() {
	var containerHeight = $(window).height() - $('nav').height() - $('footer').height();
	$('#intro').css('padding-top', (containerHeight - $('#intro').height()) / 2.5);
	$('#search').css('padding-top', (containerHeight - $('#search').height()) / 2.75);
}

// set top padding on load
$(function() {
	setTopPadding();
	$('#intro').removeClass('invisible');
});

// set top padding on resize
$(window).resize(function() {
	setTopPadding()
});

// adding animations to buttons
$('#downButton').click(function() {
	$('#intro')
		.slideUp('slow')
		.fadeOut('fast', function() {
			$('#search')
				.slideUp('slow')
				.fadeIn();
		})
	;
});

$('#upButton').click(function() {
	$('#search')
		.slideDown('slow')
		.fadeOut('fast', function() {
			$('#intro')
				.slideDown('slow')
				.fadeIn();
		})
	;
});