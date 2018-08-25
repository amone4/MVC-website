<h3>Introduction</h3>

This is an MVC design based framework. It treats a project as aggregate of multiple components, with each component, being controlled by a controller, interacting with the database through a model, and showing output to the user by rendering views. The framework is fully usable, and new features are being worked on constantly<br>

<h3>Working</h3>
The project is divided into `app` and `public` folders. The root `.htaccess`, directs all traffic into the public folder, and calls `public/index.php`. All images, css, js, and vendor files, are kept within this folder<br>

`public/index.php` defines `_INDEX_EXEC` to allow files to check, if they have been called by this file. It then calls in `app/bootstrap.php`, and initialises `app/Core.php`<br>

`app/bootstrap.php` includes all files, that are necessary for the app to work. This includes `config/config.php` which includes all constants; all helpers; and all libraries<br>

`libraries/Core.php` determines the component being requested for, and then calls it with the parameters specified in the URL. An invalid component, method, or parameters, results in an error page<br>

Each component has at least one controller. All of them extend `libraries/Controller.php`, which provides core functions for each controller: `getModel()`, `renderView()`, and `dispatchMethod()`<br>

A component interacts with the database through its model. Each model extends `libraries/Model.php`, which provides the functions for basic CRUD functionality. `libraries/Database.php` takes care of the nuances for using PDO. A model can be accessed using `getModel()` / `getModel('User')` / `getModel('users/User')`. Note that each model should begin by defining the table name<br>

A component method, when called from URL, can either be in the definition of the controller, or as a class in `methods` folder. This was done, because the controller files were getting too long. Methods used for internal working of app, should be kept in the controller class. Methods which provide output to user, and are called through URL, should be kept as classes, and called using `dispatchMethod()`. All controllers begin by defining component name. All methods begin by calling parent constructor<br>

Views for each component are kept in its `views` folder. Common `header` and `footer` are kept in `app/views` folder, and automatically get included for all views, if they aren't found in the views folder. `navbar.php`, if found, too gets included automatically after header. The content of the main view, is kept in a `div` with id `container`<br>

<h3>Agendas</h3>
<ul>
	<li>
		<h4>Handling forms</h4>
		It is too cumbersome to type down markup for rendering each form. Similarly, typing down validations for all form fields seems too redundant. A simple solution would be to create a `forms.json`, with details of all forms for a component, and only calling `renderForm()` and `validateForm()` methods to do the necessary tasks.<br>
	</li>
	<li>
		<h4>Command-line interface</h4>
		Already, remembering the nuances of using this framework seems a difficult task. An ideal way to avoid mistakes would be to have a command-line interface, and type down just the commands to perform basic, but error-prone tasks<br>
	</li>
	<li>
		<h4>Uniform view and JSON output rendering</h4>
		This idea is still under development. Currently, JSON output for APIs has to be handled very differently as compared to HTML output. The idea is to make this uniform, with the back-end sending JSON data, API controller sending it to the caller, and Web controller rendering HTML<br>
	</li>
</ul>