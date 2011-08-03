<?php

namespace Noize\Utilities;

/**
 * Provides basic tools for working with string which are not possible as
 * one-liner in PHP.
 *
 * @final
 * @author Jan Gräfen
 * @package Utilities
 * @version 1.0
 */
final class StringHelper {

    /**
     * Stores the current instance of this class.
     *
     * @static
     * @var StringHelper
     */
    private static $instance = null;

    /**
     * Creates an instance of this class.
     * The constructor is private since this class is singleton.
     */
    private function __construct() {

    }

    /**
     * Clones an instance of this class.
     * The clone magic function is private since this class is singleton.
     */
    private function __clone() {

    }

    /**
     * Converts a string into a typed variable.
     * All strings which starts and end with a '"' are returned as strings,
     * strings which starts with a '[' and ends with a ']' are returned as
     * arrays and so on.
     *
     * @param string $string A string that should be converted.
     * @return mixed
     */
    public function stringToType($string) {
        $string = trim($string);

        if (ctype_digit($string))
            return intval($string);
        elseif ($string === 'false' || $string === 'FALSE')
            return false;
        elseif ($string === 'true' || $string === 'TRUE')
            return true;
        elseif ($string === 'null' || $string === 'NULL')
            return null;
        elseif ($string[0] === '"' && $string[strlen($string) - 1] === '"')
            return \substr($string, 1, -1);
        elseif ($string[0] === '[' && $string[strlen($string) - 1] === ']') {
            $array = array ();
            foreach (explode(',', $string) as $element)
                $array[] = trim($element);

            return $array;
        } else
            return $string;
    }

    /**
     * Returns the current instance of this class.s
     *
     * @static
     * @return StringHelper
     */
    public static function instance() {
        if (self::$instance == null)
            self::$instance = new StringHelper();

        return self::$instance;
    }
}