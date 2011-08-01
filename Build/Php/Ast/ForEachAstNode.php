<?php

namespace Noize\Build\Php\Ast;

/**
 * A foreach node of the PHP abstract syntax tree.
 *
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Php\Ast
 * @version 1.0
 */
final class ForEachAstNode extends BaseAstNode {
    
    /**
     * Stores the condition of the current foreach node.
     *
     * @var string
     */
    private $condition = '';

    /**
     * Returns TRUE if the current node has a condition, FALSE otherwise.
     *
     * @return bool
     */
    public function hasCondition() {
        return ($this->condition !== '');
    }
    
    /**
     * Returns the condition of the current node.
     *
     * @return string
     */
    public function getCondition() {
        return $this->condition;
    }
    
    /**
     * Sets the condition of the current node
     *
     * @param string $condition A boolean expression.
     */
    public function setCondition($condition) {
        $this->condition = $condition;
    }

    /**
     * Generates from this attribute and returns it.
     * 
     * @return string
     */
    public function generate() {
        $code = 'foreach (' . $this->condition . ') { ';
        
        foreach ($this->children as $child)
            $code .= $child->generate() . ' ';
        
        return $code . '}';
    }
}

?>
