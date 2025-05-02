<?php

namespace NewSQL\AbstractSyntaxTree;


class select_statement {
    public function __construct(
        public DistinctNode $distinct,
        /* @var ColumnNode[] */
        public ColumnListNode $columnList,
        public TableNode $table,
        public ?ConditionNode $condition
    ) {}
}
