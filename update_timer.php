<?php
include('db_connect.php');

// Check if the timer duration is set by the admin
if (isset($_POST['timer_duration']) && isset($_GET['id'])) {
    $timer_duration = intval($_POST['timer_duration']);
    $voting_id = intval($_GET['id']);

    // Update the timer duration in the voting_list table
    $conn->query("UPDATE voting_list SET timer_duration = $timer_duration WHERE id = $voting_id");

    echo "Timer duration updated successfully!";
} else {
    echo "Invalid request!";
}
?>
