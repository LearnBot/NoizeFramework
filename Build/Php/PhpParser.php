<?php

namespace Noize\Build\Php;

/**
 * A parser for PHP code which allows the build API to generate PHP code from
 * e.g. annotations or to optimize source codes.
 *
 * @final
 * @author Jan Gräfen
 * @package Build
 * @subpackage Php
 * @version 1.0
 */
final class PhpParser {

    /**
     * Stores the current instance of this class.
     *
     * @static
     * @var PhpParser
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
     * Parses an input PHP file into an abstract syntax tree.
     *
     * @param string $file A input file name.
     * @return Ast\ParentAstNode
     */
    public function parse($file) {
        $stream = new TokenStream($file);
        $tree = new Ast\ParentAstNode(0, null);
        $curParent = $tree;

        $tree->setFileName($file);

        // Setting declaration buffers
        $docCommentBuffer = null;
        $attrBuffer = array();
        $closeDec = false;

        while (($token = $stream->getNextToken()) != null) {
            // Setting token attributes
            $tokType = $token[0];
            $tokText = $token[1];
            $tokLine = $token[2];

            // Handles curly braces
            if ($tokType === T_CURLYCLOSE) {
                $newParent = $curParent->getParent();
                if ($newParent == null)
                    throw new PhpParserException($tokLine, $tokText);

                $curParent = $newParent;

                // Handles doccomments
            } elseif ($tokType === T_DOC_COMMENT) {
                $docCommentBuffer = $tokText;

                // Handles semicolons
            } elseif ($tokType === T_STRING
                    && $tokText === ';'
                    && $closeDec === true) {
                $curParent->addChild(new Ast\TokenAstNode(
                                $tokLine, $tokText, $curParent));

                $newParent = $curParent->getParent();
                if ($newParent == null)
                    throw new PhpParserException($tokLine, $tokText);

                $curParent = $newParent;
                $closeDec = false;

                // Handles public attributes
            } elseif ($tokType === T_PUBLIC || $tokType === T_VAR) {
                $attrBuffer[] = new Ast\PublicAstAttribute($tokLine);

                // Handles protected attributes
            } elseif ($tokType === T_PROTECTED) {
                $attrBuffer[] = new Ast\ProtectedAstAttribute($tokLine);

                // Handles private attributes
            } elseif ($tokType === T_PRIVATE) {
                $attrBuffer[] = new Ast\PrivateAstAttribute($tokLine);

                // Handles abstract attributes
            } elseif ($tokType === T_ABSTRACT) {
                $attrBuffer[] = new Ast\AbstractAstAttribute($tokLine);

                // Handles final attributes
            } elseif ($tokType === T_FINAL) {
                $attrBuffer[] = new Ast\FinalAstAttribute($tokLine);

                // Handles static attributes
            } elseif ($tokType === T_STATIC) {
                $next = $stream->getNextToken();
                if ($next[0] === T_STRING && $next[1] === '::')
                    continue;
                else
                    $stream->getPreviousToken();

                $attrBuffer[] = new Ast\StaticAstAttribute($tokLine);

                // Handles namespace definitions
            } elseif ($tokType === \T_NAMESPACE) {
                $nsNode = new Ast\NamespaceAstNode($tokLine, $tokText, $curParent);

                // Process namespace path
                $token = $stream->getNextToken();
                $tokType = $token[0];
                $tokText = $token[1];
                $tokLine = $token[2];

                if ($token == null || $tokType !== T_STRING)
                    throw new PhpParserException($tokLine, $tokText);

                $nsNode->setNamespace($tokText);
                $tree->addChild($nsNode);

                // Handles function definitions
            } elseif ($tokType === T_FUNCTION) {
                // Create an function AST node
                $funcNode = new Ast\FunctionAstNode(
                                $tokLine, $tokText, $curParent);

                // Flush the attribute buffer
                foreach ($attrBuffer as $attr)
                    $funcNode->addAttribute($attr);

                $attrBuffer = array();

                // Add the doccomment
                if ($docCommentBuffer != null) {
                    $funcNode->setDocComment($docCommentBuffer);
                    $docCommentBuffer = null;
                }

                // Process function name
                $token = $stream->getNextToken();
                $tokType = $token[0];
                $tokText = $token[1];
                $tokLine = $token[2];

                if ($token == null || $tokType !== T_STRING)
                    throw new PhpParserException($tokLine, $tokText);

                // Handle anonymous functions
                if ($tokType === T_BREAKED_OPEN) {
                    $funcNode->isAnonymous(true);
                    $funcNode->setFunctionName(null);

                    $stream->getPreviousToken();
                } else {
                    $funcNode->isAnonymous(false);
                    $funcNode->setFunctionName($tokText);

                    $token = $stream->getNextToken();
                    $tokType = $token[0];
                    $tokText = $token[1];
                    $tokLine = $token[2];
                }

                // Prcoess parameters
                if ($tokType !== T_BREAKED_OPEN)
                    throw new PhpParserException($tokLine, $tokText);

                $paramTokens = $stream->getAllUntil(T_BREAKED_CLOSE);
                $paramCount = count($paramTokens);

                if (count($paramTokens) === 1)
                    continue;

                $curParamName = '';
                $curParamDefault = T_NO_DEFAULT;
                $curParamType = null;

                for ($i = 0; $i < $paramCount; $i++) {
                    $token = $paramTokens[$i];
                    $tokType = $token[0];
                    $tokText = $token[1];
                    $tokLine = $token[2];

                    if ($tokType === T_STRING && $tokText === ',') {
                        $funcNode->addParameter(
                                $curParamName, $curParamDefault, $curParamType);

                        $curParamName = '';
                        $curParamDefault = T_NO_DEFAULT;
                        $curParamType = null;

                        continue;
                    }

                    if ($tokType === T_VARIABLE) {
                        $curParamName = $tokText;
                        continue;
                    }

                    if ($tokType === T_STRING && $tokText === '=') {
                        if ($paramCount <= ++$i)
                            throw new PhpParserException($tokLine, $tokText);

                        $token = $paramTokens[$i];
                        $tokType = $token[0];
                        $tokText = $token[1];
                        $tokLine = $token[2];

                        $curParamDefault = $tokText;
                        continue;
                    }

                    if ($tokType === T_STRING || $tokType === T_ARRAY) {
                        $curParamType = $tokText;
                    }
                }

                $stream->getNextToken();

                if ($curParamName !== '')
                    $funcNode->addParameter(
                            $curParamName, $curParamDefault, $curParamType);

                $curParent->addChild($funcNode);
                $curParent = $funcNode;

                // Handles class definitions
            } elseif ($tokType === T_CLASS) {
                // Create an class AST node
                $classNode = new Ast\ClassAstNode(
                                $tokLine, $tokText, $curParent);

                // Flush the attribute buffer
                foreach ($attrBuffer as $attr)
                    $classNode->addAttribute($attr);

                $attrBuffer = array();

                // Add the doccomment
                if ($docCommentBuffer != null) {
                    $classNode->setDocComment($docCommentBuffer);
                    $docCommentBuffer = null;
                }

                // Process class name
                $token = $stream->getNextToken();
                $tokType = $token[0];
                $tokText = $token[1];
                $tokLine = $token[2];

                if ($token == null || $tokType !== T_STRING)
                    throw new PhpParserException($tokLine, $tokText);

                $classNode->setClassName($tokText);

                // Process eventual extends
                $token = $stream->getNextToken();
                $tokType = $token[0];
                $tokText = $token[1];
                $tokLine = $token[2];

                if ($token == null)
                    throw new PhpParserException($tokLine, $tokText);

                if ($tokType === T_CURLYOPEN)
                    continue;

                if ($tokType === T_EXTENDS) {
                    $token = $stream->getNextToken();
                    $tokType = $token[0];
                    $tokText = $token[1];
                    $tokLine = $token[2];

                    if ($token == null || $tokType !== T_STRING)
                        throw new PhpParserException($tokLine, $tokText);

                    $classNode->setExtends($tokText);
                }

                // Process implements
                $token = $stream->getNextToken();
                $tokType = $token[0];
                $tokText = $token[1];
                $tokLine = $token[2];

                if ($token == null)
                    throw new PhpParserException($tokLine);

                if ($tokType === T_CURLYOPEN)
                    continue;

                if ($tokType === T_IMPLEMENTS) {
                    $implements = $stream->getAllUntil(T_CURLYOPEN);
                    $count = count($implements);

                    if ($count % 2 === 0)
                        throw new PhpParserException($tokLine, $tokText);

                    for ($i = 0; $i < $count; $i++) {
                        $tokType = $implements[$i][0];
                        $tokText = $implements[$i][1];
                        $tokLine = $implements[$i][2];

                        if ($i % 2 === 1) {
                            if ($tokType !== T_STRING || $tokText !== ',')
                                throw new PhpParserException(
                                        $tokLine, $tokText);

                            continue;
                        }
                        if ($tokType !== T_STRING)
                            throw new PhpParserException($tokLine, $tokText);

                        $classNode->addImplements($tokText);
                    }
                }

                $curParent->addChild($classNode);
                $curParent = $classNode;

                // Handles variable declarations
            } elseif ($tokType === T_VARIABLE) {
                $varNode = new Ast\VariableAstNode(
                        $tokLine, $tokText, $curParent);

                // Flush the attribute buffer
                foreach ($attrBuffer as $attr)
                    $varNode->addAttribute($attr);

                $attrBuffer = array();

                // Add the doccomment
                if ($docCommentBuffer != null) {
                    $classNode->setDocComment($docCommentBuffer);
                    $docCommentBuffer = null;
                }

                $token = $stream->getNextToken();
                $tokType = $token[0];
                $tokText = $token[1];
                $tokLine = $token[2];

                if ($tokType === T_STRING && $tokText === '=') {
                    $tokens = $stream->getAllUntil(T_SEMICOLON);
                    $count = count($tokens);
                    $defaultValue = '';

                    for ($i = 0; $i < $count; $i++)
                        $defaultValue .= $tokens[$i][1];

                    $varNode->setDefaultValue($defaultValue);
                    $stream->getPreviousToken();
                }

                $curParent->addChild($varNode);



                // Handles IF, ELSEIF, FOR, FOREACH and WHILE blocks
            } elseif ($tokType === T_IF 
                    || $tokType === T_ELSEIF
                    || $tokType === T_FOR
                    || $tokType === T_FOREACH
                    || $tokType === T_WHILE) {
                $node = null;

                switch ($tokType) {
                    case T_IF:
                        $node = new Ast\IfAstNode($tokLine, $tokText, $curParent);
                        break;
                    case T_ELSEIF:
                        $node = new Ast\ElseIfAstNode($tokLine, $tokText, $curParent);
                        break;
                    case T_FOR:
                        $node = new Ast\ForAstNode($tokLine, $tokText, $curParent);
                        break;
                    case T_FOREACH:
                        $node = new Ast\ForEachAstNode($tokLine, $tokText, $curParent);
                        break;
                    case T_WHILE:
                        $node = new Ast\WhileAstNode($tokLine, $tokText, $curParent);
                        break;
                    default: throw new PhpParserException($tokLine, $tokText);
                }

                $token = $stream->getNextToken();
                $tokType = $token[0];
                $tokText = $token[1];
                $tokLine = $token[2];

                if ($tokType !== T_BREAKED_OPEN)
                    throw new PhpParserException($tokLine, $tokText);

                $condTokens = $stream->getAllUntilBreaked('(', ')');
                $condCount = count($condTokens);
                $condString = '';

                for ($i = 0; $i < $condCount; $i++)
                    $condString .= $condTokens[$i][1] . ' ';

                $condString = substr($condString, 0, -1);

                $token = $stream->getNextToken();
                $tokType = $token[0];
                $tokText = $token[1];
                $tokLine = $token[2];

                $closeDec = ($tokType !== T_CURLYOPEN);
                if ($closeDec)
                    $stream->getPreviousToken();

                $node->setCondition($condString);
                $curParent->addChild($node);
                $curParent = $node;

                // Handles else tokens
            } elseif ($tokType === T_ELSE) {
                $elseNode = new Ast\ElseAstNode($tokLine, $tokText, $curParent);

                $token = $stream->getNextToken();
                $tokType = $token[0];
                $tokText = $token[1];
                $tokLine = $token[2];

                $closeDec = ($tokType !== T_CURLYOPEN);
                if ($closeDec)
                    $stream->getPreviousToken();

                $curParent->addChild($elseNode);
                $curParent = $elseNode;

                // Handles all other tokens
            } else {
                $curParent->addChild(new Ast\TokenAstNode(
                                $tokLine, $tokText, $curParent));
            }
        }

        return $tree;
    }

    /**
     * Returns the current instance of this class.
     *
     * @static
     * @return PhpParser
     */
    public static function instance() {
        if (self::$instance == null)
            self::$instance = new self();

        return self::$instance;
    }

}