<?php

namespace NewSQL\Token;

use NewSQL\Exception\IllegalCharacterException;
use NewSQL\Pattern\PatternHelper;

class TokenService {

    public function __construct(private string $value, private TokenType $type) {
    }

    public static function getTokenType(string $token): TokenType
    {
        $tokenType = TokenType::get($token);
        
        // reserved words
        if (isset($tokenType)) {
            return $tokenType;
        }
        // ID
        if (PatternHelper::isIdentifier($token)) {
            return TokenType::IDENTIFIER;
        }
        // numbers
        if (PatternHelper::isNumber($token)) {
            return TokenType::NUMBER;
        }
        
        throw new IllegalCharacterException($token);
    }
}
