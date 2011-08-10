<?php

namespace Noize\Annotations;

use Noize\Build\Php\Ast\FunctionAstNode;
use Noize\Build\Php\Ast\TokenAstNode;
use Noize\Build\Php\Reflection\ClassReflection;
use Noize\Build\Php\Reflection\FunctionReflection;

/**
 * An annotation for making an array property of a class available as properties.
 *
 * @final
 * @author Jan Gräfen
 * @package Annotations
 * @version 1.0
 */
final class ArrayPropertyAnnotation extends BaseAnnotation {
    
    /**
     * Applies the annotation.
     */
    public function apply() {
        $arrayName = $this->parameters[0];
        
        if (!is_string($arrayName))
            return;
        
        if (!($this->reflection instanceof ClassReflection))
            return;

        $varReflection = $this->reflection->getVariable($arrayName);
        if ($varReflection->hasAttribute('public')) {
            $varReflection->removeAttribute('public');
            $varReflection->addAttribute('protected');
        }
        
        $getReflection = new FunctionReflection(new FunctionAstNode(0, 'function'));
        $getReflection->addAttribute('public');
        $getReflection->setFunctionName('__get');
        $getReflection->addParameter('property');
        $getReflection->getAstNode()->addChild(new TokenAstNode(0, 'return $this->' . $varReflection->getVariableName() . '[$property];'));
        
        $setReflection = new FunctionReflection(new FunctionAstNode(0, 'function'));
        $setReflection->addAttribute('public');
        $setReflection->setFunctionName('__set');
        $setReflection->addParameter('property');
        $setReflection->addParameter('value');
        
        $issetReflection = new FunctionReflection(new FunctionAstNode(0, 'function'));
        $issetReflection->addAttribute('public');
        $issetReflection->setFunctionName('__isset');
        $issetReflection->addParameter('property');
        
        $unsetReflection = new FunctionReflection(new FunctionAstNode(0, 'function'));
        $unsetReflection->addAttribute('public');
        $unsetReflection->setFunctionName('__unset');
        $unsetReflection->addParameter('property');
        
        $this->reflection->addFunction($getReflection);
        $this->reflection->addFunction($setReflection);
        $this->reflection->addFunction($issetReflection);
        $this->reflection->addFunction($unsetReflection);
        
        return $this->reflection;
    }
}
