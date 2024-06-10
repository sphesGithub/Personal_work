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
// Define variables and set to empty
$emailErr = $passwordErr = $formErr = $formSuccess = "";
$email = $password = "";

// Initialize log message
$logMessage = "";

// Get the client's IP address
$clientIP = $_SERVER['REMOTE_ADDR'];

// Checks if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Load form values
    $email = test_input($_POST["email"]);
    $password = test_input($_POST["password"]);

    // Check for required fields
    if (empty($email)) {
        $emailErr = "Email is required";
    }
    if (empty($password)) {
        $passwordErr = "Password is required";
    }

    // More form validation

    // Check if a valid email is submitted
    if (!isValidEmail($email)) {
        $emailErr = "Invalid email";
    }
    validatePassword($password);
    if ($passwordErr !== "") {
        $passwordErr = "Invalid password";
    }

    // If there are no validation errors, proceed with the login
    if ($emailErr == "" && $passwordErr == "") {
        $query = "SELECT * FROM USER WHERE email = '$email'";
        $result = $conn->query($query);

        $logMessage = "Login attempt for email: $email - IP: $clientIP - Result: ";

        if ($result->num_rows == 1) {
            // Email exists in the database, now check the password
            $row = $result->fetch_assoc();
            $hashed_password = $row["password"];

            if (password_verify($password, $hashed_password)) {
                // Successful login
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['profile_pic'] = $row['profile_pic'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['fullname'] = $row['name'] . " " . $row['surname'];
                // Redirect user to the appropriate page
                if ($_SESSION['role'] == 'admin') {
                    $logMessage .= "Successful user login";
                    // Log the login
                    file_put_contents("login_logs.txt", date("Y-m-d H:i:s") . " - " . $logMessage . PHP_EOL, FILE_APPEND);
                    header("location: admin-users.php");
                    exit();
                } else {
                    $logMessage .= "Successful admin login";
                    // Log the login
                    file_put_contents("login_logs.txt", date("Y-m-d H:i:s") . " - " . $logMessage . PHP_EOL, FILE_APPEND);
                    header("Location: calendar.php");
                    exit();
                }
            } else {
                // Incorrect password
                $formErr = "Incorrect password. Please try again.";
                $logMessage .= "Incorrect password";
            }
        } else {
            // Email not found
            $formErr = "Email not found. Please sign up or use a different email.";
            $logMessage .= "Email not found";
        }
    } else {
        $logMessage = "Login attempt by IP: $clientIP - Result: Form validation errors";
    }

    // Log the login attempt and result
    file_put_contents("login_logs.txt", date("Y-m-d H:i:s") . " - " . $logMessage . PHP_EOL, FILE_APPEND);

    $conn->close();
}
?>



<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title> Sign In | Planify</title>
    <link href="style.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width" />
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
</head>

<body>

    <?php include("components/navbar.php") ?>
    <?php $page_title="Sign In"; include("components/header.php") ?>

    <div class="form-container custom-form">
        <div id="div-form" class="slide-in">
            <form name="signin_form" id="signin_form" action="signin.php" method="POST" novalidate>
                <label>Email<span> *</span>
                    <input type="email" name="email" required value="<?php echo $email ?>" />
                    <div class="invalid-feedback"> <?php echo $emailErr ?>
                    </div>
                </label>
                <br /><br />
                <label>Password<span> *</span>
                    <input type="password" name="password" required value="<?php echo $password ?>" />
                    <div class="invalid-feedback"> <?php echo $passwordErr ?>
                    </div>
                </label>
                <br /><br />
                <div class="invalid-feedback"> <?php echo $formErr ?>
                </div>
                <div class="valid-feedback"> <?php echo $formSuccess ?>
                </div>
                <div class="button-container">
                    <input type="submit" class="btn-primary send-btn" value="Sign In">
                </div>
            </form>
        </div>
    </div>
    <?php include("components/footer.php") ?>
</body>

</html>