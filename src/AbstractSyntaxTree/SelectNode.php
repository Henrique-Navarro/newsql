<?php

namespace NewSQL\AbstractSyntaxTree;

class SelectNode {
    public ?DistinctNode $child = null;

    public function __construct(public DistinctNode $distinct) {
        $this->child = $distinct;
    }
}
