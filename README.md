# Messenger Application

Here is a Repo for my Messenger application for a ZWA.
Usage of the web application is explained on this page. 

Code is also saved in Semestralka folder in the box above.

For more information about the code used check the [Automatically generated documentation](https://zwa.toad.cz/~janakja5/Semestralka/docs/api/)

## Important Links:

- Hosted Application: [ZWA Server Link](https://zwa.toad.cz/~janakja5/Semestralka/login.php)
- Automatically generated documentation: [ZWA Documentation Link](https://zwa.toad.cz/~janakja5/Semestralka/docs/api/)

## Creating an account

When you dont have an account you are automatically redirected to the login.php page.

By clicking on the purple button next to the messenger text you can switch between Log In and Sign Up page.

#### Signup Page

If you dont have an accout you need to go to the Sign Up page and create an account there
- Username can repeat, somethig you would like to be called in the network
- email cannot repeat and the signup prcess will fail if it does
- password has to be at least 8 characters long
- password repeat and password has to be the same, checked by the JS and server side logic

When you create an account you are immediatelly redirected to the Chats page

#### Login Page

If you are not logged in, therefore you session key is not valid you need to use login page to get into the account.
- Email is the email you used to sign up
- Password is the password you used in sign up stage

If the loggin process is not succesfull you wil get information that username or password is wrong.
We cannot state if one or the other is  wrong for security reasons.

If the loggin process succeeeded you will be redirected to the chats page.

To use the login page you need to have an account, created in signup page.

## Using an logged acount

#### Chats page

In the chats page you can list thrue all users with profiles created in our application.

You can even search for the specific user by username.

You can send message if user is selected, if you receive a message your chat will be refreshed.

You can even delete message if it was created by you.

#### Remove Message Page

If message is clicked and it belongs to you, you are redirected to the remove messages page.

Thee you can decide if you want to delete the message selected, 
after that you are immediatelly redirected to the chats page

#### Settings Page

If you want to change something about your profile you ca do it on the settings page.

You can change:
- Username
- Profile Pic: must be a jpg, jpeg or png and cannot b bigger than 200KB
- Sign Out: if you dont wannat to have your account opened anymore