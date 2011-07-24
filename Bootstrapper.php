<?php

namespace Noize;

use Noize\Utilities\Configuration;

/**
 * This class is resonseable for initializing the framework.
 * You just need to include this file and call the init function of this class.
 *
 * @final
 * @author Jan Gräfen
 * @package default
 * @version 1.0
 */
final class Bootstrapper {

    /**
     * Stores the instance of this class.
     *
     * @static
     * @var Bootstrapper
     */
    private static $instance = null;

    /**
     * Creates an instance of this class.
     * The constructor is private since this class is singleton.
     */
    private function __construct() {
        
    }

    /**
     * Clones an instance of this class.
     * The clone magic function is private since this class is singleton.
     */
    private function __clone() {
        
    }

    /**
     * This method initializes the framework by registering an autoloader,
     * read the configuration file and a few other things which depends on the
     * Configuration.
     */
    public function init() {
        // Before we do anything else we need to register the autoloader.
        $this->registerAutoloader(array ($this, 'loadClass'));
        // Now we are able to access the entire framework, so we can do cool
        // stuff from now on.
        Configuration::instance()->load(__DIR__ . '/framework.json', 'noize');
    }
    
    /**
     * Registers an autoloader.
     *
     * @param callable $callback An function that will be used for autoloading.
     */
    public function registerAutoloader($callback) {
        if (is_callable($callback))
            spl_autoload_register($callback);
    }
    
    /**
     * This function is the default autoloader of the framework.
     *
     * @internal
     * @param string $className The classname of the missing class.
     * @return bool
     */
    public function loadClass($className) {
        if (\strpos($className, 'Noize') !== 0)
            return false;

        $directories    = \explode('\\', $className, 2);
        $classPath      = \str_replace('\\', '/', $directories[1]) . '.php';

        require_once __DIR__ . '/' . $classPath;
        return \class_exists($className, false);
    }
    
    /**
     * Returns the current instance of this class.
     *
     * @static
     * @return Bootstrapper
     */
    public static function instance() {
        if (self::$instance == null)
            self::$instance = new Bootstrapper();

        return self::$instance;
    }

}

?>
