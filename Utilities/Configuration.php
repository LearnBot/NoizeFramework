<?php

namespace Noize\Utilities;

/**
 * This class provides a unified interface to the configuration API of the
 * NoizeFramework.
 * The configuration is <strong>READ-ONLY</strong>.
 *
 * @final
 * @author Jan Gräfen
 * @package Utilities
 * @version 1.0
 */
final class Configuration {
    
    /**
     * Stores the current instance of this class.
     * 
     * @static
     * @var Configuration
     */
    private static $instance = null;
    
    /**
     * Stores all currently loaded confiugration values.
     *
     * @var array
     */
    private $values = array ();
    
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
     * Loads a new configuration file into memory.
     *
     * @param string $file A path to a configuration file that should be loaded.
     * @param string $namespace A config namespace, default is 'default'.
     */
    public function load($file, $namespace = null) {
        if ($namespace == null)
            $namespace = 'default';
        
        $newValues = self::createConfig(
                $namespace, 
                json_decode(file_get_contents($file), true));
        
        $this->values += $newValues;
    }
    
    /**
     * Returns a configuration value.
     *
     * @param string $key A namespaced key of the required value.
     * @return mixed
     */
    public function getValue($key) {
        return $this->values[$key];
    }
    
    /**
     * Returns the current instance of this class.s
     *
     * @static
     * @return Configuration
     */
    public static function instance() {
        if (self::$instance == null)
            self::$instance = new Configuration();
        
        return self::$instance;
    }
    
    /**
     * Flats an incoming array into a config array.
     *
     * @internal
     * @static
     * @param string $namespace The namespace that should be used.
     * @param array $array The array that should be flattned.
     * @return array
     */
    private static function createConfig($namespace, $array) {
        $return = array ();
        
        foreach ($array as $key => $value) {
            if (is_array($value))
                $return = self::createConfig ($namespace . '.' . $key, $value);
            else
                $return[$namespace . '.' . $key] = $value;
        }
        
        return $return; 
    }
}

?>
