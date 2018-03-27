# MVC-website
A website based on MVC design pattern.

The website searches through various doctors, based on their city and category.

The website won't run without its database.
Create a database and run `database.sql` to make all the tables required.
Fill in app/config/config.php with constants to initialize the website.
.
It also. has user related features:.
	Register;
	Login;
	Logout;
	Forgot Password;
	Change Password;
	Email Confirmation;

The website can be interfaced with an OTP service.
The service that I used for testing, requires an API key.
The OTP related code has not been removed.

The flow of the website:
	The root `.htaccess` file routes the root request onto `public/index.php`.
	The included `Core.php` checks the URL and calls the required function, from the required controller.
	`Models` manipulate data, and `Views` handle what is displayed to the user.
	`Controllers` manage the interchange between models and views.
	`config/config.php` contains all the constants relevant to the website.
	All controllers inherit properties from the base controller in `libraries/Controller.php`.
	Database related stuff is managed using PDO by `libraries/Database.php`.
	`helpers` contain all stand-alone functions that have been used in the website.
