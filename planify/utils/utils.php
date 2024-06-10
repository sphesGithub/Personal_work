<?php 
// this file contains commonly used functions in our app

// source: https://www.w3schools.com/php/php_form_validation.asp
// sanitize inputs
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
    }

function isValidEmail($email) {
    // Define a regular expression pattern for a valid email address
    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    if (preg_match($pattern, $email)) {
        return true; // Valid email
    } else {
        return false;
    }
}
function validatePassWord($password) {
    $minLengthRegex = '/.{8,}/'; // At least 8 characters
    $uppercaseRegex = '/[A-Z]/'; // At least 1 uppercase letter
    $lowercaseRegex = '/[a-z]/'; // At least 1 lowercase letter
    $symbolRegex = '/[!@#$%&*]/'; // At least 1 of the specified special characters
    $numberRegex = '/[0-9]/'; // At least 1 number
    $spaceRegex = '/\s/';

    global $passwordErr;

    if (!preg_match($minLengthRegex, $password)) {
        $passwordErr .= "<li> at least 8 characters. </li>";
    }

    if (!preg_match($uppercaseRegex, $password)) {
        $passwordErr .= "<li>at least 1 uppercase letter. </li>";
    }

    if (!preg_match($lowercaseRegex, $password)) {
        $passwordErr .= "<li>at least 1 lowercase letter. </li>";
    }

    if (!preg_match($symbolRegex, $password)) {
        $passwordErr .= "<li>at least 1 of the specified special characters. </li>";
    }

    if (!preg_match($numberRegex, $password)) {
        $passwordErr .= "<li>at least 1 number. </li>";
    }

    if (preg_match($spaceRegex, $password)) {
        $passwordErr .= "<li>must not contain spaces.</li> ";
    }
}

function isPasswordsMatch($password,$confirm_password){
    if($password == $confirm_password){
        return true;
    }
}
?>