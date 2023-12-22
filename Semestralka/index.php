<?php
/**
 * This file contains a main Controller
 * 
 * Handling the Controller side of the MVC framework,
 * Redirecting to the profile side of the page,
 * or returns the user to the login page
 * 
 * @author Jakub Janák
 */

session_start();

//Sign Out the user if request was captured
if (isset($_POST['sign_out']) and $_POST['sign_out'] == "true") {
    session_destroy();
    header("Location: login.php");
}

//Index page that will redirect the user, check sessions
if (isset($_SESSION['email']) and isset($_SESSION['username'])) {
    header("Location: chats.php");
} else {
    header("Location: login.php");
}

?>