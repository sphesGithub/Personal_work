<?php include "config/config.php" ?>
<?php

 $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

 $user_id = $_REQUEST['user_id'];

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch data from the "event" table
$sql = "SELECT date, event_id, title,start_time,end_time FROM event where user_id = $user_id";
$result = $conn->query($sql);

// Prepare an array to store event data
$events = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

// Close the database connection
$conn->close();

// Return the event data as JSON
header('Content-Type: application/json');
echo json_encode($events);
?>