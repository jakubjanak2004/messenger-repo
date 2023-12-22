<?php
/**
 * This file contains an API used to get the Chat,
 * between two users
 * 
 * This Aplication programming interface,
 * is being used by the front end JavaScript
 * 
 * @author Jakub Janák
 */

session_start();

/**
 * Including User and Messages Model
 */
include "../Model/User.php";
include "../Model/Messages.php";

/**
 * Variables declared
 * 
 * @var User        $user
 * @var Messages    $messages
 * @var string      $messageduserEmail
 * @var array       $messagesU2U
 * @var array       $messagesArr
 * @var string      $email
 */
$user = new User("../json_db", "../img_db");
$messages = new Messages("../json_db", "../json_db", "../img_db");
$messagedUserEmail = "";
$messagesU2U = [];
$messagesArr = [];
$email = $_SESSION['email'];

if (isset($_POST['user_messaged'])) {
    $messagedUserEmail = $_POST['user_messaged'];
}

$messagesArr = $messages->getAllMessagesU2U($email, $messagedUserEmail);

if (isset($messagesArr[$email])) {
    $my_messages = $messagesArr[$email];

    foreach ($my_messages as $timestamp => $value) {

        $content = $value['content'];
        $content = htmlspecialchars($content);
        $from = htmlspecialchars($value['from']);
        $to = htmlspecialchars($value['to']);
        $messagesU2U[$timestamp] = "
        <li class='my_message'>
            <a href='removeMessage.php?from=$from&to=$to&timestamp=$timestamp&content=$content'>
                $content
            </a>
        </li>";
    }
}

if (isset($messagesArr[$messagedUserEmail])) {
    $user_messages = $messagesArr[$messagedUserEmail];

    foreach ($user_messages as $timestamp => $value) {

        $content = $value['content'];
        $content = htmlspecialchars($content);
        $messagesU2U[$timestamp] = "<li class='respondent_message'><span>$content</span></li>";
    }
}

ksort($messagesU2U);

echo json_encode($messagesU2U);

?>