<?php include 'db_connect.php' ?>
<?php
if (isset($_POST['voting_id']) && isset($_POST['opt_txt'])) {
	$voting_id = $_POST['voting_id'];
	$opt_txt = $_POST['opt_txt'];
	$motto = isset($_POST['motto']) ? $_POST['motto'] : ''; // Check if 'motto' is set

	// Insert data into the voting_opt table
	$stmt = $conn->prepare("INSERT INTO voting_opt (voting_id, opt_txt, motto) VALUES (?, ?, ?)");
	$stmt->bind_param("iss", $voting_id, $opt_txt, $motto);
	$stmt->execute();
	$stmt->close();
} else {
}
?>
<div class="container-fluid">
	<div class="col-lg-12">
		<form action="" id="manage-opt">
			<input type="hidden" name="voting_id" value="<?php echo $_GET['vid'] ?>">
			<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
			<div class="form-group">
				<label for="" class="control-label">Position</label>
				<select name="category_id" id="" class="custom-select browser-default">
					<?php
					$cats = $conn->query("SELECT * FROM category_list order by id asc");
					while ($row = $cats->fetch_assoc()) :
					?>
						<option value="<?php echo $row['id'] ?>" <?php echo isset($category_id) && $category_id == $row['id'] ? 'selected' : '' ?>>
							<?php echo $row['category'] ?>
						</option>
					<?php endwhile; ?>
				</select>
			</div>
			<div class="form-group">
				<label for="" class="control-label">Candidate Full Name</label>
				<input type="text" class="form-control" name="opt_txt" value="<?php echo isset($opt_txt) ? $opt_txt : '' ?>">
			</div>
			<div class="form-group">
				<label for="" class="control-label">Course</label>
				<input type="text" class="form-control" name="can_course" value="<?php echo isset($can_course) ? $can_course : '' ?>">
			</div>
			<div class="form-group">
				<label for="" class="control-label">Partylist</label>
				<select name="partylist_id" id="partylist" class="custom-select browser-default">
					<?php
					$partylists = $conn->query("SELECT * FROM party_list order by id asc");
					while ($row = $partylists->fetch_assoc()) :
					?>
						<option value="<?php echo $row['id'] ?>" <?php echo isset($partylist_id) && $partylist_id == $row['id'] ? 'selected' : '' ?>>
							<?php echo $row['partylist'] ?>
						</option>
					<?php endwhile; ?>
				</select>

			</div>
			<div class="form-group">
				<label for="" class="control-label">Image</label>
				<input type="file" class="form-control" name="img" onchange="displayImg(this,$(this))">
			</div>
			<div class="form-group">
				<img src="<?php echo isset($image_path) ? 'assets/img/' . $image_path : '' ?>" alt="" id="cimg">
			</div>
		</form>
	</div>
</div>

<style>
	img#cimg {
		max-height: 10vh;
		max-width: 6vw;
	}
</style>
<script>
	function displayImg(input, _this) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#cimg').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}
	$('#manage-opt').submit(function(e) {
		e.preventDefault()
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_opt',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			error: err => {
				console.log(err)
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast('Data successfully saved.', 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)
				} else if (resp == 2) {
					alert_toast('Data successfully updated.', 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)
				}
			}
		})

	})
	$('#manage-opt').submit(function(e) {
		e.preventDefault();
		start_load();
		$.ajax({
			// ... existing AJAX settings ...
		});
	});
</script>