<?php
namespace Kesch\Exception;

use Kesch\Storage\StorageInterface;

class InvalidKeyException extends InvalidArgumentException
{
    public static function assertValidKeyForStorage(StorageInterface $storage, $key, $symbol, $position)
    {
        if (!$storage->isValidKey($key)) {
            throw new static(
                sprintf(
                    '%s() expected parameter %d to be a valid key for %s, "%s" given',
                    $symbol,
                    $position,
                    get_class($storage),
                    $key
                )
            );
        }
    }
}
