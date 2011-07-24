<?php

namespace Noize\Build\Generation\Ast;

/**
 * Provides a class for all tokens which don't need an own AST class.
 *
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Generation\Ast
 * @version 1.0
 */
final class TokenAstNode extends BaseAstNode {
    
    /**
     * This function generates PHP code from this node and returns it.
     * 
     * @return string
     */
    public function generate() {
        return $this->text;
    }
}

?>
