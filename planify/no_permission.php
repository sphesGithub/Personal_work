<?php
// Start a session if one is not already started
session_start();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title> Access Denied | PlanifyAdmin</title>
    <link href="style.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width" />
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
</head>

<body>
    <?php include("components/navbar.php") ?>
    <?php $page_title="No permission"; include("components/header.php") ?>
    <section class="container" style="margin-top: 30px;">
        <div>
            <p>No permission to access this page!</p>
        </div>

    </section>
    <?php include("components/footer.php") ?>
</body>

</html>