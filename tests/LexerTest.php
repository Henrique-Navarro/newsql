<?php

use PHPUnit\Framework\TestCase;
use NewSQL\Engine\Lexer;
use NewSQL\Token\TokenType;

class LexerTest extends TestCase
{
    public function test_all_token_types_one_by_one()
    {
        $lexer = new Lexer();
        $query = $this->getAllTokenTypes();
        
        foreach($query as $input => $tokenType) {
            $tokens = $lexer->tokenize(strtolower($input));
            $this->assertEquals($tokens[0]->getType(), $tokenType, "{$input} Expected: {$tokenType->value}, Given: {$tokens[0]->getType()->value}");
            echo "✅ Successfully tokenized '$input' as '{$tokens[0]->getType()->value}'.\n";
        }
    }
    public function test_select_query_upper()
    {
        $query = "SELECT DISTINCT COL1, COL2 FROM TABELA WHERE COL = 1; SELECT*FROMWHERE WHERE FROM";

        $lexer = new Lexer();
        $tokens = $lexer->tokenize($query);

        $expectedTypes = [
            TokenType::SELECT,
            TokenType::DISTINCT,
            TokenType::IDENTIFIER,
            TokenType::COMMA,
            TokenType::IDENTIFIER,
            TokenType::FROM,
            TokenType::IDENTIFIER,
            TokenType::WHERE,
            TokenType::IDENTIFIER,
            TokenType::EQUAL,
            TokenType::NUMBER,
            TokenType::SEMICOLON,
            TokenType::SELECT,
            TokenType::ASTERISK,
            TokenType::IDENTIFIER,
            TokenType::WHERE,
            TokenType::FROM,

            TokenType::EOF
        ];

        $this->assertCount(count($expectedTypes), $tokens);
        echo "✅ Successfully tokenized '".count($expectedTypes)."' tokens, expected '".count($tokens)."' tokens\n";

        foreach ($tokens as $index => $token) {
            $this->assertEquals($expectedTypes[$index], $token->getType(), "Mismatch at token index $index.");
            echo "✅ Successfully tokenized '".$token->getValue()."' as '".$expectedTypes[$index]->value."'.\n";
        }
    }

    public function test_select_query_lower()
    {
        $query = "select distinct col1, col2 from tabela where col = 1;";

        $lexer = new Lexer();
        $tokens = $lexer->tokenize($query);

        $expectedTypes = [
            TokenType::SELECT,
            TokenType::DISTINCT,
            TokenType::IDENTIFIER,
            TokenType::COMMA,
            TokenType::IDENTIFIER,
            TokenType::FROM,
            TokenType::IDENTIFIER,
            TokenType::WHERE,
            TokenType::IDENTIFIER,
            TokenType::EQUAL,
            TokenType::NUMBER,
            TokenType::SEMICOLON,
            TokenType::EOF
        ];

        $this->assertCount(count($expectedTypes), $tokens);
        echo "✅ Successfully tokenized '".count($expectedTypes)."' tokens, expected '".count($tokens)."' tokens\n";

        foreach ($tokens as $index => $token) {
            $this->assertEquals($expectedTypes[$index], $token->getType(), "Mismatch at token index $index.");
            echo "✅ Successfully tokenized '".$token->getValue()."' as '".$expectedTypes[$index]->value."'.\n";
        }
    }

    public function test_identifiers()
    {
        $query = "22bb aaa111 aa11aa ab1b123basbdab coluna colna, asd 123 a,sd, 123123,,,;()";

        $lexer = new Lexer();
        $tokens = $lexer->tokenize($query);

        $expectedTypes = [
            TokenType::NUMBER,
            TokenType::IDENTIFIER,
            TokenType::IDENTIFIER,
            TokenType::IDENTIFIER,
            TokenType::IDENTIFIER,
            TokenType::IDENTIFIER,
            TokenType::IDENTIFIER,
            TokenType::COMMA,
            TokenType::IDENTIFIER,
            TokenType::NUMBER,
            TokenType::IDENTIFIER,
            TokenType::COMMA,
            TokenType::IDENTIFIER,
            TokenType::COMMA,
            TokenType::NUMBER,
            TokenType::COMMA,
            TokenType::COMMA,
            TokenType::COMMA,
            TokenType::SEMICOLON,
            TokenType::OPEN_PARENTHESIS,
            TokenType::CLOSE_PARENTHESIS,

            TokenType::EOF
        ];

        $this->assertCount(count($expectedTypes), $tokens);
        echo "✅ Successfully tokenized '".count($expectedTypes)."' tokens, expected '".count($tokens)."' tokens\n";

        foreach ($tokens as $index => $token) {
            $this->assertEquals($expectedTypes[$index], $token->getType(), "Mismatch at token index $index.");
            echo "✅ Successfully tokenized '".$token->getValue()."' as '".$expectedTypes[$index]->value."'.\n";
        }
    }

    // public function test_syntax_error()
    // {

    // }


    private function getAllTokenTypes(): array
    {
        $query = [];

        foreach (TokenType::cases() as $case) {
            $query[$case->value] = $case;
        }

        return $query;
    }
}
