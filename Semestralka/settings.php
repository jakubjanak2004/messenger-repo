<?php
/**
 * This file contains a settings page Controller and View
 * 
 * If user is logged in he/she can
 * change the settings on this page.
 * Settings are done true the PHP.
 * 
 * @author Jakub Janák
 */

session_start();

/**
 * User class Included here
 */
include "Model/User.php";

/**
 * User variables
 * 
 * @var User    $user
 * @var string  $username
 * @var string  $email
 * @var string  $profilePic
 * @var string  $userPicError
 * @var string  $username_token
 * @var string  $pic_token
 */
$user = new User();
$username = "";
$email = "";
$profilePic = "Default_IMG/person.png";
$userPicError = "";

$username_token = "";
$pic_token = "";

if (isset($_SESSION['email']) and isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
} else {
    header("Location: index.php");
}

if (isset($_POST['new_username']) and isset($_POST['username_token']) and isset($_SESSION['username_token'])) {
    if ($_POST['username_token'] == $_SESSION['username_token']) {
        $user->changeUsername($email, $_POST['new_username']);
        $username = $_SESSION['username'];
    }
}

if (isset($_FILES['photo']) and isset($_POST['pic_token']) and isset($_SESSION['pic_token'])) {
    if ($_POST['pic_token'] == $_SESSION['pic_token']) {
        $userPicError = $user->changeUserPic($email, $_FILES['photo']);
    }
}

$username_token = hash("sha1", uniqid());
$_SESSION['username_token'] = $username_token;

$pic_token = hash("sha1", uniqid());
$_SESSION['pic_token'] = $pic_token;

if ($user->getUserPic($email)) {
    $profilePic = $user->getUserPic($email);
}

/**
 * Settings View included here
 */
include "view/settingsView.php";

?>