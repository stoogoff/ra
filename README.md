# Ra

A simple PHP MVC library which I've been using since 2007.

## Usage

Assuming a folder structure something like:

- lib
	- components
		- blog
		- (other components)
	- vendor
		- Ra
		- (other vendor specific code)
- www
	- index.php
	- (other assets)

Your webserver should be set up to forward all page requirests to a single file (usually index.php).

**index.php** gets everything started:

``` PHP
require_once 'settings.local'; # any site specific settings this is optional and can be named anything
require_once '../lib/vendor/Ra/AutoLoader.php';

$settings = new Ra\Settings();
$settings->setArray($config); # from settings.local

$loader = new Ra\AutoLoader($settings);
$app = new Ra\Application($settings);

# add any components
$loader->addComponent('blog', $app);

# create a default controller and method to be called for the URL /
$app->setDefault('BlogController', 'index');
$app->run();
```

A component is really any folder of code but should be a self contained unit. The folder is added to the autoloader so any PHP files will found. If a folder contains a file called `main.php` it is called when the component is added. Use this for any component specific config.

### Controllers

Ra uses controllers to build URLs rather than specifying routes. Controllers need to inherit from `\Ra\Controller` and must be named with `Controller` as a suffix. The class name forms the base of the URL, with the `Controller` part stripped. The second part of the URL will be called as a public method of the controller. Any subsequent parts are passed as parameters to the method. So a request for `/blog/view/3` would pass the paremeter `3` to the view `method` of the `BlogController` class.


``` PHP
class BlogController extends \Ra\Controller {
	# called by the URL /blog/index
	# using the above index.php with its default controller settings:
	# $app->setDefault('BlogController', 'index');
	# this would also be called for / and /blog as well as /blog/index
	public function index() {
		return "Hello"; # returned to the client
	}

	# called by the URL /blog/view/<parameter>
	public function view($id) {
		# $post = ... sanitise $id and load post from it
		$this->model->post = $post;

		return $this->render>();
	}
}
```

A Ra controller automatically creates a model object for you, which extends the `Ra\Model` class. You can add any properties you need to it and the model will be sent to your template engine using the controller `render` method. Controller methods can also completely bypass this and return a string which will be sent to the client.

### Views

Ra doesn't provide a templating engine but it's simple to add one by extending the `Ra\View` class. I normally use [XaminProject's PHP Handlebars](https://github.com/XaminProject/handlebars.php) implementation because I find it useful to have the same template syntax on both the back and front end. To add **Handlebars** as a template engine create a component folder for templates and load it from your index.php file (`$loader->addComponent('blog', $app);`). Create a **main.php** file for your component:

``` PHP
$settings->view = 'HandlebarsView'; # the class Ra will use to render templates
require $settings->vendor . 'Handlebars/Autoloader.php';

Handlebars_Autoloader::register();
```

Extend the `\Ra\View` class and override its `render` method.

``` PHP
class HandlebarsView extends \Ra\View {
	public function render() {
		$engine = new Handlebars_Engine(array(
			'partials_loader' => new Handlebars_Loader_FilesystemLoader($this->settings->templates, array(
				'extension' => '.html'
			)),
		));

		# add any custom template helpers
		#$engine->addHelper('helper', function($template, $context, $args, $source) {});

		return $engine->render($this->template, $this->model);
	}
}
```

The default location for templates is `www/media/tpl/`, which can be overriden by setting using the settings class (`$settings->templates = '/path/to/templates')`. The template name can be passed to the controller `render` method but Ra will look for a template based on controller and method name. A URL of `/blog/view/3` would look for:

- <template-path>/blog_view.html
- <template-path>/blog/view.html

If a template can't be found an exception is raised.

### Content Negotiation

Ra can handle content negotiation by adding file extensions to the URL, e.g. `/blog/view/3.json`. At the moment it doesn't use the HTTP accepts header for this. If a file extension is added then the view will look for a template with that extension (e.g. `blog_view.json` or `blog/view.json`) instead of the default HTML template. Correct HTTP content type headers will be sent.

### Handling Exceptions

Ra has a `CoreException` object with the following constructor:

``` PHP
class CoreException extends \Exception {
	/**
	 * Constructor.
	 *
	 * @param  $message        string    Exception message.
	 * @param  $code           integer   Optional HTTP Error code. Defaults to 500 (server error).
	 * @param  $innerException Exception Optional exception which caused the current exception.
	 */
	public function __construct($message, $code = 500, Exception $innerException = null) {}
}
```

Throwing a PHP exception anywhere in your application will result in a `CoreException` being thrown with a `code` of **500** and the original exception as the inner exception. You can also throw a `CoreException` anywhere to get the same behaviour.

A basic `ErrorController` needs to be created to handle exceptions, this is as simple as:

``` PHP
class ErrorController extends \Ra\Controller {
	public function view($error) {
		return $this->render();
	}
}
```

The error message can be logged as required. You need to set up appropriate templates to render the view (e.g. `error_view.html` or `error/view.html`).
