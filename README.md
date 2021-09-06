# Routing

A simple way to stat with symfony/routing

Require php 7.3^

```
composer require danidoble/routing
```

```Debug::enable();``` This is only for debug mode, change it for a custom page,
check [symfony/error-handler](https://github.com/symfony/error-handler)

Make a file named ```.env``` with ```APP_URL="https://localhost/"```
change localhost for your url site

```php
use Symfony\Component\ErrorHandler\Debug;
use Danidoble\Routing\Route;
use Danidoble\Routing\Testing;

include __DIR__ . "/vendor/autoload.php";

// This is only for debug mode, change it for a custom page, check [symfony/error-handler](https://github.com/symfony/error-handler)
Debug::enable();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__");
$dotenv->load();

```

Instantiate Route of ```\Danidoble\Routing\Route;```

```php
$routes = new Route();

//add routes
$routes->add('/', [Testing::class, 'index'])->setMethods(['GET','POST']); //only get and post allowed
$routes->add('/help', [Testing::class, 'index'])->setMethods('GET'); //only get allowed
$routes->add('/danidoble', [Testing::class, 'index', 'a']); // all methods allowed

// dispatch 
$routes->dispatch();
```

Creating route example

```php
$path = "/lorem";//route slug
$controller = \Danidoble\Routing\Testing::class; // class/controller
$method = "index"; // method to execute
$route_name = "example_route"; // name of route

$routes->add($path,[$controller,$method,$route_name]);
```

In your controller

```php
class Testing extends \Danidoble\Routing\Controller
{
    public function index()
    {
        //dump($this->getParent());
        //dump($this->param('demo'));
        dump($this->getParams());
    }
}
```

If you want to overwrite ```__construct()``` be sure to apply some code like below  

```php
public function __construct(RequestContext $_context, RouteCollection $_routes, UrlMatcher $_matcher, Route $_parent)
{
    parent::__construct($_context, $_routes, $_matcher, $_parent);
    // ... your code here
}
```