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

    .card-header {
        background-color: #3498db;
        color: #fff;
        font-weight: bold;
    }

    .card-footer {
        background-color: #f2f2f2;
    }


    .btn-default {
        background-color: #e0e0e0;
        color: #333;
        border-color: #ccc;
    }

    .btn-default:hover {
        background-color: #d4d4d4;
        color: #333;
        border-color: #bbb;
    }

    th {
        text-align: center;
        padding: 10px;
        background-color: #ffcc80;
        color: #1d3557;
        font-weight: bold;
        border: 1px solid #ffcc80;
    }
</style>
<?php include('db_connect.php'); ?>

<div class="container-fluid">

    <div class="col-lg-12">
        <div class="row">
            <!-- FORM Panel -->
            <div class="col-md-6">
                <form action="" id="manage-partylist">
                    <div class="card">
                        <div class="card-header">
                            Partylist Form
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="id">
                            <div class="form-group">
                                <label class="control-label">Party List</label>
                                <input type="text" class="form-control" name="partylist">
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary col-sm-2"> Save</button>
                                    <button class="btn btn-sm btn-default col-sm-2" type="button" onclick="$('#manage-partylist').get(0).reset()"> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>





            <!-- FORM Panel -->


            <!-- Table Panel -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">List of Party</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $cats = $conn->query("SELECT * FROM party_list order by id asc");
                                while ($row = $cats->fetch_assoc()) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td class="text-center"><?php echo $row['partylist'] ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary edit_cat" type="button" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['partylist'] ?>">Edit</button>
                                            <button class="btn btn-sm btn-danger delete_cat" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>

</div>

<script>
    $('#manage-partylist').submit(function(e) {
        e.preventDefault()
        start_load()
        $.ajax({
            url: 'ajax.php?action=save_partylist',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully added", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)

                } else if (resp == 2) {
                    alert_toast("Data successfully updated", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)

                }
            }
        })
    })
    $('.edit_cat').click(function() {
        start_load()
        var cat = $('#manage-partylist')
        cat.get(0).reset()
        cat.find("[name='id']").val($(this).attr('data-id'))
        cat.find("[name='partylist']").val($(this).attr('data-name'))
        end_load()
    })
    $('.delete_cat').click(function() {
        _conf("Are you sure to delete this Partylist?", "delete_cat", [$(this).attr('data-id')])
    })

    function delete_cat($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_partylist',
            method: 'POST',
            data: {
                id: $id
            },
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