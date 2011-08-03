<?php

namespace Noize\Build\Php\Reflection;

/**
 * This class reflects variable code and is able to modify it.
 *
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Php\Reflection
 * @version 1.0
 */
final class VariableReflection extends BaseReflection {

    /**
     * Returns the default value of the reflected variable.
     *
     * @return mixed
     */
    public function getDefaultValue() {
        return $this->node->getDefaultValue();
    }

    /**
     * Sets a new default value for the reflected variable.
     *
     * @param mixed $defaultValue The new default value.
     */
    public function setDefaultValue($defaultValue) {
        if (is_object($defaultValue))
            $defaultValue = 'new ' . get_class ($defaultValue);

        $this->node->setDefaultValue($defaultValue);
    }

    /**
     * Returns the variable identifier of the reflected variable.
     *
     * @return string
     */
    public function getVariableName() {
        return $this->node->getVariableName();
    }

    /**
     * Sets a new variable name for the reflected variable.
     *
     * @param string $variableName A new variable name.
     */
    public function setVariableName($variableName) {
        $this->node->setVariableName($variableName);
    }
}
