<?php
namespace PhalconBoot\Traits;

use \Phalcon\Config;

trait PhalconNamespaces
{
	/**
     * Common namespace register
     *
     * @param  Phalcon\Config  $config
     * @return array
     */
	private function commonNamespaces(Config $config)
	{
		return [
    		'Carbon'	=>	LIB_PATH . 'vendor/nesbot/carbon/src/Carbon/',
		];
	}
	/**
     * Example for an app named (SomeApp) specific namespace register
     *
     * @param  Phalcon\Config  $config
     * @return array
     */
	private function SomeAppNamespaces(Config $config)
	{
		return [
    		'SomeNamespace'	=>	'some/namespace/path/to/src/',
		];
	}
}