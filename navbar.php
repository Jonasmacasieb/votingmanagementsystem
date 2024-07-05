<style>
	#sidebar {
		margin-top: 30px;
		/* Adjust the margin-top value as needed */
	}
</style>

<!-- <nav id="sidebar" class='mx-lt-5 bg-dark' > -->
<nav id="sidebar" class='mx-lt-5' style="background: linear-gradient(to bottom,  #3498db, #000000); box-shadow: 0 0 10px rgba(0, 0, 0, 0.8);">
	<!-- <nav id="sidebar" class='mx-lt-5' style="background: linear-gradient(to bottom,  #3498db, #87ceeb); box-shadow: 0 0 10px rgba(0, 0, 0, 0.8);"> -->

	<div class="sidebar-list">
	<?php if ($_SESSION['login_type'] == 1) : ?>
		<a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Home</a>
		<a href="index.php?page=categories" class="nav-item nav-categories"><span class='icon-field'><i class="fa fa-list"></i></span> Position List</a>
		<a href="index.php?page=partylist" class="nav-item nav-partylist"><span class='icon-field'><i class="fa fa-list"></i></span> Party List</a>
		<a href="index.php?page=voting_list" class="nav-item nav-voting_list nav-manage_voting"><span class='icon-field'><i class="fa fa-poll-h"></i></span> Category List</a>
	
			<a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users"></i></span> Users</a>
			<a href="index.php?page=ongoingvotes" class="nav-item nav-ongoingvotes"><span class='icon-field'><i class="fa fa-chart-area"></i></span> On Going Votes </a>
			<a href="index.php?page=logs" class="nav-item nav-logs"><span class='icon-field'><i class="fa fa-file"></i></span> Logs </a>
			<a href="index.php?page=votelogs" class="nav-item nav-votelogs"><span class='icon-field'><i class="fa fa-file"></i></span> Voter Logs </a>
			<a href="index.php?page=departmentgraph" class="nav-item nav-departmentgraph"><span class='icon-field'><i class="fa fa-chart-bar"></i></span> Departments Graph </a>

		<?php endif; ?>
	</div>

</nav>
<script>
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>