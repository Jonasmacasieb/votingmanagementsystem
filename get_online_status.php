<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $result = $conn->query("SELECT online_status FROM users WHERE id = $user_id");

    if ($result) {
        $row = $result->fetch_assoc();

        // Set the color based on online status
        $color = ($row['online_status'] == 'online') ? 'green' : 'red';

        // Apply the color to the HTML output
        echo "<div style='color: $color;'>{$row['online_status']}</div>";
    } else {
        echo "Error fetching online status";
    }
} else {
    echo "User ID not provided";
}
