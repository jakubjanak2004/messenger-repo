# Messenger Application - Product Documentation

Here is a Repo for my Messenger application for a class ZWA.
This page should be considered as the Product documentation,
therefore usage of the web application is explained on this page. 

The code is also saved in the Semestralka folder in the folder above. 

More information about the code is here:
- [Programmer documentation](https://github.com/jakubjanak2004/messenger-repo/blob/main/ProgrammerDocumentation.md) 
- [Automatically generated documentation](https://zwa.toad.cz/~janakja5/Semestralka/docs/api/)

## Important Links:

- Hosted Application: [ZWA Server Link](https://zwa.toad.cz/~janakja5/Semestralka/login.php)
- Programmer Documentation [Programmer Docs Link](https://github.com/jakubjanak2004/messenger-repo/blob/main/ProgrammerDocumentation.md)
- Automatically generated documentation: [ZWA Documentation Link](https://zwa.toad.cz/~janakja5/Semestralka/docs/api/)

# Creating an account

You cannot use this application without having an account and being logged in. 
If you are new to this messenger app you probably don't have an account and therefore you should create one.  
The process of creating an account is described below.

When you don't have an account you are automatically redirected to the login.php page.

By clicking on the purple button next to the messenger text you can switch between Log In and Sign Up page.

## Signup Page

![Signup page Image](https://github.com/jakubjanak2004/messenger-repo/blob/main/Semestralka/images/Sn%C3%ADmka%20obrazovky%202023-12-28%20o%205.31.55%E2%80%AFPM.png)

The signup page is used for creating new accounts, therefore not for accessing already created ones, the login page is for that purpose.

On the signup page, you have a form with 4 inputs. You need to fill all of them and they need to satisfy certain conditions.

These are the individual inputs and their requirements:
- Username can repeat, something you would like to be called in the network
- email cannot be repeated and the signup process will fail if it does
- the password has to be at least 8 characters long
- password repeat and password has to be the same, checked by the JS and server-side logic

![Passwords check Image](https://github.com/jakubjanak2004/messenger-repo/blob/main/Semestralka/images/Sn%C3%ADmka%20obrazovky%202023-12-28%20o%206.04.13%E2%80%AFPM.png)

Passwords are always asynchronously checked against each other and when are not the same the input label changes background to red.

When you create an account you are immediately redirected to the Chats page.

If there is an error with the signup process you will be warned with error messages just below the form.

![Signup Error Image](https://github.com/jakubjanak2004/messenger-repo/blob/main/Semestralka/images/Sn%C3%ADmka%20obrazovky%202023-12-28%20o%206.04.30%E2%80%AFPM.png)

Above is an image of the signup form not being successful as the user with the signup email already exists.

This is a common issue and is not checked by the asynchronous javascript,
because a user has to know if he/she used that email already.

## Login Page

![Login page Image](https://github.com/jakubjanak2004/messenger-repo/blob/main/Semestralka/images/Sn%C3%ADmka%20obrazovky%202023-12-28%20o%205.31.48%E2%80%AFPM.png)

If you created an account through the signup page in the past and wanna access it,
you can do that through the login page.
Logging has to be done when the session expires and therefore needs to be reset.

Here is a list of inputs with their criteria:
- Email is the email you used to sign up
- Password is the password you used in sign up stage

![Login Page Error Image](https://github.com/jakubjanak2004/messenger-repo/blob/main/Semestralka/images/Sn%C3%ADmka%20obrazovky%202023-12-28%20o%206.03.52%E2%80%AFPM.png)

If the login process is not successful you will get information that username or password is wrong.
We cannot state if one or the other is wrong for security reasons. Please check the email and password you entered.

If the logging process succeeds you will be redirected to the chats page automatically.
Then you can continue using the application as a logged user.

# Using a logged account

When you are logged in session is created on the server side of the application and while the session is valid,
you are considered a logged user. After that period you will be redirected to the login page.

## Chats page

![Chats Page Image](https://github.com/jakubjanak2004/messenger-repo/blob/main/Semestralka/images/Sn%C3%ADmka%20obrazovky%202023-12-28%20o%205.32.06%E2%80%AFPM.png)

On the chats page, you can see all the users that use the messenger application,
if there are more than 10 users paging is being applied.

If you cannot see the users you wanna find and there are lots of them,
you can use the search bar and look for certain users. If found all users with searched phrases will appear on the chats page.

When you want to send the message you have to select the user otherwise the message won't be sent.
After that you can write the message and send it, it will immediately appear in the chat section.

If the user you are messaging to sends a message the chat will automatically refresh.

If a certain message belongs to you you can delete it by clicking on the message.

## Remove Message Page

![Chats Page Image](https://github.com/jakubjanak2004/messenger-repo/blob/main/Semestralka/images/Sn%C3%ADmka%20obrazovky%202023-12-28%20o%205.39.24%E2%80%AFPM.png)

This page is created for deleting messages created by certain users.

If the message is clicked and it belongs to you, you are redirected to this page.

Here you can decide if you want to delete the message selected, 
after that, you are immediately redirected back to the chats page.

## Settings Page

![Chats Page Image](https://github.com/jakubjanak2004/messenger-repo/blob/main/Semestralka/images/Sn%C3%ADmka%20obrazovky%202023-12-28%20o%205.32.13%E2%80%AFPM.png)

If you are logged in and wanna change your username, or profile pic or wanna sign out of the messenger application,
you can do it here on the settings page.

You can change the following:
- Username
- Profile Pic: must be a jpg, jpeg, or png and cannot be bigger than 200KB
- Sign Out: if you don't want to have your account opened anymore

This page was a product/user documentation describing what and how things work from the user side.
If you are interested in the programmer/code side of my application you can check the programmer documentation.