<?php

namespace Noize\Build\Generation\Ast;

/**
 * A parent node of the PHP abstract syntax tree.
 * This node is always the top-level node of all ASTs.
 *
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Generation\Ast
 * @version 1.0
 */
final class ParentAstNode extends BaseAstNode {
    
    /**
     * Returns TRUE if the current node has a parent node, FALSE otherwise.
     *
     * @return bool
     */
    public function hasParent() {
        return false;
    }
    
    /**
     * Returns the parent node of the current node if one exist, NULL otherwise.
     *
     * @return BaseAstNode
     */
    public function getParent() {
        return null;
    }
    
    /**
     * This function generates PHP code from this node and returns it.
     * 
     * @return string
     */
    public function generate() {
        $code = '<?php' . "\n\n";
        
        foreach ($this->children as $child)
            $code .= $child->generate();
        
        return $code;
    }
}

?>
