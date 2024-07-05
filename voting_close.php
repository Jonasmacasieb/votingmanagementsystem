<?php
include('db_connect.php'); // Make sure this path is correct

date_default_timezone_set('Asia/Shanghai');
$date = date('Y-m-d H:i:s');
// Check for elections to be closed
$queryCloseElections = "SELECT id FROM voting_list WHERE is_default = 1 AND CONCAT(votedate, ' ', endtime) <= '$date'";

$resultCloseElections = $conn->query($queryCloseElections);

if ($resultCloseElections === FALSE) {
    echo "Error fetching records: " . $conn->error . "\n";
} else {
    if ($resultCloseElections->num_rows > 0) {
        while ($row = $resultCloseElections->fetch_assoc()) {
            $electionId = $row['id'];
            $updateQuery = "UPDATE voting_list SET is_default = 0, is_closed = 1 WHERE id = " . $electionId;
            if ($conn->query($updateQuery) === TRUE) {
                echo "Election ID $electionId has been closed.\n";
            } else {
                echo "Error updating record for Election ID $electionId: " . $conn->error . "\n";
            }
        }
    } else {
        echo "No elections needed to be closed.\n";
    }
}
