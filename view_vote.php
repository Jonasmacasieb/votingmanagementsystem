<?php

include('db_connect.php');
session_start(); // Start the session

// Check if $_SESSION['login_id'] is set before using it
if (isset($_SESSION['login_id'])) {
    $login_id = $_SESSION['login_id'];
} else {
    // Handle the case where $_SESSION['login_id'] is not set
    // For example, redirect the user to a login page
    header("Location: login.php");
    exit();
}

$voting_result = $conn->query("SELECT * FROM voting_list where is_default = 1 ");
$voting_row = $voting_result->fetch_assoc();
$id = $voting_row['id']; // Assuming 'id' is the column name
$title = $voting_row['title']; // Assuming 'title' is the column name
$description = $voting_row['description']; // Assuming 'description' is the column name

// Fetch votes for the current user and voting
$mvotes = $conn->query("SELECT * FROM votes where voting_id = $id and user_id = $login_id");

// Initialize arrays
$vote_arr = array();
$opt_arr = array();

while ($row = $mvotes->fetch_assoc()) {
    $vote_arr[$row['category_id']][] = $row;
}

// Fetch options for the current voting
$opts = $conn->query("SELECT * FROM voting_opt where voting_id = $id");

while ($row = $opts->fetch_assoc()) {
    $opt_arr[$row['id']] = $row;
}
?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <style>
        .body {
            margin: 0;
            padding: 0;
        }

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

        @media (min-width: 992px) {
            .container-fluid {
                max-width: 1140px;
                /* Adjust container width for larger screens */
            }

            .col-md-12 {
                width: 100%;
                /* Full width for larger screens */
            }

            .candidate {
                width: 22%;
                /* Adjust candidate width for larger screens */
            }

            .serif-font {
                font-size: 42px;
                /* Increase title font size for larger screens */
            }

            .serif-description1,
            .serif-description {
                font-size: 18px;
                /* Increase description font size for larger screens */
            }
        }

        /* Media queries for responsiveness */
        @media (mix-width: 768px) {
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


            .item img {
                max-width: 100%;
                /* Adjust width for mobile devices */
                height: auto;
                /* Allow the height to adjust proportionally to the width */
            }
        }

        /* Additional media query for smaller tablets */
    </style>
</head>
<?php include('topbaruser.php')

?>
<br><br><br><br>
<div class="container-fluid">
    <div class="col-lg-12">

        <div class="card">
            <div class="row">
                <div class="col-md-12">
                    <a class="btn btn-primary btn-sm  col-md-2 " href="ongoingvotesuser.php?page=home">Click to View On Going Votes</a>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12">
                    <div class="text-center gradient-background">
                        <center>
                            <h3 class="serif-font"><b><?php echo $title ?></b></h3>
                            <small class="serif-description1"><b><?php echo $description; ?></b></small>
                        </center>
                        <br>


                    </div>

                    <?php
                    $cats = $conn->query("SELECT * FROM category_list where id in (SELECT category_id from voting_opt where voting_id = '" . $id . "' )");
                    while ($row = $cats->fetch_assoc()) :
                    ?>
                        <hr>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="text-center">
                                    <center>
                                        <h3><b><?php echo $row['category'] ?></b></h3>
                                    </center>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <?php
                            foreach ($vote_arr[$row['id']] as $voted) {
                            ?>
                                <div class="candidate" style="position: relative;">
                                    <div class="item">
                                        <div style="display: flex">
                                            <img src="assets/img/<?php echo $opt_arr[$voted['voting_opt_id']]['image_path'] ?>" alt="">
                                        </div>
                                        <br>
                                        <div class="text-center">
                                            <center>
                                                <large class="text-center"><b><?php echo ucwords($opt_arr[$voted['voting_opt_id']]['opt_txt']) ?></b></large>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php endwhile; ?>
                </div>
                <hr>
            </div>
        </div>
    </div>
</div>
<script>

</script>