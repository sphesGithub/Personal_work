<?php
// Start a session if one is not already started
session_start();
?>
<?php include "utils/utils.php" ?>
<?php include "config/config.php" ?>
<?php
 // define variables and set to empty
 $nameErr = $emailErr = $messageErr = $formErr = $formSuccess = "";
 $name = $email = $message = "";
 
// checks if form has been submitted 
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        //load form values
        $name = test_input($_POST["contactName"]);
        $email = test_input($_POST["email"]);
        $message = test_input($_POST["message"]);
        //check for required fields
        if(empty($name)){
            $nameErr = "Name is required";
        }
        if(empty($email)){
            $emailErr = "Email is required";
        }else{
            // check if valid email is submitted
            if(!isValidEmail($email) ){
                $emailErr = "Invalid email format.";
            }
        }
        if(empty($message)){
            $messageErr = "Message is required";
        }
        // more form validation
        if(strlen($name) > 25){
            $nameErr = "Name too long! max is 25 characters.";
        }
        if(strlen($message) > 255){
            $messageErr = "Character limit reached !";
        }
       
        if($nameErr == "" && $emailErr == "" &&  $messageErr == ""){
            // form had no errors, continue with submission.
            // clear the form
            // insert data to message table in the db!
            // success message
            $new_user_query = "INSERT INTO MESSAGE (name, email, message,date_sent)
                                    VALUES ('$name', '$email', '$message',NOW())";

                if ($conn->query($new_user_query) === TRUE) {
                    $formSuccess = "Message sent!";
                    // Clear the form
                    $name = $email = $message = "";
                } else {
                    $formErr = "error occurred when sending message " . $conn->error;
                }
            
        }
        else{
            $formErr = "Please fix your errors and send again.";
            
        }
        $conn->close();
 }
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title> Contact | Planify</title>
    <link href="style.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width" />
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
</head>

<body>

    <?php include("components/navbar.php") ?>

    <header>
        <div class="page-title">
            <div class="container">
                <h2>Contact Us</h2>
            </div>
        </div>
    </header>

    <div class="form-container custom-form">
        <div id="div-form" class="slide-in">
            <form id="contact_form" action="contact.php" method="POST" novalidate>
                <label>Name <span>*</span>
                    <input type="text" name="contactName" required value="<?php echo $name ?>" />
                    <div class="invalid-feedback"> <?php echo $nameErr ?>
                    </div>
                </label>
                <br /><br />
                <label>Email <span>*</span>
                    <input type="email" name="email" required value="<?php echo $email ?>" />
                    <div class="invalid-feedback"> <?php echo $emailErr ?>
                    </div>
                </label>
                <br /><br />
                <label>Message <span>*</span>
                    <br />
                    <textarea name="message" rows="4" required maxlength="255"><?php echo $message ?></textarea>
                    <div class="invalid-feedback"> <?php echo $messageErr ?>
                    </div>
                    <div id="charCount_message">Characters left: 255</div>
                </label>
                <br />
                <div class="invalid-feedback"> <?php echo $formErr ?>
                </div>
                <div class="valid-feedback"> <?php echo $formSuccess ?>
                </div>
                <br />
                <div class="button-container">
                    <input type="submit" class="btn-primary send-btn" value="Send"></input>
                </div>
            </form>

        </div>

    </div>
    <?php include("components/footer.php") ?>



</body>

</html>