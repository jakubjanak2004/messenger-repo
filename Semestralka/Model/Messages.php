<?php
/**
 * This file contains a class Messages
 * 
 * Handling the Model side of the MVC framework,
 * Primarilly comunicating with the Database
 * 
 * @author Jakub Janák
 */

/**
 * Class of a messages, handles the management of messages.
 * 
 * @package Model
 * @author Jakub Janák
 */
class Messages
{

    private string $messagesFolder = "json_db";
    private string $messagesFile = "messages.json";
    private string $messagesPath = "";
    private null|User $user = NULL;

    /**
     * Constructor of a class User.
     * 
     * If parameter is set it will override the default folder path.
     * 
     * @param string    $messagesFolder
     * @param string    $userFolder
     * @param string    $imagesFolder
     */
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

    /**
     * Send Message to a user.
     * 
     * @param string    $email
     * @param string    $to_email
     * @param string    $content
     */
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

    /**
     * Get All messages between two users.
     * 
     * @param string    $from_email
     * @param string    $to_email
     * 
     * @return array    $return_messages
     */
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

    /**
     * Get all messages from Database.
     * 
     * @return array    $json_data
     */
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

    /**
     * Erase a message from database.
     * 
     * @param string    $from
     * @param string    $to
     * @param string    $timestamp
     * 
     * @return bool
     */
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