# Programmer Documentation

This is a docmentation for programmer showing the code structure as well as agorthyhms used in the Messenger application.

This app is created with respect to MVC framework, therefore the documentation will follow the same pattern of:
- Model
- View
- Controller

# Model

this part of the code is managng the data and communicates with the database.
There are two classes used in here, the User class and Message class.

## User

This class represents a user and has atributes and mthods to validate user, create new user,...

Class User has 4 atributes that are all encpsulated and cannot be accessed, therefore are private,
if you wanna change them you can do that thrue the constructor of a class.

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
this method is used to log in user with already creted username, if error occured it is returned thrue the $error variable.
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
if the suer is not founf false will be returned
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
this method will return all users from the database in a form of a json
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
this method will change username of a user if exists
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
if not error is returned
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
if not false is returned
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
this method saves all users in the database folder in a form of a json encoded string
```php
    public function saveAllUsers(array $users): void
    {
        file_put_contents($this->userPath, json_encode($users));
    }
}
```

## Message

Class Message is used when user is loggen in and wants to access a chat, wants t send message,...

Class has 4 atributes, message folder, user folder and images folder can be changed thrue the constructor

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
this method is used to send a message from user to user if the user s logged in and if the users exists
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
this method is used to get the chat between two users
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
this method is used to get all messages form the json folder databse specified by the path in the atribute messages path
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
this method is used to erase a certain image specified by the from and to email string variables and the timestamp
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