<?php
namespace Kesch\Exception;

use OutOfBoundsException;

class CacheMissException extends OutOfBoundsException
{
    public static function assertIsHit($isHit, $key, $symbol)
    {
        if (!$isHit) {
            throw new static(
                sprintf('%s() expected "%s" to result in a cache hit, miss occured', $symbol, $key)
            );
        }
    }
}