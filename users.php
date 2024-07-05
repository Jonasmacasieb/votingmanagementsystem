<?php
include 'db_connect.php';
?>

<style>
	.container-fluid {
		margin-top: 40px;
		/* Adjust the margin-top value as needed */
	}

	.card {
		border: 1px solid #3498db;
		border-radius: 10px;
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
	}

	th {
		text-align: center;
		padding: 10px;
		background-color: #ffcc80;
		color: #1d3557;
		font-weight: bold;
		border: 1px solid #ffcc80;
	}

	.header {
		background-color: #3498db;
		color: #fff;
		font-weight: bold;
	}
</style>
<div class="container-fluid">

	<div class="column">
		<div class="col-lg-12">
			<button class="btn btn-primary float-right btn-sm" id="new_user"><i class="fa fa-plus"></i>Students</button>
		</div>
		<div class="col-lg-10">
			<button class="btn btn-primary float-right btn-sm" id="new_admin"><i class="fa fa-plus"></i> Admin</button>
		</div>

	</div>
	<br>


	<br>



	<!-- admin -->
	<div class="row">
		<div class="card col-lg-12">

			<div class="card-body">

				<table class="table-striped table-bordered col-md-12">
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">Name</th>
							<th class="text-center">School ID</th>
							<th class="text-center">Status</th>
							<th class="text-center">Roles</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						include 'db_connect.php';
						$users = $conn->query("SELECT * FROM users where type =1 ");
						$i = 1;
						while ($row = $users->fetch_assoc()) :
						?>
							<tr>
								<td>
									<?php echo $i++ ?>
								</td>
								<td>
									<?php echo $row['name'] ?>
								</td>
								<td>
									<center><?php echo $row['username'] ?></center>
								</td>

								<td data-user-id="<?php echo $row['id']; ?>">
									<center id="online_status_<?php echo $row['id']; ?>">
										<?php echo $row['online_status']; ?>
									</center>
								</td>
								<td>
									<center> <?php echo ($row['type'] == 1) ? 'Admin' : 'Student'; ?></center>
								</td>
								<td>
									<center>
										<div class="btn-group">
											<button type="button" class="btn btn-primary">Action</button>
											<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="sr-only">Toggle Dropdown</span>
											</button>
											<div class="dropdown-menu">
												<button class="dropdown-item edit_admin" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'>Edit</button>
												<div class="dropdown-divider"></div>
												<button class="dropdown-item delete_user" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'>Delete</button>


											</div>
										</div>
									</center>
								</td>
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<br>

<?php


	// Fetch the voting ongoing status from the database
	$votingQuery = $conn->query("SELECT is_default FROM voting_list WHERE is_default = 1");

	// Check if there is a row with is_default set to 1
	if ($votingQuery->num_rows > 0) {
		// Voting is ongoing, disable the action buttons
		$actionDisabled = true;
	} else {
		// Voting is not ongoing, enable the action buttons
		$actionDisabled = false;
	}


	?>

	<div class="row">
		<div class="card col-lg-12">
			<div>
				List of Students
			</div>
			<div class="card-body">
				<table id="studentTable" class="table-striped table-bordered col-md-12">
					<!-- Table Header -->
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">Name</th>
							<th class="text-center">School ID</th>
							<th class="text-center">Course</th>
							<th class="text-center">Status</th>
							<th class="text-center">Roles</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<!-- Table Body -->
					<tbody>
						<?php
						$users = $conn->query("SELECT * FROM users where type = 2");
						$i = 1;
						while ($row = $users->fetch_assoc()) :
						?>
							<tr>
								<td><?php echo $i++ ?></td>
								<td><?php echo $row['name'] ?></td>
								<td>
									<center><?php echo $row['username'] ?></center>
								</td>
								<td>
									<center><?php echo $row['department'] ?></center>
								</td>
								<td data-user-id="<?php echo $row['id']; ?>">
									<center id="online_status_<?php echo $row['id']; ?>"><?php echo $row['online_status']; ?></center>
								</td>
								<td>
									<center><?php echo ($row['type'] == 1) ? 'Admin' : 'Student'; ?></center>
								</td>
								<td>
									<center>
										<div class="btn-group">
											<button type="button" class="btn btn-primary <?php echo ($actionDisabled) ? 'disabled' : ''; ?>">Action</button>
											<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" <?php echo ($actionDisabled) ? 'disabled' : ''; ?>>
												<span class="sr-only">Toggle Dropdown</span>
											</button>
											<div class="dropdown-menu">
												<button class="dropdown-item edit_user" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' <?php echo ($actionDisabled) ? 'disabled' : ''; ?>>Edit</button>
												<div class="dropdown-divider"></div>
												<button class="dropdown-item delete_user" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' <?php echo ($actionDisabled) ? 'disabled' : ''; ?>>Delete</button>
											</div>
										</div>
									</center>
								</td>
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<script>
		// Initialize DataTable
		$(document).ready(function() {
			$('#studentTable').DataTable();
		});
	</script>

	<script>
		function updateOnlineStatus(user_id) {
			$.ajax({
				url: 'get_online_status.php',
				type: 'GET',
				data: {
					id: user_id
				},
				success: function(data) {
					$('#online_status_' + user_id).html(data);
				},
				error: function() {
					console.error('Error updating online status');
				}
			});
		}

		// Call updateOnlineStatus for each user every 0,5 seconds
		function refreshOnlineStatus() {
			$('[data-user-id]').each(function() {
				var user_id = $(this).data('user-id');
				updateOnlineStatus(user_id);
			});
		}

		setInterval(refreshOnlineStatus, 500);
	</script>


	<script>
		$('#new_user').click(function() {
			uni_modal('Student Details', 'manage_user.php')
		})
		$('#new_admin').click(function() {
			uni_modal('New admin', 'manage_admin.php')
		})
		$('.edit_admin').click(function() {
			uni_modal('Edit Admin', 'manage_admin.php?id=' + $(this).attr('data-id'))
		})

		$('.edit_user').click(function() {
			uni_modal('Edit User', 'manage_user.php?id=' + $(this).attr('data-id'))
		})

		$('.delete_user').click(function() {
			var user_id = $(this).attr('data-id'); // Capture the data-id attribute
			_conf("Are you sure to delete this user?", "delete_user", [user_id])
		})

		function delete_user(user_id) {
			start_load()
			$.ajax({
				url: 'ajax.php?action=delete_user',
				method: 'POST',
				data: {
					id: user_id
				}, // Use the captured user_id instead of undefined $id
				success: function(resp) {
					if (resp == 1) {
						alert_toast("Data successfully deleted", 'success')
						setTimeout(function() {
							location.reload()
						}, 1500)
					}
				}
			})
		}
	</script>