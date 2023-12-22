<?php
/**
 * This file contains a chats page Controller
 * 
 * If user is logged in his chats are displayed here.
 * The chat is done thrue the JS and a lot of the PHP
 * script is not used if JS is running.
 * 
 * @author Jakub Janák
 */

session_start();

/**
 * Including Models of User and Messages
 */
include "Model/User.php";
include "Model/Messages.php";

/**
 * Variables for the user signed in.
 * 
 * @var User        $user
 * @var Messages    $messages
 * @var string      $username
 * @var string      $email
 * @var string      $profilePic
 * @var array       $allUsers
 */
$user = new User();
$messages = new Messages();
$username = "";
$email = "";
$profilePic = "Default_IMG/person.png";
$allUsers = $user->getAllUsers();

/**
 * Variables for the messaged user.
 * 
 * @var string  $messagedUserName
 * @var string  $messagedUserPic
 * @var string  $messagedUserEmail
 * @var array   $messagesArr
 * @var string  $searchFilter
 */
$messagedUserName = "";
$messagedUserPic = "Default_IMG/person.png";
$messagedUserEmail = "";
$messagesArr = [];
$searchFilter = "";

/**
 * Tokens used by the forms
 * 
 * @var string $message_token
 * @var string $search_token
 */
$message_token = "";
$search_token = "";

if (isset($_SESSION['email']) and isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
} else {
    header("Location: index.php");
}

if (isset($_GET['user_messaged'])) {
    $messagedUserEmail = $_GET['user_messaged'];
}

if (isset($_POST['message']) and isset($_GET['user_messaged']) and isset($_POST['message_token']) and isset($_SESSION['message_token'])) {

    if ($_POST['message_token'] == $_SESSION['message_token']) {
        $messages->sendMessage($email, $messagedUserEmail, $_POST['message']);
    }
}

if (isset($_POST['user_search']) and isset($_POST['search_token']) and isset($_SESSION['search_token'])) {

    if ($_POST['search_token'] == $_SESSION['search_token']) {
        $searchFilter = $_POST['user_search'];
    }
}

$message_token = hash("sha1", uniqid());
$_SESSION['message_token'] = $message_token;

$search_token = hash("sha1", uniqid());
$_SESSION['search_token'] = $search_token;

if ($user->getUserPic($email)) {
    $profilePic = $user->getUserPic($email);
}

$messagesArr = $messages->getAllMessagesU2U($email, $messagedUserEmail);


/**
 * View of the chats Controller includded here
 */
include "view/chatsView.php";

?>