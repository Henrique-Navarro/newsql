<?php

namespace NewSQL\AbstractSyntaxTree;

class ConditionNode {
    public ?object $child = null;

    public function __construct(
        public ColumnNode|ConditionNode $left,
        public string $operator, // '=', 'AND', 'OR'
        public ValueNode|ConditionNode $right
    ) {}
}
