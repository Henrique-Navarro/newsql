<?php

namespace NewSQL\Token;

class Token {

    public function __construct(private string $value, private TokenType $type) {
    }

    public function getValue(): string 
    {
        return $this->value;
    }

    public function getType(): TokenType 
    {
        return $this->type;
    }

    public function setValue(string $value): void 
    {
        $this->value = $value;
    }

    public function setType(TokenType $type): void 
    {
        $this->type = $type;
    }
}
