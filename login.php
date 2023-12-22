<?php
/**
 * This file contains a login page Controller and View
 * 
 * If user has an accout he/she can log in here.
 * The logging is done thrue the JS and a lot of the PHP
 * script is not used if JS is running.
 * 
 * @author Jakub Janák
 */

session_start();

/**
 * Including Models of User
 */
include "Model/User.php";

/**
 * User variables declared here
 * 
 * @var null|User   $user
 * @var string      $error
 * @var string      $email
 * @var string      $login_token
 */
$user = new User();
$error = "";
$email = "";

$login_token = "";

if (isset($_POST['email']) and isset($_POST['password']) and isset($_POST['login_token']) and isset($_SESSION['login_token'])) {

    if ($_POST['login_token'] == $_SESSION['login_token']) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $error = $user->logInUser($email, $password);
    }

    if (!$error) {
        header("Location: index.php");
    }
}

$login_token = hash("sha1", uniqid());
$_SESSION['login_token'] = $login_token;

/**
 * Login View included here
 */
include "view/loginView.php";

?>