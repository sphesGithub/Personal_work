<?php
include "secure.php"; // Ensure that the user is signed in and has the necessary role permissions

// Include your configuration and utility files
include "config/config.php";
include "utils/utils.php";

// Check if the user_id is provided as a query parameter
if (isset($_GET['user_id'])) {
    // Retrieve the user_id from the query parameter
    $user_id = test_input($_GET['user_id']);

    // Create a database connection
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Start a database transaction for multiple SQL operations
    $conn->begin_transaction();

    // Construct the SQL query to delete all event records belonging to the user
    $deleteEventsSql = "DELETE FROM event WHERE user_id = $user_id";

    if ($conn->query($deleteEventsSql) === TRUE) {
        // Event records deleted successfully

        // Construct the SQL query to delete the user record
        $deleteUserSql = "DELETE FROM user WHERE user_id = $user_id";

        if ($conn->query($deleteUserSql) === TRUE) {
            // User record deleted successfully
            // Commit the transaction and redirect the user to a suitable page
            $conn->commit();
            header("Location: admin-users.php");
        } else {
            // Error handling for user record deletion
            $conn->rollback();
            echo "Error deleting user record: " . $conn->error;
        }
    } else {
        // Error handling for event record deletion
        $conn->rollback();
        echo "Error deleting event records: " . $conn->error;
    }

    $conn->close();
} else {
    // If user_id is not provided, handle the error or redirect as needed
    echo "User ID not provided.";
}
?>