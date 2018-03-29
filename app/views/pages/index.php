<?php require_once APPROOT . '/views/inc/header.php'; ?>

	<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/index.css">

	<div id="intro" class="m-auto col-lg-6 col-md-8 col-sm-10 col-12 invisible">
		<div class="container-fluid">
			<div class="row d-inline">
				<h1>A one-liner relevant to the website</h1>
			</div>
			<div class="displayToggle" id="downButton">
				<h4>Search for Doctor<br><i class="fa fa-angle-down"></i></h4>
			</div>
		</div>
	</div>

	<div id="search" class="m-auto col-lg-6 col-md-8 col-sm-10 col-12">
		<div class="container-fluid">
			<div class="displayToggle" id="upButton">
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

	<script src="<?php echo URLROOT; ?>/js/index.js"></script>

<?php require_once APPROOT . '/views/inc/footer.php'; ?>