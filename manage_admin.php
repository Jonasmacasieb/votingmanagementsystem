<?php
include('db_connect.php');

if (isset($_GET['id'])) {
    $user = $conn->query("SELECT * FROM users where id =" . $_GET['id']);
    foreach ($user->fetch_array() as $k => $v) {
        $meta[$k] = $v;
    }
}
?>
<style>
    .face {
        position: absolute;
        top: 90px;
    }

    #black-space.gray-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: black;
        opacity: 0.5;
        z-index: 9999;
        /* Adjust z-index as needed */
        display: none;
        /* Initially hide the black space */
    }
</style>
<div class="container-fluid">

    <form action="" id="manage-user" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id'] : '' ?>">

        <div class="form-group">
            <label for="picture">Profile Picture</label>
            <input type="file" name="picture" id="picture" class="form-control-file">

            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>" placeholder="Doe John M">

            <label for="username">School ID</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username'] : '' ?>" maxlength="11" oninput="formatSchoolID(this)" placeholder="00-0000-000">

            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" <?php if (!empty($_GET['id'])) echo 'placeholder="Leave blank to keep current password"'; ?> required>

            <label for="type">User Type</label>
            <select name="type" id="type" class="custom-select">
                <option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected' : '' ?>>Admin</option>
                <!-- Add other user type options here -->
            </select>
        </div>
    </form>


    <script>
        function formatSchoolID(input) {
            // Remove all non-digit characters
            var schoolID = input.value.replace(/\D/g, '');

            // Apply the pattern "00-0000-000"
            if (schoolID.length > 2) {
                schoolID = schoolID.substring(0, 2) + '-' + schoolID.substring(2);
            }
            if (schoolID.length > 7) {
                schoolID = schoolID.substring(0, 7) + '-' + schoolID.substring(7);
            }

            // Set the formatted value back to the input
            input.value = schoolID;
        }
    </script>
    <script>
        $('#manage-user').submit(function(e) {
            e.preventDefault();
            start_load();
            var formData = new FormData(this);

            $.ajax({
                url: 'ajax.php?action=save_admin',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(resp) {
                    if (resp == 1) {
                        alert_toast("Data successfully saved", 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        alert_toast("Failed to save data", 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert_toast("Error: " + error, 'error');
                }
            });
        });
    </script>

</div>