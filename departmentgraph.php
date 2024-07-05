<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pie Chart with Database</title>
    <style>
        /* Styles for the chart container go here */
        #chart-container {
            position: relative;
            margin: auto;
            width: 40%;
        }

        #bar-container {
            position: relative;
            margin: auto;
            width: 90%;
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

        .gradient-background {
            background: linear-gradient(to bottom, #ff7e5f, #feb47b);
            color: white;
            padding: 20px;
            border-radius: 10px;
            position: relative;
            top: 20px;
        }
    </style>
    <?php include('db_connect.php'); ?>
    <?php


    $sql = "SELECT department, COUNT(*) as vote_count FROM users WHERE type = 2 GROUP BY department";
    $result = $conn->query($sql);

    $totalUsers = 0;
    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $totalUsers += $row['vote_count'];
            $data[] = [
                'department' => $row['department'],
                'vote_count' => $row['vote_count'],
            ];
        }
    }

    // Calculate the percentage for each department
    foreach ($data as &$entry) {
        $entry['percentage'] = ($entry['vote_count'] / $totalUsers) * 100;
    }

    // Output the JSON-encoded data
    echo '<script>';
    echo 'var data = ' . json_encode($data) . ';';
    echo '</script>';
    ?>

</head>

<body>
    <br><br>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="container-fluid">
                    <div class="title">
                        <div class="text-center gradient-background">
                            <h3 class="serif-font"><b>Students</b></h3>
                        </div>
                    </div>
                    <br>
                    <div id="chart-container" style=" float: left;">
                        <canvas id="pieChart"></canvas>
                    </div>
                    <br><br>
                    <div id="legend-container" style="float: left;">
                        <!-- Legend will be displayed here -->
                    </div>

                    <script src="assets/js/chart.js"></script>
                    <script>
                        // Define a global variable to store the colors
                        let pieChartColors = [];

                        document.addEventListener("DOMContentLoaded", function() {
                            // Use the PHP-generated data to create the pie chart
                            createPieChart(data);
                        });

                        function createPieChart(data) {
                            const ctx = document.getElementById("pieChart").getContext("2d");

                            // Check if colors are not generated yet
                            if (pieChartColors.length === 0) {
                                // Generate colors and store them
                                pieChartColors = getRandomColors(data.length);
                            }

                            new Chart(ctx, {
                                type: 'doughnut',
                                data: {
                                    labels: data.map(entry => entry.department),
                                    datasets: [{
                                        data: data.map(entry => entry.percentage),
                                        backgroundColor: pieChartColors,
                                    }],
                                },
                                options: {
                                    tooltips: {
                                        callbacks: {
                                            label: function(tooltipItem, data) {
                                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                                var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                                                    return previousValue + currentValue;
                                                });
                                                var currentValue = dataset.data[tooltipItem.index];
                                                var percentage = ((currentValue / total) * 100).toFixed(2);
                                                return data.labels[tooltipItem.index] + ": " + percentage;
                                            }
                                        }
                                    },
                                    legend: {
                                        display: false, // Hide default legend
                                    },
                                },

                            });
                        }

                        // Create custom legend
                        const legendContainer = document.getElementById("legend-container");
                        const legendHTML = createLegendHTML(data);
                        legendContainer.innerHTML = legendHTML;


                        function createLegendHTML(data) {
                            let legendHTML = '<ul>';
                            data.forEach(entry => {
                                legendHTML += `<li>${entry.department}: ${entry.vote_count}</li>`;
                            });
                            legendHTML += '</ul>';
                            return legendHTML;
                        }

                        function getRandomColors(count) {
                            // Define a fixed set of colors
                            const fixedColors = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff', '#c0c0c0', '#808080', '#800000', '#008000'];

                            // Return a slice of the fixed colors array based on the count
                            return fixedColors.slice(0, count);
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>

    <br>

    <?php


    // Retrieve distinct department names from the database
    $department_query = "SELECT DISTINCT department FROM users WHERE type = 2";
    $department_result = $conn->query($department_query);

    // Initialize an array to store department names
    $all_departments = array();

    // Populate the array with department names
    while ($row = $department_result->fetch_assoc()) {
        $all_departments[] = $row['department'];
    }

    // Retrieve voting data for the default category
    $voting_query = "SELECT * FROM voting_list WHERE is_default = 1";
    $voting_result = $conn->query($voting_query);

    // Check if a default voting category exists
    if ($voting_result && $voting_result->num_rows > 0) {
        $default_voting = $voting_result->fetch_assoc();
        $default_voting_id = $default_voting['id'];

        // Retrieve votes for the default category, counting distinct user IDs
        $default_votes_query = "SELECT department, section, COUNT(DISTINCT v.user_id) as has_voted 
        FROM users u 
        INNER JOIN votes v ON u.id = v.user_id 
        WHERE v.voting_id = $default_voting_id 
        GROUP BY department, section";

        $default_votes_result = $conn->query($default_votes_query);

        // Initialize an array to store department data
        $departmentData = array();

        // Populate department data with votes for the default category
        while ($row = $default_votes_result->fetch_assoc()) {
            $department = $row['department'];
            $section = $row['section'];
            $has_voted = $row['has_voted'];

            // Create sub-array for department if not exists
            if (!isset($departmentData[$department])) {
                $departmentData[$department] = array();
            }

            // Store vote count for each section
            $departmentData[$department][$section] = array('section' => $section, 'has_voted' => $has_voted);
        }

        // Ensure all departments have data, even if some have no sections
        foreach ($all_departments as $department_name) {
            if (!isset($departmentData[$department_name])) {
                $departmentData[$department_name] = array();
            }
        }
    }
    ?>

    <br>

    <?php


    // Retrieve distinct department names from the database
    $department_query = "SELECT DISTINCT department FROM users WHERE type = 2";
    $department_result = $conn->query($department_query);

    // Initialize an array to store department names
    $all_departments = array();

    // Populate the array with department names
    while ($row = $department_result->fetch_assoc()) {
        $all_departments[] = $row['department'];
    }

    // Retrieve voting data for the default category
    $voting_query = "SELECT * FROM voting_list WHERE is_default = 1";
    $voting_result = $conn->query($voting_query);

    // Check if a default voting category exists
    if ($voting_result && $voting_result->num_rows > 0) {
        $default_voting = $voting_result->fetch_assoc();
        $default_voting_id = $default_voting['id'];

        // Retrieve votes for the default category, counting distinct user IDs
        $default_votes_query = "SELECT department, section, COUNT(DISTINCT v.user_id) as has_voted 
        FROM users u 
        INNER JOIN votes v ON u.id = v.user_id 
        WHERE v.voting_id = $default_voting_id 
        GROUP BY department, section";

        $default_votes_result = $conn->query($default_votes_query);

        // Initialize an array to store department data
        $departmentData = array();

        // Populate department data with votes for the default category
        while ($row = $default_votes_result->fetch_assoc()) {
            $department = $row['department'];
            $section = $row['section'];
            $has_voted = $row['has_voted'];

            // Create sub-array for department if not exists
            if (!isset($departmentData[$department])) {
                $departmentData[$department] = array();
            }

            // Store vote count for each section
            $departmentData[$department][$section] = array('section' => $section, 'has_voted' => $has_voted);
        }

        // Ensure all departments have data, even if some have no sections
        foreach ($all_departments as $department_name) {
            if (!isset($departmentData[$department_name])) {
                $departmentData[$department_name] = array();
            }
        }
    }
    ?>

    <!-- Bar chart section -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="title">
                    <div class="text-center gradient-background">
                        <h3 class="serif-font"><b>Departments</b></h3>
                    </div>
                </div>
                <div id="bar-container">
                    <canvas id="myBarChart"></canvas>
                </div>
                <script>
                    // JavaScript code for generating the bar chart
                    // Define a function to get random colors
                    function getRandomColor(count) {
                        // Define a fixed set of colors
                        const fixedColors = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff', '#c0c0c0', '#808080', '#800000', '#008000'];
                        // bsit pink  crim maron b
                        // Return a slice of the fixed colors array based on the count
                        return fixedColors.slice(0, count);
                    }

                    // Your data from PHP
                    var departmentData = <?php echo json_encode($departmentData); ?>;

                    // Create an array to store the datasets
                    var datasets = [];

                    // Get random colors based on the number of departments
                    var randomColors = getRandomColor(Object.keys(departmentData).length);


                    // Iterate through the departmentData and create datasets for each department
                    Object.keys(departmentData)
                        .sort() // Sort the departments alphabetically
                        .forEach(function(department, index) {
                            var departmentDataset = {
                                label: department, // Set the label to department name
                                data: [], // Initialize an array to store vote counts
                                backgroundColor: randomColors[index], // Use random color
                                borderColor: 'rgba(75, 192, 192, 1)', // Border color remains the same
                                borderWidth: 1
                            };

                            // Sum up all section votes for this department
                            var totalVotes = Object.values(departmentData[department]).reduce(function(total, sectionData) {
                                return total + sectionData.has_voted;
                            }, 0);

                            // Store total votes for the department
                            departmentDataset.data.push(totalVotes);

                            // Push the dataset to the datasets array
                            datasets.push(departmentDataset);
                        });


                    // Create a bar chart for each department
                    datasets.forEach(function(departmentDataset) {
                        var container = document.createElement('div'); // Create a container div for each chart
                        container.style.marginBottom = '50px'; // Add some bottom margin to separate the charts
                        document.getElementById('bar-container').appendChild(container); // Append container to chart-container

                        var ctx = document.createElement('canvas').getContext('2d');
                        container.appendChild(ctx.canvas); // Append canvas to container

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: [departmentDataset.label], // Set the label to department name
                                datasets: [departmentDataset]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 100, // Set your desired maximum value
                                        ticks: {
                                            stepSize: 25, // Set your desired step size
                                            callback: function(value) {
                                                return value.toFixed(0);
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>

            </div>
        </div>
    </div>



</body>

</html>