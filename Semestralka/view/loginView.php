<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css">
    <script src="JS/login.js" defer></script>
    <title>Login</title>
</head>

<body>

    <header>
        <section id='firm_name'>
            <h1>Messenger</h1>
            <a href='signup.php'>Sign Up</a>
        </section>
    </header>

    <main id="sign_or_login">
        <form action="login.php" method="POST" id="loggin_form">
            <h1>Log In</h1>
            <label for="email_input" class="form_label">Email</label>
            <input type="email" name="email" id="email_input" placeholder="Email" required
                value="<?php echo htmlspecialchars($email); ?>">
            <label for="password_input" class="form_label">Password</label>
            <input type="password" name="password" id="password_input" placeholder="Password" required>
            <input type="submit" value="Log In">
            <input type="hidden" name="login_token" value="<?php echo $login_token; ?>">

            <p class="error">
                <?php echo $error; ?>
            </p>
        </form>
    </main>
</body>

</html>