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