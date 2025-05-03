<?php

namespace NewSQL\Statement;

use NewSQL\AbstractSyntaxTree\CreateNode;
use NewSQL\AbstractSyntaxTree\DatabaseNode;
use NewSQL\AbstractSyntaxTree\TableNode;
use NewSQL\AbstractSyntaxTree\UseNode;

use NewSQL\Engine\Parser;
use NewSQL\Token\Token;
use NewSQL\Token\TokenType;

/**
 * create_statement ::= CREATE DATABASE ID ';'
 *                    | CREATE TABLE ID ';'
 */
class CreateStatement {
    public function __construct(private Parser $parser) {}

    public function parse_create_statement(): CreateNode
    {
        $this->parser->consume(TokenType::CREATE, "Expect 'CREATE'.");

        $kind = $this->parseKind();

        $database = $this->parseDatabaseName($kind);

        $this->parser->consume(TokenType::SEMICOLON, "Expect ';' at end of statement.");

        return new CreateNode($database);
    }

    private function parseKind(): Token
    {
        if($this->parser->check(TokenType::DATABASE)){
            return $this->parser->consume(TokenType::DATABASE, "Expect 'DATABASE'.");
        }
        if($this->parser->check(TokenType::TABLE)){
            return $this->parser->consume(TokenType::TABLE, "Expect 'TABLE'.");
        }
        
        throw $this->parser->error($this->parser->peek(), "Expect 'DATABASE' or 'TABLE' after 'CREATE'.");
    }

    private function parseDatabaseName(Token $kind): DatabaseNode|TableNode
    {
        $token = $this->parser->consume(TokenType::IDENTIFIER, "Expect identifier.");

        if($kind->getType() === TokenType::DATABASE){
            return new DatabaseNode($token->getValue());
        }
        if($kind->getType() === TokenType::TABLE){
            return new TableNode($token->getValue());
        }

        throw $this->parser->error($token, "Expect 'DATABASE' or 'TABLE' after 'CREATE'.");
    }
}
