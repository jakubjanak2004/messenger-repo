<?php
/**
 * This file contains a remove Message page Controller and View
 * 
 * If user is logged in and selects his/hers message,
 * he/she can delete it here o this page.
 * Removing a message is done true the PHP.
 * 
 * @author Jakub Janák
 */

session_start();

/**
 * Including Models of User and Messages
 */
include "Model/Messages.php";
include "Model/User.php";

/**
 * Variables declared here
 * 
 * @var Messages    $messages
 * @var string      $from 
 * @var string      $to
 * @var string      $timestamp
 * @var string      $content
 * @var string      $delete_token
 */
$messages = new Messages();
$from = "";
$to = "";
$timestamp = "";
$content = "";

$delete_token = "";

if (isset($_GET['from']) and isset($_GET['to']) and isset($_GET['timestamp']) and isset($_GET['content'])) {

    $from = $_GET['from'];
    $to = $_GET['to'];
    $timestamp = $_GET['timestamp'];
    $content = $_GET['content'];
} else if (isset($_POST['from']) and isset($_POST['to']) and isset($_POST['timestamp']) and isset($_SESSION['delete_token']) and isset($_POST['delete_token'])) {

    if ($_POST['delete_token'] == $_SESSION['delete_token']) {
        $messages->eraseMessage($_POST['from'], $_POST['to'], $_POST['timestamp']);
        header("Location: chats.php?user_messaged=$_POST[to]");
    }
} else {
    header("Location: chats.php?user_messaged=$_POST[to]");
}

$delete_token = hash("sha1", uniqid());
$_SESSION['delete_token'] = $delete_token;

/**
 * Including the view here
 */
include "view/removeMessagesView.php";

?>