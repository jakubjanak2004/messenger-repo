# Programmer Documentation

This is a docmentation for programmer showing the code structure as well as agorthyhms used in the Messenger application.

This app is created with respect to MVC framework, therefore the documentation will follow the same pattern of:
- Model: Database Communication management
- View: Viewing the data
- Controller: Controlling the user interaction with the app

# Model

this part of the code is managng the data and communicates with the database.
There are two classes used in here, the User class and Message class.

## User

This class represents a user and has atributes and mthods to validate user, create new user,...

Class User has 4 atributes that are all encpsulated and cannot be accessed, therefore are private,
if you wanna change them you can do that thrue the constructor of a class.

Attributes:
- $userFolder: string the folder in which the databse is stored
- $userFile: string the filename in which the data is stored
- $imagesFolder: string name name of the images folder in which the images data is stored
- $userPath: string the path of the user file which is being constructed in the constructor

```php
class User
{

    private string $userFolder = "json_db";
    private string $userFile = "users.json";
    private string $imagesFolder = "img_db";
    private string $userPath = "";

    function __construct(string $userFolder = Null, string $imagesFolder = Null)
    {

        if ($userFolder) {
            $this->userFolder = $userFolder;
        }

        if ($imagesFolder) {
            $this->imagesFolder = $imagesFolder;
        }

        $this->userPath = $this->userFolder . "/" . $this->userFile;

        if (!file_exists($this->userFolder)) {
            mkdir($this->userFolder);
        }
        if (!file_exists($this->imagesFolder)) {
            mkdir($this->imagesFolder);
        }
    }

```
### signUpUser

This metod is used for signing up the user, it returns an error string,
if empty the signup was successful if not the error messages are displayed there.

Parameters:
- $username: string, username of the user
- $email: string email of the user
- $ password: string password of the user
- $password2: string this should be same os the first password

The method returns $error string
```php

    public function signUpUser(string $username, string $email, string $password, string $password2): string
    {

        $can_sign = true;
        $error = "";

        if ($password != $password2) {
            $error .= "Passwords are not the same!" . "<br>";
            $can_sign = false;
        }

        if (strlen($password) < 8) {
            $error .= "Password should be at lease 8 characters!" . "<br>";
            $can_sign = false;
        }

        if ($this->returnUser($email)) {
            $error .= "User with this email exists!" . "<br>";
            $can_sign = false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error .= "Invalid email format!" . "<br>";
        }

        if ($can_sign) {
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $username;

            $json_data = $this->getAllUsers();

            $json_data[] = ["email" => $email, "username" => $username, "password" => sha1($password, False), "profile_pic" => NULL];

            $this->saveAllUsers($json_data);
        }

        return $error;
    }
```
### logInUser

this method is used to log in user with already creted username, 
if error occured it is returned thrue the $error variable.

Parameters:
- $email: string email of the user
- $password: string password of the user

The method returns $error string.
```php
    public function logInUser(string $email, string $password): string
    {
        $can_sign = true;
        $error = "";
        $user = $this->returnUser($email);

        if (!$user or sha1($password, False) != $user['password']) {
            $error .= "Username or Password is Wrong" . "<br>";
            $can_sign = false;
        }

        if ($can_sign) {
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $user['username'];
        }

        return $error;
    }
```
### returnUser

this method is used when we wanna return a user with certain emal,
if the suer is not founf false will be returned.

Parameters: 
- $email: string email of a user

The method returns array with information about the user,
or the boolean of false if the user is not found.
```php
    public function returnUser(string $email): array|bool
    {
        $json_data = $this->getAllUsers();
        $email = trim($email);

        foreach ($json_data as $key => $value) {
            if ($value['email'] == $email) {
                return $value;
            }
        }

        return False;
    }
```
### getAllUsers

this method will return all users from the database in a form of a json.

There are no parameters in this function.

It returns all users from the databsse folder in form of an php assoc array.
```php
    public function getAllUsers(): array
    {
        if (file_exists($this->userPath)) {
            $data = file_get_contents($this->userPath);
            $json_data = json_decode($data, true);
        } else {
            $json_data = [];
        }

        if (!$json_data) {
            $json_data = [];
        }

        return $json_data;
    }
```
### changeUsername

