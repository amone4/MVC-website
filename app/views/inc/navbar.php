	<nav class="container-fluid fixed-top">
		<div>
			<a id="brand" href="<?php echo URLROOT;?>"><?php echo SITENAME; ?></a>
			<a id="showOptions" class="float-right optionsToggle"><span class="fa fa-user"></span></a>
			<a id="hideOptions" class="float-right optionsToggle"><span class="fa fa-times"></span></a>
		</div>

		<div id="options" class="float-right text-center col-2 col-md-1">
			<table class="col-12">
				<thead>
				<?php if (validateLogin()) { ?>
					<tr><td><a href="<?php echo URLROOT; ?>/users/logout">Logout</a></td></tr>
					<tr><td><a href="<?php echo URLROOT; ?>/users/change">Change Password</a></td></tr>
				<?php } else { ?>
					<tr><td><a href="<?php echo URLROOT; ?>/users">Login</a></td></tr>
					<tr><td><a href="<?php echo URLROOT; ?>/users/register">Register</a></td></tr>
				<?php }?>
				</thead>
			</table>
		</div>
	</nav>

	<script src="<?php echo URLROOT; ?>/js/navbar.js"></script>