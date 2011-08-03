<?php

namespace Noize\Persistence\Storages;

/**
 * Provides an abstract interface of an persistence storage backend.
 * All concrete storage backends extend this class.
 *
 * @abstract
 * @author Jan Gräfen
 * @package Persistence
 * @subpackage Storages
 * @version 1.0
 */
abstract class BaseStorage {
    
    /**
     * Stores the current instance of this class.
     * 
     * @static
     * @var BaseStorage
     */
    protected static $instance = null;
    
    /**
     * Creates a new instance of this class.
     * The constructor is protected since this class is singleton.
     */
    protected function __construct() {
        
    }
    
    /**
     * Clones an instance of this class.
     * The clone magic function is protected since this class is singleton.
     */
    protected function __clone() {
        
    }
    
    /**
     * Tries to access an object inside the backend storage and returns it.
     * 
     * @abstract
     * @param mixed $objKey The key of the persited object.
     * @return mixed
     */
    public abstract function getObject($objKey);
    
    /**
     * Tries to save an object into the backend storage.
     * 
     * @abstract
     * @param mixed $objKey The key of the persited object.
     * @param mixed $obj The object that should be persited.
     */
    public abstract function saveObject($objKey, $obj);
    
    /**
     * Deletes an object from the persitence storage.
     * 
     * @abstract
     * @param mixed $objKey The key of the persited object.
     */
    public abstract function deleteObject($objKey);
    
    /**
     * Returns the current storage instance.
     *
     * @static
     * @return BaseStorage
     */
    public static function instance() {
        if (static::$instance == null)
            static::$instance = new static();
        
        return static::$instance;
    }
}