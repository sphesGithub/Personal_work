<?php include "secure.php" ?>
<?php include "utils/utils.php" ?>
<?php include "config/config.php" ?>
<?php
    $picErr = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define the directory where profile pictures will be stored
    $targetDirectory = "images/profile_pictures/";

    if(empty(basename($_FILES["profile_picture"]["name"]))){
        $picErr = "No image selected!";
    }else{
    // Get the uploaded file name
    $fileName = time() . "_" . basename($_FILES["profile_picture"]["name"]);

    // Define the target path where the file will be saved
    $targetPath = $targetDirectory . $fileName;

    // Check if the file is an image
    $imageFileType = pathinfo($targetPath, PATHINFO_EXTENSION);
    if (getimagesize($_FILES["profile_picture"]["tmp_name"]) === false) {
        $picErr = "Invalid file format. Please upload an image.";
    } elseif (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetPath)) {
        // File uploaded successfully
        $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $user_id = $_SESSION['user_id'];

        // Construct the SQL query to update the user's profile picture
        $sql = "UPDATE user SET profile_pic = '$fileName' WHERE user_id = $user_id";

        if ($conn->query($sql) === TRUE) {
            // Profile picture updated successfully
            // Redirect the user back to profile page
            header("Location: profile.php");
        } else {
            $picErr = "Error updating profile picture: " . $conn->error;
        }

        $conn->close();
    
    } else {
        $picErr = "Error uploading the file. Please try again.";
    }
}
}
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title> Profile | Planify</title>
    <link href="style.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width" />
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
</head>

<body>

    <?php include("components/navbar.php") ?>
    <?php $page_title="User Profile"; include("components/header.php") ?>

    <section class="container" style="margin-top: 30px;">
        <?php 
         $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
         $name = $surname = $email = $birthday =$profile_pic= $role = $gender = "";
         $user_id = $_SESSION['user_id'];

            // Query to fetch data from the "user" table
            $sql = "SELECT Name, Surname, Email, Gender, Birthday ,Profile_Pic, Role FROM user WHERE user_id = $user_id";
            $result = $conn->query($sql);

            // Check if the query was successful
            if ($result) {
                // Fetch and display the data
                while ($row = $result->fetch_assoc()) {
                    $name = $row["Name"];
                    $surname = $row["Surname"];
                    $email = $row["Email"];
                    $birthday = $row["Birthday"];
                    $gender = $row["Gender"];
                    $profile_pic = $row["Profile_Pic"];
                    $role = $row["Role"];
                }
                if($gender == "pnts"){
                    $gender="Prefer not to say";
                }
            } else {
                echo "Error in the SQL query: " . $conn->error;
            }



    ?>
        <div class="invalid-feedback"> <?php echo $picErr ?>
        </div>

        <img src="images/profile_pictures/<?php echo $profile_pic ?>" alt="User Image" id="profile_pic"
            style="display: block; margin: 0 auto; width: 300px; height: 300px ;border-radius:30px; cursor:pointer;">
        <div>
            <a href="delete_profilepic.php?user_id=<?php echo $user_id  ?>" class="link-text"> delete profile picture
            </a>
        </div>
        <table>
            <tr>
                <td>Name:</td>
                <td><?php echo $name  ?></td>
            </tr>
            <tr>
                <td>Surname:</td>
                <td><?php echo $surname ?></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><?php echo $email ?></td>
            </tr>
            <tr>
                <td>Gender:</td>
                <td><?php echo $gender?></td>
            </tr>
            <tr>
                <td>Birthday:</td>
                <td><?php echo $birthday ?></td>
            </tr>
            <tr>
                <td>Role:</td>
                <td><?php echo $role ?></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td>*************</td>
            </tr>
        </table>
        <div style=" display: block; margin: 5px;padding: 20px">
            <a href="edit_user.php" class="btn-primary">Edit</a>
        </div>

        <div id="event_form" class="form_center-div hidden" style="background-image: none;">
            <div class="events-div-form">
                <form id="add_picform_section" action="profile.php" method="POST" enctype="multipart/form-data"
                    novalidate>
                    <label>Profile picture
                        <input type="file" name="profile_picture" />
                        <div class="invalid-feedback"> <?php echo $picErr ?>
                        </div>
                    </label>
                    <div class="button-container">
                        <input type="submit" class="btn-primary send-btn" value="Save">
                        <button id="discard_button" class="btn-secondary send-btn">Discard</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    </div>
    <?php include("components/footer.php") ?>
</body>

</html>