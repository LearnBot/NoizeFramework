<?php

namespace Noize\Build\Php\Ast;

/**
 * Implements an attribute for the keyword 'public'.
 *
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Php\Ast
 * @version 1.0
 */
final class PublicAstAttribute extends BaseAstAttribute {
    
    /**
     * Generates from this attribute and returns it.
     * 
     * @return string
     */
    public function generate() {
        return 'public';
    }
}

?>
