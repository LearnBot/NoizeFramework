<?php

namespace Noize\Build\Generation;

// This is ugly, I know, but I need to define some additional token constants!
define('T_CURLYOPEN',       501);
define('T_CURLYCLOSE',      502);
define('T_BREAKED_OPEN',    503);
define('T_BREAKED_CLOSE',   504);
define('T_NO_DEFAULT',      731);

/**
 * Implements a tokenstream for a PHP lexer output.
 *
 * @author Jan Gräfen
 * @package Build
 * @subpackage Generation
 * @version 1.0
 */
final class TokenStream {
    
    /**
     * Stores an array of special strings which are handled specially.
     *
     * @static
     * @var array
     */
    private static $specialStrings = array (
        '{' => T_CURLYOPEN,
        '}' => T_CURLYCLOSE,
        '(' => T_BREAKED_OPEN,
        ')' => T_BREAKED_CLOSE,
    );
    
    /**
     * Stores all tokens which were detected by the PHP lexer.
     *
     * @var array
     */
    private $tokens = array ();
    
    /**
     * Stores the current index.
     *
     * @var int
     */
    private $currentIndex = 0;
    
    /**
     * Stores the number of tokens inside the stream.
     *
     * @var int
     */
    private $count = 0;
    
    /**
     * Creates a new token stream.
     *
     * @param type $file The PHP source file.
     */
    public function __construct($file) {
        $this->tokens   = token_get_all(file_get_contents($file));
        $this->count    = count($this->tokens);
    }
    
    /**
     * Returns the next relevant token inside the stream until no further
     * tokens are available.
     * In this case NULL is returned.
     * 
     * @return array
     */
    public function getNextToken() {
        if ($this->currentIndex === $this->count - 1)
            return null;
        
        $currentToken = $this->tokens[++$this->currentIndex];
        
        if (is_string($currentToken)) {
            if (in_array($currentToken, array_keys(self::$specialStrings))) {
                $const = self::$specialStrings[$currentToken];
                return array ($const, $currentToken, $this->getCurrentLine());
            } else {
                return array (T_STRING, $currentToken, $this->getCurrentLine());
            }
        }
        
        if ($currentToken[0] === T_WHITESPACE)
            return $this->getNextToken(); 
        
        return $currentToken;
    }
    
    /**
     * Returns the previous relevant token inside the stream until no further
     * tokens are available.
     * In this case NULL is returned.
     *
     * @return array
     */
    public function getPreviousToken() {
        if ($this->currentIndex === 0)
            return null;
        
        $currentToken = $this->tokens[--$this->currentIndex];
        
        if (is_string($currentToken))
            return array(T_STRING, $currentToken, $this->getCurrentLine());
        
        if ($currentToken[0] === T_WHITESPACE)
            return $this->getPreviousToken();
        
        return $currentToken;
    }
    
    /**
     * Behaves exactly as goToNext, but returns all tokens until a token given
     * token is found.
     *
     * @param mixed $token  A token constant or an array of token constants.
     * @return array
     */
    public function getAllUntil($token) {
        if (is_int($token))
            $token = array ($token);
        
        $returnedTokens = array ();
        
        while (($cur = $this->getNextToken()) != null) {
            if (in_array($cur[0], $token))
                break;
            else
                $returnedTokens[] = $cur;
        }
        
        return $returnedTokens;
    }
    
    /**
     * Behaves exactly as getAllUntil, but keeps an eye on the breaked
     * structure.
     *
     * @param string $openChar The char which opens the structure.
     * @param string $closeChar The char which closes the structure.
     * @return array
     */
    public function getAllUntilBreaked($openChar, $closeChar) {
        $stack = 0;
        $returnedTokens = array ();
        
        while (($cur = $this->getNextToken()) != null) {
            if ($cur[1] === $openChar)
                $stack++;
            elseif ($cur[1] === $closeChar && --$stack <= 0)
                break;
            
            $returnedTokens[] = $cur;
        }
        
        return $returnedTokens;
    }
    
    /**
     * Skipts the next tokens until a given token or the end of the stream is
     * reached.
     * It's possible to supply one token constant or an array of token
     * constants.
     * If more then one token constants are supplied the stream is skiped until
     * the first occassion of one of the given token.
     * 
     * @param mixed $token A token constant or an array of token constants.
     */
    public function goToNext($token) {
        if (is_int($token))
            $token = array ($token);
        
        while (($cur = $this->getNextToken()) != null) {
            if (in_array($cur[0], $token))
                break;
        }
        
        $this->getPreviousToken();
    }
    
    /**
     * Returns the current line number of the stream.
     *
     * @return int
     */
    public function getCurrentLine() {
        $currentToken = $this->tokens[$this->currentIndex];
        $cur = 0;
        
        while (is_string($currentToken) && $this->currentIndex - $cur >= 0) {
            $currentToken = $this->tokens[$this->currentIndex - (++$cur)];
        }
        
        return (is_string($currentToken) ? 0 :$currentToken[2]);
    }
    
    /**
     * Returns the number of tokens inside the stream.
     *
     * @return int
     */
    public function getTokenCount() {
        return $this->count;
    }
}

?>
