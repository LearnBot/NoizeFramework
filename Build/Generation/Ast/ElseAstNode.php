<?php

namespace Noize\Build\Generation\Ast;

/**
 * A else node of the PHP abstract syntax tree.
 *
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Generation\Ast
 * @version 1.0
 */
final class ElseAstNode extends BaseAstNode {

    /**
     * Generates from this attribute and returns it.
     * 
     * @return string
     */
    public function generate() {
        $code = 'else { ';
        
        foreach ($this->children as $child)
            $code .= $child->generate() . ' ';
        
        return $code . '}';
    }
}

?>
