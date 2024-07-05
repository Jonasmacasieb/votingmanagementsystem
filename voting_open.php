<?php
include('db_connect.php');

date_default_timezone_set('Asia/Shanghai');
$date = date('Y-m-d H:i:s');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn->autocommit(FALSE); // Start transaction

    $queryOpenElections = "SELECT id FROM voting_list WHERE is_default = 0 AND CONCAT(votedate, ' ', starttime) <= '$date' AND CONCAT(votedate, ' ', endtime) > '$date'";
    $resultOpenElections = $conn->query($queryOpenElections);

    if ($resultOpenElections->num_rows > 0) {
        while ($row = $resultOpenElections->fetch_assoc()) {
            $electionId = $row['id'];
            $updateQueryOpen = "UPDATE voting_list SET is_default = 1 WHERE id = " . $electionId;
            
            if ($conn->query($updateQueryOpen) === TRUE) {
                echo "Election ID $electionId is now open and set as default.\n";
            } else {
                throw new Exception("Error updating record to open Election ID $electionId: " . $conn->error);
            }
        }
        $conn->commit(); // Commit transaction
    } else {
        echo "No elections met the criteria to be opened or set as default.\n".$date;
    }
} catch (Exception $exception) {
    $conn->rollback(); // Rollback on exception
    echo "Error occurred: " . $exception->getMessage() . "\n";
}

$conn->close(); // Close the connection
?>
