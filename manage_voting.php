<?php include('db_connect.php'); ?>
<?php



$vote = $conn->query("SELECT * FROM voting_list where id=" . $_GET['id']);
foreach ($vote->fetch_array() as $key => $value) {
	$$key = $value;
}
$opts = $conn->query("SELECT * FROM voting_opt where voting_id =" . $_GET['id']);
$opt_arr = array();
$set_arr = array();

while ($row = $opts->fetch_assoc()) {
	$category_id = $row['category_id'];
	$opt_arr[$category_id][] = $row;
	$set_arr[$category_id] = array('id' => '', 'max_selection' => 1);

	// Check if the motto is not null before assigning
	$opt_arr[$category_id][count($opt_arr[$category_id]) - 1]['motto'] = $row['motto'] ?: '';

	// Debugging statement
	// var_dump($opt_arr[$category_id][count($opt_arr[$category_id]) - 1]['motto']);
}

$settings = $conn->query("SELECT * FROM voting_cat_settings where voting_id=" . $_GET['id']);
while ($row = $settings->fetch_assoc()) {
	$set_arr[$row['category_id']] = $row;
}

?>
<style>
	.candidate {
		margin: auto;
		width: 16vw;
		padding: 10px;
		cursor: pointer;
		border-radius: 3px;
		margin-bottom: 1em
	}

	.candidate:hover {
		background-color: #80808030;
		box-shadow: 2.5px 3px #00000063;
	}

	.candidate img {
		height: 14vh;
		width: 8vw;
		margin: auto;
	}

	span.rem_btn {
		position: absolute;
		right: 0;
		top: -1em;
		z-index: 10
	}
    .candidate-image {
        width: 200px;
        height: 200px;
        object-fit: cover;
    }

</style>
<div class="container-fluid mt-4">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<div class="col-lg-12">
					<div class="text-center">
						<h3><b>
								<?php echo $title ?>
							</b></h3>
						<small><b>
								<?php echo $description; ?>
							</b></small>
						<!-- new time -->
						<small><b><br>
								<?php echo $starttime . " - " . $endtime; ?>

							</b></small>
					</div>

					<div class="row">
						<div class="col-md-12">


							<!-- <button class="btn btn-sm btn-primary float-right" type="button" id="new_time"> Set time
									</button> -->



						</div>
					</div>


					<hr>
					<div class="row">
						<div class="col-md-12">
							<button class="btn btn-sm btn-primary float-right" type="button" id="new_opt">New
								Candidate</button>
						</div>
					</div>
					<?php
					$cats = $conn->query("SELECT * FROM category_list where id in (SELECT category_id from voting_opt where voting_id = '" . $_GET['id'] . "' )");

					while ($row = $cats->fetch_assoc()) :

					?>
						<hr>
						<div class="row mb-4">
							<div class="col-md-12">
								<div class="text-center">
									<h3><b>
											<?php echo $row['category'] ?>
										</b></h3>
									<span><a href="javascript:void(0)" class="edit_selection" data-id="<?php echo $set_arr[$row['id']]['id'] ?>" data-cid="<?php echo $row['id'] ?>" data-cname="<?php echo $row['category'] ?>" data-max="<?php echo $set_arr[$row['id']]['max_selection'] ?>"> <i class='fa fa-pen'></i></a></span>
									<small>Max Selection : <b>
											<?php echo $set_arr[$row['id']]['max_selection']; ?>
										</b></small>

								</div>
							</div>
						</div>
						<div class="row mt-3">

							<?php
							$partyListQuery = $conn->query("SELECT * FROM party_list WHERE id IN (SELECT partylist_id FROM voting_opt WHERE voting_id = '" . $_GET['id'] . "')");

							$partyListData = [];
							while ($party = $partyListQuery->fetch_assoc()) {
								$partyListData[$party['id']] = $party;
							}

							foreach ($opt_arr[$row['id']] as $candidate) {
							?>
								<div class="candidate" style="position: relative;">
									<span class="rem_btn"><button class="btn btn-rounded btn-sm btn-outline-danger del_candidated" data-id="<?php echo $candidate['id'] ?>"><i class="fa fa-trash"></i></button></span>
									<div class="item" data-id="<?php echo $candidate['id'] ?>">
										<div style="display: flex">
                                            <img src="assets/img/<?php echo $candidate['image_path']; ?>" alt="" class="candidate-image">
										</div>
										<br>
										<div class="text-center">
											<large class="text-center"><b><?php echo ucwords($candidate['opt_txt']) ?></b></large>

											<?php
											if (isset($partyListData[$candidate['partylist_id']])) {
												$party = $partyListData[$candidate['partylist_id']];
												echo "<p><em>Party: {$party['partylist']}</em></p>";
											}
											?>
										</div>
									</div>
								</div>
							<?php } ?>

						</div>
					<?php endwhile; ?>
				</div>

			</div>
		</div>
	</div>
</div>

<script>
	$('#new_time').click(function() {
		uni_modal("Set Time", 'manage_time.php?vid=<?php echo $_GET['id'] ?>')
	})

	$('#new_opt').click(function() {
		uni_modal("New Candidate", 'manage_opt.php?vid=<?php echo $_GET['id'] ?>')
	})
	$('.candidate>.item').click(function() {
		uni_modal("Edit Candidate", 'manage_opt.php?vid=<?php echo $_GET['id'] ?>&id=' + $(this).attr('data-id'))
	})
	$('.edit_selection').click(function() {
		uni_modal("Edit " + $(this).attr('data-cname') + " Category's Max Selection", 'manage_catset.php?vid=<?php echo $_GET['id'] ?>&cid=' + $(this).attr('data-cid') + '&max=' + $(this).attr('data-max') + '&id=' + $(this).attr('data-id'))
	})
	$('.del_candidated').click(function(e) {
		e.preventDefault()
		_conf("Are you sure to delete this candidate?", "delete_candidate", [$(this).attr('data-id')])
	})

	function delete_candidate($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_candidate',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						location.reload()
					}, 1000)

				}
			}
		})
	}
</script>