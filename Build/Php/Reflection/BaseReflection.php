<?php

namespace Noize\Build\Php\Reflection;

use Noize\Build\Php\Ast\BaseAstNode;

/**
 * Provides a basic interface for reflection classes.
 * 
 * @abstract
 * @author Jan Gräfen
 * @package Build
 * @subpackage Php\Reflection
 * @version 1.0
 */
abstract class BaseReflection {

    /**
     * Stores the node which is reflected by this class.
     *
     * @var BaseAstNode
     */
    protected $node = null;

    /**
     * Creates a new instance of this class.
     *
     * @param BaseAstNode $node A node which should be reflected by this class.
     */
    public function __construct(BaseAstNode $node) {
        $this->node         = $node;
    }

    /**
     * Adds an attribute to the current reflection.
     *
     * @param string $attribute The attribute that should be added.
     */
    public function addAttribute($attribute) {
        $class = 'Noize\\Build\\Php\\Ast\\' . ucfirst($attribute) . 'AstAttribute';
        $this->node->addAttribute(new $class(0, $this->node));
    }

    /**
     * Returns an array of all attributes of the current reflection.
     *
     * @return array
     */
    public function getAttributes() {
        $attributes = $this->node->getAttributes();
        $return     = array ();

        foreach ($attributes as $attr)
            $return[] = $attr->generate();

        return $return;
    }

    /**
     * Returns TRUE if a given attribute is present in this reflection, FALSE otherwise.
     *
     * @param string $attribute The name of the attribute which is checked.
     * @return bool
     */
    public function hasAttribute($attribute) {
        return in_array($attribute, $this->getAttributes());
    }

    /**
     * Removes an attribute from the current reflection.
     *
     * @param string $attribute The name of the attribute.
     */
    public function removeAttribute($attribute) {
        $this->removeAttribute($attribute);
    }

    /**
     * Returns the document comment of this reflection.
     *
     * @return string
     */
    public function getDocComment() {
        return $this->node->getDocComment();
    }

    /**
     * Sets a new document comment for this reflection.
     *
     * @param string $docComment A document comment text.
     */
    public function setDocComment($docComment) {
        $this->node->setDocComment($docComment);
    }

    /**
     * Returns the AST node which is reflected by the current reflection.
     *
     * @return BaseAstNode
     */
    public function getAstNode() {
        return $this->node;
    }
}
