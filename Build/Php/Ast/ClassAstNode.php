<?php

namespace Noize\Build\Php\Ast;

/**
 * A class node for the PHP abstract syntax tree.
 * It stores class definition tokens.
 *
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Php\Ast
 * @version 1.0
 */
final class ClassAstNode extends BaseAstNode {
    
    /**
     * Stores the parent class name.
     *
     * @var string
     */
    private $extends = '';
    
    /**
     * Stores an array of interface names which are implemented by this class.
     *
     * @var array
     */
    private $implements = array ();
    
    /**
     * Stores the name of the class.
     *
     * @var string
     */
    private $className = '';
    
    /**
     * Returns TRUE if the current node is extending a class, FALSE otherwise.
     *
     * @return bool
     */
    public function hasExtends() {
        return ($this->extends !== '');
    }
    
    /**
     * Returns the name of the extended class.
     *
     * @return string
     */
    public function getExtends() {
        return $this->extends;
    }

    /**
     * Sets the name of the extended class.
     *
     * @param string $extends The name of the extended class.
     */
    public function setExtends($extends) {
        $this->extends = $extends;
    }
    
    /**
     * Adds a new interface implementation to the current node.
     *
     * @param type $implements The name of the interface which is implemented by
     *                         this node.
     */
    public function addImplements($implements) {
        $this->implements[] = $implements;
    }
    
    /**
     * Returns TRUE if this class has implementations, FALSE otherwises.
     *
     * @return bool
     */
    public function hasImplements() {
        return !(empty ($this->implements));
    }
    
    /**
     * Returns a name of all interfaces which are implemented by the current
     * node.
     *
     * @return array
     */
    public function getImplements() {
        return $this->implements;
    }
    
    /**
     * Returns an interface which is implemented by the current node at an
     * given index.
     *
     * @param int $index The index of the implementation.
     * @return string
     */
    public function getImplementsAt($index) {
        return $this->implements[$index];
    }
    
    /**
     * Returns the number of interfaces implemented by the current node.
     *
     * @return int
     */
    public function getNumberOfImplements() {
        return count($this->implements);
    }
    
    /**
     * Returns the class name of the current node.
     *
     * @return string
     */
    public function getName() {
        return $this->className;
    }
    
    /**
     * Sets the class name for the current node.
     *
     * @param string $className A new class name.
     */
    public function setClassName($className) {
        $this->className = $className;
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
        
        $return = $attrString . 'class ' . $this->className;
        
        if ($this->hasExtends())
            $return .= ' extends ' . $this->extends;
        
        if ($this->hasImplements())
                $return .= ' implements ' . implode (', ', $this->implements);
        
        $return .= ' { ';
        
        foreach ($this->children as $child)
            $return .= $child->generate() . ' ';
        
        $return .= ' }';
        
        return $return;
    }
}

?>
