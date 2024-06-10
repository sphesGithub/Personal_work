<?php include "secure.php" ?>
<?php include "config/config.php" ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title> Contact | PlanifyAdmin</title>
    <link href="style.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width" />
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
</head>

<body>

    <?php include("components/navbar.php") ?>
    <?php $page_title="Recieved Messages"; include("components/header.php") ?>
    <section class="container" style="margin-top: 30px;">
        <?php 
         $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Query to fetch data from the "message" table
        $sql = "SELECT * FROM message ";
        $result = $conn->query($sql);
        
            echo "
                    <table>
                    <h2>Staff</h2>
                    <tr>
                        <th style='text-align: left;'>Name</th>
                        <th style='text-align: left;'>Email</th>
                        <th style='text-align: left;'>Message</th>
                        <th style='text-align: left;'>Date sent</th>                          
                        <th style='text-align: left;'>Action</th>
                    </tr>    
                ";
            // Fetch and display the data
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["name"] . "</td>
                        <td>" . $row["email"] . "</td>
                        <td>" . $row["message"] . "</td>
                        <td>" . $row["date_sent"] . "</td>
                        <td>
                            <a href='delete_message.php?message_id=" . $row['message_id'] . "' class='link-text' onClick= \" return confirm('Are you sure you want to delete message from"
                            .  $row['name'] . " ?') \"> delete </a>
                        </td>
                        </tr>";
                        
            }
            // close the table
            echo "</table>";
            //close db connection
            $conn->close();
        ?>
    </section>
    <?php include("components/footer.php") ?>

</body>

</html>