this method will change username of a user if exists.

Parameters:
- $email: string email of the user
- $newUsername: string new username that will replace the old one

The method retuns a boolean of true if succeeded.
```php
    public function changeUsername(string $email, string $newUsername): bool
    {
        $user = $this->returnUser($email);
        if (!$user) {
            return False;
        }

        $allUsers = $this->getAllUsers();

        foreach ($allUsers as &$oneUser) {
            if ($oneUser['email'] == $email) {
                $oneUser['username'] = $newUsername;
                $_SESSION['username'] = $newUsername;
            }
        }

        $json_data = $allUsers;

        $this->saveAllUsers($json_data);

        return True;
    }
```
### changeUserPic

this method will change pic of a user if exists,
if not error is returned.

Parameters:
- $email: string email of the user
- $profilePic: array with the image sent by the post request

The method returns $error string.
```php
    public function changeUserPic(string $email, array $profilePic): string
    {
        $user = $this->returnUser($email);
        $imageFileType = strtolower(pathinfo($profilePic['name'], PATHINFO_EXTENSION));
        $error = "";

        if (!$user) {
            $error .= "User does not exist!<br>";
        }

        if ($imageFileType != "jpg" and $imageFileType != "png" and $imageFileType != "jpeg") {
            $error .= "Format is not supported!<br>";
        }

        if (filesize($profilePic['tmp_name']) >= 1_600_000) {
            $error .= "Image is too big!<br>";
        }

        if($error){
            return $error;
        }

        $target_file = $this->imagesFolder . "/" . $email;

        if (!file_exists($target_file)) {
            mkdir($target_file);
        }

        $target_file .= "/" . basename($profilePic["name"]);

        $response = move_uploaded_file($profilePic["tmp_name"], $target_file);

        if(!$response){
            $error .= "Failed to save the image!<br>";
            return $error;
        }

        $allUsers = $this->getAllUsers();

        foreach ($allUsers as &$oneUser) {
            if ($oneUser['email'] == $email) {
                $oneUser['profile_pic'] = $target_file;
            }
        }

        $this->saveAllUsers($allUsers);

        return $error;
    }
```
### getUserPic

this method will return user pic if the user with the email exists,
if not false is returned.

Parameters: 
- $email: string email of the user

The method returns the pic path or false boolean
```php
    public function getUserPic(string $email): string|bool
    {
        $allUsers = $this->getAllUsers();

        foreach ($allUsers as &$oneUser) {
            if ($oneUser['email'] == $email) {

                if ($oneUser['profile_pic'] != Null) {
                    return $oneUser['profile_pic'];
                }
            }
        }

        return False;
    }
```
### saveAllUsers

this method saves all users in the database folder in a form of a json encoded string.

Parameters:
- $users: array of users to be saved into the database

The method does not return anything.
```php
    public function saveAllUsers(array $users): void
    {
        file_put_contents($this->userPath, json_encode($users));
    }
}
```

## Message

Class Message is used when user is loggen in and wants to access a chat, wants t send message,...

Class has 4 atributes, message folder, user folder and images folder can be changed thrue the constructor.

Attributes:
- $messagesFolder: string the folder of the user database
- $messagesFile: string name of the messsages data file
- $messagesPath: string the path of the folder constructed in the constructor
- $user: user object or null of it is not created

```php
class Messages
{

    private string $messagesFolder = "json_db";
    private string $messagesFile = "messages.json";
    private string $messagesPath = "";
    private null|User $user = NULL;

    function __construct(string $messagesFolder = Null, string $userFolder = Null, string $imagesFolder = Null)
    {

        if ($messagesFolder) {
            $this->messagesFolder = $messagesFolder;
        }

        if ($userFolder and $imagesFolder) {
            $this->user = new User($userFolder, $imagesFolder);
        } else {
            $this->user = new User();
        }

        $this->messagesPath = $this->messagesFolder . "/" . $this->messagesFile;

        if (!file_exists($this->messagesFolder)) {
            mkdir($this->messagesFolder);
        }
    }
```
### send Message

