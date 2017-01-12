<?php

namespace PhalconBoot\Traits;

use Phalcon\Config;

trait PhalconFiles
{
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
}