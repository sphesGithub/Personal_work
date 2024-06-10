<?php include "secure.php" ?>
<?php
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

    // Construct the SQL query to retrieve the user's current profile picture
    $sql = "SELECT profile_pic FROM user WHERE user_id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentProfilePic = $row['profile_pic'];

        // Check if the current profile picture is not 'default.png'
        if ($currentProfilePic !== 'default.png') {
            // Delete the non-default profile picture
            $profilePicPath = 'path/to/your/profile/pictures/' . $currentProfilePic;
            if (file_exists($profilePicPath)) {
                unlink($profilePicPath);
            }
        }

        // Construct the SQL query to update the user's profile picture to 'default.png'
        $updateSql = "UPDATE user SET profile_pic = 'default.png' WHERE user_id = $user_id";

        if ($conn->query($updateSql) === TRUE) {
            // Profile picture updated successfully
            // Redirect the user back to the profile page
            header("Location: profile.php");
        } else {
            // Error handling
            echo "Error updating profile picture: " . $conn->error;
        }
    } else {
        // User not found, handle accordingly
        echo "User not found.";
    }

    $conn->close();
} else {
    // If user_id is not provided, handle the error or redirect as needed
    echo "User ID not provided.";
}
?>