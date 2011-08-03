<?php

namespace Noize\Persistence\Handler;

/**
 * Provides a base-class for session handler classes.
 * These handler decide how a client is handles by the persistence session.
 *
 * @abstract
 * @author Jan Gräfen
 * @package Persistence
 * @subpackage Handler
 * @version 1.0
 */
abstract class BaseHandler {
    
    /**
     * Stores the current instance of this class.
     * 
     * @static
     * @var BaseHandler
     */
    protected static $instance = null;
    
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
     * Creates a new session handler and returns the corresponding session hash.
     * 
     * @abstract
     * @return string
     */
    public abstract function getHandle();

    /**
     * Returns the current instance of this class.
     * 
     * @static
     * @return BaseHandler
     */
    public static function instance() {
        if (static::$instance == null)
            static::$instance = new static();
        
        return static::$instance;
    }
}