<?php

namespace NewSQL\AbstractSyntaxTree;

class DistinctNode {
    public ?ColumnListNode $child = null;

    public function __construct(public bool $hasDistinct) {}
}
