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

```php
<?php

class User
{

    private string $userFolder = "json_db";
    private string $userFile = "users.json";
    private string $imagesFolder = "img_db";
    private string $userPath = "";

    /**
     * Constructor of a class User.
     * 
     * If parameter is set it will override the default folder path.
     * 
     * @param string    $userFolder
     * @param string    $imagesFolder
     */
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

    /**
     * Function to sign up a user.
     * 
     * @param string    $username
     * @param string    $email
     * @param string    $password
     * @param string    $password2
     * 
     * @return string   $error
     */
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

    /**
     * Function to log in a user.
     * 
     * @param string    $email
     * @param string    $password
     * 
     * @return string   $error
     */
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

    /**
     * Function to return a user.
     * 
     * @param string    $email
     * 
     * @return array|bool   $value|False
     */
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

    /**
     * Function to return all users in the databse.
     * 
     * @return array    $json_data
     */
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

    /**
     * Function to change username.
     * 
     * @param string    $email
     * @param string    $newUsername
     * 
     * @return bool     True|False
     */
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

    /**
     * Function to change user Pic.
     * 
     * The filesize shouldn`t be bigger than 200KB -> 1.6m bits 
     * 
     * @param string    $email
     * @param array     $profilePic
     * 
     * @return string    $error
     */
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

    /**
     * Function to get User Pic.
     * 
     * @param string    $email
     * 
     * @return string|bool   $oneUser|False
     */
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

    /**
     * Function to save all users.
     * 
     * @param array    $users
     */
    public function saveAllUsers(array $users): void
    {
        file_put_contents($this->userPath, json_encode($users));
    }
}
```