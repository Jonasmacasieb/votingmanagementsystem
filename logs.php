<?php
include('db_connect.php');


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter's Logs</title>
    <style>
        /* Add any additional styles here */
        @media print {
            body * {
                visibility: hidden;
            }

            /* Show only the printable tables */
            #adminprintableTable,
            #StudentsprintableTable {
                visibility: visible;
            }

            /* Additional styles for table printing */
            table {
                border-collapse: collapse;
                width: 100%;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
                font-weight: bold;
                text-align: center;
            }

            /* Custom design for printed table */
            th.print-header,
            td.print-data {
                background-color: #f9f9f9;
                font-weight: bold;
            }

            th.print-header {
                border-bottom: 2px solid #ddd;
            }

            td.print-data {
                border-bottom: 1px solid #ddd;
            }

            /* Custom design for table caption */
            caption {
                margin-bottom: 10px;
                font-style: italic;
                color: #666;
            }
        }


        /* Additional styles for non-print view */
        .button-15 {
            background-image: linear-gradient(#42A1EC, #0070C9);
            border: 1px solid #0077CC;
            border-radius: 4px;
            box-sizing: border-box;
            color: #FFFFFF;
            cursor: pointer;
            direction: ltr;
            display: block;
            font-family: "SF Pro Text", "SF Pro Icons", "AOS Icons", "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 17px;
            font-weight: 400;
            letter-spacing: -.022em;
            line-height: 1.47059;
            min-width: 30px;
            overflow: visible;
            padding: 4px 15px;
            text-align: center;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            white-space: nowrap;
        }

        .button-15:disabled {
            cursor: default;
            opacity: .3;
        }

        .button-15:hover {
            background-image: linear-gradient(#51A9EE, #147BCD);
            border-color: #1482D0;
            text-decoration: none;
        }

        .button-15:active {
            background-image: linear-gradient(#3D94D9, #0067B9);
            border-color: #006DBC;
            outline: none;
        }

        .button-15:focus {
            box-shadow: rgba(131, 192, 253, 0.5) 0 0 0 3px;
            outline: none;
        }

        th {
            text-align: center;
            background-color: #ffcc80;
            color: #000;
            font-size: 15px;
        }

        @media screen {
            #headerlogo {
                display: none;
            }
        }
    </style>
</head>

<body>
    <br>
    <h2>Logs</h2>




    <?php
    function fetchAdminLoginActivities($conn)
    {
        // Set the timezone to Manila
        date_default_timezone_set('Asia/Manila');

        // SQL query to fetch admin login activities
        $sql = "SELECT *, DATE_FORMAT(login_time, '%Y-%m-%d %h:%i:%s %p') AS formatted_login_time,
               DATE_FORMAT(logout_time, '%Y-%m-%d %h:%i:%s %p') AS formatted_logout_time
               FROM login_logs INNER JOIN users ON users.id = login_logs.user_id
               WHERE user_type = 'Admin'
               ORDER BY login_time DESC";

        // Execute the query
        $result = mysqli_query($conn, $sql);

        // Array to store login activities
        $loginActivities = array();

        // Fetch login activities and format time
        while ($row = mysqli_fetch_assoc($result)) {
            // Add formatted login time to the row
            $row['formatted_login_time'] = date('Y-m-d h:i:s A', strtotime($row['login_time']));
            // Add formatted logout time to the row
            $row['formatted_logout_time'] = date('Y-m-d h:i:s A', strtotime($row['logout_time']));
            $loginActivities[] = $row;
        }

        // Return login activities
        return $loginActivities;
    }

    // Call the function to fetch admin login activities
    $adminLoginActivities = fetchAdminLoginActivities($conn);
    ?>

    <button class="float-right text-white mt-3 button-15" id="adminprintButton"> <i class="fa fa-print" style="color: black;"></i> Print</button>
    <table id="adminprintableTable" border="1" class="table table-bordered table-hover">


        <thead>

            <tr>
                <th>User type</th>
                <th>School ID</th>
                <th>Login Time</th>
                <th>Logout Time</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($adminLoginActivities as $adminactivity) : ?>
                <tr>
                    <td>
                        <center> <?php echo $adminactivity['user_type']; ?></center>
                    </td>
                    <td>
                        <center> <?php echo $adminactivity['username']; ?></center>

                    <td>
                        <center><?php echo $adminactivity['formatted_login_time']; ?></center>
                    </td>
                    <td>
                        <center><?php echo $adminactivity['formatted_logout_time']; ?></center>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

    <?php
    function fetchStudentsLoginActivities($conn)
    {
        // Set the timezone to Manila
        date_default_timezone_set('Asia/Manila');

        // SQL query to fetch admin login activities
        $sql = "SELECT *, DATE_FORMAT(login_time, '%Y-%m-%d %h:%i:%s %p') AS formatted_login_time,
        DATE_FORMAT(logout_time, '%Y-%m-%d %h:%i:%s %p') AS formatted_logout_time
        FROM login_logs INNER JOIN users ON users.id = login_logs.user_id
        WHERE user_type = 'Students'
        ORDER BY login_time DESC";

        // Execute the query
        $result = mysqli_query($conn, $sql);

        // Array to store login activities
        $loginActivities = array();

        // Fetch login activities and format time
        while ($row = mysqli_fetch_assoc($result)) {
            // Add formatted login time to the row
            $row['formatted_login_time'] = date('Y-m-d h:i:s A', strtotime($row['login_time']));
            // Add formatted logout time to the row
            $row['formatted_logout_time'] = date('Y-m-d h:i:s A', strtotime($row['logout_time']));
            $loginActivities[] = $row;
        }

        // Return login activities
        return $loginActivities;
    }

    // Call the function to fetch admin login activities
    $StudentsLoginActivities = fetchStudentsLoginActivities($conn);
    ?>

    <br><br>
    <button class="float-right text-white mt-3 button-15" id="studentprintButton"> <i class="fa fa-print" style="color: black;"></i> Print</button>
    <table id="StudentsprintableTable" border="1" class="table table-bordered table-hover">


        <thead>

            <tr>
                <th>User type</th>
                <th>School ID</th>
                <th>Login Time</th>
                <th>Logout Time</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($StudentsLoginActivities as $Studentsactivity) : ?>
                <tr>
                    <td>
                        <center> <?php echo $Studentsactivity['user_type']; ?></center>
                    </td>
                    <td>
                        <center> <?php echo $Studentsactivity['username']; ?></center>

                    <td>
                        <center><?php echo $Studentsactivity['formatted_login_time']; ?></center>
                    </td>
                    <td>
                        <center><?php echo $Studentsactivity['formatted_logout_time']; ?></center>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#StudentsprintableTable').DataTable();
        });
    </script>
    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#adminprintableTable').DataTable();
        });
    </script>

    <script>
        document.getElementById("adminprintButton").addEventListener("click", function() {
            printContent("adminprintableTable");
        });

        document.getElementById("studentprintButton").addEventListener("click", function() {
            printContent("StudentsprintableTable");
        });

        function printContent(tableId) {
            var content = document.getElementById(tableId).outerHTML;
            var printWindow = window.open('', '_blank');
            var logoSrc = 'perps logo.png'; // Specify the path or URL of your logo image
            var designHTML = '<div style="background-color: #f5f5f5; padding: 20px;">' +
                '<div style="display: inline-block;">' +
                '<img src="' + logoSrc + '" style="width: 50px; height: 50px; margin-bottom: 20px;">' +
                '</div>' +
                '<div style="display: inline-block; vertical-align: top;">' +
                '<h2 style="color: #333; text-align: center; width: 100%; height: 50px; margin: 10px;">Perpetual Help College of Pangasinan</h2>' +
                '</div>' +
                '</div>';

            printWindow.document.open();
            printWindow.document.write('<html><head><title>Print</title></head><body>');
            printWindow.document.write(designHTML); // Add the design HTML with logo and heading
            printWindow.document.write(content); // Add the table content
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>





</body>

</html>