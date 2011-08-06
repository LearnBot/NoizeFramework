<?php

namespace Noize\Build\Php\Ast;

/**
 * A parent node of the PHP abstract syntax tree.
 * This node is always the top-level node of all ASTs.
 *
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Php\Ast
 * @version 1.0
 */
final class ParentAstNode extends BaseAstNode {

    /**
     * Stores the file name of the current parent node.
     *
     * @var string
     */
    private $fileName = '';

    /**
     * Returns TRUE if the current node has a file name, FALSE otherwise.
     *
     * @return bool
     */
    public function hasFileName() {
        return ($this->fileName !== '');
    }

    /**
     * Returns the file name of the current parent node.
     *
     * @return string
     */
    public function getFileName() {
        return $this->fileName;
    }

    /**
     * Sets a new file name for the current parent node.
     *
     * @param string $fileName A new file name for the current node.
     */
    public function setFileName($fileName) {
        $this->fileName = $fileName;
    }

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
