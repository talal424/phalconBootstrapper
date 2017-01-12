<?php
namespace PhalconBoot\Traits;

use Phalcon\Filter;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Session\Adapter\Files as SessionAdapter;


trait PhalconServices
{
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

    /**
     * Examples for all apps
     *
     * @return function
     */
    private function ___url()
    {
        return function () {
            $config = $this->getConfig();

            $url = new UrlResolver();
            $url->setBaseUri($config->application->baseUri);

            return $url;
        };
    }
    
    private function ___view()
    {
        return function () {
            $config = $this->getConfig();
            $view = new View();
            $view->setDI($this);
            $view->setViewsDir($config->application->viewsDir);
            $view->registerEngines([
                '.phtml' => PhpEngine::class
            ]);
            return $view;
        };
    }
        
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
    
    private function ___session()
    {
        // using app name as unique id for sessions
        $appName = $this->appName;
        return function () use ($appName) {
            $session = new SessionAdapter(["uniqueId" => $appName]);
            $session->start();
            return $session;
        };
    }
    
    private function ___filter()
    {
        return function () {
            return new Filter;
        };
    }
}