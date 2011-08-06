<?php

namespace Noize\Build\Annotations;

use Noize\Utilities\StringHelper;
use Noize\Build\Php\Reflection\BaseReflection;

/**
 * A parser for document comments.
 * It filters a doc-comments for annotations and creates them.
 *
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Annotations
 * @version 1.0
 */
final class AnnotationParser {

    /**
     * Stores the current instance of this class.
     *
     * @static
     * @var AnnotationParser
     */
    private static $instance = null;

    /**
     * Stores an array of ignored annotation names.
     *
     * @var array
     */
    private $ignoredAnnotations = array ();

    /**
     * Creates an instance of this class.
     * The constructor is private since this class is singleton.
     */
    private function __construct() {
        // Blacklisting phpdoc annotations
        $this->ignoredAnnotations = array (
            'abstract',
            'access',
            'author',
            'category',
            'copyright',
            'deprecated',
            'example',
            'final',
            'filesource',
            'global',
            'id',
            'ignore',
            'internal',
            'inheritdoc',
            'license',
            'link',
            'method',
            'name',
            'package',
            'param',
            'property',
            'return',
            'see',
            'since',
            'static',
            'staticvar',
            'subpackage',
            'toc',
            'todo',
            'tutorial',
            'uses',
            'var',
            'version'
        );
    }

    /**
     * Clones an instance of this class.
     * The clone magic function is private since this class is singleton.
     */
    private function __clone() {

    }

    /**
     * Parses a document comment and returns all annotations found on the
     * top level.
     *
     * @param string $comment A string that should be parsed for annotations.
     * @param BaseReflection $reflection The reflection of the object which is annotated.
     * @return array
     */
    public function parse($comment, BaseReflection $reflection) {
        $tokens = array ();
        preg_match_all(
                '~@([a-zA-Z][a-zA-Z0-9]*(?:\(.*\))?)~',
                $comment,
                $tokens);

        $annotations = array ();

        foreach ($tokens[1] as $token) {
            $paramsBegin = strpos($token, '(');
            if ($paramsBegin === false)
                $paramsBegin = strlen($token);

            $name       = substr($token, 0, $paramsBegin);
            $parameters = array();

            if (in_array($name, $this->ignoredAnnotations))
                continue;

            $paramString = \substr($token, $paramsBegin + 1, -1);
            if ($paramString !== false) {
                $parts = explode(',', $paramString);

                foreach ($parts as $part) {
                    $part = StringHelper::instance()->stringToType($part);
                    if (is_string($part) && $part[0] === '@') {
                        $return = $this->parse($part, $reflection);
                        $parameters[] = $return[0];
                    }
                }
            }

            $name = 'Noize\\Annotations\\' . $name . 'Annotation';
            if (!class_exists($name) || is_subclass_of($name, 'Noize\\Annotations\\BaseAnnotation'))
                throw new AnnotationParserException (0, $name);

            $annotations[] = new $name($reflection, $parameters);
        }

        return $annotations;
    }

    /**
     * Advices the parser to ignore certain annotations.
     *
     * @param string $name The name of the ignored annotation.
     */
    public function ignoreAnnotation($name) {
        $this->ignoredAnnotations[] = $name;
    }

    /**
     * Returns the current instance of this class.
     *
     * @static
     * @return AnnotationParser
     */
    public static function instance() {
        if (self::$instance == null)
            self::$instance = new self();

        return self::$instance;
    }
}