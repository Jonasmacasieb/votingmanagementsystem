<?php
include('db_connect.php');


?>

<?php
$voting = $conn->query("SELECT * FROM voting_list where is_default = 1 ");
if ($voting && $voting->num_rows > 0) {
    foreach ($voting->fetch_array() as $key => $value) {
        $$key = $value;
    }

    $mvotes = $conn->query("SELECT * FROM votes where voting_id = $id and user_id = " . $_SESSION['login_id'] . " ");
    $vote_arr = array();
    while ($row = $mvotes->fetch_assoc()) {
        $vote_arr[$row['category_id']][] = $row;
    }

    $opts = $conn->query("SELECT * FROM voting_opt where voting_id=" . $id);
    $opt_arr = array();
    while ($row = $opts->fetch_assoc()) {
        $opt_arr[$row['id']] = $row;
    }
} else {
    echo "No active voting found.";
    exit; // Exit the script if there's no active voting
}
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

            #printableTable,
            #printableTable * {
                visibility: visible;
            }

            #printableTable {
                position: absolute;
                left: 0;
                top: 0;
            }

            /* Additional styles to improve table printing */
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
    </style>
</head>

<body>
    <br>
    <button class="float-right text-white mt-3 button-15" id="printButton"> <i class="fa fa-print" style="color: black;"></i> Print</button>
    <br><br><br>

    <h2>Voters Logs</h2>
    <?php
    include('db_connect.php');
    $cats = $conn->query("SELECT * FROM category_list WHERE id IN (SELECT category_id FROM voting_opt WHERE voting_id = '" . $id . "')");
    ?>

    <table id="printableTable" border="1" class="table table-bordered table-hover">

        <thead>
            <tr class="tr">
                <th class="print-header">Names</th> <!-- Print header style -->

            </tr>
        </thead>
        <tbody>
            <?php
            // Fetching users who voted
            $mycats = $conn->query("SELECT name, id FROM users WHERE id IN (SELECT user_id FROM votes WHERE voting_id = '" . $id . "')");
            while ($rowUser = $mycats->fetch_assoc()) : ?>
                <tr>
                    <td class="text-center print-data"><?php echo $rowUser['name']; ?></td> <!-- Print data style -->

                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>


    <script>
        document.getElementById("printButton").addEventListener("click", function() {
            printContent("printableTable");
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