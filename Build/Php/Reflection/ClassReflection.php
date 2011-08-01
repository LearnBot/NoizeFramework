<?php

namespace Noize\Build\Php\Reflection;

use Noize\Build\Php\Ast\FunctionAstNode;

/**
 * This class reflects class code and is able to modify it.
 *
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Php\Reflection
 * @version 1.0
 */
final class ClassReflection extends BaseReflection {

    /**
     * Adds a function to the current class reflection.
     *
     * @param FunctionReflection $reflection A function reflection that should be added.
     */
    public function addFunction(FunctionReflection $reflection) {
        $this->node->addChild($reflection->getAstNode());
    }

    /**
     * Returns a function of the current class reflection.
     *
     * @param string $functionName The name of the function.
     * @return FunctionReflection
     */
    public function getFunction($functionName) {
        foreach ($this->getAllFunctions() as $function) {
            if ($function->getFunctionName === $functionName)
                return $function;
        }
    }

    /**
     * Returns an array of all functions of the current class reflection.
     *
     * @return array
     */
    public function getAllFunctions() {
        $functions = array ();

        foreach ($this->node->getChildren() as $child) {
            if ($child instanceof FunctionAstNode) {
                $functions[$child->getFunctionName()] = new FunctionReflection($child);
            }
        }

        return $functions;
    }

    /**
     * Removes a function from the current class reflection.
     *
     * @param string $functionName The function name of the function that should be deleted.
     */
    public function removeFunction($functionName) {
        $this->node->removeChild($this->getFunction($functionName)->getAstNode());
    }
}