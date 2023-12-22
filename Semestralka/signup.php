<?php
/**
 * This file contains a Sign Up page Controller and View
 * 
 * User can create account on this page.
 * The logging is done thrue the JS and a lot of the PHP
 * script is not used if JS is running.
 * 
 * @author Jakub Janák
 */

session_start();

/**
 * User class included here
 */
include "Model/User.php";

/**
 * Variables are included here
 * 
 * @var User    $user
 * @var string  $error
 * @var string  $username
 * @var string  $email
 * @var string  $signup_token
 */
$user = new User();
$error = "";
$username = "";
$email = "";

$signup_token = "";

if (isset($_POST['username']) and isset($_POST['email']) and isset($_POST['password']) and isset($_POST['password_repeat']) and isset($_POST['signup_token']) and isset($_SESSION['signup_token'])) {

    if ($_POST['signup_token'] == $_SESSION['signup_token']) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_repeat = $_POST['password_repeat'];

        $error = $user->signUpUser($username, $email, $password, $password_repeat);

        if (!$error) {
            header("Location: index.php");
        }
    }
}

$signup_token = hash("sha1", uniqid());
$_SESSION['signup_token'] = $signup_token;

/**
 * Including the signup View
 */
include "view/signupView.php";

?>