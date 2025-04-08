<?php

namespace NewSQL\Pattern;


class PatternHelper {

    private static $TOKEN_PATTERN = '/[a-zA-Z][a-zA-Z0-9_]*|\d+(\.\d+)?|\S/';
    private static $IDENTIFIER_PATTERN = '/[a-zA-Z][a-zA-Z0-9_]*/';
    private static $NUMBER_PATTERN = '/\d+(\.\d+)?/';

    public static function getMatches(string $input): array
    {
        preg_match_all(self::$TOKEN_PATTERN, $input, $matches);
        return $matches[0];
    }

    public static function isIdentifier(string $token): bool
    {
        return preg_match(self::$IDENTIFIER_PATTERN, $token) === 1;
    }

    public static function isNumber(string $token): bool
    {
        return preg_match(self::$NUMBER_PATTERN, $token) === 1;
    }
}
