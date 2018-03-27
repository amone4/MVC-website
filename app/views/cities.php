<?php require_once APPROOT . '/views/inc/header.php'; ?>

	<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/cities.css">

	<script type="application/javascript">
		const allCities = [<?php foreach ($data as $city) :
			echo "{
				sno: '$city->sno',
				city: '$city->name',
				photo: '$city->photo'
			},";
		endforeach; ?>];
		var cities = allCities;
	</script>
	<script type="application/javascript" src="<?php echo URLROOT; ?>/js/cities.js"></script>

	<div class="container" id="search">
		<div class="col-12 btn-group">
			<input type="text" class="form-control" name="search" id="searchInput" placeholder="Search for City" onkeyup="selectCities();">
			<button onclick="selectCities();"><i class="fas fa-search"></i></button>
		</div>
	</div>

	<div id="cities"></div>

	<div aria-label="City navigation">
		<ul class="pagination justify-content-center" id="tabs"></ul>
	</div>

<?php require_once APPROOT . '/views/inc/footer.php'; ?>