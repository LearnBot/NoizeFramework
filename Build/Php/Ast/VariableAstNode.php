<?php

namespace Noize\Build\Php\Ast;

/**
 * A variable node of the PHP abstract syntax tree.
 *
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Php\Ast
 * @version 1.0
 */
final class VariableAstNode extends BaseAstNode {

    /**
     * Stores the default value of the current variable node.
     *
     * @var string
     */
    private $defaultValue = null;

    /**
     * Returns the variable identifier of the current variable node.
     *
     * @return string
     */
    public function getVariableName() {
        return substr($this->text, 1);
    }

    /**
     * Sets a new variable identifier for the current variable node.
     *
     * @param string $variableName A new variable name.
     */
    public function setVariableName($variableName) {

    }

    /**
     * Returns TRUE if the current node has a default value, FALSE otherwise.
     *
     * @return bool
     */
    public function hasDefaultValue() {
        return ($this->defaultValue != null);
    }

    /**
     * Returns the default value of the current variable node.
     *
     * @return string
     */
    public function getDefaultValue() {
        return $this->defaultValue;
    }

    /**
     * Sets a new default value for the current variable node.
     *
     * @param string $defaultValue A new default value.
     */
    public function setDefaultValue($defaultValue) {
        $this->defaultValue = $defaultValue;
    }

    /**
     * This function generates PHP code from this node and returns it.
     * 
     * @return string
     */
    public function generate() {
        $attrString = '';

        foreach ($this->attributes as $attr)
            $attrString .= $attr->generate() . ' ';
        
        $code = $attrString . $this->text;

        if ($this->hasDefaultValue())
            $code .= ' = ' . $this->defaultValue;

        return $code;
    }
}

?>
