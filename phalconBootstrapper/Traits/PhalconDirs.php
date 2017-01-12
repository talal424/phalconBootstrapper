<?php

namespace PhalconBoot\Traits;

use Phalcon\Config;

trait PhalconDirs
{
	/**
     * Common directories register for all apps
     *
     * @param  Phalcon\Config  $config
     * @return array
     */
	private function commonDirs(Config $config)
	{
		return [
			$config->application->controllersDir,
        	$config->application->modelsDir,
        	$config->application->libraryDir,
		];
	}
	/**
     * Example for an app named (SomeApp) specific directory register
     *
     * @param  Phalcon\Config  $config
     * @return array
     */
	private function SomeAppDirs(Config $config)
	{
		return [
    		'some/namespace/path/to/dir/',
		];
	}
}