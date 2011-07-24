<?php

namespace Noize\Build\Generation\Ast;

/**
 * A base class for abstract syntax tree node attributes.
 * For example a class node can have attributes like 'private', 'final' or
 * 'abstract'.
 *
 * @abstract
 * @author Jan Gräfen
 * @package Build
 * @subpackage Generation\Ast
 * @version 1.0
 */
abstract class BaseAstAttribute {
    
    /**
     * Stores the AST node which has the current attribute.
     *
     * @var BaseAstNode
     */
    protected $parent = null;
    
    /**
     * Stores the line number of the current attribute.
     *
     * @var int
     */
    protected $line = 0;

    /**
     * Creates a new node attribute.
     *
     * @param int $line The line number of the new attribute.
     * @param BaseAstNode $parent The node which has the new attribute.
     */
    public function __construct($line, BaseAstNode $parent = null) {
        $this->parent   = $parent;
        $this->line     = $line;
    }
    
    /**
     * Returns the parent node of the current attribute.
     *
     * @return BaseAstNode
     */
    public function getParent() {
        return $this->parent;
    }
    
    /**
     * Sets a new parent for the current attribute.
     *
     * @param BaseAstNode $parent A new parent for this attribute.
     */
    public function setParent(BaseAstNode $parent) {
        $this->parent = $parent;
    }
    
    /**
     * Returns the line of the current attribute.
     *
     * @return int
     */
    public function getLine() {
        return $this->line;
    }
    
    /**
     * Generates from this attribute and returns it.
     * 
     * @abstract
     * @return string
     */
    public abstract function generate();
}

?>
