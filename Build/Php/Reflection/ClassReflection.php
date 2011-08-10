<?php

namespace Noize\Build\Php\Reflection;

use Noize\Build\Php\Ast\FunctionAstNode;
use Noize\Build\Php\Ast\VariableAstNode;

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
     * Returns the class name of the currently reflected class.
     *
     * @return string
     */
    public function getClassName() {
        return $this->node->getClassName();
    }

    /**
     * Sets a new class name for the reflected class.
     *
     * @param string $className A new class name. 
     */
    public function setClassName($className) {
        $this->node->setClassName($className);
    }

    /**
     * Adds a variable to the current class reflection.
     *
     * @param VariableReflection $reflection A variable reflection that should be added.
     */
    public function addVariable(VariableReflection $reflection) {
        $this->node->addChild($reflection->getAstNode());
    }

    /**
     * Returns a variable of the current class reflection.
     *
     * @param string $variableName The name of the variable.
     * @return VariableReflection
     */
    public function getVariable($variableName) {
        $variables = $this->getAllVariables();
        return $variables[$variableName];
    }

    /**
     * Returns an array of all functions of the current class reflection.
     *
     * @return array
     */
    public function getAllVariables() {
        $variables = array ();

        foreach ($this->node->getChildren() as $child)
            if ($child instanceof VariableAstNode)
                $variables[$child->getVariableName()] = new VariableReflection($child);

        return $variables;
    }

    /**
     * Removes a variable from the current class reflection.
     *
     * @param string $functionName The variable name of the variable that should be deleted.
     */
    public function removeVariable($variableName) {
        $this->node->removeChild($this->getVariable($variableName)->getAstNode());
    }

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
        $functions = $this->getAllFunctions();
        return $functions[$functionName];
    }

    /**
     * Returns an array of all functions of the current class reflection.
     *
     * @return array
     */
    public function getAllFunctions() {
        $functions = array ();

        foreach ($this->node->getChildren() as $child)
            if ($child instanceof FunctionAstNode)
                $functions[$child->getFunctionName()] = new FunctionReflection($child);

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