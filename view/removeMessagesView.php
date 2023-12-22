<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css">
    <title>Remove Message</title>
</head>

<body>
    <main id="delete-main">
        <p class="my_message">Your Message: <span>
                <?php echo htmlspecialchars($content); ?>
            </span></p>
        <form action="removeMessage.php" method="POST">
            <input type="submit" value="Remove">
            <input type="hidden" name="from" value=<?php echo htmlspecialchars($from); ?>>
            <input type="hidden" name="to" value=<?php echo htmlspecialchars($to); ?>>
            <input type="hidden" name="timestamp" value=<?php echo htmlspecialchars($timestamp); ?>>
            <input type="hidden" name="delete_token" value="<?php echo $delete_token; ?>">
        </form>
        <a href="chats.php">Don`t Remove</a>
    </main>
</body>

</html>