<?php

namespace Noize\Build\Php\Ast;

/**
 * A function node for the PHP abstract syntax tree.
 *
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Php\Ast
 * @version 1.0
 */
final class FunctionAstNode extends BaseAstNode {
    
    /**
     * Stores an array of parameters belonging to the current node.
     *
     * @var array
     */
    private $parameters = array();
    
    /**
     * Stores the name of the current function node.
     *
     * @var string
     */
    private $functionName = '';
    
    /**
     * Decides if the current node represents an anonymous function.
     *
     * @var bool
     */
    private $isAnonymous = false;
    
    /**
     * Adds a parameter to the current function node.
     *
     * @param string $name The name of the parameter.
     * @param mixed $default The default-value of the parameter.
     * @param string $type The type-hinting of the parameter.
     */
    public function addParameter($name, $default = T_NO_DEFAULT, $type = null, $order = null) {
        $this->parameters[$name] = array ($default, $type, $order);
    }
    
    /**
     * Returns TRUE if the current node has any parameters, FALSE otherwise.
     *
     * @return bool
     */
    public function hasParameters() {
        return !(empty ($this->parameters));
    }
    
    /**
     * Returns an array of all parameters of the current node.
     *
     * @return array
     */
    public function getParameters() {
        return $this->parameters;
    }
    
    /**
     * Returns a parameter of the current node at an given index.
     *
     * @param string $index The index of the required parameter.
     * @return FunctionAstNode
     */
    public function getParameterAt($index) {
        return $this->parameters[$index];
    }

    /**
     * Removes a parameter from this function.
     *
     * @param string $index The index of the removed parameter.
     */
    public function removeParameter($index) {
        unset ($this->parameters[$index]);
    }

    /**
     * Returns the number of parameters of the current node.
     *
     * @return int
     */
    public function getNumberOfParameters() {
        return count($this->parameters);
    }
    
    /**
     * Returns TRUE if the current node has a function name, FALSE otherwise.
     *
     * @return bool
     */
    public function hasFunctionName() {
        return ($this->functionName !== '');
    }
    
    /**
     * Returns the current function name.
     *
     * @return string
     */
    public function getFunctionName() {
        return $this->functionName;
    }
    
    /**
     * Sets a new name for the current function node.
     *
     * @param string $functionName A new name for this function node.
     */
    public function setFunctionName($functionName) {
        $this->functionName = $functionName;
    }
    
    /**
     * Decides if the current function node represents an anonymous function.
     *
     * @param bool $isAnonymous Sets a new value for this property.
     * @return bool
     */
    public function isAnonymous($isAnonymous = null) {
        if ($isAnonymous != null)
            $this->isAnonymous = $isAnonymous;
        
        return $this->isAnonymous;
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
        
        $code = $attrString . 'function';
        
        if ($this->isAnonymous())
            $code .= ' (';
        else
            $code .= ' ' . $this->functionName . ' (';

        uksort($this->parameters, function ($a, $b) {
            if ($a[2] === $b[2])
                return 0;

            return ($a[2] < $b ? -1 : 1);
        });

        foreach ($this->parameters as $paramName => $param) {
            if ($param[1] != null)
                $code .= $param[1] . ' ';
            
            $code .= $paramName;
            
            if ($param[0] !== T_NO_DEFAULT)
                $code .= ' = ' . $param[0];
            
            $code .= ', ';
        }
        
        if ($this->hasParameters())
            $code = substr($code, 0, -2);
        
        $code .= ') { ';
        
        foreach ($this->children as $child)
            $code .= $child->generate() . ' ';
        
        $code .= ' }';
        
        return $code;
    }
}

?>