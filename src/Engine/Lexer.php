<?php

namespace NewSQL\Engine;

use NewSQL\Pattern\PatternHelper;
use NewSQL\Token\Token;
use NewSQL\Token\TokenService;
use NewSQL\Token\TokenType;

class Lexer {

    public function __construct() {
    }

    /**
     * @return Token[]
     */
    public function tokenize(string $input): array
    {
        $tokens = [];
    
        $matches = PatternHelper::getMatches($input);
    
        foreach ($matches as $token) {
            $type = TokenService::getTokenType($token);
            $tokens[] = new Token($token, $type);
        }
    
        $tokens[] = new Token('', TokenType::EOF);

        return $tokens;
    }
}
