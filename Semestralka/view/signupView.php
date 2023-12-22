<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css">
    <script src="JS/signup.js" defer></script>
    <title>Signup</title>
</head>

<body>

    <header>
        <section id='firm_name'>
            <h1>Messenger</h1>
            <a href='login.php'>Log In</a>
        </section>
    </header>

    <main id="sign_or_login">

        <form action="signup.php" id="signup_form" method="POST">
            <h1>Sign Up</h1>

            <label for="username" class="form_label">Username</label>
            <input type="text" name="username" id="username" placeholder="Username" required
                Value="<?php echo htmlspecialchars($username); ?>">

            <label for="email_input" class="form_label">Email</label>
            <input type="email" name="email" id="email_input" placeholder="Your Email" required
                Value="<?php echo htmlspecialchars($email); ?>">

            <label for="password" class="form_label">Password</label>
            <input type="password" name="password" id="password" placeholder="Your Password" required>

            <label for="password_input_repeat" class="form_label">Repeat Password</label>
            <input type="password" name="password_repeat" id="password_input_repeat" placeholder="Repeat The Password"
                required>

            <input type="submit" value="Sign Up">

            <input type="hidden" name="signup_token" value="<?php echo $signup_token; ?>">
        </form>

        <p class="error">
            <?php echo $error; ?>
        </p>
    </main>
</body>

</html>