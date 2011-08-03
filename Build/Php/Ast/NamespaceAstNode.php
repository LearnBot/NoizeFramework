<?php

namespace Noize\Build\Php\Ast;

/**
 * A namespace node of the PHP abstract syntax tree.
 *
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Php\Ast
 * @version 1.0
 */
final class NamespaceAstNode extends BaseAstNode {

    /**
     * Stores the namespace of the current node.
     *
     * @var string
     */
    private $namespace = '';

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
     * This function generates PHP code from this node and returns it.
     * 
     * @return string
     */
    public function generate() {
        return $this->text;
    }
}

?>
