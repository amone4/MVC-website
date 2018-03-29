<?php require_once APPROOT . '/views/inc/header.php'; ?>

	<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/categories.css">

	<script type="application/javascript">
		const city = <?php echo $data['city']; ?>;
		const allCategories = [<?php foreach ($data['categories'] as $category) :
			echo "{
				sno: '$category->sno',
				category: '$category->name',
				photo: '$category->photo'
			},";
		endforeach; ?>];
		var categories = allCategories;
	</script>
	<script type="application/javascript" src="<?php echo URLROOT; ?>/js/categories.js"></script>

	<div class="container" id="search">
		<div class="col-12 btn-group">
			<input type="text" class="form-control" name="search" id="searchInput" placeholder="Search for Category" onkeyup="selectCategories();">
			<button onclick="selectCategories();"><i class="fas fa-search"></i></button>
		</div>
	</div>

	<div id="categories"></div>

	<div aria-label="Category navigation">
		<ul class="pagination justify-content-center" id="tabs"></ul>
	</div>

<?php require_once APPROOT . '/views/inc/footer.php'; ?>