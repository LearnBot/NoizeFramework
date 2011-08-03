<?php

namespace Noize\Persistence;

/**
 * An interface for classes that should be persistable of the persistence API.
 * This interface should be implemented not just by classes which will be
 * persited, but also by all classes which are stored by classes that should be
 * persistable.
 * 
 * {@example
 *  public static function __set_state($array) {
 *      $instance = new self();
 *
 *      foreach ($array as $key => $value) {
 *          $instance->$key = $value;
 *      }
 *
 *      return $instance;
 *  }
 * }
 * 
 * @author Jan Gräfen
 * @package Persistence
 * @version 1.0
 */
interface IPersistable {
  
    /**
     * This magic method is called every time the state of an object should be
     * restored from the persistance storage.
     * It returns an new instance of the persisted object.
     * 
     * @static
     * @param array $array Contains all property-value pairs.
     * @return IPersistable
     */
    public static function __set_state($array);
}