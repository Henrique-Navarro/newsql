<?php

namespace NewSQL\AbstractSyntaxTree;

class JoinNode {
    public ?object $child = null;

    public function __construct(
        public string $joinType, // INNER, LEFT, RIGHT, etc.
        public TableNode $table,
        public ConditionNode $onCondition
    ) {}
}