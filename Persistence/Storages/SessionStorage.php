<?php

namespace Noize\Persistence\Storages;

/**
 * Provides a persitence storage which uses a file as a backend.
 * In most scenarious this is not the best/fastest solution, but is provided for
 * infrastructors where no other option is available.
 *
 * @final
 * @author Jan Gräfen
 * @package Persistence
 * @subpackage Storages
 * @version 1.0
 */
final class FileStorage extends BaseStorage {
    
    /**
     * Loads the persistence file into memory.
     * 
     * Creates a new instance of this class.
     * The constructor is protected since this class is singleton.
     */
    protected function __construct() {
        // Call the parent constructor
        parent::__construct();
        
        session_start();
    }
    
    /**
     * Saves the persited values into a file.
     */
    public function __destruct() {
        session_write_close();
    }
    
    /**
     * Tries to access an object inside the backend storage and returns it.
     * 
     * @param mixed $objKey The key of the persited object.
     * @return mixed
     */
    public function getObject($objKey) {
        return $_SESSION[$objKey];
    }
    
    /**
     * Tries to save an object into the backend storage.
     * 
     * @param mixed $objKey The key of the persited object.
     * @param mixed $obj The object that should be persited.
     */
    public function saveObject($objKey, $obj) {
        $_SESSION[$objKey] = $obj;
    }
    
    /**
     * Deletes an object from the persitence storage.
     * 
     * @param mixed $objKey The key of the persited object.
     */
    public function deleteObject($objKey) {
        unset ($_SESSION[$objKey]);
    }
}