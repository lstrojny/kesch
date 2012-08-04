<?php
namespace Kesch;

use Kesch\Exception\InvalidCallbackException;
use Kesch\Storage\StorageInterface;

class Cache
{
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function load($key, $onSuccess = null)
    {
        $result = $this->storage->load($key);

        if ($onSuccess !== null) {
            InvalidCallbackException::assertValidCallback($onSuccess, __METHOD__, 2);
            return call_user_func($onSuccess, $result);
        }

        return $result;
    }

    public function save($key, $value, $ttl = null, array $tags = array(), $onSuccess = null)
    {
        if (is_callable($value)) {
            $value = call_user_func($value, $key);
        }

        return $this->storage->save($key, $value, $ttl);
    }
}
