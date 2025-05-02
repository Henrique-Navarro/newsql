<?php

namespace NewSQL\Statement;

use NewSQL\AbstractSyntaxTree\ColumnListNode;
use NewSQL\AbstractSyntaxTree\ColumnNode;
use NewSQL\AbstractSyntaxTree\ConditionNode;
use NewSQL\AbstractSyntaxTree\DistinctNode;
use NewSQL\AbstractSyntaxTree\ExpressionNode;
use NewSQL\AbstractSyntaxTree\SelectNode;
use NewSQL\AbstractSyntaxTree\FromNode;
use NewSQL\AbstractSyntaxTree\TableNode;
use NewSQL\AbstractSyntaxTree\ValueNode;

use NewSQL\Engine\Parser;
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
class SelectStatement2 {
    public function __construct(private Parser $parser) {}

    public function parse_select_statement(): SelectNode
    {
        $this->parser->consume(TokenType::SELECT, "Expect 'SELECT'.");

        $distinctNode = $this->parseDistinct();              // DISTINCT
        $columnListNode = $this->parseSelectList();         // ColumnList
        $distinctNode->child = $columnListNode;

        $this->parser->consume(TokenType::FROM, "Expect 'FROM'.");

        $tableNode = $this->parseTableName();               // Table
        $fromNode = new FromNode($tableNode);
        $columnListNode->child = $fromNode;

        if ($this->parser->match(TokenType::WHERE)) {
            $condition = $this->parseCondition();           // Condition (opcional)
            $tableNode->setChild($condition);
        }

        $this->parser->consume(TokenType::SEMICOLON, "Expect ';' at end of statement.");

        return new SelectNode($distinctNode); // raiz da árvore
    }

    private function parseDistinct(): DistinctNode
    {
        $hasDistinct = $this->parser->match(TokenType::DISTINCT);
        return new DistinctNode($hasDistinct);
    }

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

    private function parseTableName(): TableNode {
        $token = $this->parser->consume(TokenType::IDENTIFIER, "Expect table name.");
        return new TableNode($token->getValue());
    }

    private function parseCondition(): ConditionNode {
        $left = $this->parseExpression();

        while ($this->parser->match(TokenType::AND, TokenType::OR)) {
            $operator = $this->parser->previous()->getValue();
            $right = $this->parseCondition(); // associatividade à direita
            $left = new ConditionNode($left, $operator, $right);
        }

        return $left;
    }

    private function parseExpression(): ConditionNode {
        $column = $this->parseColumnName(); // ColumnNode
        $this->parser->consume(TokenType::EQUAL, "Expect '='.");
        $value = $this->parseValue();

        return new ConditionNode($column, '=', $value);
    }

    private function parseValue(): ValueNode {
        if ($this->parser->match(TokenType::NUMBER, TokenType::TEXT, TokenType::IDENTIFIER)) {
            $token = $this->parser->previous();
            return new ValueNode($token->getValue());
        }

        throw $this->parser->error($this->parser->peek(), "Expect value.");
    }
}
