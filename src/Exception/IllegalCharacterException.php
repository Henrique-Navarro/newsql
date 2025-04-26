<?php

namespace NewSQL\Exception;

use Exception;

class IllegalCharacterException extends Exception
{
    public function __construct(private string $token)
    {
        $message = "Illegal character encountered: '{$this->token}'.";
        parent::__construct($message);
    }
}
