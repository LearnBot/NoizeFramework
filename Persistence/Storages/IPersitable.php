<?php

namespace Noize\Persistence;

/**
 * This interface should be implemented by all objects that should be persitable
 * by the persistence API.
 * This does also include objects which are stored inside an object that should
 * be persistable.
 * 
 * A easy implementation would looke like this:
 * {@example
 * public static function __set_state($array) {
 *       $instance = new self();
 *       
 *       foreach ($array as $key => $value) {
 *           $instance->$key = $value;
 *       }
 *       
 *       return $instance;
 * }
 * }
 *
 * @author Jan Gräfen
 * @package Persistence
 * @version 1.0
 */
interface IPersitable {
    
    /**
     * This method is called everytime the persistence layer restores an object
     * from the persistence storage.
     * It takes an associated array of $propertyName => $propertyValue and 
     * returns the new object.
     * 
     * @param array $array An array of property values that should be restored.
     * @return IPersistable
     */
    public static function __set_state($array);
}

?>
