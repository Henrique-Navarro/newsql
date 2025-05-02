<?php

namespace NewSQL\Statement;

use NewSQL\AbstractSyntaxTree\ColumnListNode;
use NewSQL\AbstractSyntaxTree\ColumnNode;
use NewSQL\AbstractSyntaxTree\ConditionNode;
use NewSQL\AbstractSyntaxTree\DistinctNode;
use NewSQL\AbstractSyntaxTree\ExpressionNode;
use NewSQL\AbstractSyntaxTree\select_statement;
use NewSQL\AbstractSyntaxTree\TableNode;
use NewSQL\Engine\Parser;
use NewSQL\Token\Token;
use NewSQL\Token\TokenType;

/**
 * select_statement ::= SELECT distinct_statement select_list FROM table ';'
 *
 * distinct_statement ::= DISTINCT
 *                       | ε
 *
 * select_list ::= '*'
 *               | column_list
 *
 * column_list ::= column_name
 *               | column_list ',' column_name
 *
 * column_name ::= ID
 */
class SelectStatement {
    public function __construct(private Parser $parser) {}

    public function parse_select_statement(): select_statement
    {
        $distinct = null;
        $condition = null;

        $this->parser->consume(TokenType::SELECT, "Expect 'SELECT'.");

        $distinct = $this->parseDistinct();

        $columnList = $this->parseSelectList();

        $this->parser->consume(TokenType::FROM, "Expect 'FROM'.");

        $table = $this->parseTableName();

        if ($this->parser->match(TokenType::WHERE)) {
            $condition = $this->parseCondition();
        }

        $this->parser->consume(TokenType::SEMICOLON, "Expect ';' at end of statement.");

        return new select_statement($distinct, $columnList, $table, $condition);
    }

    // distinct
    private function parseDistinct(): DistinctNode
    {
        $exists = $this->parser->match(TokenType::DISTINCT);
        return new DistinctNode($exists);
    }

    // colunas
    private function parseSelectList(): ColumnListNode {
        if ($this->parser->match(TokenType::ASTERISK)) {
            return new ColumnListNode(['*']);
        }

        return $this->parseColumnList();
    }

    private function parseColumnList(): ColumnListNode {
        $columns = [];
        $columns[] = $this->parseColumnName();

        while ($this->parser->match(TokenType::COMMA)) {
            $columns[] = $this->parseColumnName();
        }

        return new ColumnListNode($columns);
    }

    private function parseColumnName(): ColumnNode {
        $token = $this->parser->consume(TokenType::IDENTIFIER, "Expect column name.");
        return new ColumnNode($token->getValue());
    }


    // tabela
    private function parseTableName(): TableNode {
        $token = $this->parser->consume(TokenType::IDENTIFIER, "Expect table name.");
        return new TableNode($token->getValue());
    }

    // Condição
    private function parseCondition(): ConditionNode {
        $left = $this->parseExpression(); // retorna ConditionNode base (=)

        while ($this->parser->match(TokenType::AND, TokenType::OR)) {
            $operator = $this->parser->previous()->getValue();
            $right = $this->parseCondition(); // recursivo para associatividade à direita
            $left = new ConditionNode($left, $operator, $right);
        }

        return $left;
    }

    // Expressão simples do tipo: coluna = valor
    private function parseExpression(): ConditionNode {
        $column = $this->parseColumnName(); // retorna ColumnNode
        $this->parser->consume(TokenType::EQUAL, "Expect '='.");
        $value = $this->parseValue(); // string

        return new ConditionNode($column, '=', $value);
    }

    // Valor: número, texto ou identificador
    private function parseValue(): string {
        if ($this->parser->match(TokenType::NUMBER, TokenType::TEXT, TokenType::IDENTIFIER)) {
            return $this->parser->previous()->getValue();
        }

        throw $this->parser->error($this->parser->peek(), "Expect value.");
    }

}