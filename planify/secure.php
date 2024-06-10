<?php
session_start(); // Start the session

// Get the current page filename (e.g., "index.php", "calendar.php", etc.)
$current_page = basename($_SERVER['PHP_SELF']);

// Define an array that specifies which roles are allowed to access each page
$page_access_roles = array(
    "profile.php" => array("user","admin"),
    "edit_user.php" => array("user","admin"),
    "change_password.php" => array("user","admin"),
    "calendar.php" => array("user"),
    
    "new_admin.php" => array("admin"),
    "admin-messages.php" => array("admin"),
    "admin-users.php" => array("admin"),
    "systemtest.php" => array("user","admin"),

    "delete_profilepic.php" =>array("user","admin"),
    "delete_event.php" =>array("user","admin"),
    "delete_user.php" =>array("admin"),
    "delete_message.php" =>array("admin"),
    
);


// Check if the user is logged in
if (isset($_SESSION["role"])) {
    // Check if the current page is in the $page_access_roles array
    if (isset($page_access_roles[$current_page])) {
        // Check if the user's role is allowed for the current page
        if (in_array($_SESSION["role"], $page_access_roles[$current_page])) {
            // User has the required rights, so allow access
        } else {
            // User does not have the required rights, redirect to no_permission.php
            header("Location: no_permission.php");
            exit;
        }
    } else {
        // Current page is not specified in $page_access_roles, redirect to no_permission.php
        header("Location: no_permission.php");
        exit;
    }
} else {
    // User is not logged in, redirect to signin.php
    header("Location: signin.php");
    exit;
}
?>