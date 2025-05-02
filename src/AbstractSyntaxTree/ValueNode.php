<?php

namespace NewSQL\AbstractSyntaxTree;

class ValueNode {
    public function __construct(public string|int $value) {}
}
