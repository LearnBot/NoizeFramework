<?php

namespace Noize\Persistence;

use Noize\Utilities\Configuration;

/**
 * This class encapsulates the persitence API backend.
 * It is possible to have one persistence session for all users or a single
 * session for each user.
 *
 * @final
 * @author Jan Gräfen
 * @package Persistence
 * @version 1.0
 */
final class PersistenceSession {

    /**
     * Stores the current class instance
     *
     * @static
     * @var PersistenceStorage
     */
    private static $instance = null;
    
    /**
     * Stores the currently configured storage instance.
     *
     * @var BaseStorage
     */
    private $storage = null;
    
    /**
     * Stores the currently configured default handler instance.
     *
     * @var BaseHandler
     */
    private $defaultHandler = null;

    /**
     * Creates an instance of this class.
     * The constructor is private since this class is singleton.
     * 
     * {@internal A storage and a handler instance are created here,
     * base on configuration values inside the persistence namespace.}
     */
    private function __construct() {
        // Get the current persistence storage
        $storageClass =
                'Noize\\Persistence\\Storages\\' .
                Configuration::instance()->getValue('noize.persistence.storage') .
                'Storage';

        if (!is_subclass_of($storageClass, 'Noize\\Persistence\\Storages\\BaseStorage'))
            throw new PersistenceException('Invalid storage engine');
        
        // Get the current persistence handler
        $handlerClass =
                'Noize\\Persistence\\Handler\\' .
                Configuration::instance()->getValue('noize.persistence.defaultHandler') .
                'Handler';

        if (!is_subclass_of($handlerClass, 'Noize\\Persistence\\Handler\\BaseHandler'))
            throw new PersistenceException('Invalid default handler');

        // Get all required instances
        $this->storage          = $storageClass::instance();
        $this->defaultHandler   = $handlerClass::instance();
    }

    /**
     * Clones an instance of this class.
     * The clone magic function is private since this class is singleton.
     */
    private function __clone() {
        
    }

    /**
     * Returns a persisted object.
     *
     * @param string $objKey The key of the persisted object.
     * @param string $handle Optional an own handle can be supplied.
     * @return mixed
     */
    public function getObject($objKey, $handle = null) {
        if ($handle == null)
            $handle =$this->defaultHandler->getHandle();
        
        return $this->storage->getObject($handle . '-' . $objKey);
    }
    
    /**
     * Persists or overrides an object.
     *
     * @param string $objKey The key of the persisted object.
     * @param mixed $obj The object that should be persisted.
     * @param string $handle Optional an own handle can be supplied.
     */
    public function setObject($objKey, $obj, $handle = null) {
        if ($handle == null)
            $handle = $this->defaultHandler->getHandle();
        
        $this->storage->saveObject($handle . '-' . $objKey, $obj);
    }
    
    /**
     * Deletes an persisted object.
     *
     * @param string $objKey The key of the persisted object.
     * @param string $handle Optional an own handle can be supplied.
     */
    public function deleteObject($objKey, $handle = null) {
        if ($handle == null)
            $handle = $this->defaultHandler->getHandle();
        
        $this->storage->deleteObject($handle . '-' . $objKey);
    }

    /**
     * Returns the current instance of this class.
     * 
     * @static
     * @return PersistenceSession
     */
    public static function instance() {
        if (self::$instance == null)
            self::$instance = new PersistenceSession();
        
        return self::$instance;
    }
}