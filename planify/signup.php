<?php
// Start a session if one is not already started
session_start();

// Check if the user is already logged in
if (isset($_SESSION["role"])) {
    header("Location: calendar.php");
    exit;
}
?>
<?php include "utils/utils.php" ?>
<?php include "config/config.php" ?>
<?php
// Define a function to calculate age from a birthdate
function calculateAge($birthdate) {
    $today = new DateTime();
    $dob = new DateTime($birthdate);
    $age = $today->diff($dob);
    return $age->y;
}
 // define variables and set to empty
 $nameErr = $surnameErr = $emailErr =$birthdayErr= $confirm_passwordErr = $Err = $formErr = $formSuccess = "";
 $name = $surname= $email = $birthday = $password= $confirm_password  = "";
 $gender = "pnts";
 $passwordErr = "<ul>";

// checks if form has been submitted 
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        //load form values
        $name = test_input($_POST["name"]);
        $surname = test_input($_POST["surname"]);
        $email = test_input($_POST["email"]);
        $birthday = test_input($_POST["birthdate"]);
        $password = test_input($_POST["password"]);
        if(isset($_POST['gender'])){
            $gender= test_input($_POST["gender"]);
        }
        $confirm_password = test_input($_POST["confirm_password"]);

        //check for required fields
        if(empty($name)){
            $nameErr = "Name is required";
        }
        if(empty($surname)){
            $surnameErr = "Surname is required";
        }
        if(empty($email)){
            $emailErr = "Email is required";
        }
        if(empty($password)){
            $passwordErr = "password is required";
        }
        if(empty($confirm_password)){
            $confirm_passwordErr = "confirm password cannot empty";
        }
        if(empty($birthday)){
            $birthdayErr = "Enter your birthday";
        }
        // more form validation
        if(strlen($name) > 25){
            $nameErr = "Name too long! max is 25 characters.";
        }
        if(strlen($surname) > 25){
            $surnameErr = "Surname too long! max is 25 characters.";
        }
        // check if valid email is submitted
        if(!isValidEmail($email)){
            $emailErr = "Invalid email";
        }
        validatePassWord($password);
        $passwordErr .= "</ul>";

        // check if user is over the age of 13
        $age = calculateAge($birthday);
        if ($age < 13) {
            $birthdayErr = "You must be at least 13 years old to sign up.";
        }

       if(!isPasswordsMatch($password,$confirm_password)){
            $confirm_passwordErr= "Passwords Dont Match";
       }

        if($nameErr == "" && $surnameErr == "" && $emailErr == "" &&  $passwordErr == "<ul></ul>" && $confirm_passwordErr == "" && $birthdayErr == ""){
            // Check if the email already exists in the database
            $email_check_query = "SELECT * FROM USER WHERE email = '$email'";
            $email_check_result = $conn->query($email_check_query);

            if ($email_check_result->num_rows > 0) {
                $emailErr = "Email already exists in the database. Please use a different email address.";
            } else {
                // The email is not in use, so proceed with the insertion.
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert data into the users table in the database
                $new_user_query = "INSERT INTO USER (name, surname, gender, birthday, email, password, role,profile_pic)
                                    VALUES ('$name', '$surname', '$gender', '$birthday', '$email', '$hashed_password', 'user','default.png')";

                if ($conn->query($new_user_query) === TRUE) {
                    $formSuccess = "Sign up is successful you can now login!";
                    // Clear the form
                    $name = $surname = $email = $birthday = $password = $confirm_password = "";
                } else {
                    $formErr = "Error adding your data to the database: " . $conn->error;
                }
            }

        }
        else{
            $formErr = "Please fix your errors and try again.";
        }
        $conn->close();
 }
?>



<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title> Sign Up | Planify</title>
    <link href="style.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width" />
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
</head>

<body>

    <?php include("components/navbar.php") ?>
    <?php $page_title="Sign Up"; include("components/header.php") ?>

    <div class="form-container custom-form_signup">
        <div id="div-form" class="slide-in">

            <form name="signup_form" id="signup_form" action="signup.php" method="POST" novalidate>
                <div class="valid-feedback"> <?php echo $formSuccess ?>
                </div>
                <label>Name<span> *</span>
                    <input type="text" name="name" required value="<?php echo $name ?>" />
                    <div class="invalid-feedback"> <?php echo $nameErr ?>
                    </div>
                    <br /><br />
                    <label>Surname<span> *</span>
                        <input type="text" name="surname" required value="<?php echo $surname ?>" />
                        <div class="invalid-feedback"> <?php echo $surnameErr ?>
                        </div>
                    </label>
                    <br /><br />
                    <label>Email<span> *</span>
                        <input type="email" name="email" required value="<?php echo $email ?>" />
                        <div class="invalid-feedback"> <?php echo $emailErr ?>
                        </div>
                    </label>
                    <p>Please select your gender:</p>
                    <div id="gender_selection">
                        <span>
                            <label for="male">Male
                                <input type="radio" id="male" name="gender" value="male">
                            </label>
                        </span>
                        <span>
                            <label for="female">Female
                                <input type="radio" id="female" name="gender" value="female">
                            </label>
                        </span>
                        <span>
                            <label for="other">Other
                                <input type="radio" id="other" name="gender" value="other">
                            </label>
                        </span>
                    </div>
                    <br /><br />
                    <label>Birthday<span> *</span>
                        <input type="date" name="birthdate" value="<?php echo $birthday ?>" />
                        <div class="invalid-feedback"> <?php echo $birthdayErr ?>
                        </div>
                    </label>
                    <br /><br />
                    <label>Password<span> *</span>
                        <input type="password" name="password" required value="<?php echo $password ?>" />
                        <div class="invalid-feedback"> <?php echo $passwordErr ?>
                        </div>
                    </label>
                    <br /><br />
                    <label>Confirm Password<span> *</span>
                        <input type="password" name="confirm_password" required
                            value="<?php echo $confirm_password ?>" />
                        <div class="invalid-feedback"> <?php echo $confirm_passwordErr ?>
                        </div>
                    </label>
                    <br /><br />
                    <div class="invalid-feedback"> <?php echo $formErr ?>
                    </div>
                    <div class="button-container">
                        <input type="submit" class="btn-primary send-btn" value="Create">
                    </div>
            </form>
        </div>
    </div>
    <?php include("components/footer.php") ?>
</body>

</html>