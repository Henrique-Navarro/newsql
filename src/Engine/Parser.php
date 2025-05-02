<?php

namespace NewSQL\Engine;

use Exception;
use NewSQL\AbstractSyntaxTree\ASTLabelNode;
use NewSQL\AbstractSyntaxTree\ColumnListNode;
use NewSQL\AbstractSyntaxTree\ColumnNode;
use NewSQL\AbstractSyntaxTree\ConditionNode;
use NewSQL\AbstractSyntaxTree\DistinctNode;
use NewSQL\AbstractSyntaxTree\select_statement;
use NewSQL\AbstractSyntaxTree\TableNode;
use NewSQL\Statement\SelectStatement;
use NewSQL\Statement\SelectStatement2;
use NewSQL\Statement\UseStatement;
use NewSQL\Token\Token;
use NewSQL\Token\TokenType;

class Parser {

    private array $tokens;
    private int $current;

    public function __construct() {
        $this->current = 0;
    }

    /**
     * @param Token[] $tokens
     */
    public function parse(array $tokens)
    {
        $this->tokens = $tokens;

        while (!$this->isAtEnd()) {
            $root = $this->parseStatement();
        }

        // retornar 'Node' generico;
        return $root;
    }

    public function isAtEnd(): bool
    {
        return $this->peek()->getType() === TokenType::EOF;
    }

    public function peek(): Token
    {
        // dump($this->current, $this->tokens[$this->current]->getValue());
        return $this->tokens[$this->current];
    }

    public function check(TokenType $type): bool
    {
        if ($this->isAtEnd()){
            return false;
        }
        return $this->peek()->getType() === $type;
    }

    public function advance(): Token
    {
        if (!$this->isAtEnd()){
            $this->current++;
        }
        return $this->previous();
    }

    public function previous(): Token
    {
        return $this->tokens[$this->current - 1];
    }

    public function error(Token $token, string $message): Exception
    {
        // return new ParseError($token, $message);
        return new Exception();
    }

    public function consume(TokenType $type, string $message): Token
    {
        if ($this->check($type)) {
            return $this->advance();
        }
        throw $this->error($this->peek(), $message);
    }

    public function match(TokenType ...$types): bool
    {
        $matched = array_filter($types, fn($type) => $this->check($type));

        if ($matched) {
            $this->advance();
            return true;
        }
        return false;
    }

    // return $ast
    private function parseStatement()
    {
        $token = $this->peek()->getType();

        $ast = match ($token) {
            TokenType::SELECT => (new SelectStatement2($this))->parse_select_statement(),
            TokenType::USE => (new UseStatement($this))->parse_use_statement(),
            // TokenType::DROP => (new DropStatement($this))->parseDropStatement(),
            // TokenType::SHOW => (new ShowStatement($this))->parseShowStatement(),
            // TokenType::CREATE => (new CreateStatement($this))->parseCreateStatement(),
            // DESCRIBE
            // UPDATE

            default => throw $this->error($this->peek(), 'Unexpected statement.')
        };

        // print_r($ast);
        // dd($ast);
        // self::printAST($ast);
        self::printChain($ast);
        // criar um tipo generico 'Node'
        return $ast;
    }

    public function printChain($node, $indent = '')
    {
        $prefix = '└── ';
        while ($node !== null) {
            echo $indent . $prefix . str_replace("NewSQL\\AbstractSyntaxTree\\", '', get_class($node));

            if ($node instanceof ColumnListNode && $node->columns) {
                $columns = $node->toArray();
                echo " " . implode(', ', $columns);
            }
            if ($node instanceof TableNode) {
                $name = $node->name();
                echo " {$name}";
            }
            if ($node instanceof ConditionNode && $node->operator) {
                echo " {$node->operator}";
            }

            echo "\n";
            $node = $node->child ?? null;
            $indent .= "  ";
        }
    }


    function printAST($node, $indent = '', $isLast = true)
{
    $prefix = $indent . ($isLast ? '└── ' : '├── ');
    // dump($node);
    if ($node instanceof select_statement) {
        echo "{$prefix}SelectStatement\n";

        $this->printAST($node->distinct, $indent . ($isLast ? '    ' : '│   '), false);

        // Imprime cabeçalho 'Columns'
        $columnsIndent = $indent . ($isLast ? '    ' : '│   ');
        echo "{$columnsIndent}├── Columns\n";

        // Imprime colunas
        $columnIndent = $columnsIndent . "│   ";
        $names = $node->columns->names;
        foreach ($names as $i => $col) {
            $isLastCol = $i === array_key_last($names);
            $colPrefix = $columnIndent . ($isLastCol ? '└── ' : '├── ');

            if (is_string($col)) {
                echo "{$colPrefix}Column: {$col}\n";
            } elseif ($col instanceof ColumnNode) {
                echo "{$colPrefix}Column: {$col->name}\n";
            } else {
                echo "{$colPrefix}[Unknown Column Type]\n";
            }
        }

        // Table
        $this->printAST($node->from, $indent . ($isLast ? '    ' : '│   '), false);

        // Optional WHERE
        if ($node->where !== null) {
            $this->printAST($node->where, $indent . ($isLast ? '    ' : '│   '), true);
        }

    } elseif ($node instanceof DistinctNode) {
        echo "{$prefix}Distinct: " . ($node->exists ? "true" : "false") . "\n";

    } elseif ($node instanceof TableNode) {
        echo "{$prefix}Table: {$node->name}\n";

    } elseif ($node instanceof ConditionNode) {
        // dd($node->left, $node->operator, $node->right);
        echo "{$prefix}Condition: {$node->left->name} {$node->operator} {$node->right}\n";
        // $this->printAST($node->left, $indent . ($isLast ? '    ' : '│   '), false);
        // $this->printAST($node->right, $indent . ($isLast ? '    ' : '│   '), true);

    } elseif ($node instanceof ASTLabelNode) {
        echo "{$prefix}{$node->label}\n";

    } elseif ($node === null) {
        // Do nothing
    } else {
        echo "{$prefix}[Unknown node type]\n";
    }
}


}
