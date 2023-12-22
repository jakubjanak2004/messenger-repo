<?php
/**
 * This file contains an API used to get a certain user
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
 * @var User    $user
 * @var string  $searchFilter
 * @var array   $outputArray
 * @var array   $allUsers
 */
$user = new User("../json_db", "../img_db");
$searchFilter = "";
$outputArray = [];

$allUsers = $user->getAllUsers();

if (isset($_POST['user_messaged'])) {
    $searchFilter = $_POST['user_messaged'];
}

//loop thrue all users and print them
foreach ($allUsers as $key => $value) {

    //printed user Vars
    $nameOfUser = $value['username'];
    $userEmail = $value['email'];
    $userPic = "Default_IMG/person.png";

    $searchResult = strpos(strtolower($value['email']), strtolower($searchFilter));

    //filtering not searched users
    if ($searchFilter and !$searchResult) {
        if ($searchResult !== 0) {
            continue;
        }
    }

    if ($_SESSION['email'] == $value['email']) {
        continue;
    }

    //give user its pic if is set
    if ($user->getUserPic($value['email'])) {
        $userPic = $user->getUserPic($value['email']);
    }
    $userEmailChars = htmlspecialchars($userEmail);
    $nameOfUserChars = htmlspecialchars($nameOfUser);

    $outputArray = ["email" => $userEmailChars, "username" => $nameOfUserChars, "userpic" => $userPic];
    break;
}

echo json_encode($outputArray);

?>