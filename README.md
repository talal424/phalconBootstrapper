# phalconBootstrapper

if you like making projects using phalcon as i do

and when you have multiple projects with similar structure

its going to be a nightmare when make changes to the structure

you end up with editing all your projects over and over

thats why this would come in handy

# Installation

download or clone then move the phalconBootstrapper directory to you root path 

where the other phalcon projects are, then make the changes

# Changes

## app/config/services.php

remove all services except Phalcon\config for example:

```php
<?php

$di->setShared('config', function () {
    $config = include APP_PATH . "/config/config.php";
    defined('BASE_URI') || define('BASE_URI', $config->application->baseUri);
    return $config;
});
```
## app/config/loader.php

```php
<?php
$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */

// this is very important the path to our PhalconBoot
defined('LIB_PATH') || define('LIB_PATH', BASE_PATH . '/../phalconBootstrapper/');

$loader->registerNamespaces(
    [
        // define and register the namespace so we start
        "PhalconBoot" =>   LIB_PATH,
    ]
)->register();

// change your app name 'MyAppName'
$PhalconBootstrapper = new PhalconBoot\PhalconBootstrapper($di,new \Phalcon\Loader,'MyAppName');
```

# How to use

## Services

located in Traits/PhalconServices.php

```php
/**
 * Example for an app named (SomeApp)
 * service name: someservice
 * class name: someClass
 *
 * @return function
 */
private function ___someserviceOnlyForSomeApp()
{
    return function () {
        return new someClass();
    };
}
```

this will inject someservice as a service name but only for an app called (SomeApp)

```php
/**
 * Example for all apps except (SomeApp)
 *
 * @return function
 */
private function ___routerNotForSomeApp()
{
    return function () {
        require APP_PATH . '/config/routes.php';
        return $router;
    };
}
```

this will inject router as a service name for all apps except an app called (SomeApp)

```php
private function ___db()
{
    return function () {
        $config = $this->getConfig();
        $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
        return new $class([
            'host'     => $config->database->host,
            'username' => $config->database->username,
            'password' => $config->database->password,
            'dbname'   => $config->database->dbname,
            'charset'  => $config->database->charset
        ]);
    };
}
```

this would inject the service db for all apps

## Namespaces, Classes, Dirs and Files
located in 
Traits/PhalconNamespaces.php
Traits/PhalconClasses.php
Traits/PhalconDirs.php
Traits/PhalconFiles.php

```php
/**
   * Common files registery for all apps
   *
   * @param  Phalcon\Config  $config
   * @return array
   */
private function commonFiles(Config $config)
{
  return [
    // don't forget to change this
    LIB_PATH . 'Tools/Helpers.php'
    ];
}
```

any app would load the files in the array

```php
/**
   * Example for an app named (SomeApp) specific file registery
   *
   * @param  Phalcon\Config  $config
   * @return array
   */
private function SomeAppFiles(Config $config)
{
  return [
    'some/namespace/path/to/src/file.php'
    ];
}
```

this would load the files in the array for only an app called SomeApp

the rest is the same and stright forward