this method is used to send a message from user to user if the user s logged in and if the users exists.

Parameters:
- $email: string email of the user
- $to_email: string name of the user to send message to
- $content: string content of the message

The method does not return anything.
```php
    public function sendMessage(string $email, string $to_email, string $content): void
    {

        if (!$this->user->returnUser($to_email)) {
            return;
        }

        if($email == $to_email){
            return;
        }

        $allMessages = $this->getAllMessages();

        $allMessages[$email][time()] = ["from" => $email, "to" => $to_email, "content" => $content];

        file_put_contents($this->messagesPath, json_encode($allMessages));
    }
```
### getAllMessagesU2U

this method is used to get the chat between two users.

Parameters: 
- $from_email: string the email of the user looking at his/hers chat
- $to_email: string name of the user chatting to

The method returns array of messages.
```php
    public function getAllMessagesU2U(string $from_email, string $to_email): array
    {
        $messages = $this->getAllMessages();
        $return_messages = [];

        foreach ($messages as $email => $value) {
            if ($email == $from_email) {
                foreach ($value as $timestamp => $value2) {
                    if ($value2['to'] == $to_email) {
                        $return_messages[$email][$timestamp] = $value2;
                    }
                }
            }

            if ($email == $to_email) {
                foreach ($value as $timestamp => $value2) {
                    if ($value2['to'] == $from_email) {
                        $return_messages[$email][$timestamp] = $value2;
                    }
                }
            }
        }

        return $return_messages;
    }
```
### getAllMessagesU2U

this method is used to get all messages form the json folder databse specified by the path in the atribute messages path.

There are not paramteres in this method.

This method returns all messages from the database.
```php
    public function getAllMessages(): array
    {
        if (file_exists($this->messagesPath)) {
            $data = file_get_contents($this->messagesPath);
            $json_data = json_decode($data, true);
        } else {
            $json_data = [];
        }

        if (!$json_data) {
            $json_data = [];
        }

        return $json_data;
    }
```
### getAllMessagesU2U

this method is used to erase a certain image specified by the from and to email string variables and the timestamp.

Parameters: 
- $from: string email from the message is
- $to: string email of the user the message is to
- $timestamp: timestamp in a form of a string to locate the message selected

The method returns boolean.
```php
    public function eraseMessage(string $from, string $to, string $timestamp): bool
    {
        if ($from != $_SESSION['email']) {
            return False;
        }

        $messages = $this->getAllMessages();

        foreach ($messages as $email => $value) {
            if ($email == $from) {
                foreach ($value as $timeS => $value2) {
                    if ($value2['to'] == $to and $timestamp == $timeS) {
                        unset($messages[$email][$timeS]);
                    }
                }
            }
        }

        file_put_contents($this->messagesPath, json_encode($messages));
        return true;
    }
}
```

# Controller

Controller is on every public url location that the user can reach. It contains the logic for what to do next.
Controller either redirects the user or loads all the important variables and then calls the view to display them to the user.
Controller is also used to handle POST and GET requests.

Individual Controllers are already described in a automatically generated documentation I am going to document only one page here and that is the chats Controlelr as the functionality of others are very simmilar and this is the most complicated one.

## Chats Controller

Session is stated for state holding and then User and Messages are both included,
these are the models described above and are used to hadle user and chatting needs

