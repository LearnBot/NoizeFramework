<?php

namespace Noize\Build\Php\Reflection;

use Noize\Build\Php\Ast\BaseAstNode;
use Noize\Build\Php\Ast\ClassAstNode;
use Noize\Build\Php\Ast\FunctionAstNode;
use Noize\Build\Php\Ast\VariableAstNode;
use Noize\Build\Php\Ast\NamespaceAstNode;

/**
 * Reflects PHP files.
 * 
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Php\Reflection
 * @version 1.0
 */
final class FileReflection extends BaseReflection {

    /**
     * Returns the namespace defined the reflected file.
     *
     * @return string
     */
    public function getNamespace() {
        foreach ($this->node->getChildren() as $child)
            if ($child instanceof NamespaceAstNode)
                return $child->getNamespace();
    }

    /**
     * Sets a namespace for the reflected file.
     *
     * @param string $namespace A new namespace for the document.
     */
    public function setNamespace($namespace) {
        foreach ($this->node->getChildren() as $child) {
            if ($child instanceof NamespaceAstNode) {
                $child->setNamespace($namespace);
                return;
            }
        }

        // If this is reached no namespace is defined so far
        $nsNode = new NamespaceAstNode(0, 'namespace', $this->node);
        $nsNode->setNamespace($namespace);

        $this->node->addChild($nsNode, BaseAstNode::CHILD_POSITION_BEGINING);
    }

    /**
     * Adds a class to the reflected file.
     *
     * @param ClassReflection $reflection The class that should be added.
     */
    public function addClass(ClassReflection $reflection) {
        $this->node->addChild($reflection->getAstNode());
    }

    /**
     * Returns a specific class inside the reflected file.
     *
     * @param string $className The name of the desired class.
     * @return ClassReflection
     */
    public function getClass($className) {
        foreach ($this->getAllClasses() as $class)
            if ($class->getClassName() === $className)
                return $class;
    }

    /**
     * Returns an array of all class inside the reflected file.
     *
     * @return array
     */
    public function getAllClasses() {
        $classes = array();

        foreach ($this->node->getChildren() as $child)
            if ($child instanceof ClassAstNode)
                $classes[$child->getClassName()] = new ClassReflection($child);

        return $classes;
    }

    /**
     * Removes a class from the reflected file.
     *
     * @param string $className The class name of the class that should be removed.
     */
    public function removeClass($className) {
        $this->node->removeChild($this->getClass($className)->getAstNode());
    }

    /**
     * Adds a function to the reflected file.
     *
     * @param FunctionReflection $reflection The function that should be added.
     */
    public function addFunction(FunctionReflection $reflection) {
        $this->node->addChild($reflection->getAstNode());
    }

    /**
     * Returns a specific function inside the reflected file.
     *
     * @param string $functionName The name of the desired function.
     * @return FunctionReflection
     */
    public function getFunction($functionName) {
        $functions = $this->getAllFunctions();
        return $functions[$functionName];
    }

    /**
     * Returns an array of all functions inside the reflected file.
     *
     * @return array
     */
    public function getAllFunctions() {
        $functions = array();

        foreach ($this->node->getChildren() as $child)
            if ($child instanceof FunctionAstNode)
                $functions[$child->getFunctionName()] = new FunctionReflection($child);

        return $functions;
    }

    /**
     * Removes a function from the reflected file.
     *
     * @param string $functionName The function name of the function that should be removed.
     */
    public function removeFunction($functionName) {
        $this->node->removeChild($this->getFunction($functionName)->getAstNode());
    }

    /**
     * Adds a variable to the reflected file.
     *
     * @param VariableReflection $reflection The variable that should be added.
     */
    public function addVariable(VariableReflection $reflection) {
        $this->node->addChild($reflection->getAstNode());
    }

    /**
     * Returns a specific variable inside the reflected file.
     *
     * @param string $variableName The name of the desired variable.
     * @return VariableReflection
     */
    public function getVariable($variableName) {
        $variables = $this->getAllVariables();
        return $variables[$variableName];
    }

    /**
     * Returns an array of all variables inside the reflected file.
     *
     * @return array
     */
    public function getAllVariables() {
        $variables = array();

        foreach ($this->node->getChildren() as $child)
            if ($child instanceof VariableAstNode)
                $variables[$child->getVariableName()] = new VariableReflection($child);

        return $variables;
    }

    /**
     * Removes a variable from the reflected file.
     *
     * @param string $variableName The variable name of the variable that should be removed.
     */
    public function removeVariable($variableName) {
        $this->node->removeChild($this->getVariable($variableName)->getAstNode());
    }
}
