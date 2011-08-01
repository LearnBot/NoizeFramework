<?php

namespace Noize\Build\Annotations;

/**
 * Is thrown by the AnnotationParser class if invalid code is detected.
 *
 * @author Jan Gräfen
 * @package Build
 * @subpackage Php
 * @version 1.0
 */
final class AnnotationParserException extends \Exception {

    /**
     * Creates the new exception.
     *
     * @param int $line The line number of the invalid token.
     * @param string $text The text of the invalid token.
     */
    public function __construct($line, $text) {
        parent::__construct(
            'Invalid token "' . $text . '" at line "' . $line . '"',
            E_PARSE, null);
    }
}

?>
