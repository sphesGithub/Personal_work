<?php include "secure.php" ?>
<?php include "utils/utils.php" ?>
<?php include "config/config.php" ?>
<?php

if(!isset($_REQUEST['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = test_input($_REQUEST['user_id']);
}

// Define a function to calculate age from a birthdate
function calculateAge($birthdate) {
    $today = new DateTime();
    $dob = new DateTime($birthdate);
    $age = $today->diff($dob);
    return $age->y;
}
 $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
 // define variables and set to empty
 $nameErr = $surnameErr = $emailErr =$birthdayErr= $confirm_passwordErr = $old_passwordErr =$formErr = $formSuccess = "";
 $name = $surname= $email = $birthday = $old_password= $password= $confirm_password  = "";
 
 $passwordErr = "<ul>";

$sql = "SELECT Name, Surname, Email, Gender, Birthday, Role FROM user WHERE user_id = $user_id";
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
        }

    } else {
        echo "Error in the SQL query: " . $conn->error;
    }


// checks if form has been submitted 
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
        //load form values
        $name = test_input($_POST["name"]);
        $surname = test_input($_POST["surname"]);
        $email = test_input($_POST["email"]);
        $birthday = test_input($_POST["birthdate"]);
        $old_password = test_input($_POST["old_password"]);
        $password = test_input($_POST["password"]);
        $user_id = test_input($_POST['user_id']);
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
        if (empty($old_password)) {
            // User didn't change password
            // You can update other profile information here
            // Make sure to validate and update the data

    
        } else {
            // User wants to change the password
            $query = "SELECT * FROM user WHERE user_id = '$user_id'";
            $result = $conn->query($query);
    
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $hashed_password = $row["password"];
    
                if (password_verify($old_password, $hashed_password)) {
                    if (empty($password)) {
                        $passwordErr = "Password is required";
                    }
                    if (empty($confirm_password)) {
                        $confirm_passwordErr = "Confirm password cannot be empty";
                    } else {
                        validatePassword($password);
                       
    
                        if (!isPasswordsMatch($password, $confirm_password)) {
                            $confirm_passwordErr = "Passwords don't match";
                        } else {
                            // If the old password is correct and the new password is valid,
                            // you can proceed to update the password in the database here.
                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                            $update_query = "UPDATE user SET Password = '$hashed_password' WHERE user_id = '$user_id'";
                            if ($conn->query($update_query) === TRUE) {
                                $formSuccess = "Password updated successfully!";
                            } else {
                                $formErr = "Error updating password: " . $conn->error;
                            }
                        }
                    }
                } else {
                    $old_passwordErr = "Incorrect old password";
                }
            } else {
                $old_passwordErr = "User not found";
            }
        }
        $passwordErr .= "</ul>";

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
        // check if user is over the age of 13
        $age = calculateAge($birthday);
        if ($age < 13) {
            $birthdayErr = "You must be at least 13 years old to sign up.";
        }

        if($nameErr == "" && $surnameErr == "" && $emailErr == "" &&  $passwordErr == "<ul></ul>" && $confirm_passwordErr == "" && $birthdayErr == ""){

                // update data into the users table in the database
                $update_user_query = "UPDATE USER
                                    SET name = '$name',
                                        surname = '$surname',
                                        gender = '$gender',
                                        birthday = '$birthday',
                                        email = '$email'
                                    WHERE user_id = $user_id";


                if ($conn->query($update_user_query) === TRUE) {
                    $formSuccess = "edit success";
                } else {
                    $formErr = "Error adding your data to the database: " . $conn->error;
                }

        }
        else{
            $formErr = "Please fix your errors and try again.";
        }
        
 }
 $conn->close();
?>



<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title> Edit Profile | Planify</title>
    <link href="style.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width" />
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
</head>

<body>

    <?php include("components/navbar.php") ?>
    <?php $page_title="Edit Profile"; include("components/header.php") ?>

    <div class="form-container custom-form_signup">
        <div id="div-form" class="slide-in">

            <form name="signup_form" id="signup_form" action="edit_user.php" method="POST" novalidate>
                <div class="valid-feedback"> <?php echo $formSuccess ?>
                </div>
                <input type="hidden" name="user_id" required value="<?php echo $user_id ?>" />
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
                                <input type="radio" id="male" name="gender" value="male" <?php echo ($gender === 'male') ? 'checked' : ''; ?>>
                            </label>
                        </span>
                        <span>
                            <label for="female">Female
                                <input type="radio" id="female" name="gender" value="female" <?php echo ($gender === 'female') ? 'checked' : ''; ?>>
                            </label>
                        </span>
                        <span>
                            <label for="other">Other
                                <input type="radio" id="other" name="gender" value="other" <?php echo ($gender === 'other') ? 'checked' : ''; ?>>
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
                    <label>Old Password<span> *</span>
                        <input type="password" name="old_password" required value="<?php echo $old_password ?>" />
                        <div class="invalid-feedback"> <?php echo $old_passwordErr ?>
                        </div>
                    </label>
                    <br /><br />
                    <label>New Password<span> *</span>
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
                        <a class="btn-secondary" href="profile.php">back</a>
                        <input type="submit" class="btn-primary send-btn" value="Edit">
                    </div>
            </form>
        </div>
    </div>
    <?php include("components/footer.php") ?>
</body>

</html>