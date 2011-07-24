<?php

namespace Noize\Build\Generation\Ast;

/**
 * A base class for the abstract syntax tree nodes which are produced from
 * the {@see Noize\Build\Generation\PhpParser} class.
 *
 * @abstract
 * @author Jan Gräfen
 * @package Build
 * @subpackage Generation\Ast
 * @version 1.0
 */
abstract class BaseAstNode {
    
    /**
     * Stores the parent node of the current node.
     *
     * @var BaseAstNode
     */
    protected $parent = null;
    
    /**
     * Stores an array of child nodes of the current node.
     *
     * @var array
     */
    protected $children = array ();
    
    /**
     * Stores an array of attributes belonging to the current node.
     *
     * @var array
     */
    protected $attributes = array();
    
    /**
     * Stores the doccoment comment of the current node.
     *
     * @var string
     */
    protected $docComment = '';
    
    /**
     * The line-number of the token which is represented by this node.
     *
     * @var int
     */
    protected $line = 0;
    
    /**
     * The code fragment which represents this node.
     *
     * @var string
     */
    protected $text = '';
    
    /**
     * Creates a new abstract syntax tree node.
     *
     * @param int $line The line of the code element which is represented by the
     *                  new node.
     * @param string $text The code element which represents the node.
     * @param BaseAstNode $parent The parent node of the new node.
     */
    public function __construct($line, $text, BaseAstNode $parent = null) {
        $this->line     = $line;
        $this->text     = $text;
        $this->parent   = $parent;
    }
    
    /**
     * Returns TRUE if the current node has a parent node, FALSE otherwise.
     *
     * @return bool
     */
    public function hasParent() {
        return ($parent != null);
    }
    
    /**
     * Returns the parent node of the current node if one exist, NULL otherwise.
     *
     * @return BaseAstNode
     */
    public function getParent() {
        return $this->parent;
    }
    
    /**
     * Adds a child node to the current node.
     *
     * @param BaseAstNode $childNode An AST node that should be added as a child
     *                               to this one.
     */
    public function addChild(BaseAstNode $childNode) {
        $this->children[] = $childNode;
    }
    
    /**
     * Returns TRUE if the current node has any children, FALSE otherwise.
     *
     * @return bool
     */
    public function hasChildren() {
        return !(empty ($children));
    }
    
    /**
     * Returns an array of all child nodes of the current node.
     *
     * @return array
     */
    public function getChildren() {
        return $this->children;
    }
    
    /**
     * Returns a specific child node of the current node at an given index.
     *
     * @param int $index An index of the child node.
     * @return BaseAstNode
     */
    public function getChildAt($index) {
        return $this->children[$index];
    }
    
    /**
     * Returns the number of children of the current node.
     * 
     * @return int
     */
    public function getNumberOfChildren() {
        return count($this->children);
    }
    
    /**
     * Adds a new attribute to the current node.
     *
     * @param BaseAstAttribute $attribute An attribute to add.
     */
    public function addAttribute(BaseAstAttribute $attribute) {
        $attribute->setParent($this);
        $this->attributes[] = $attribute;
    }
    
    /**
     * Returns TRUE if the current node has any attributes, otherwise FALSE.
     *
     * @return bool
     */
    public function hasAttributes() {
        return !(empty ($this->attributes));
    }
    
    /**
     * Returns an array of all attributes of the current node.
     *
     * @return array
     */
    public function getAttributes() {
        return $this->attributes;
    }
    
    /**
     * Returns a specific attribute of the current node at an given index.
     *
     * @param int $index The index of the required attribute.
     * @return BaseAstAttribute
     */
    public function getAttributeAt($index) {
        return $this->attributes[$index];
    }
    
    /**
     * Returns the number of attributes of the current node.
     *
     * @return int
     */
    public function getNumberOfAttributes() {
        return count($this->attributes);
    }
    
    /**
     * Returns TRUE if the current node has a doccomment, FALSE otherwise.
     *
     * @return bool
     */
    public function hasDocComment() {
        return ($this->docComment !== '');
    }
    
    /**
     * Returns the doccoment of the current node.
     *
     * @return string
     */
    public function getDocComment() {
        return $this->docComment;
    }
    
    /**
     * Sets a doccomment for the current node.
     *
     * @param string $docComment The doccomment which belongs to this node.
     */
    public function setDocComment($docComment) {
        $this->docComment = $docComment;
    }
    
    /**
     * Returns the line number of the current node.
     *
     * @return int
     */
    public function getLine() {
        return $this->line;
    }
    
    /**
     * Returns a string which was the origin of this node.
     *
     * @return string
     */
    public function getText() {
        return $this->text;
    }
    
    /**
     * This function generates code from this node and returns it.
     * 
     * @abstract
     * @return string
     */
    public abstract function generate();
}

?>
