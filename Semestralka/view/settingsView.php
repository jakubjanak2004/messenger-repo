<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css">
    <title>Settings</title>
</head>

<body>

    <header>
        <section class='profile_bar'>
            <img src='<?php echo htmlspecialchars($profilePic); ?>' alt='Profile Image is displayed here'>
            <h1>
                <?php echo htmlspecialchars($username); ?>
            </h1>
        </section>
        <nav>
            <ul>
                <li><a href='chats.php'>Chats</a></li>
                <li class='active'><a href='settings.php'>Settings</a></li>
            </ul>
        </nav>
    </header>

    <main id="settings">

        <h3>My email is:
            <?php echo htmlspecialchars($email); ?>
        </h3><br>

        <form action="settings.php" method="POST">
            <label for="new_username">Change Your Username</label>
            <input type="text" name="new_username" id="new_username" value="<?php echo htmlspecialchars($username); ?>">
            <input type="submit" value="Set">
            <input type="hidden" name="username_token" value="<?php echo $username_token; ?>">
        </form>

        <form action="settings.php" method="POST" enctype="multipart/form-data">
            <label for="new_profile_photo">Pick New Profile Photo</label>
            <input type="file" name="photo" id="new_profile_photo" />
            <input type="submit" value="Set">
            <input type="hidden" name="pic_token" value="<?php echo $pic_token; ?>">
        </form>

        <form action="index.php" method="POST">
            <input type="hidden" name="sign_out" Value="true">
            <input type="submit" value="Sign Out">
        </form>

        <p class="error">
            <?php echo $userPicError; ?>
        </p>

    </main>
</body>

</html>