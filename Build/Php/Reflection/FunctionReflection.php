<?php

namespace Noize\Build\Php\Reflection;

use Noize\Build\Php\Ast\FunctionAstNode;

/**
 * A reflection class for functions.
 *
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Php\Reflection
 * @version 1.0
 */
final class FunctionReflection extends BaseReflection {

    /**
     * Adds a parameter to the current function reflection.
     *
     * @param string $name The name of the parameter.
     * @param mixed $default The default-value of the parameter.
     * @param string $type The type-hinting of the parameter.
     */
    public function addParameter($name, $default = T_NO_DEFAULT, $type = null, $order = null) {
        $this->node->addParameter('$' . $name, $default, $type, $order);
    }

    /**
     * Returns a parameter with a specific name.
     *
     * @param string $name The name of the parameter.
     * @return array
     */
    public function getParameter($name) {
        $array =  $this->node->getParameterAt($name);
        return array ('default' => $array[0], 'type' => $array[1], 'order' => $array[2]);
    }

    /**
     * Returns all parameters of this function.
     *
     * @return array
     */
    public function getParameters() {
        $params = $this->node->getParameters();
        $return = array ();

        foreach ($params as $name => $values)
            $return[$name] = array ('default' => $values[0], 'type' => $values[1], 'order' => $values[2]);

        return $return;
    }

    /**
     * Removes a parameter from the reflected function.
     *
     * @param <type> $name The name of the parameter that should be removed.
     */
    public function removeParameter($name) {
        $this->node->removeParameter($name);
    }

    /**
     * Gets the current function name of this reflection.
     *
     * @return string
     */
    public function getFunctionName() {
        return $this->node->getFunctionName();
    }

    /**
     * Sets the function name of this reflection.
     *
     * @param string $functionName A new function name for this reflection.
     */
    public function setFunctionName($functionName) {
        $this->node->setFunctionName($functionName);
    }
}
