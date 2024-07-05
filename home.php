<style>
	.custom-menu {
		z-index: 1000;
		position: absolute;
		background-color: #ffffff;
		border: 1px solid #0000001c;
		border-radius: 5px;
		padding: 8px;
		min-width: 13vw;
	}

	a.custom-menu-list {
		width: 100%;
		display: flex;
		color: #4c4b4b;
		font-weight: 600;
		font-size: 1em;
		padding: 1px 11px;
	}

	.containe-fluid {
		margin-top: 40px;
		/* Adjust the margin-top value as needed */
	}

	span.card-icon {
		position: absolute;
		font-size: 3em;
		bottom: .2em;
		color: #ffffff80;
	}

	.file-item {
		cursor: pointer;
	}

	a.custom-menu-list:hover,
	.file-item:hover,
	.file-item.active {
		background: #80808024;
	}

	a.custom-menu-list span.icon {
		width: 1em;
		margin-right: 5px
	}

	.candidate {
		margin: auto;
		width: 23vw;
		padding: 0 10px;
		border-radius: 20px;
		margin-bottom: 1em;
		display: flex;
		border: 3px solid #00000008;
		background: #8080801a;

	}

	.candidate_name {
		margin: 8px;
		margin-left: 3.4em;
		margin-right: 3em;
		width: 100%;
	}

	.img-field {
		display: flex;
		height: 8vh;
		width: 4.3vw;
		padding: .3em;
		background: #80808047;
		border-radius: 50%;
		position: absolute;
		left: -.7em;
		top: -.7em;
	}

	.candidate img {
		height: 100%;
		width: 100%;
		margin: auto;
		border-radius: 50%;
	}

	.vote-field {
		position: absolute;
		right: 0;
		bottom: -.4em;
	}

	table {
		width: 100%;
		border-collapse: collapse;
		margin-top: 20px;
	}

	th,
	td {
		border: 1px solid #dddddd;
		padding: 12px;
		text-align: left;
	}

	th {
		background-color: yellow;
		color: #000;
		/* Header text color */
	}

	tr:nth-child(even) {
		background-color: lightblue;
		/* Even row background color */
	}

	tr:nth-child(odd) {
		background-color: lightyellow;
		/* Odd row background color */
	}


	td {
		color: #000;
		/* Cell text color */
	}

	h1 {
		text-align: center;
		color: #333;
		/* Heading text color */
	}
</style>

<div class="containe-fluid">
	<?php include('db_connect.php');


	?>
	<br><br>
	<div class="row">
		<div class="col-lg-12">
			<div class="card col-md-4 offset-2 bg-info float-left">
				<div class="card-body text-white">
					<h4><b>Voters</b></h4>
					<hr>
					<span class="card-icon"><i class="fa fa-users"></i></span>
					<h3 class="text-right"><b><?php echo $conn->query('SELECT * FROM users where type = 2 ')->num_rows ?></b></h3>
				</div>
			</div>
			<div class="card col-md-4 offset-2 bg-primary ml-4 float-left">
				<div class="card-body text-white">
					<h4><b>Admin</b></h4>
					<hr>
					<span class="card-icon"><i class="fa fa-user-tie"></i></span>
					<h3 class="text-right"><b><?php echo $conn->query('SELECT * FROM users where type = 1')->num_rows ?></b></h3>
				</div>
			</div>
		</div>
	</div>
	<!-- Calendar remainder -->
	<br>
	<table>
		<center>
			<h1>Events Reminder</h1>
		</center>
		<tr>

			<th>
				<center>Title</center>
			</th>
			<th>
				<center>Date</center>
			</th>

			<th>
				<center>Description</center>
			</th>
		</tr>

		<tbody>
			<?php
			$votes = $conn->query("SELECT * FROM voting_list");

			while ($row = $votes->fetch_assoc()) :
			?>
				<tr>

					<td>
						<center><?php echo $row['title'] ?></center>
					</td>
					<td>
						<center><?php echo $row['votedate'] ?></center>
					</td>

					<td>
						<center><?php echo $row['description'] ?></center>
					</td>

				</tr>
			<?php endwhile; ?>
		</tbody>

	</table>

	<script>

	</script>