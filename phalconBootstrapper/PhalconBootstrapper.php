<?php

namespace PhalconBoot;

use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Config;
use PhalconBoot\Traits\PhalconServices;
use PhalconBoot\Traits\PhalconNamespaces;
use PhalconBoot\Traits\PhalconDirs;
use PhalconBoot\Traits\PhalconFiles;

class PhalconBootstrapper {

	use PhalconNamespaces;
	use PhalconDirs;
	use PhalconFiles;
	use PhalconServices;
	
	private $appName;

	/**
     * Start the Booting
     *
     * @param  Phalcon\DiInterface  $di
     * @param  Phalcon\Loader  $loader
     * @param  string  $appName
     * @return void
     */
	public function __construct(DiInterface $di, Loader $loader, $appName = null) {
		// get Phalcon\Config service
		$config = $di->getConfig();
		// set an uid for the app or use basefolder name as an app name
		$this->appName = is_null($appName) ? str_replace('/', '', $config->application->baseUri) : $appName;
		// register namespaces
		$this->registerNamespaces($loader,$config);
		// register directories
		$this->registerDirs($loader,$config);
		// register files
		$this->registerFiles($loader,$config);
		// register classes
		$this->registerClasses($loader,$config);
		// inject services
		$this->injectServices($di);
		// load composer (optional)
		$this->loadComposer();
	}
	/**
     * Start the namespaces register
     *
     * @param  Phalcon\Loader  $loader
	 * @param  Phalcon\Config  $config
     * @return void
     */
	private function registerNamespaces(Loader $loader, Config $config)
	{
		// register common namespaces for all apps
		$loader->registerNamespaces($this->commonNamespaces($config),true);
		// check if there is a namespace method for the current app
		if (method_exists($this,$this->appName . 'Namespaces')) {
			// register the namespaces for the current app
			$loader->registerNamespaces($this->{$this->appName . 'Namespaces'}($config),true);
		}
	}
	/**
     * Start the directories register
     *
     * @param  Phalcon\Loader  $loader
     * @param  Phalcon\Config  $config
     * @return void
     */
	private function registerDirs(Loader $loader, Config $config)
	{
		// register common directories for all apps		
		$loader->registerDirs($this->commonDirs($config),true);
		// check if there is a directory method for the current app
		if (method_exists($this,$this->appName . 'Dirs')) {
			// register the directories for the current app
			$loader->registerDirs($this->{$this->appName . 'Dirs'}($config),true);
		}
	}
	/**
     * Start the files register
     *
     * @param  Phalcon\Loader  $loader
	 * @param  Phalcon\Config  $config
     * @return void
     */
	private function registerFiles(Loader $loader, Config $config)
	{
		// register common files for all apps
		$loader->registerFiles($this->commonFiles($config),true);
		// check if there is a file method for the current app
		if (method_exists($this,$this->appName . 'Files')) {
			// register the files for the current app
			$loader->registerFiles($this->{$this->appName . 'Files'}($config),true);
		}
	}
	/**
     * Start the class register
     *
     * @param  Phalcon\Loader  $loader
	 * @param  Phalcon\Config  $config
     * @return void
     */
	private function registerClasses(Loader $loader, Config $config)
	{
		$loader->registerClasses($this->commonClasses($config),true);
		if (method_exists($this,$this->appName . 'Classes')) {
			$loader->registerClasses($this->{$this->appName . 'Classes'}($config),true);
		}
		// last step is to register all our previous work (namespaces, directories and files)
		$loader->register();
	}
	/**
     * Start the services injection
     *
     * @param  Phalcon\DiInterface  $di
     * @return void
     */
	protected function injectServices(DiInterface $di)
	{
		// start looking for services to inject
		foreach (get_class_methods($this) as $method) {
			// do checks on method name if this is a service and if can be run on this app
			if (!$service = $this->checkIfCanRun($method)) {
		    	continue;
		    }
    		// get the anonymous function
    		$function = $this->{$method}();
	    	// finally inject the service
	    	$di->setShared($service,$function);
		}
	}
	/**
     * checks if method is a service and can be run on this app
     *
     * @param  string  $method
     * @return boolean
     */
	private function checkIfCanRun($method = null)
	{
		// i used a pre 3 underscores to identify services to be injected
		$methodIsService = strpos($method, '___') === 0 ? true : false;
		if (!$methodIsService) {
	    	return false;
	    }
	    // is it common but not for this app ?
	    if (($index = strpos($method, 'NotFor')) > 4) {
	    	// is it not for this app ?
	    	if (strpos($method, $this->appName) === 4) {
	    		return false;
	    	}
	    	// it is common and this app is not restricted
	    	return substr($method,3,$index - 3);
	    }
	    // is it exclusive for an app ? and is it for this app ?
	    if (strpos($method, 'OnlyFor') > 4 && strpos($method, $this->appName === false)) {
	    	return false;
	    }
	    // if the method bypasses all filters then it can be run
	    // remove all identifying text so we can use it as a service name
		return str_replace(['___','OnlyFor',$this->appName], '', $method);
	}

	private function loadComposer()
	{
		// uncomment and change the path to composer vendor dir
		//require LIB_PATH . 'vendor/autoload.php';
	}
}