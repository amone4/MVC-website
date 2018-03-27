$(function() {
	$('#hideOptions').hide();
	$('#showOptions').show();
	$('#options').hide();
});

$('#showOptions').click(function() {
	$('#showOptions').hide();
	$('#options').slideDown().show();
	$('#hideOptions').show();
});

$('#hideOptions').click(function() {
	$('#hideOptions').hide();
	$('#options').slideUp(function() {
		$('#options').hide()
	});
	$('#showOptions').show();
});