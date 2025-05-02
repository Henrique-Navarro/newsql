<?php

namespace NewSQL\AbstractSyntaxTree;


class ExpressionNode {
    public function __construct(
        public ColumnNode $column,
        public string $operator, // '='
        public string $value
    ) {}
}