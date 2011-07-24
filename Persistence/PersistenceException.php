<?php

namespace Noize\Persistence;

/**
 * This exception handles exceptions inside the persistence API, for example if
 * an invalid configuration was supplied or if a backend was unreadable.
 *
 * @author Jan Gräfen
 * @package Persistence
 * @version 1.0
 */
final class PersistenceException extends \Exception {
    
    /**
     * Creates a new exception.
     */
    public function __construct($message, $previous = null) {
        parent::__construct($message, 0, $previous);
    }
}

?>
