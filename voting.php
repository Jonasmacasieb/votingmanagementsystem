<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Voting System</title>

    <?php
    session_start();
    if (!isset($_SESSION['login_id']))
        header('location:trylogin.php');
    include('header.php');
    ?>

    <?php
    include('db_connect.php');

    $user_id = $_SESSION['login_id'];
    $has_voted = $conn->query("SELECT has_voted FROM users WHERE id = $user_id")->fetch_assoc()['has_voted'];
    if ($has_voted == 1) {
        header('Location: already_voted.php');
        exit();
    }
    ?>

    <?php include 'topbar.php' ?>
</head>

<style>
    body {
        background: #80808045;
    }

    main {
        margin-left: unset !important;
        width: calc(100%) !important;
    }
</style>

<body>

    <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-white"></div>
    </div>
    <main id="view-panel">
        <?php $page = isset($_GET['page']) ? $_GET['page'] : 'vote_sheet'; ?>
        <?php include $page . '.php' ?>
    </main>

    <div id="preloader"></div>
    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

    <div class="modal fade" id="confirm_modal" role='dialog'>
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                </div>
                <div class="modal-body">
                    <div id="delete_content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uni_modal" role='dialog'>
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.start_load = function() {
            $('body').prepend('<div id="preloader2"></div>');
        }
        window.end_load = function() {
            $('#preloader2').fadeOut('fast', function() {
                $(this).remove();
            });
        }

        window.uni_modal = function($title = '', $url = '') {
            start_load();
            $.ajax({
                url: $url,
                error: err => {
                    console.log();
                    alert("An error occured");
                },
                success: function(resp) {
                    if (resp) {
                        $('#uni_modal .modal-title').html($title);
                        $('#uni_modal .modal-body').html(resp);
                        $('#uni_modal').modal('show');
                        end_load();
                    }
                }
            });
        }

        window._conf = function($msg = '', $func = '', $params = []) {
            $('#confirm_modal #confirm').attr('onclick', $func + "(" + $params.join(',') + ")");
            $('#confirm_modal .modal-body').html($msg);
            $('#confirm_modal').modal('show');
        }

        window.alert_toast = function($msg = 'TEST', $bg = 'success') {
            $('#alert_toast').removeClass('bg-success bg-danger bg-info bg-warning');

            if ($bg == 'success')
                $('#alert_toast').addClass('bg-success');
            if ($bg == 'danger')
                $('#alert_toast').addClass('bg-danger');
            if ($bg == 'info')
                $('#alert_toast').addClass('bg-info');
            if ($bg == 'warning')
                $('#alert_toast').addClass('bg-warning');
            $('#alert_toast .toast-body').html($msg);
            $('#alert_toast').toast({
                delay: 3000
            }).toast('show');
        }

        $(document).ready(function() {
            $('#preloader').fadeOut('fast', function() {
                $(this).remove();
            });
        });
    </script>

</body>
</html>
