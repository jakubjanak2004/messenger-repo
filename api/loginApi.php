<?php
/**
 * This file contains an API used to log in the user
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
 * @var User   $user
 * @var string $error
 */
$user = new User("../json_db", "../img_db");
$error = "";

if (isset($_POST['email']) and isset($_POST['password'])) {

   $error = $user->logInUser($_POST['email'], $_POST['password']);

   if (!$error) {
      echo json_encode(["logged" => True, "error" => $error]);
   } else {
      echo json_encode(["logged" => False, "error" => $error]);
   }

}

?>