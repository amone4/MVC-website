README

The `.htaccess` files help route all requests through `public/index.php`<br>
It initiates the application by including `app/bootstrap.php`, and calls `app/Core.php`<br>
All PHP files begin with `defined('_INDEX_EXEC') or die('Restricted access');` to ensure that they are being called by `public/index.php`<br>
The core checks for the component being requested, and calls `dispatchMethod()` to invoke the controller method being asked for<br>
A component has a controller class, which is of the same name as the folder, except with the first letter being capital<br>
Each controller extends `app/libraries/Controller.php`, which provides a method to call a model, or render a view<br>
The methods can be defined as functions within the controller class, or be defined as separate classes inside `methods` folder<br>
The common views are kept within `app/views`. This includes the header, footer, and the common error page<br>
Component specific views are kept within `views` folder, under that component<br>
Each component specific view includes the common header and footer. A component specific navbar can also be created in `navbar.php`<br>
All models extend `app/libraries/Model.php`, which contains the basic select, insert, update, and delete methods<br>
The database interaction is done using `PDO`<br>

To create a new component, make a folder under `app/components` with the name of that component<br>
Under that folder make a file of same name. This will be the controller for the component<br>
For example, `app/components/users/Users.php` will create a `users` component<br>
The constructor of the controller should begin with defining the name of the component. This is a must<br>
The folder may contain a model class if the component requires to interact with the database<br>
The model can then be required using the inherited `model()`. You need not specify
Usually, controller names are kept plural, and model names, singular<br>
If that's not the case, you need to define the model name in the function call<br>
Last line should be the call to the parent constructor<br>
A model constructor should begin by defining the name of the table it'll interact with, if table name is not similar to the component and model names<br>
For example, we don't need to define the table name, if `User.php` interacts with `users` table<br>
The constructor of each class defined in the `methods` folder, must begin by calling the parent constructor<br>