<?php
/**
 * This file contains an API used to send a message
 * 
 * This Aplication programming interface,
 * is being used by the front end JavaScript
 * 
 * @author Jakub Janák
 */

session_start();

/**
 * Including User and Messages Models
 */
include "../Model/User.php";
include "../Model/Messages.php";

/**
 * @var User    $user
 * @var Messages    $Messages
 * @var array    $MessagesU2U
 * @var array    $messagesArr
 * @var string    $messagedUserEmail
 * @var string    $email
 */
$user = new User("../json_db", "../img_db");
$messages = new Messages("../json_db", "../json_db", "../img_db");
$messagesU2U = [];
$messagesArr = [];
$messagedUserEmail = "";
$email = $_SESSION['email'];

if (isset($_POST['message']) and isset($_POST['user_messaged'])) {
    $messagedUserEmail = $_POST['user_messaged'];
    $messages->sendMessage($email, $_POST['user_messaged'], $_POST['message']);
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