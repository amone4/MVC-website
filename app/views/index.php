<?php require_once APPROOT . '/views/inc/header.php'; ?>

	<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/index.css">

	<div id="intro" class="m-auto col-lg-6 col-md-8 col-sm-10 col-12 invisible">
		<div class="container-fluid">
			<div class="row d-inline">
				<h1>A one-liner relevant to the website</h1>
			</div>
			<div onclick="$('#intro')
			.slideUp('slow')
			.fadeOut('fast', function() {
				$('#search')
				.slideUp('slow')
				.fadeIn();
			});" class="displayToggle">
				<h4>Search for Doctor<br><i class="fa fa-angle-down"></i></h4>
			</div>
		</div>
	</div>

	<div id="search" class="m-auto col-lg-6 col-md-8 col-sm-10 col-12">
		<div class="container-fluid">
			<div onclick="$('#search')
			.slideDown('slow')
			.fadeOut('fast', function() {
				$('#intro')
				.slideDown('slow')
				.fadeIn();
			});" class="displayToggle">
				<h4><i class="fa fa-angle-up"></i></h4>
			</div>
			<div class="row">
				<h2>Search for Doctor</h2>
			</div>
			<div class="row">
				<form action="<?php echo URLROOT; ?>/doctors/search/" method="GET" class="col-12 btn-group">
					<input type="text" class="form-control" name="search" placeholder="Enter name of the Doctor">
					<button><i class="fas fa-search"></i></button>
				</form>
			</div>
			<form action="<?php echo URLROOT; ?>/cities" method="GET" class="row">
				<button class="btn btn-lg offset-2 col-8">Find your Doctor</button>
			</form>
		</div>
	</div>

	<script>
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
	</script>

<?php require_once APPROOT . '/views/inc/footer.php'; ?>