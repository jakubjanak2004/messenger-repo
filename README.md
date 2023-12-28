# Messenger Application - Product Documentation

Here is a Repo for my Messenger application for a class ZWA.
This page should be considered as the Product documentation,
therefore usage of the web application is explained on this page. 

Code is also saved in Semestralka folder in the box above. 

For more information about the code used check the [Automatically generated documentation](https://zwa.toad.cz/~janakja5/Semestralka/docs/api/)

# Important Links:

- Hosted Application: [ZWA Server Link](https://zwa.toad.cz/~janakja5/Semestralka/login.php)
- Automatically generated documentation: [ZWA Documentation Link](https://zwa.toad.cz/~janakja5/Semestralka/docs/api/)

# Creating an account

You cannot use this application without having a account and being logged in. 
If you are new to this messenger app you probably dont have an account and therefore you should create one.  
The process of creating account is described below.

When you dont have an account you are automatically redirected to the login.php page.

By clicking on the purple button next to the messenger text you can switch between Log In and Sign Up page.

### Signup Page

![Signup page Image](https://github.com/jakubjanak2004/messenger-repo/blob/main/Semestralka/images/Sn%C3%ADmka%20obrazovky%202023-12-28%20o%205.31.55%E2%80%AFPM.png)

Signup page is used for creating new accounts, therefore not for accessing already created ones, login page is for that purpose.

In the signup page you have a form with 4 inputs. You need to fill all of them and they need to satisfy certain conditions.

This are the individual inputs and their requirements:
- Username can repeat, somethig you would like to be called in the network
- email cannot repeat and the signup prcess will fail if it does
- password has to be at least 8 characters long
- password repeat and password has to be the same, checked by the JS and server side logic

![Passwords check Image](https://github.com/jakubjanak2004/messenger-repo/blob/main/Semestralka/images/Sn%C3%ADmka%20obrazovky%202023-12-28%20o%206.04.13%E2%80%AFPM.png)

Password are always asynchronously checked against each other and when are not the same the input label changes background to red

When you create an account you are immediatelly redirected to the Chats page.

If there is an error with the signup process you will be warned with error messages just below the form.

![Signup Error Image](https://github.com/jakubjanak2004/messenger-repo/blob/main/Semestralka/images/Sn%C3%ADmka%20obrazovky%202023-12-28%20o%206.04.30%E2%80%AFPM.png)

Above is a immage of the signup form not being successfull as the user with the signu email already exists.

### Login Page

![Login page Image](https://github.com/jakubjanak2004/messenger-repo/blob/main/Semestralka/images/Sn%C3%ADmka%20obrazovky%202023-12-28%20o%205.31.48%E2%80%AFPM.png)

If you created an account thrue the signup page in the past and wanna access it,
you can do that thrue the login page.
Logging has to be done when the session expires and therefore needs to be reset.

Here is a list of inputs with their criteria:
- Email is the email you used to sign up
- Password is the password you used in sign up stage

![Login Page Error Image](https://github.com/jakubjanak2004/messenger-repo/blob/main/Semestralka/images/Sn%C3%ADmka%20obrazovky%202023-12-28%20o%206.03.52%E2%80%AFPM.png)

If the loggin process is not succesfull you wil get information that username or password is wrong.
We cannot state if one or the other is  wrong for security reasons. Please check the email and password you entered.

If the loggin process succeeeded you will be redirected to the chats page.

# Using an logged acount

### Chats page

![Chats Page Image](https://github.com/jakubjanak2004/messenger-repo/blob/main/Semestralka/images/Sn%C3%ADmka%20obrazovky%202023-12-28%20o%205.32.06%E2%80%AFPM.png)

In the chats page you can see all the users that use the messener application,
if there is more than 10 user paging is being applied.

If you cannot see the user you wanna find and there are lost of them,
zou can use the search bar and look for certain user. If found all users with searched phrase will appear on the chats page.

When you want to send message you have to select the user orherwise the message wont be sent.
After that you can write the message and send, it will immediatelly appear in the chat section.

If the user you are messaging to sends message the chat will automatically refesh.

If a certain message belongs to you you can delete it by clicking on the message.

### Remove Message Page

![Chats Page Image](https://github.com/jakubjanak2004/messenger-repo/blob/main/Semestralka/images/Sn%C3%ADmka%20obrazovky%202023-12-28%20o%205.39.24%E2%80%AFPM.png)

This page is created for deleting messages created by certain user.

If message is clicked and it belongs to you, you are redirected to this page.

Here you can decide if you want to delete the message selected, 
after that you are immediatelly redirected back to the chats page.

### Settings Page

![Chats Page Image](https://github.com/jakubjanak2004/messenger-repo/blob/main/Semestralka/images/Sn%C3%ADmka%20obrazovky%202023-12-28%20o%205.32.13%E2%80%AFPM.png)

If you are logged in and wanna change username, profilepic or wanna sign out of the messenger application,
you can do it in here in settings page.

You can change the following:
- Username
- Profile Pic: must be a jpg, jpeg or png and cannot be bigger than 200KB
- Sign Out: if you dont wannat to have your account opened anymore
