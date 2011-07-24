<?php

namespace Noize\Build\Generation\Ast;

/**
 * Implements an attribute for the keyword 'final'.
 *
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Generation\Ast
 * @version 1.0
 */
final class FinalAstAttribute extends BaseAstAttribute {
    
    /**
     * Generates from this attribute and returns it.
     * 
     * @return string
     */
    public function generate() {
        return 'final';
    }
}

?>
