<?php

namespace Noize\Build\Php\Ast;

/**
 * A use node of the PHP abstract syntax tree.
 *
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Php\Ast
 * @version 1.0
 */
final class UseAstNode extends BaseAstNode {

    /**
     * Stores the namespace of the current node.
     *
     * @var string
     */
    private $namespace = '';

    /**
     * Stores the alias of the current node.
     *
     * @var string
     */
    private $alias = '';
    
    /**
     * Returns the namespace value of the current node.
     *
     * @return string
     */
    public function getNamespace() {
        return $this->namespace;
    }

    /**
     * Sets a new namespace for this node.
     *
     * @param string $namespace A new namespace for this node.
     */
    public function setNamespace($namespace) {
        $this->namespace = $namespace;
    }

    /**
     * Returns TRUE if the current node has a namespace, FALSE otherwise.
     *
     * @return bool
     */
    public function hasNamespace() {
        return ($this->namespace !== '');
    }

    /**
     * Returns the alias of the current node.
     *
     * @return string
     */
    public function getAlias() {
        return $this->alias;
    }
    
    /**
     * Sets a new alias for the current node.
     *
     * @param string $alias A new alias.
     */
    public function setAlias($alias) {
        $this->alias = $alias;
    }
    
    /**
     * Returns TRUE of the current node has a alias, FALSE otherwise.
     *
     * @return bool
     */
    public function hasAlias() {
        return ($this->alias !== '');
    }
    
    /**
     * This function generates PHP code from this node and returns it.
     * 
     * @return string
     */
    public function generate() {
        $code = 'use ' . $this->namespace;
        
        if ($this->hasAlias())
            $code .= ' as ' . $this->alias;
        
        return $code;
    }
}

?>
