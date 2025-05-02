<?php

namespace NewSQL\Statement;

use NewSQL\AbstractSyntaxTree\DatabaseNode;
use NewSQL\AbstractSyntaxTree\UseNode;

use NewSQL\Engine\Parser;
use NewSQL\Token\TokenType;

/**
 * use_statement ::= USE ID ';'
 */
class UseStatement {
    public function __construct(private Parser $parser) {}

    public function parse_use_statement(): UseNode
    {
        $this->parser->consume(TokenType::USE, "Expect 'USE'.");

        $database = $this->parseDatabaseName();

        $this->parser->consume(TokenType::SEMICOLON, "Expect ';' at end of statement.");

        return new UseNode($database);
    }

    private function parseDatabaseName(): DatabaseNode 
    {
        $token = $this->parser->consume(TokenType::IDENTIFIER, "Expect database name.");
        return new DatabaseNode($token->getValue());
    }
}