```php
session_start();

include "Model/User.php";
include "Model/Messages.php";

```
Main variables are inicialised here,
these are the most important variables containing information for the user or the object instances
```php
$user = new User();
$messages = new Messages();
$username = "";
$email = "";
$profilePic = "Default_IMG/person.png";
$allUsers = $user->getAllUsers();
```
These are the variables containing the information about the user that is being messaged,
they can be empty is the messaged user is not selected
```php
$messagedUserName = "";
$messagedUserPic = "Default_IMG/person.png";
$messagedUserEmail = "";
$messagesArr = [];
$searchFilter = "";
```
These are the tokens used to secure the resubmitting problem
```php
$message_token = "";
$search_token = "";

```
if user is not logged in he/she is reidrected to the index page,
to secure that user has to be logged to access chats page
```php
if (isset($_SESSION['email']) and isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
} else {
    header("Location: index.php");
}

```
Asigning the messaged User Email if exists in the GET request
```php
if (isset($_GET['user_messaged'])) {
    $messagedUserEmail = $_GET['user_messaged'];
}
```
If message was sent by the post request it is handled by the messsages object and the new message is saves as well as displayed on the chats page
```php
if (isset($_POST['message']) and isset($_GET['user_messaged']) and isset($_POST['message_token']) and isset($_SESSION['message_token'])) {

    if ($_POST['message_token'] == $_SESSION['message_token']) {
        $messages->sendMessage($email, $messagedUserEmail, $_POST['message']);
    }
}
```
If the user is searching for another user it is handled here
```php
if (isset($_POST['user_search']) and isset($_POST['search_token']) and isset($_SESSION['search_token'])) {

    if ($_POST['search_token'] == $_SESSION['search_token']) {
        $searchFilter = $_POST['user_search'];
    }
}
```
Here we ar assigning new values to the tokens,
this whole process is done to secure the resubmitting
```php
$message_token = hash("sha1", uniqid());
$_SESSION['message_token'] = $message_token;

$search_token = hash("sha1", uniqid());
$_SESSION['search_token'] = $search_token;

if ($user->getUserPic($email)) {
    $profilePic = $user->getUserPic($email);
}

$messagesArr = $messages->getAllMessagesU2U($email, $messagedUserEmail);

```
Here we include the chats view that displays all the information to the user in a form of html
```php
include "view/chatsView.php";
```

# View

View is a part of the MVC framework where the data is displayed to the user by HTML styled by the CSS.

I am going to do the vew documentation in the same fashion as done in the Controller section by documenting the most complicated page,
therefore chats view and not all of the view ages as I would be repeating a lot of times.

HTML header is here with all the important meta tags.
CSS and JS is also linked here,
and the title of the page is stated here.
```php
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css">
    <script src="JS/chats.js" defer></script>
    <title>Chats</title>
</head>
```
In this block of code is the body section of the application,
conatining all the data that the user is going to see in the browser.

The main logic is processed in the controller and the only php logic is done thrue displaying variables,
or thrue paging and logic that coulnt be moved to the controller section.
```php
<body>

    <header>
        <section class='profile_bar chats_profile_bar'>
            <img src='<?php echo htmlspecialchars($profilePic); ?>' alt='Profile Image is displayed here'>
            <h1>
                <?php echo htmlspecialchars($username); ?>
            </h1>
            <p>&#60;</p>
        </section>
        <nav>
            <ul>
                <li class='active'><a href='chats.php'>Chats</a></li>
                <li><a href='settings.php'>Settings</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="search_panel">
            <form action="chats.php" method="POST" id="search_user_form">
                <input type="text" name="user_search" placeholder="Search"
                    value="<?php echo htmlspecialchars($searchFilter); ?>">
                <input type="hidden" name="search_token" value="<?php echo $search_token; ?>">
            </form>
            <ul id="chats_panel">
```
User search panel is displayed here as well as paging is done in this code.

If there are more than 10 users they are paged so they wont cover too much space. 
However the php paging is disabled thrue thrue the js,
because the js pagin is asynchronous.

Users are selected from the database and then loopedthrue.

