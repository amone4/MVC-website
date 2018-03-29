<?php require_once APPROOT . '/views/inc/header.php'; ?>

	<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/doctors.css">

	<script type="application/javascript">
		const doctors = [<?php foreach ($data as $doctor) :
			echo "{
				sno: '$doctor->sno',
				doctor: '$doctor->name',
				photo: '$doctor->photo',
				description: '$doctor->description',
				city: '$doctor->city',
				category: '$doctor->category'
			},";
		endforeach;?>];
	</script>

	<div class="container" id="doctorSearch">
		<div class="row">
			<form action="<?php echo URLROOT; ?>/doctors/search/" method="GET" class="col-12 btn-group">
				<input type="text" class="form-control" name="search" placeholder="Enter name of the Doctor">
				<button><i class="fas fa-search"></i></button>
			</form>
		</div>
	</div>

	<div class="container">
		<?php dequeMessages(); ?>
	</div>

	<div class="container" id="doctors"></div>

	<div aria-label="Doctor navigation">
		<ul class="pagination justify-content-center" id="tabs"></ul>
	</div>

	<script type="application/javascript" src="<?php echo URLROOT; ?>/js/doctors.js"></script>

<?php require_once APPROOT . '/views/inc/footer.php'; ?>