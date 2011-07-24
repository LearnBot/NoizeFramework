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
     * Stores the current persited objects.
     *
     * @var array
     */
    private $memStorage = array();

    /**
     * Loads the persistence file into memory.
     * 
     * Creates a new instance of this class.
     * The constructor is protected since this class is singleton.
     */
    protected function __construct() {
        // Call the parent constructor
        parent::__construct();

        $export = file_get_contents(__DIR__ . '/../../memory.per');
        eval ('$this->memStorage = ' . $export . ';');
    }

    /**
     * Saves the persited values into a file.
     */
    public function __destruct() {
        file_put_contents(
                __DIR__ . '/../../memory.per', 
                var_export($this->memStorage, true));
    }

    /**
     * Tries to access an object inside the backend storage and returns it.
     * 
     * @param mixed $objKey The key of the persited object.
     * @return mixed
     */
    public function getObject($objKey) {
        if (isset($this->memStorage[$objKey]))
            return $this->memStorage[$objKey];
        else
            return null;
    }

    /**
     * Tries to save an object into the backend storage.
     * 
     * @param mixed $objKey The key of the persited object.
     * @param mixed $obj The object that should be persited.
     */
    public function saveObject($objKey, $obj) {
        $this->memStorage[$objKey] = $obj;
    }

    /**
     * Deletes an object from the persitence storage.
     * 
     * @param mixed $objKey The key of the persited object.
     */
    public function deleteObject($objKey) {
        unset($this->memStorag[$objKey]);
    }

}

?>
