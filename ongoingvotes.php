<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Title</title>
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/all.min.css">

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

        .gradient-background {
            background: linear-gradient(to bottom, #ff7e5f, #feb47b);
            color: white;
            padding: 10px;
            border-radius: 20px;
        }

        .card {
            border: 1px solid #3498db;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }





        .serif-font {
            font-family: serif;
            font-size: 40px;
        }

        .serif-description {
            font-family: sans-serif;
            font-size: 15px;
            color: #000;
        }

        .unsettled {
            position: relative;
            top: 20px;

        }

        @media (max-width: 768px) {
            .candidate {
                width: 90%;
                /* Adjust width for smaller screens */
                margin-left: auto;
                margin-right: auto;
            }

            .candidate_name {
                margin-left: 2em;
                /* Adjust margin for smaller screens */
                margin-right: 2em;
            }

            .unsettled {
                top: 0;
                /* Adjust position for smaller screens */
            }
        }
    </style>
</head>
<div class="containe-fluid">
    <?php include('db_connect.php');
    $voting = $conn->query("SELECT * FROM voting_list where  is_default = 1 ");
    foreach ($voting->fetch_array() as $key => $value) {
        $$key = $value;
    }
    $votes  = $conn->query("SELECT * FROM votes where voting_id = $id ");
    $v_arr = array();
    while ($row = $votes->fetch_assoc()) {
        if (!isset($v_arr[$row['voting_opt_id']]))
            $v_arr[$row['voting_opt_id']] = 0;

        $v_arr[$row['voting_opt_id']] += 1;
    }
    $opts = $conn->query("SELECT * FROM voting_opt where voting_id=" . $id);
    $opt_arr = array();
    while ($row = $opts->fetch_assoc()) {
        $opt_arr[$row['category_id']][] = $row;
    }

    ?>
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
                    <h4><b>Voted</b></h4>
                    <hr>
                    <span class="card-icon"><i class="fa fa-edit"></i></span>
                    <h3 class="text-right"><b><?php echo $conn->query('SELECT distinct(user_id) FROM votes where voting_id = ' . $id)->num_rows ?></b></h3>
                </div>

            </div>

            <div class="unsettled">
                <div class="p-4">
                    <div class="card col-md-4 offset-2 bg-danger mx-auto d-flex align-items-center ">


                        <div class="card-body text-white">

                            <h4><b>Unsettled</b></h4>

                            <hr>
                            <span class="card-icon"><i class="fa fa-user-tie"></i></span>
                            <h3 class="text-right"><b><?php

                                                        $usersWhoVoted = $conn->query('SELECT COUNT(DISTINCT user_id) AS num_users FROM votes WHERE voting_id = ' . $id)->fetch_assoc()['num_users'];


                                                        $totalUsersOfType2 = $conn->query('SELECT COUNT(*) AS num_users FROM users WHERE type = 2 ')->fetch_assoc()['num_users'];


                                                        $pendingVotes = max(0, $totalUsersOfType2 - $usersWhoVoted);


                                                        echo $pendingVotes;
                                                        ?>
                                </b></h3>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>



    <br><br><br>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            position: relative;
            margin: auto;
            width: 80%;
        }
    </style>

    <body>

        <div class="row mt-3 ml-3 mr-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">


                        <div class="text-center gradient-background">
                            <h3 class="serif-font"><b><?php echo $title ?></b></h3>
                            <small class="serif-description"><b><?php echo $description; ?></b></small>
                        </div>


                        <?php
                        $cats = $conn->query("SELECT * FROM category_list where id in (SELECT category_id from voting_opt where voting_id = '" . $id . "' )");
                        while ($row = $cats->fetch_assoc()) :
                        ?>
                            <hr>
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h3><b><?php echo $row['category'] ?></b></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="chart-container">
                                    <canvas id="chart-<?php echo $row['id']; ?>"></canvas>
                                </div>
                            </div>

                            <script>
                                var ctx<?php echo $row['id']; ?> = document.getElementById('chart-<?php echo $row['id']; ?>').getContext('2d');

                                // Calculate the total number of voters
                                var totalVoters = <?php echo $conn->query('SELECT COUNT(*) AS num_users FROM users WHERE type = 2 ')->fetch_assoc()['num_users']; ?>;

                                // Calculate the total number of votes
                                var totalVotes = <?php echo $conn->query('SELECT COUNT(*) FROM votes WHERE voting_id = ' . $id)->fetch_row()[0]; ?>;

                                // Calculate the total users who haven't voted
                                var totalUsersOfType2 = <?php echo $conn->query('SELECT COUNT(*) AS num_users FROM users WHERE type = 2 ')->fetch_assoc()['num_users']; ?>;

                                var myBarChart<?php echo $row['id']; ?> = new Chart(ctx<?php echo $row['id']; ?>, {
                                    type: 'bar',
                                    data: {
                                        labels: [<?php
                                                    $candidates = $opt_arr[$row['id']];
                                                    $labels = array_map(function ($candidate) {
                                                        return "'{$candidate['opt_txt']}'";
                                                    }, $candidates);
                                                    echo implode(',', $labels);
                                                    ?>],
                                        datasets: [{
                                            label: 'Votes',
                                            data: [<?php
                                                    $votes = array_map(function ($candidate) use ($v_arr) {
                                                        return isset($v_arr[$candidate['id']]) ? $v_arr[$candidate['id']] : 0;
                                                    }, $candidates);
                                                    echo implode(',', $votes);
                                                    ?>],
                                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                                            borderColor: 'rgba(75, 192, 192, 1)',
                                            borderWidth: 1,
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                max: totalVoters, // Set the maximum value of the y-axis to the total voters
                                                ticks: {
                                                    stepSize: Math.ceil(totalVoters * 0.25), // Set the step size to 25% of the total voters
                                                    callback: function(value) {
                                                        switch (value) {
                                                            case totalVoters:
                                                                return '100%';
                                                            case Math.ceil(totalVoters * 0.75):
                                                                return '75%';
                                                            case Math.ceil(totalVoters * 0.5):
                                                                return '50%';
                                                            case Math.ceil(totalVoters * 0.25):
                                                                return '25%';
                                                            case 0:
                                                                return '0%';
                                                            default:
                                                                return '';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }

                                });
                            </script>





                        <?php endwhile; ?>




                    </div>
                </div>
            </div>
        </div>

    </body>

    </html>