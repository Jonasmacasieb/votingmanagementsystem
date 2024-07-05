<?php include 'db_connect.php'; ?>

<form action="" method="POST" id="manage-time">
    <label for="" class="control-label">Timer Duration</label>
    <select name="time_duration" class="form-control">
        <option value="8:00:00">8 hours</option>
      
      
    </select>
  
</form>


<script>$('#manage-time').submit(function (e) {
		e.preventDefault()
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_time',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			error: err => {
				console.log(err)
			},
			success: function (resp) {
				if (resp == 1) {
					alert_toast('Data successfully saved.', 'success')
					setTimeout(function () {
						location.reload()
					}, 1500)
				} else if (resp == 2) {
					alert_toast('Data successfully updated.', 'success')
					setTimeout(function () {
						location.reload()
					}, 1500)
				}
                else{
                    alert_toast(' Data error', 'danger')
                }
			}
		})

	})
    </script>