They are then displayed in the users section when the user can interact with them.
```php
                <?php

                $user_page = 1;
                $higher_page = 1;
                $lower_page = 1;

                if (isset($_GET['user_p']) and $_GET['user_p'] != "none") {

                    $urlAdd = "";

                    if ($messagedUserEmail) {
                        $urlAdd = "&user_messaged=" . $messagedUserEmail;
                    }

                    if (isset($_GET['user_p'])) {
                        $user_page = $_GET['user_p'];
                    }

                    $higher_page = $user_page;
                    $lower_page = $user_page;

                    if (!(count($allUsers) / 10 <= $user_page)) {
                        $higher_page = $user_page + 1;
                    }

                    if ($user_page != 1) {
                        $lower_page = $user_page - 1;
                    }

                    if ($user_page != 1) {
                        echo "<li>
                            <a href='chats.php?user_p=$lower_page$urlAdd' id='higher'>&uarr;</a>
                        </li>";
                    }
                }

                $counter = 0;

                //loop thrue all users and print them
                foreach ($allUsers as $key => $value) {
                    $counter++;

                    if ($counter > ($user_page - 1) * 10 and $counter < ($user_page - 1) * 10 + 10) {

                    } else if (isset($_GET['user_p']) and $_GET['user_p'] == "none") {

                    } else {
                        continue;
                    }

                    //printed user Vars
                    $nameOfUser = $value['username'];
                    $userEmail = $value['email'];
                    $userPic = "Default_IMG/person.png";
                    $class = "";

                    //skip if the looped user is logged in
                    if ($value['email'] == $email) {
                        continue;
                    }

                    $searchResult = strpos(strtolower($value['username']), strtolower($searchFilter));

                    //filtering not searched users
                    if ($searchFilter and !$searchResult) {
                        if ($searchResult !== 0) {
                            continue;
                        }
                    }

                    //condition for the messaged user
                    if (isset($_GET['user_messaged'])) {

                        if ($_GET['user_messaged'] == $userEmail) {
                            $class = "class='active'";
                            $messagedUserName = $nameOfUser;

                            if ($user->getUserPic($value['email'])) {
                                $messagedUserPic = $user->getUserPic($value['email']);
                            }
                        }
                    }

                    //give user its pic if is set
                    if ($user->getUserPic($value['email'])) {
                        $userPic = $user->getUserPic($value['email']);
                    }

                    $userEmailChars = htmlspecialchars($userEmail);
                    $nameOfUserChars = htmlspecialchars($nameOfUser);
                    $userPicChars = htmlspecialchars($userPic);
                    //echo the list item containig the user
                    echo "
                        <li>
                            <a href='chats.php?user_messaged=$userEmailChars' $class data-email='$userEmailChars'>
                                <img src=$userPicChars alt='Image of a User'>
                                <h2>$nameOfUserChars</h2>
                            </a>
                        </li>
                        ";
                }

                if ($higher_page != $user_page and isset($_GET['user_p']) and $_GET['user_p'] != "none") {
                    echo "
                    <li>
                        <a href='chats.php?user_p=$higher_page$urlAdd' id='lower'>&darr;</a>  
                    </li>";
                }
                ?>
            </ul>
        </section>

        <section id="opened_chat">
            <section id="profile_info">
                <img src="<?php echo htmlspecialchars($messagedUserPic); ?>"
                    alt="Image of a person you are messegaing to">
                <h1>
                    <?php echo htmlspecialchars($messagedUserName); ?>
                </h1>
            </section>
            <section id="chat">
                <ul>
```
Here the messages are displayed to the user if another user is selected.

If user is selected the app will retrieve all the messages from the dtabse and sort them by time.

After that they are displayed in the chat page so that the user can look at the messages or
delete some of his message if he does not like it.

He can also send some message if user is selected and it will get send.

The main php code is however disabled by the javascript to do the messaging asynchronously.
```php
                    <?php

                    $messagesU2U = [];

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
                            $contentChars = htmlspecialchars($content);
                            $messagesU2U[$timestamp] = "
                                <li class='respondent_message'>
                                    <span>
                                        $contentChars
                                    </span>
                                </li>";
                        }
                    }

                    ksort($messagesU2U);

                    foreach ($messagesU2U as $message) {
                        echo $message;
                    }

                    ?>

                </ul>
                <form action="chats.php?user_messaged=<?php echo htmlspecialchars($messagedUserEmail) ?>" method="POST"
                    id="message_form">
                    <input type="text" name="message" placeholder="Message" autofocus>
                    <input type="hidden" name="message_token" value="<?php echo $message_token; ?>">
                </form>
            </section>
        </section>
    </main>
</body>

</html>
```