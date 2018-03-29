// adjusting font size in navbar
adjustFontSize('nav #brand', {min: 20, max: 35});
adjustFontSize('#options a', {min: 10, max: 17.5});

$(function() {
	$('nav #brand').removeClass('d-none');
});

$('#options').hide();

$('#showOptions')
	.show()
	.click(function() {
		$('#showOptions').hide();
		$('#options').slideDown().show();
		$('#hideOptions').show();
	})
;

$('#hideOptions')
	.hide()
	.click(function() {
		$('#hideOptions').hide();
		$('#options').slideUp(function() {
			$('#options').hide()
		});
		$('#showOptions').show();
	})
;