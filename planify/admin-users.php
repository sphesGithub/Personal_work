<?php include "secure.php" ?>
<?php include "config/config.php" ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title> Users | PlanifyAdmin</title>
    <link href="style.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width" />
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
</head>

<body>

    <?php include("components/navbar.php") ?>
    <?php $page_title="Personnel"; include("components/header.php") ?>
    <section class="container" style="margin-top: 30px;">

        <a href='new_admin.php' class='btn-primary' style="margin-bottom: 30px;"> New Admin </a>

        <?php 
         $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Query to fetch data from the "user" table
        $sql = "SELECT user_id, Name, Surname, Email FROM user where role = 'admin'";
        $result = $conn->query($sql);
        
            echo "
                    <table>
                    <h2>Staff</h2>
                    <tr>
                        <th style='text-align: left;'>Name</th>
                        <th style='text-align: left;'>Surname</th>
                        <th style='text-align: left;'>Email</th>
                        <th style='text-align: left;'>Action</th>
                    </tr>    
                ";
            // Fetch and display the data
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["Name"] . "</td>
                        <td>" . $row["Surname"] . "</td>
                        <td>" . $row["Email"] . "</td>
                        <td>
                            <a href='edit_user.php?user_id=" . $row['user_id'] . "' class='link-text' > edit </a>
                            <a href='delete_user.php?user_id=" . $row['user_id'] . "' class='link-text' onClick= \" return confirm('Are you sure you want to delete "
                            .  $row['Name'] . " ?') \"> delete </a>
                        </td>
                        </tr>";
                        
            }
            // close the table
            echo "</table>";
            //close db connection
            $conn->close();
        ?>
        <?php 
         $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Query to fetch data from the "user" table
        $sql = "SELECT user_id, Name, Surname, Email FROM user where role = 'user'";
        $result = $conn->query($sql);
        
            echo "
                    <table>
                    <h2>Users</h2>
                    <tr>
                        <th style='text-align: left;'>Name</th>
                        <th style='text-align: left;'>Surname</th>
                        <th style='text-align: left;'>Email</th>
                        <th style='text-align: left;'>Action</th>
                    </tr>    
                ";
            // Fetch and display the data
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["Name"] . "</td>
                        <td>" . $row["Surname"] . "</td>
                        <td>" . $row["Email"] . "</td>
                        <td>
                            <a href='edit_user.php?user_id=" . $row['user_id'] . "' class='link-text' > edit </a>
                            <a href='delete_user.php?user_id=" . $row['user_id'] . "' class='link-text' onClick= \" return confirm('Are you sure you want to delete "
                            .  $row['Name'] . " ?') \"> delete </a>
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