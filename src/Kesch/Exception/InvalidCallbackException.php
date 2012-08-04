<?php
namespace Kesch\Exception;


class InvalidCallbackException extends InvalidArgumentException
{
    public static function assertValidCallback($value, $symbol, $position)
    {
        if (!is_callable($value)) {
            throw new static(
                sprintf(
                    '%s() expected parameter %d to be valid callback, %s given',
                    $symbol,
                    $position,
                    gettype($value)
                )
            );
        }
    }
}
