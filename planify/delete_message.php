<?php include "secure.php" ?>
<?php include "config/config.php" ?>
<?php include "utils/utils.php" ?>
<?php
// Check if the event_id is provided as a query parameter
if (isset($_GET['message_id'])) {
    // Retrieve the event_id from the query parameter
    $message_id = test_input($_GET['message_id']);

    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Construct the SQL query to delete the event with the provided event_id
    $sql = "DELETE FROM Message WHERE message_id = $message_id";

    if ($conn->query($sql) === TRUE) {
        // Event deleted successfully
        // Redirect the user back to the calendar page
        header("Location: admin-messages.php");
    } else {
        // Error handling
        echo "Error deleting messge: " . $conn->error;
    }

    $conn->close();
} else {
    // If event_id is not provided, handle the error or redirect as needed
    echo "Message ID not provided.";
}
?>