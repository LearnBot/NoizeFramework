<?php

namespace Noize\Annotations;

use Noize\Build\Php\Reflection\BaseReflection;

/**
 * Provides a base class for annotations.
 *
 * @author Jan Gräfen
 * @package Annotations
 * @version 1.0
 */
abstract class BaseAnnotation {
    
    /**
     * Stores the reflection of the annotated object.
     *
     * @var BaseReflection
     */
    protected $reflection = null;
    
    /**
     * Stores an array of parameters supplied to this annotation.
     *
     * @var array
     */
    protected $parameters = array ();
    
    /**
     * Creates a new annotation.
     *
     * @param BaseReflection $reflection A reflection of the annotated object.
     * @param array $parameters An array of parameters supplied to the annotation.
     */
    public function __construct(BaseReflection $reflection, array $parameters) {
        $this->reflection = $reflection;
        $this->parameters = $parameters;
    }
    
    /**
     * Applies the annotation and may return something.
     * 
     * @abstract
     * @return mixed|null
     */
    public abstract function apply();
}