<?php

namespace Noize\Persistence\Handler;

/**
 * This class provides a session handler which returns a different session hash
 * for each user.
 * This means each user has it's own persistence storage.
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
        return 'n' . md5($_SERVER['REMOTE_ADDR'] . $_SERVER['USER_AGENT']);
    }
}