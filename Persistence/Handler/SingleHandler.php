<?php

namespace Noize\Persistence\Handler;

/**
 * This class provides a session handler which returns the same session handler
 * for all clients.
 * This means the persitence storage is the same for all users.
 *
 * @final
 * @author Jan Gräfen
 * @package Persistence
 * @subpackage Handler
 * @version 1.0
 */
final class SingleHandler extends BaseHandler {
     
    /**
     * Creates a new session handler and returns the corresponding session hash.
     * 
     * @return string
     */
    public function getHandle() {
        return 'n' . md5('noize.persitence.handler.singlehandler');
    }
}