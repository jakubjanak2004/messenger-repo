<?php
/**
 * This file contains an API used to sign up a user
 * 
 * This Aplication programming interface,
 * is being used by the front end JavaScript
 * 
 * @author Jakub Janák
 */

session_start();

/**
 * User Model included here
 */
include "../Model/User.php";

/**
 * Variables declared here
 * 
 * @var User $user
 * @var string $error
 * @var string $username
 * @var string $email
 */
$user = new User("../json_db", "../img_db");
$error = "";
$username = "";
$email = "";

if (isset($_POST['username']) and isset($_POST['email']) and isset($_POST['password']) and isset($_POST['password_repeat'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $error = $user->signUpUser($username, $email, $_POST['password'], $_POST['password_repeat']);

    if (!$error) {
        echo json_encode(["signed" => True, "error" => $error]);
    } else {
        echo json_encode(["signed" => False, "error" => $error]);
    }

}

?>