<?php
include('db_connect.php');

// Check if the user is logged in
if (!isset($_SESSION['login_id'])) {
    // Redirect to login page or any appropriate page
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['login_id'];
?>

<?php
$voting = $conn->query("SELECT * FROM voting_list WHERE is_default = 1");
foreach ($voting->fetch_array() as $key => $value) {
    $$key = $value;
}

$vote = $conn->query("SELECT * FROM voting_list WHERE id = " . $id);
foreach ($vote->fetch_array() as $key => $value) {
    $$key = $value;
}
$opts = $conn->query("SELECT * FROM voting_opt WHERE voting_id = " . $id);
$opt_arr = array();
$set_arr = array();

while ($row = $opts->fetch_assoc()) {
    $opt_arr[$row['category_id']][] = $row;
    $set_arr[$row['category_id']] = array('id' => '', 'max_selection' => 1);
}

$settings = $conn->query("SELECT * FROM voting_cat_settings WHERE voting_id = " . $id);
while ($row = $settings->fetch_assoc()) {
    $set_arr[$row['category_id']] = $row;
}

// Fetch party list information
$partyListQuery = $conn->query("SELECT * FROM party_list WHERE id IN (SELECT partylist_id FROM voting_opt WHERE voting_id = '" . $id . "')");
$partyListData = [];
while ($party = $partyListQuery->fetch_assoc()) {
    $partyListData[$party['id']] = $party;
}
?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
		.candidate {
			margin: auto;
			width: 100%;
			/* Adjust width for mobile devices */
			padding: 10px;
			cursor: pointer;
			border-radius: 3px;
			margin-bottom: 1em;
		}

		.candidate:hover {
			background-color: #80808030;
			box-shadow: 2.5px 3px #00000063;
		}

		.candidate img {
			height: 14vh;
			width: 100%;
			/* Adjust image width for mobile devices */
			margin: auto;
		}

		span.rem_btn {
			position: absolute;
			right: 0;
			top: -1em;
			z-index: 10;
			display: none;
		}

		span.rem_btn.active {
			display: block;
		}

		.gradient-background {
			background: linear-gradient(to bottom, #ff7e5f, #feb47b);
			color: white;
			padding: 10px;
			border-radius: 20px;
		}

		.serif-font {
			font-family: serif;
			font-size: 24px;
			/* Adjust title font size for mobile devices */
		}

		.serif-description1,
		.serif-description {
			font-family: sans-serif;
			font-size: 12px;
			/* Adjust description font size for mobile devices */
			color: #000;
		}

		.item img {
			max-width: 100%;
			height: auto;
		}

		/* Media queries for responsiveness */
		@media (min-width: 576px) {
			.candidate {
				width: 48%;
				/* Adjust width for medium-sized screens */
			}

			.serif-font {
				font-size: 32px;
				/* Adjust title font size for medium-sized screens */
			}

			.serif-description1,
			.serif-description {
				font-size: 14px;
				/* Adjust description font size for medium-sized screens */
			}

			button.btn-primary {
				width: 100%;
				/* Set button width to 100% of its container */
				max-width: 120px;
				/* Set maximum width for the button */
				margin: 0 auto;
				/* Center the button horizontally */
			}

			.item img {
				max-width: 100%;
				/* Adjust width for mobile devices */
				height: auto;
			}
		}

		@media (min-width: 768px) {
			.candidate {
				width: 22%;
				height: 32%;
			}

			.serif-font {
				font-size: 28px;
			}

			.serif-description1,
			.serif-description {
				font-size: 14px;
			}

			button.btn-primary {
				width: 200px;
			}

			.item img {
				max-width: 70%;
				height: auto;
			}
		}
        </style>
</head>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="" id="manage-vote">
                    <input type="hidden" name="voting_id" value="<?php echo $id; ?>">
                    <div class="col-lg-12">
                        <div class="text-center gradient-background">
                            <h3 class="serif-font"><b><?php echo $title; ?></b></h3>
                            <small class="serif-description1"><b><?php echo $description; ?></b></small>
                            <br>
                            <small class="serif-description"><b><?php echo $starttime . " - " . $endtime; ?></b></small>
                            <br>
                            <small class="serif-description"><b><?php echo $votedate; ?></b></small>
                        </div>
                        
                        <?php
                        $cats = $conn->query("SELECT * FROM category_list WHERE id IN (SELECT category_id FROM voting_opt WHERE voting_id = '" . $id . "')");
                        while ($row = $cats->fetch_assoc()) :
                        ?>
                            <hr>
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h3><b><?php echo $row['category']; ?></b></h3>
                                        <small>Max Selection: <b><?php echo $set_arr[$row['id']]['max_selection']; ?></b></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <?php foreach ($opt_arr[$row['id']] as $candidate) : ?>
                                    <div class="candidate" style="position: relative;" data-cid='<?php echo $row['id']; ?>' data-max="<?php echo $set_arr[$row['id']]['max_selection']; ?>" data-name="<?php echo $row['category']; ?>">
                                        <input type="checkbox" name="opt_id[<?php echo $row['id']; ?>][]" value="<?php echo $candidate['id']; ?>" style="display: none">
                                        <span class="rem_btn">
                                            <label for="" class="btn btn-primary"><span class="fa fa-circle"></span></label>
                                        </span>
                                        <div class="item" data-id="<?php echo $candidate['id']; ?>">
                                            <div style="display: flex">
                                                <img src="assets/img/<?php echo $candidate['image_path']; ?>" alt="">
                                            </div>
                                            <br>
                                            <div class="text-center">
                                                <large class="text-center"><b><?php echo ucwords($candidate['opt_txt']); ?></b></large>
                                                <?php
                                                if (isset($partyListData[$candidate['partylist_id']])) {
                                                    $party = $partyListData[$candidate['partylist_id']];
                                                    echo "<p><em>Partylist: {$party['partylist']}</em></p>";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <hr>
                    <center><button class="btn btn-primary">Submit</button></center>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
	$('.candidate').click(function() {
		var maxSelection = parseInt($(this).attr('data-max'));
		var checkedCount = $("input[name='opt_id[" + $(this).attr('data-cid') + "][]']:checked").length;

		if ($(this).find('input[type="checkbox"]').prop("checked")) {
			$(this).find('input[type="checkbox"]').prop("checked", false);
		} else {
			if (checkedCount >= maxSelection) {
				alert_toast("Choose only " + maxSelection + " for " + $(this).attr('data-name') + " category", "warning");
				return false;
			}
			$(this).find('input[type="checkbox"]').prop("checked", true);
		}

		updateRemBtnStatus();
	});

	function updateRemBtnStatus() {
		$('.candidate').each(function() {
			if ($(this).find('input[type="checkbox"]').prop("checked")) {
				$(this).find('.rem_btn').addClass('active');
			} else {
				$(this).find('.rem_btn').removeClass('active');
			}
		});
	}

	$('#manage-vote').submit(function(e) {
		e.preventDefault();
		var selected = false;
		$('.candidate input[type="radio"]').each(function() {
			if ($(this).is(':checked')) {
				selected = true;
				return false; 
			}
		});


		start_load();
		$.ajax({
			url: 'ajax.php?action=submit_vote',
			method: 'POST',
			data: $(this).serialize(),
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Vote successfully submitted");
					setTimeout(function() {
						window.location.href = 'already_voted.php';
					}, 1500);
				}
			}
		});
	});
</script>