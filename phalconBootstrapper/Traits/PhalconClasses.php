<?php
namespace PhalconBoot\Traits;

use \Phalcon\Config;

trait PhalconClasses
{
    /**
     * Common classes loader
     *
     * @param  Phalcon\Config  $config
     * @return array
     */
    private function commonClasses(Config $config)
    {
        return [
        ];
    }

    /**
     * Example for an app named (SomeApp) specific classes register
     *
     * @param  Phalcon\Config  $config
     * @return array
     */
    private function SomeAppClasses(Config $config)
    {
        return [
            'PHPExcel' => LIB_PATH . 'PHPExcel/PHPExcel.php',
        ];
    }
}